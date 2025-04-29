<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Foundation\Response\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserLoginRequest;
use App\Http\Requests\Api\UserRegiserRequest;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    /**
     * Register User
     * 
     * @unauthenticated
     */
    
    public function register(UserRegiserRequest $request)
    {
        $request = $request->validated();

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'password' => $request['password'],
        ]);


        return ApiResponse::respondCreated($user,'User Register Successfully');
    }

    /**
     * Login with creditional
     */
    public function login(UserLoginRequest $request)
    {
        $request = $request->validated();

        User::query()
            ->where('email',$request['email'])
            ->orWhere('phone',$request['phone'])
            ->firstOrFail();

        $credentials = [
            'password' => $request['password']
        ];
        if($request['email'] !== null){
            $credentials['email'] = $request['email'];
        }
        if($request['phone'] !== null){
            $credentials['phone'] = $request['phone'];
        }

        if (Auth::attempt($credentials,true)) {
            $user = Auth::user();

            $tokenRequest = app('request')->create('/oauth/token','POST', [
                'grant_type' => 'password',
                'client_id' =>  config('passport.password_grand_client.id'),
                'client_secret' => config('passport.password_grand_client.secret'),
                'username' => $request['email'],
                'password' => $request['password'],
                'scope' => '',
            ]);
            $response = app('router')->prepareResponse($tokenRequest, app()->handle($tokenRequest));
            // dd($response);
            $tokenData = json_decode($response->getContent(), true);
            $user['token'] = $tokenData;

            return ApiResponse::respondWithSuccess($user);
        } else {
            return response()->json([
                'success' => true,
                'statusCode' => 401,
                'message' => 'Unauthorized.',
                'errors' => 'Unauthorized',
            ], 401);
        }
    }

    /**
     * Logout with token
     */
    public function logout(Request $request)
    {
        Auth::user()->tokens()->delete();
        return ApiResponse::respondOk('Success Logout');
    }
}
