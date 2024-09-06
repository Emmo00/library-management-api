<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $limit = request()->limit ?? 15;
        $books = Book::paginate($limit);

        return respondSuccess("Books", $books);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
    {
        $book = Book::create($request->all());

        return respondSuccess("Book created Successfully", $book, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        return respondSuccess("Book", $book);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        $book->update($request->all());

        return respondSuccess("Book updated Successfully", $book);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();
        return respondSuccess("Book deleted successfully");
    }

    /**
     * Borrow a book
     */
    public function borrowBook(Book $book)
    {
        $book->borrow();

        return respondSuccess("Book status updated successfully", $book);
    }

    /**
     * Return a book
     */
    public function returnBook(Book $book)
    {
        $book->returned();

        return respondSuccess("Book status updated successfully", $book);
    }
}
