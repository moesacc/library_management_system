<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Foundation\Response\ApiResponse;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\Api\CategoryStoreRequest;
use App\Http\Requests\Api\CategoryUpdateRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource [Categoy].
     */
    public function index()
    {
        $query = Category::query()
            ->whereDoesntHave('parent')
            ->with(['children'])->latest()->paginate(30);
        $category = CategoryResource::collection($query);

        return ApiResponse::respondWithSuccess($category);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryStoreRequest $request)
    {
        $validated = $request->validated();
        $created = Category::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        return ApiResponse::respondCreated(CategoryResource::make($created),'Craete Category Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $query = $category->load(['parent','children']);
        $category = CategoryResource::make($query);

        return ApiResponse::respondWithSuccess($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryUpdateRequest $request, Category $category)
    {
        $validated = $request->validated();
        $category->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        return ApiResponse::respondWithSuccess(CategoryResource::make($category));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return ApiResponse::respondOk('Category Deleted Successfully');
    }
}
