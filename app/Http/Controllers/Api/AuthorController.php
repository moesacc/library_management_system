<?php

namespace App\Http\Controllers\Api;

use App\Models\Author;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ApiRequest;
use App\Http\Resources\AuthorResource;
use App\Foundation\Response\ApiResponse;
use App\Http\Requests\Api\AuthorStoreRequest;
use App\Http\Requests\Api\AuthorUpdateRequest;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ApiRequest $request)
    {
        $query = Author::query()
            
            ->with(['books'])->latest()->paginate(30);
            // ->toResourceCollection();
        $author = AuthorResource::collection($query);

        return ApiResponse::respondWithSuccess($author);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AuthorStoreRequest $request)
    {
        $validated = $request->validated();
        $created = Author::create([
            'name' => $validated['name'],
            'bio' => $validated['bio'],
        ]);
        return ApiResponse::respondCreated(AuthorResource::make($created),'Craete Author Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Author $author)
    {
        $query = $author->load(['books']);
        $show = authorResource::make($query);

        return ApiResponse::respondWithSuccess($show);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AuthorUpdateRequest $request, Author $author)
    {
        $validated = $request->validated();
        $author->update([
            'name' => $validated['name'],
            'bio' => $validated['bio'],
        ]);

        return ApiResponse::respondWithSuccess(AuthorResource::make($author));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Author $author)
    {
        $author->delete();
        return ApiResponse::respondOk('Author Deleted Successfully');
    }
}
