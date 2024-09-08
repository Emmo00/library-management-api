<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Models\Author;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $limit = request()->limit ?? 15;

        $authors = Author::paginate($limit);
        return respondSuccess("Authors", $authors);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAuthorRequest $request)
    {
        $author = Author::create($request->validated());

        return respondSuccess("Author created successfully", $author, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Author $author)
    {
        return respondSuccess("Author", $author);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAuthorRequest $request, Author $author)
    {
        $author->update($request->validated());

        return respondSuccess("Author updated successfully", $author);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Author $author)
    {
        $author->delete();

        return respondSuccess("Author deleted successfully");
    }
}
