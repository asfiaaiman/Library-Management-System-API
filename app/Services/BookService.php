<?php

namespace App\Services;

use App\Models\Author;
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
    public function store(array $bookData, array $authorIds): Book
    {
        $book = Book::create($bookData);
        $book->authors()->attach($authorIds); // Attach authors to the book

        return $book;
    }
    // Updating a book
    public function update(array $bookData, Book $book): Book
    {
        // Update book attributes
        $book->update($bookData);

        // Sync authors for the updated book
        if (isset($bookData['authors']) && is_array($bookData['authors'])) {
            $book->authors()->sync($bookData['authors']);
        }

        return $book->fresh();
    }
    // Deleting a book
    public function delete(Book $book): bool
    {
        $book->authors()->detach(); // Detach book from authors
        return $book->delete();
    }
    // Searching books
    public function searchByTitleAndAuthor(string $title, $authors)
    {
        return Book::where('title', $title)
        ->whereHas('authors', function ($query) use ($authors) {
            $query->whereIn('authors.id', $authors->pluck('id')->toArray());
        })
        ->with('authors:id,first_name,last_name') // Eager loading the authors with specific columns
        ->get(['id', 'title']);
    }
    // Fetch book by author name
    public function fetchBooksByAuthor(Author $author)
    {
        return $author->books()->get();
    }
}
