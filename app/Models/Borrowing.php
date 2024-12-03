<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id', 
        'user_id', 
        'borrow_date', 
        'due_date', 
        'return_date'
    ];

    protected $dates = [
        'borrow_date',
        'due_date',
        'return_date'
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Check if book is overdue
    public function isOverdue()
    {
        return $this->due_date < now() && !$this->return_date;
    }

    // Scope for active borrowings
    public function scopeActive($query)
    {
        return $query->whereNull('return_date');
    }
}