<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class BookScanController extends Controller
{
    /**
     * Display the barcode scanner view.
     */
    public function showScanner()
    {
        return view('books.scan');
    }

    /**
     * Fetch book details based on the scanned barcode.
     */
    public function fetchBook(Request $request)
    {
        $barcode = $request->input('barcode');

        // Find the book by its barcode
        $book = Book::where('barcode', $barcode)->first();

        if ($book) {
            return response()->json($book);
        } else {
            return response()->json(['error' => 'Book not found.'], 404);
        }
    }
}