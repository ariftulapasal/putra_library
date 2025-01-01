<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BorrowRecord;
use App\Models\Book;
use Carbon\Carbon;

class BorrowController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'books' => 'required|array',
            'books.*.id' => 'required|integer|exists:books,id',
        ]);

        $borrowDate = Carbon::now();
        $dueDate = Carbon::now()->addDays(14); // 2 weeks due date (adjust as needed)

        foreach ($validatedData['books'] as $bookData) {
            $book = Book::find($bookData['id']);

            if ($book && $book->status === 'available') {
                // Update book status to 'borrowed'
                $book->update(['status' => 'borrowed']);

                // Insert record into borrow_records table
                BorrowRecord::create([
                    'user_id' => $validatedData['user_id'],
                    'book_id' => $book->id,
                    'borrow_date' => $borrowDate,
                    'due_date' => $dueDate,
                    'status' => 'borrowed',
                ]);
            }
        }

        return response()->json(['message' => 'Books borrowed successfully!']);
    }
}
