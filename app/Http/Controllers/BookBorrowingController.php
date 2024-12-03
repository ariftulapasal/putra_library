<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BookBorrowingController extends Controller
{
    public function showBorrowingPage()
    {
        return view('books.borrow');
    }

    public function verifyBookBarcode(Request $request)
    {
        try {
            $request->validate([
                'barcode' => 'required|string',
            ]);

            $book = Book::where('barcode', $request->barcode)->first();

            if (!$book) {
                return response()->json([
                    'success' => false,
                    'message' => 'Book not found in the library system'
                ], 404);
            }

            // Check book availability
            $activeBorrowing = Borrowing::where('book_id', $book->id)
                ->whereNull('return_date')
                ->first();

            if ($activeBorrowing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Book is currently borrowed and not available'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'book' => [
                    'id' => $book->id,
                    'title' => $book->title,
                    'author' => $book->author,
                    'barcode' => $book->barcode
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Book verification error', [
                'error' => $e->getMessage(),
                'barcode' => $request->barcode
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while verifying the book'
            ], 500);
        }
    }

    public function borrowBook(Request $request)
    {
        try {
            $request->validate([
                'book_id' => 'required|exists:books,id',
                'user_id' => 'required|exists:users,id'
            ]);

            DB::beginTransaction();

            // Check if book is already borrowed
            $existingBorrowing = Borrowing::where('book_id', $request->book_id)
                ->whereNull('return_date')
                ->first();

            if ($existingBorrowing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Book is already borrowed'
                ], 400);
            }

            // Create borrowing record
            $borrowing = Borrowing::create([
                'book_id' => $request->book_id,
                'user_id' => $request->user_id,
                'borrow_date' => now(),
                'due_date' => now()->addDays(14) // 2 weeks borrowing period
            ]);

            // Update book status (optional)
            $book = Book::findOrFail($request->book_id);
            $book->status = 'borrowed';
            $book->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Book borrowed successfully',
                'borrowing' => [
                    'id' => $borrowing->id,
                    'book_title' => $book->title,
                    'due_date' => $borrowing->due_date->format('Y-m-d')
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Book borrowing error', [
                'error' => $e->getMessage(),
                'book_id' => $request->book_id,
                'user_id' => $request->user_id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while borrowing the book'
            ], 500);
        }
    }
}