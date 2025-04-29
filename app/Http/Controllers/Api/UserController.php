<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Foundation\Response\ApiResponse;
use App\Http\Requests\Api\ApiRequest;
use App\Http\Requests\Api\UserStoreRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource [User].
     */
    public function index()
    {
        $query = User::query()->latest()->paginate(30);
        $category = UserResource::collection($query);

        return ApiResponse::respondWithSuccess($category);
    }

    /**
     * Store a newly created resource in storage.
     * 
     * Create A user From Admin level 
     * 
     * 
     */
    // @requestMediaType multipart/form-data
    public function store(UserStoreRequest $request)
    {
        $validated = $request->validated();
        // dd($validated);
        $query = User::query()
            ->create($validated);
        $user = UserResource::make($query);
        return ApiResponse::respondCreated($user,'User Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // $query = $user->load(['books']);
        $show = UserResource::make($user);

        return ApiResponse::respondWithSuccess($show);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return ApiResponse::respondOk('User Deleted Successfully');
    }
}
