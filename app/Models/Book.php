<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patron_id',
        'title',
        'description',
        'isbn',
        'publication_date',
        'borrowed_at',
        'due_back'
    ];

    /**
     * The authors who have written multiple books.
     */
    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class);
    }

    public function patron(): BelongsTo
    {
        return $this->belongsTo(Patron::class);
    }
}
