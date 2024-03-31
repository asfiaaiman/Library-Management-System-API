<?php

namespace App\Services;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

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
        return Cache::remember('all_books', 60, function () {
            return Book::with('authors')->get(['id', 'title', 'description', 'isbn', 'publication_date']);
        });
    }
    // Creating a book
    public function store(array $bookData, array $authorIds): Book
    {
        return Cache::remember('created_book', 60, function () use ($bookData, $authorIds) {
            $book = Book::create($bookData);
            $book->authors()->attach($authorIds); // Attach authors to the book

            return $book;
        });
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

        // Cache the updated book data
        Cache::put('book_' . $book->id, $book, 60); // Cache for 60 minutes

        return $book->fresh(); // Return the fresh instance of the book
    }
    // Deleting a book
    public function delete(Book $book): bool
    {
        // Check if the book is currently borrowed
        if (Cache::has('book_borrowed_' . $book->id)) {
            return false; // Book cannot be deleted if borrowed
        }

        $book->authors()->detach(); // Detach book from authors
        $deleted = $book->delete();

        if ($deleted) {
            // Cache the fact that the book is not borrowed
            Cache::put('book_borrowed_' . $book->id, false, 60); // Cache for 60 minutes
        }

        return $deleted;
    }
    // Searching books
    public function searchByTitleAndAuthor(string $title, $authors)
    {
        $cacheKey = 'search_' . md5($title . serialize($authors));

        return Cache::remember($cacheKey, 60, function () use ($title, $authors) {
            return Book::where('title', $title)
                ->whereHas('authors', function ($query) use ($authors) {
                    $query->whereIn('authors.id', $authors->pluck('id')->toArray());
                })
                ->with('authors:id,first_name,last_name') // Eager loading the authors with specific columns
                ->get(['id', 'title']);
        });
    }
    // Fetch book by author name
    public function fetchBooksByAuthor(Author $author)
    {
        $cacheKey = 'author_books_' . $author->id;

        return Cache::remember($cacheKey, 60, function () use ($author) {
            return $author->books()->get();
        });
    }
}
