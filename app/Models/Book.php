<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'isbn',
        'publication_date',
    ];

    /**
     * The authors who have written multiple books.
     */
    public function authors(): BelongsToMany
    {
        // author_book is a pivot table, authors_id is foreignPivotKey, books_id is relatedPivotKey
        return $this->belongsToMany(Author::class, 'author_book', 'author_id', 'book_id');
    }
}
