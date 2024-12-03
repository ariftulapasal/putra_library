<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BorrowRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookBorrowController extends Controller
{
    public function showBorrowForm()
    {
        return view('books.borrow');
    }

    public function processBorrow(Request $request)
    {
        try {
            $request->validate([
                'barcode' => 'required|string',
                'user_id' => 'required|integer'
            ]);

            DB::beginTransaction();

            // Find the book by barcode
            $book = Book::where('barcode', $request->barcode)->first();

            if (!$book) {
                throw new \Exception('Book not found');
            }

            if (!$book->is_available) {
                throw new \Exception('Book is currently not available for borrowing');
            }

            // Create borrow record
            BorrowRecord::create([
                'user_id' => $request->user_id,
                'book_id' => $book->id,
                'borrow_date' => now(),
                'due_date' => now()->addDays(14), // 2 weeks borrowing period
                'status' => 'borrowed'
            ]);

            // Update book availability
            $book->update([
                'is_available' => false,
                'last_borrowed_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Book borrowed successfully',
                'book' => [
                    'title' => $book->title,
                    'due_date' => now()->addDays(14)->format('Y-m-d')
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Book borrowing error', [
                'error' => $e->getMessage(),
                'barcode' => $request->barcode,
                'user_id' => $request->user_id
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}