<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAuthorRequest;
use App\Http\Resources\AuthorResource;
use App\Models\Author;
use App\Services\AuthorService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthorController extends Controller
{
    /**
     * Injecting the AuthorService class into the constructor.
     * Then, we have access to the service in whatever methods we need
     */

     private AuthorService $authorService;

     public function __construct(AuthorService $authorService)
     {
         $this->authorService = $authorService;
     }

    /**
     * Authors
     * Point 2: Retrieving all Authors
     */
    public function index()
    {
        return response()->json(AuthorResource::collection($this->authorService->getAllAuthors()),
        Response::HTTP_OK) ;
    }

    /**
     * Authors
     * Point 2: Creating/Storing an Author
     */
    public function store(StoreAuthorRequest $request)
    {
        return response()->json(new AuthorResource($this->authorService->create($request->validated())),
        Response::HTTP_CREATED);
    }

    /**
     * Books
     * Point 2: Updating an Author
     */
    public function update(StoreAuthorRequest $request, Author $author)
    {
        // Check if the author exists
        if (!$author) {
            return response()->json(['error' => 'Author not found'], Response::HTTP_NOT_FOUND);
        }

        // If author exists
        return response()->json(new AuthorResource($this->authorService->update($request->validated(), $author)),
        Response::HTTP_OK);
    }

    /**
     * Authors
     * Point 2: Deleting an Author
     */
    public function destroy(Author $author)
    {
        // Check if the author exists
        if (!$author) {
            return response()->json(['error' => 'Author not found'], Response::HTTP_NOT_FOUND);
        }

        // If author exists
        $this->authorService->delete($author);

        return response()->json(['message' => 'Author deleted successfully'],
        Response::HTTP_OK);
    }
}
