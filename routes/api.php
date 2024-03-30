<?php

use App\Http\Controllers\BookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::resource('/books', BookController::class)->only([
    'index', 'store', 'update', 'destroy'
]);

// For searching books
Route::get('/books/search', [BookController::class, 'search'])->name('books.search');