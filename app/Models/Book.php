<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use PharIo\Manifest\Author;

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
        // authors_books is a pivot table, authors_id is foreignPivotKey, books_id is relatedPivotKey
        return $this->belongsToMany(Author::class, 'authors_books', 'authors_id', 'books_id');
    }
}
