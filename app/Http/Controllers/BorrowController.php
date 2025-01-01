<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth; // Import Auth facade
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\BorrowRecord;

use App\Mail\BorrowNotification;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReturnNotification;

use App\Mail\DueReminderNotification;


class BorrowController extends Controller
{
    public function index()
    {
        return view('borrow.index');
    }

    public function showReturn()
    {
        return view('books.return');
    }

    // public function store(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'user_id' => 'required|integer|exists:users,id',
    //         'books' => 'required|array',
    //         'books.*.id' => 'required|integer|exists:books,id',
    //     ]);

    //     $borrowDate = now();
    //     $dueDate = now()->addDays(14); // Set due date to 2 weeks from now

    //     foreach ($validatedData['books'] as $bookData) {
    //         $book = Book::find($bookData['id']);

    //         if ($book && $book->status === 'available') {
    //             // Update book status to 'borrowed'
    //             $book->update(['status' => 'borrowed']);

    //             // Insert record into borrow_records table
    //             BorrowRecord::create([
    //                 'user_id' => $validatedData['user_id'],
    //                 'book_id' => $book->id,
    //                 'borrow_date' => $borrowDate,
    //                 'due_date' => $dueDate,
    //                 'status' => 'borrowed',
    //             ]);
    //         }
    //     }

    //     return response()->json(['message' => 'Books borrowed successfully!']);
    // }

    //the old latest
    // public function store(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'books' => 'required|array',
    //         'books.*.id' => 'required|integer|exists:books,id',
    //     ]);

    //     $userId = Auth::id(); // Get the authenticated user's ID
    //     $borrowDate = now();
    //     $dueDate = now()->addDays(14); // Due date set to 2 weeks later


    //     foreach ($validatedData['books'] as $bookData) {
    //         $book = Book::find($bookData['id']);

    //         if ($book && $book->status === 'available') {
    //             // Update the book status
    //             $book->update(['status' => 'borrowed']);

    //             // Create a new borrow record
    //             BorrowRecord::create([
    //                 'user_id' => $userId, // Use current active user ID
    //                 'book_id' => $book->id,
    //                 'borrow_date' => $borrowDate,
    //                 'due_date' => $dueDate,
    //                 'status' => 'borrowed',
    //             ]);
    //         }
    //     }

    //     return response()->json(['message' => 'Books borrowed successfully!']);
    // }

    public function store(Request $request)
    {
        // Validate incoming request
        $validatedData = $request->validate([
            'books' => 'required|array',
            'books.*.id' => 'required|integer|exists:books,id',
        ]);

        $userId = Auth::id(); // Get the authenticated user's ID
        $user = Auth::user(); // Get the authenticated user details
        $borrowDate = now();
        $dueDate = now()->addDays(14); // Due date set to 2 weeks later

        $borrowedBooks = []; // To collect borrowed book details for the email

        foreach ($validatedData['books'] as $bookData) {
            $book = Book::find($bookData['id']);

            if ($book && $book->status === 'available') {
                // Update the book status and availability
                $book->update([
                    'status' => 'borrowed',
                    'is_available' => 0
                ]);

                // Create a new borrow record
                BorrowRecord::create([
                    'user_id' => $userId,
                    'book_id' => $book->id,
                    'borrow_date' => $borrowDate,
                    'due_date' => $dueDate,
                    'status' => 'borrowed',
                ]);

                // Add the borrowed book to the email list
                $borrowedBooks[] = $book;
            }
        }

        // return response()->json(['message' => 'Books borrowed successfully!']);

        // Send email notification if any books were borrowed
        if (!empty($borrowedBooks)) {
            Mail::to($user->email)->send(new BorrowNotification($borrowedBooks, $user));
        }

        return response()->json(['message' => 'Books borrowed successfully!']);
    }


    public function showBorrowed()
    {
        $borrowedBooks = BorrowRecord::where('borrow_records.user_id', Auth::id())
            ->whereIn('borrow_records.status', ['borrowed', 'overdue'])
            ->join('books', 'borrow_records.book_id', '=', 'books.id')
            ->select('books.id', 'books.title', 'books.author', 'books.barcode', 'borrow_records.status')
            ->get();

        return response()->json($borrowedBooks);
    }


    // public function returnBook(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'barcode' => 'required|string|exists:books,barcode',
    //     ]);

    //     $borrowRecord = BorrowRecord::whereHas('book', function ($query) use ($validatedData) {
    //         $query->where('barcode', $validatedData['barcode']);
    //     })
    //         ->where('user_id', Auth::id())
    //         ->where('status', 'borrowed')
    //         ->first();

    //     if (!$borrowRecord) {
    //         return response()->json(['message' => 'Book not found or not borrowed by you.'], 404);
    //     }

    //     // Update borrow record and book status
    //     $borrowRecord->update([
    //         'return_date' => now(),
    //         'status' => 'returned',
    //     ]);

    //     $borrowRecord->book->update(['status' => 'available']);

    //     return response()->json(['message' => 'Book returned successfully!']);
    // }

    public function returnBook(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'barcode' => 'required|string|exists:books,barcode',
        ]);

        // Find the borrow record based on the book barcode and current user
        $borrowRecord = BorrowRecord::whereHas('book', function ($query) use ($validatedData) {
            $query->where('barcode', $validatedData['barcode']);
        })
            ->where('user_id', Auth::id())
            ->whereIn('status', ['borrowed', 'overdue'])
            ->first();

        // If no valid borrow record is found, return an error response
        if (!$borrowRecord) {
            return response()->json(['message' => 'Book not found or not borrowed by you.'], 404);
        }

        // Update the borrow record (set return date and status to 'returned')
        $borrowRecord->update([
            'return_date' => now(),
            'status' => 'returned',
        ]);

        // Update the book status and availability
        $borrowRecord->book->update([
            'status' => 'available',
            'is_available' => 1,
        ]);

        // // Return a success response
        // return response()->json(['message' => 'Book returned successfully!']);

        // Send email notification to the user
        $user = Auth::user();
        $book = $borrowRecord->book; // Retrieve the book details
        Mail::to($user->email)->send(new ReturnNotification($book, $user));

        // Return a success response
        return response()->json(['message' => 'Book returned successfully!']);
    }

    public function sendDueReminders()
    {
        $dueDays = [3, 2, 1]; // Days before due date to send notifications

        foreach ($dueDays as $day) {
            $dueDate = now()->addDays($day)->toDateString();

            // Fetch all borrow records with associated user and book
            $borrowRecords = BorrowRecord::whereDate('due_date', $dueDate)
                ->where('status', 'borrowed')
                ->with('book', 'user') // Eager load relationships
                ->get();

            // Send email reminders
            foreach ($borrowRecords as $record) {
                $user = $record->user;
                $book = $record->book;

                if ($user && $book) {
                    Mail::to($user->email)->send(new DueReminderNotification($user, $book, $day));
                }
            }
        }

        return response()->json(['message' => 'Due reminders sent successfully!']);
    }

    public function showUserBorrowRecords()
    {
        // Fetch borrow records for the authenticated user with related book details
        $borrowRecords = BorrowRecord::where('user_id', Auth::id())
            ->with('book') // Assumes BorrowRecord has a relationship with the Book model
            ->orderBy('borrow_date', 'desc')
            ->paginate(10); // Paginate results

        // Return the view with borrow records
        return view('users.borrow-records', compact('borrowRecords'));
    }

    public function adminBorrowRecords()
    {
        // Fetch all borrow records with related user and book details
        $borrowRecords = BorrowRecord::with(['user', 'book'])
            ->orderBy('borrow_date', 'desc')
            ->paginate(10);

        // Return the admin view
        return view('admin.borrow-records', compact('borrowRecords'));
    }

    // public function adminBorrowRecords(Request $request)
    // {
    //     // Get the filter from the request (default is "borrowed")
    //     $filter = $request->input('filter', 'borrowed');

    //     // Fetch borrow records based on the filter
    //     $borrowRecords = BorrowRecord::with(['user', 'book'])
    //         ->when($filter === 'borrowed', function ($query) {
    //             $query->where('status', 'borrowed');
    //         })
    //         ->when($filter === 'returned', function ($query) {
    //             $query->where('status', 'returned');
    //         })
    //         ->when($filter === 'overdue', function ($query) {
    //             $query->where('status', 'borrowed')
    //                 ->where('due_date', '<', now());
    //         })
    //         ->orderBy('borrow_date', 'desc')
    //         ->paginate(10);

    //     // Return the view with the filtered borrow records and filter type
    //     return view('admin.borrow-records', compact('borrowRecords', 'filter'));
    // }


    public function markReturned($id)
    {
        $borrowRecord = BorrowRecord::findOrFail($id);

        // if ($borrowRecord->status != 'borrowed') {
        //     return response()->json(['message' => 'This book is not currently borrowed.'], 400);
        // }

        if (!in_array($borrowRecord->status, ['borrowed', 'overdue'])) {
            return response()->json(['message' => 'This book is neither borrowed nor overdue.'], 400);
        }
        

        // Update the borrow record
        $borrowRecord->update([
            'status' => 'returned',
            'return_date' => now()
        ]);

        // Update the book status
        $borrowRecord->book->update([
            'status' => 'available',
            'is_available' => 1
        ]);

        return response()->json(['message' => 'Book marked as returned successfully.']);
    }
}
