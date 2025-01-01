<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BorrowRecord;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::orderBy('title')->paginate(10); // Paginate the list of books
        return view('books.index', compact('books'));
    }

    public function create()
    {
        return view('books.create');
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'title' => 'required|string|max:255',
    //         'author' => 'required|string|max:255',
    //         'isbn' => 'required|string|unique:Sbooks',
    //         'published_year' => 'required|integer',
    //         'category' => 'required|string',
    //         'status' => 'required|string',
    //     ]);

    //     Book::create($request->all());

    //     return redirect()->route('books.index')->with('success', 'Book added successfully!');
    // }
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

    //latest
    // public function store(Request $request)
    // {
    //     // Validate the request input
    //     $request->validate([
    //         'title' => 'required|string|max:255',
    //         'author' => 'required|string|max:255',
    //         'isbn' => 'required|string|max:255',
    //         'barcode' => 'required|string|unique:books,barcode',
    //         'published_year' => 'required|integer',
    //         'category' => 'required|string|max:255',
    //         'description' => 'nullable|string',
    //         'status' => 'required|in:available,borrowed,reserved',
    //         'image_url' => 'nullable|string|max:255'
    //     ]);

    //     // Store the book in the database
    //     Book::create([
    //         'title' => $request->title,
    //         'author' => $request->author,
    //         'isbn' => $request->isbn,
    //         'barcode' => $request->barcode,
    //         'published_year' => $request->published_year,
    //         'category' => $request->category,
    //         'description' => $request->description,
    //         'status' => $request->status,
    //         'image_url' => $request->image_url
    //     ]);

    //     // Redirect back with success message
    //     return redirect()->route('books.index')->with('success', 'Book added successfully!');
    // }

    public function store(Request $request)
    {
        // Validate the request input
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|max:255',
            'barcode' => 'required|string|unique:books,barcode',
            'published_year' => 'required|integer',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:available,borrowed,reserved',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Handle image upload
        $coverImagePath = null;
        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $fileName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('storage/book-covers'), $fileName);
            $coverImagePath = 'storage/book-covers/' . $fileName;
        }

        // Store the book in the database
        Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'isbn' => $request->isbn,
            'barcode' => $request->barcode,
            'published_year' => $request->published_year,
            'category' => $request->category,
            'description' => $request->description,
            'status' => $request->status,
            'cover_image' => $coverImagePath
        ]);

        return redirect()->route('books.index')->with('success', 'Book added successfully!');
    }

    public function edit($id)
    {
        $book = Book::findOrFail($id);
        return view('books.edit', compact('book'));
    }

    //latest
    // public function update(Request $request, $id)
    // {
    //     $book = Book::findOrFail($id);

    //     // Validate the request input
    //     $request->validate([
    //         'title' => 'required|string|max:255',
    //         'author' => 'required|string|max:255',
    //         'isbn' => 'required|string|max:255',
    //         'barcode' => 'required|string|unique:books,barcode,' . $id,
    //         'published_year' => 'required|integer',
    //         'category' => 'required|string|max:255',
    //         'description' => 'nullable|string',
    //         'status' => 'required|in:available,borrowed,reserved',
    //         'image_url' => 'nullable|string|max:255'
    //     ]);

    //     // Update the book
    //     $book->update([
    //         'title' => $request->title,
    //         'author' => $request->author,
    //         'isbn' => $request->isbn,
    //         'barcode' => $request->barcode,
    //         'published_year' => $request->published_year,
    //         'category' => $request->category,
    //         'description' => $request->description,
    //         'status' => $request->status,
    //         'image_url' => $request->image_url
    //     ]);

    //     return redirect()->route('books.index')->with('success', 'Book updated successfully!');
    // }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        // Validate the request input
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|max:255',
            'barcode' => 'required|string|unique:books,barcode,' . $id,
            'published_year' => 'required|integer',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:available,borrowed,reserved',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Handle image upload
        if ($request->hasFile('cover_image')) {
            // Delete old image if exists
            if ($book->cover_image && file_exists(public_path($book->cover_image))) {
                unlink(public_path($book->cover_image));
            }

            // Upload new image
            $image = $request->file('cover_image');
            $fileName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('storage/book-covers'), $fileName);
            $coverImagePath = 'storage/book-covers/' . $fileName;
        }

        // Update the book
        $book->update([
            'title' => $request->title,
            'author' => $request->author,
            'isbn' => $request->isbn,
            'barcode' => $request->barcode,
            'published_year' => $request->published_year,
            'category' => $request->category,
            'description' => $request->description,
            'status' => $request->status,
            'cover_image' => $request->hasFile('cover_image') ? $coverImagePath : $book->cover_image
        ]);

        return redirect()->route('books.index')->with('success', 'Book updated successfully!');
    }

    //latest
    // public function destroy($id)
    // {
    //     $book = Book::findOrFail($id);

    //     // Check if the book has any active borrow records
    //     $hasActiveBorrows = BorrowRecord::where('book_id', $id)
    //         ->where('status', 'borrowed')
    //         ->exists();

    //     if ($hasActiveBorrows) {
    //         return redirect()->route('books.index')
    //             ->with('error', 'Cannot delete book while it is borrowed.');
    //     }

    //     // Delete the book
    //     $book->delete();

    //     return redirect()->route('books.index')
    //             ->with('success', 'Book deleted successfully!');
    // }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);

        // Check if the book has any active borrow records
        $hasActiveBorrows = BorrowRecord::where('book_id', $id)
            ->where('status', 'borrowed')
            ->exists();

        if ($hasActiveBorrows) {
            return redirect()->route('books.index')
                ->with('error', 'Cannot delete book while it is borrowed.');
        }

        // Delete the book cover image if it exists
        if ($book->cover_image && file_exists(public_path($book->cover_image))) {
            unlink(public_path($book->cover_image));
        }

        // Delete the book
        $book->delete();

        return redirect()->route('books.index')
            ->with('success', 'Book deleted successfully!');
    }



    // public function borrowBooks(Request $request)
    // {
    //     $books = $request->input('books'); // Array of borrowed books

    //     foreach ($books as $bookData) {
    //         $book = Book::find($bookData['id']);
    //         if ($book && $book->isAvailable()) {
    //             $book->update([
    //                 'status' => 'borrowed',
    //                 'last_borrowed_at' => now(),
    //             ]);
    //         }
    //     }

    //     return response()->json(['message' => 'Books borrowed successfully!']);
    // }

    public function borrowBooks(Request $request)
    {
        $books = $request->input('books'); // Array of borrowed books
        $unavailableBooks = [];

        foreach ($books as $bookData) {
            $book = Book::find($bookData['id']);
            if (!$book || $book->status !== 'available') {
                $unavailableBooks[] = $book ? $book->title : 'Unknown Book';
            }
        }

        if (count($unavailableBooks) > 0) {
            return response()->json([
                'message' => 'Some books are not available for borrowing.',
                'unavailable_books' => $unavailableBooks
            ], 400); // Bad Request
        }

        foreach ($books as $bookData) {
            $book = Book::find($bookData['id']);
            if ($book) {
                $book->update([
                    'status' => 'borrowed',
                    'last_borrowed_at' => now(),
                ]);
            }
        }

        return response()->json(['message' => 'Books borrowed successfully!']);
    }

    public function catalog(Request $request)
    {
        // Fetch categories and filter books
        $categories = Book::distinct('category')->pluck('category');
        $books = Book::query();

        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $books->where('category', $request->category);
        }

        // Search for books
        if ($request->has('search') && $request->search != '') {
            $books->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('author', 'like', '%' . $request->search . '%')
                ->orWhere('isbn', 'like', '%' . $request->search . '%');
        }

        $books = $books->paginate(12);

        return view('books.catalog', compact('books', 'categories'));
    }
}
