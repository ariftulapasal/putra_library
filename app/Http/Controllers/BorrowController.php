<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Borrow;

class BorrowController extends Controller
{
    public function index()
    {
        return view('borrow.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'isbn' => 'required|exists:books,isbn',
        ]);

        $book = Book::where('isbn', $request->isbn)->first();

        if ($book->status != 'available') {
            return back()->with('error', 'Book is not available for borrowing.');
        }

        // Update book status
        $book->update(['status' => 'borrowed']);

        // Record the borrowing
        Borrow::create([
            'book_id' => $book->id,
            'user_id' => auth()->id(),
            'borrow_date' => now(),
        ]);

        return back()->with('success', 'Book borrowed successfully!');
    }
}

