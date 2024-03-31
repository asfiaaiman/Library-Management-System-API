<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Patron;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class PatronService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    // Retrieving all Patrons
    public function getPatronsWithBooks(): Collection
    {
        return Patron::with('books')->get();
    }
    // Creating a Patron
    public function create(array $data)
    {
        return Patron::create($data);
    }
    // Updating Patron
    public function update(array $data, Patron $patron): Patron
    {
        $patron->update($data);

        return $patron->refresh();
    }
    // Deleting Patron
    public function deletePatron($patronId)
    {
        $patron = Patron::findOrFail($patronId);

        // Check if the patron has any associated books
        if ($patron->books()->exists()) {
            throw new \Exception('Patron cannot be deleted because they have associated books.');
        }

        // Delete the patron if no associated books exist
        $patron->delete();
    }
    // Borrow Books
    public function borrowBook($patronId, $bookId)
    {
        $book = Book::find($bookId);

        if (!$book->patron_id) {
            $book->update([
                'patron_id' => $patronId,
                'borrowed_at' => Carbon::now(),
                'due_back' => Carbon::now()->addDays(14), // Example: due back in 14 days
            ]);

            return true; // Successfully borrowed
        }

        return false; // Book is already borrowed
    }
    // Return Books
    public function returnBook($patronId, $bookId)
    {
        $book = Book::where('patron_id', $patronId)
                   ->findOrFail($bookId);

        $book->returned_at = Carbon::now();
        $book->save();

        return $book;
    }
}
