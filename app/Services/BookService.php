<?php

namespace App\Services;

use App\Models\Book;
use Illuminate\Database\Eloquent\Collection;

class BookService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    // Retrieving all Books

    public function getAllBooks(): Collection
    {
        return Book::with('authors')->get();
    }
    // Creating a book
    public function store(array $bookData): Book
    {
        $book = Book::create($bookData);

        return $book;
    }
    // Updating a book
    public function update(array $bookData, Book $book): Book
    {
        $book->update($bookData);
        return $book->fresh();
    }
    // Deleting a book
    public function delete(Book $book): bool
    {
        return $book->delete();
    }
    // Searching books
    public function searchBooks($searchTerm): Collection
    {
        return Book::where('title', $searchTerm)
            ->orWhereHas('authors', function ($query) use ($searchTerm) {
                $query->where('first_name', $searchTerm)
                    ->orWhere('last_name', $searchTerm);
            })
            ->get();
    }
    // Fetch book by author name
    public function fetchByAuthor(Author $author)
    {
        return $author->books()->get();
    }
}
