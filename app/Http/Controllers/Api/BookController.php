<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use App\Models\Copy;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Http\Requests\BookStoreRequest;
use App\Foundation\Response\ApiResponse;
use App\Http\Requests\BookUpdateRequest;
use App\Http\Resources\BorrowingResource;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Book::query()
            ->with(['author','category','copies'])
            ->latest('published_at')->paginate(30);
            // ->toResourceCollection();
        $book = BookResource::collection($query);

        return ApiResponse::respondWithSuccess($book);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookStoreRequest $request)
    {
        $validated = $request->validated();
        $created = Book::create([
            'title' => $validated['title'],
            'summary' => $validated['summary'],
            'published_at' => $validated['published_at'],
            'author_id' => $validated['author_id'],
            'category_id' => $validated['category_id'],
        ]);
        for ($i = 0; $i < $validated['copies']; $i++) {
            $created->copies()->create([
                'condition' => 'new',
                'availability' => 'available',
            ]);
        }

        return ApiResponse::respondCreated(BookResource::make($created),'Craete Book Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $show = BookResource::make($book->load(['author','category','copies']));
        return ApiResponse::respondWithSuccess($show);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookUpdateRequest $request, Book $book)
    {
        $validated = $request->validated();
        $book->update([
            'title' => $validated['title'],
            'summary' => $validated['summary'],
            'published_at' => $validated['published_at'],
            'author_id' => $validated['author_id'],
            'category_id' => $validated['category_id'],
        ]);
        for ($i = 0; $i < $validated['copies']; $i++) {
            $book->copies()->create([
                'condition' => 'new',
                'availability' => 'available',
            ]);
        }

        return ApiResponse::respondWithSuccess(BookResource::make($book));

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();
        return ApiResponse::respondOk('Book Deleted Successfully');
    }

    public function listOfBorrow(Request $request)
    {
        $borrowings = Borrowing::with(['user', 'copy.book'])
            ->latest('borrowed_at')
            ->paginate(30);
    
        return ApiResponse::respondWithSuccess(BorrowingResource::collection($borrowings));
    }
    
    public function detailOfBorrow(Borrowing $borrowing)
    {
        $borrowing->load(['user', 'copy.book']);
    
        return ApiResponse::respondWithSuccess(BorrowingResource::make($borrowing));
    }    
}
