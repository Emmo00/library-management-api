<?php

namespace App\Http\Controllers;

use App\Enums\BookStatus;
use App\Http\Requests\BorrowBookRequest;
use App\Http\Requests\SearchBookRequest;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Author;
use App\Models\Book;
use App\Models\BorrowRecord;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

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
        $author = Author::find($request->author_id);
        if (!$author) return respondError("Author does not exist", 404);

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
        if ($request->author_id) {
            $author = Author::find($request->author_id);
            if (!$author) return respondError("Author does not exist", 404);
        }

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
    public function borrowBook(Book $book, BorrowBookRequest $request)
    {
        if ($book->status === BookStatus::AVAILABLE) {
            $borrowRecord = new BorrowRecord();
            $borrowRecord->user_id = auth()->user()->id;
            $borrowRecord->book_id = $book->id;
            $borrowRecord->borrowed_at = Carbon::now();
            $borrowRecord->due_at = Carbon::parse($request->due_date);
            $borrowRecord->save();
            $book->borrowMe();
            return respondSuccess("Book status updated successfully", $book);
        }
        return respondError("Book is unavailable", 403);
    }

    /**
     * Return a book
     */
    public function returnBook(Book $book)
    {
        if ($book->status === BookStatus::BORROWED) {
            $borrowRecord = BorrowRecord::where([
                'user_id' => auth()->user()->id,
                'book_id' => $book->id
            ])->first();
            if (!$borrowRecord) {
                return respondError("You didn't borrow this book", 403);
            }
            $borrowRecord->returned_at = Carbon::now();
            $borrowRecord->save();
            $book->returnMe();

            return respondSuccess("Book status updated successfully", $book);
        }
        return respondError("This book hasn't been borrowed", 400);
    }


    public function search(SearchBookRequest $request)
    {
        $page = $request->page ?? 1;
        $perPage = $request->perPage ?? 15;
        $resultsTitle = Book::whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($request->q) . '%'])->get();


        $resultISBN = Book::where('isbn', 'like', $request->q . '%')->get();

        $resultAuthor = Book::whereHas('author', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->q . '%');
        })->get();

        $books = $resultsTitle->merge($resultAuthor)->merge($resultISBN)->unique('id');

        if (!$books || $books->isEmpty()) {
            return respondError('No books found matching your search criteria.', 404);
        }
        $books = $this->paginate($books, $perPage, $page);
        return respondSuccess("Query results for " . $request->q, $books);
    }

    /**
     * Helper function to manually paginate a collection.
     *
     * @param \Illuminate\Support\Collection $items
     * @param int $perPage
     * @param int $currentPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    protected function paginate(Collection $items, $perPage, $currentPage)
    {
        $offset = ($currentPage - 1) * $perPage;
        return new \Illuminate\Pagination\LengthAwarePaginator(
            $items->slice($offset, $perPage)->values(),
            $items->count(),
            $perPage,
            $currentPage,
            ['path' => url()->current()]
        );
    }
}
