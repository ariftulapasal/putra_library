<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 
        'author', 
        'isbn', 
        'published_year', 
        'category', 
        'status',
        'barcode',
        'is_available',
        'last_borrowed_at'
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'last_borrowed_at' => 'datetime'
    ];

    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }

    
}


