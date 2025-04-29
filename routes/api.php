<?php

use App\Models\Book;
use App\Models\Author;
use App\Models\Category;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ActionController;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;


Route::middleware('guest')->group(function(){
    /**
     * @unauthenticated
     */
    Route::get('/', function () {
        return ['Laravel' => app()->version()];
    })->name('api.base');
    Route::post('/register', [AuthController::class, 'register'])->name('api.register');
    Route::post('/login', [AuthController::class, 'login'])->name('api.login');
});

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('api.logout');
    Route::apiResource('users', UserController::class)->names('api.users');
    Route::apiResource('categories', CategoryController::class)->names('api.categories');
    Route::apiResource('authors',AuthorController::class)->names('api.authors');
    Route::get('books/borrowings', [BookController::class, 'listOfBorrow'])->name('api.books.borrow.index');
    Route::get('books/borrowings/{borrowing}', [BookController::class, 'detailOfBorrow'])->name('api.books.borrow.show');
    Route::apiResource('books', BookController::class)->names('api.books');
    
    // Route::post('/actions/{action}', [ActionController::class, 'handle']);
    Route::post('/actions/borrow', [ActionController::class, 'borrow']);
    Route::post('/actions/return', [ActionController::class, 'returnCopy']);

});

Route::get('/test', function () {
    $data = Category::query()
        ->with('parent')
        ->latest()->get();
    $data = Category::query()
        ->whereHas('parent')
        ->latest()->get();
    $data = Category::query()
        ->where('parent_id', null)
        ->whereHas('children')
        ->with('children.children')
        ->latest()->get();
    $data = Author::query()
        ->with('books')
        ->latest()->get();
    $data = Author::query()
        ->with('books')
        ->whereHas('books')
        ->latest()->get();
    $data = Book::query()
        ->with('author','category')
        ->latest()->get();
    dd($data->toArray());
});

// Route::post('/register', [RegisteredUserController::class, 'store'])
//     ->middleware('guest')
//     ->name('register');

// Route::post('/login', [AuthenticatedSessionController::class, 'store'])
//     ->middleware('guest')
//     ->name('login');

// Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
//     ->middleware('guest')
//     ->name('password.email');

// Route::post('/reset-password', [NewPasswordController::class, 'store'])
//     ->middleware('guest')
//     ->name('password.store');

// Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
//     ->middleware(['auth', 'signed', 'throttle:6,1'])
//     ->name('verification.verify');

// Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
//     ->middleware(['auth', 'throttle:6,1'])
//     ->name('verification.send');

// Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
//     ->middleware('auth')
//     ->name('logout');
