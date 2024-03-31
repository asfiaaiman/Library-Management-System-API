<?php

namespace App\Services;

use App\Models\Author;
use Illuminate\Database\Eloquent\Collection;

class AuthorService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    // Retrieving all authors
    public function getAllAuthors() : Collection
    {
        return Author::with('books')->get();
    }

    // Creating author
    public function create(array $data): Author
    {
        $author = Author::create($data);

        if (isset($data['books']) && is_array($data['books'])) {
            $author->books()->attach($data['books']);
        }

        return $author;
    }

    // Updating author
    public function update(Author $author, array $data): Author
    {
        $author->update($data);

        if (isset($data['books'])) {
            $author->books()->sync($data['books']);
        }

        return $author->fresh();
    }

    // Deleting author
    public function delete(Author $author): bool
    {
        // Check if the author has written any books
        if ($author->books()->exists()) {
            // Detach the author from all books
            $author->books()->detach();
        }

        // Delete the author
        return $author->delete();
    }

}
