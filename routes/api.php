<?php

use App\Http\Controllers\{
    AuthorController,
    BookController
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::apiResource('/authors', AuthorController::class);
Route::resource('/books', BookController::class)->only([
    'index', 'store', 'update', 'destroy'
]);

// For searching books
Route::get('/books/search', [BookController::class, 'search'])->name('books.search');
Route::get('/authors/{author}/books', [BookController::class, 'fetchBooksByAuthor']);
