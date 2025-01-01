<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'author',
        'isbn',
        'published_year',
        'category',
        'status',
        'barcode',
        'last_borrowed_at',
        'description',
        'image_url',
        'cover_image',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'last_borrowed_at' => 'datetime',
        'published_year' => 'integer',
    ];

    /**
     * Relationship: A book can have many borrow records.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }

    /**
     * Check if the book is available.
     *
     * @return bool
     */
    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    /**
     * Get the formatted last borrowed date.
     *
     * @return string|null
     */
    public function getLastBorrowedAtFormatted(): ?string
    {
        return $this->last_borrowed_at
            ? $this->last_borrowed_at->format('d-m-Y H:i:s')
            : null;
    }

    public function borrowRecord()
    {
        return $this->hasOne(BorrowRecord::class, 'book_id')->where('status', 'borrowed');
    }
}
