<?php

use App\Http\Controllers\{
    AuthorController,
    BookController,
    PatronController
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::apiResource('/authors', AuthorController::class);
Route::resource('/books', BookController::class)->only([
    'index', 'store', 'update', 'destroy'
]);
Route::resource('/patrons', PatronController::class)->only([
    'index', 'store', 'update', 'destroy'
]);

// For searching books
Route::get('/books/search', [BookController::class, 'search'])->name('books.search');
Route::get('/authors/{author}/books', [BookController::class, 'fetchBooksByAuthor']);

// Borrow Book
Route::post('patrons/{patronId}/books/{bookId}/borrow', [PatronController::class, 'borrowBook']);

// Return Book
Route::post('patrons/{patronId}/books/{bookId}/return', [PatronController::class, 'returnBook']);

