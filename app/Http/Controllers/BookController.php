<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Resources\BookCollection;
use App\Http\Resources\BookResource;
use App\Models\Author;
use App\Models\Book;
use App\Services\BookService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BookController extends Controller
{
    /**
     * Injecting the BookService class into the constructor.
     * Then, we have access to the service in whatever methods we need
     */

    private BookService $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    /**
     * Books
     * Point 2: Retrieving all Books
     */
    public function index()
    {
        return response()->json(BookResource::collection($this->bookService->getAllBooks()),
        Response::HTTP_OK) ;
    }

    /**
     * Books
     * Point 2: Creating/Storing a Book
     */
    public function store(StoreBookRequest $request)
    {
        $validatedData = $request->validated();
        $book = $this->bookService->store($validatedData, $validatedData['authors']);
        return response()->json(new BookResource($book), Response::HTTP_CREATED);
    }

    /**
     * Books
     * Point 2: Updating a Book
     */
    public function update(StoreBookRequest $request, Book $book)
    {
        // Check if the book exists
        if (!$book) {
            return response()->json(['error' => 'Book not found'], Response::HTTP_NOT_FOUND);
        }

        // If book exists
        return response()->json(new BookResource($this->bookService->update($request->validated(), $book)),
        Response::HTTP_OK);
    }

    /**
     * Books
     * Point 2: Deleting a Book
     */
    public function destroy(Book $book)
    {
        // Check if the book exists
        if (!$book) {
            return response()->json(['error' => 'Book not found'], Response::HTTP_NOT_FOUND);
        }

        // If book exists
        $this->bookService->delete($book);

        return response()->json(['message' => 'Book deleted successfully'],
        Response::HTTP_OK);
    }

    /**
     * Books
     * Point 3: Implementing a feature to search for books by title and author
    */
    public function search(Request $request)
    {
        $title = $request->input('title');
        $authorIds = $request->input('authors');

        $authors = Author::whereIn('id', $authorIds)->get();
        $books = $this->bookService->searchByTitleAndAuthor($title, $authors);

        if ($books->isEmpty()) {
            return response()->json(['error' => 'No books found for the specified title and author'], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['books' => $books], Response::HTTP_OK);
    }

    /**
     * Books
     * Point 4:  fetching all books by a particular author
    */
    public function fetchBooksByAuthor(Author $author)
    {
        $books = $this->bookService->fetchBooksByAuthor($author);
        return response()->json(BookResource::collection($books), Response::HTTP_OK);
    }
}
