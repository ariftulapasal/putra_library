<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FaceLoginController;
use App\Http\Controllers\FaceRegistrationController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookBorrowingController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowController;

// Public (Guest) Routes
Route::group(['middleware' => 'guest'], function () {
    // Home or welcome page
    // Home or welcome page
    Route::get('/', function () {
        return view('home');
    });

    Route::get('/home', function () {
        return view('home');
    });

    // Login routes
    Route::get('/login', [FaceLoginController::class, 'showLoginForm'])->name('face.login');
    Route::post('/face-login/verify', [FaceLoginController::class, 'verifyFace'])->name('face.verify');
    Route::post('/face-login/fallback', [FaceLoginController::class, 'fallbackLogin'])->name('face.fallback');


    Route::get('/face-register', [FaceRegistrationController::class, 'showRegistrationForm'])
        ->name('face.register');
    Route::post('/face-register/store', [FaceRegistrationController::class, 'store'])
        ->name('face.store');

    // // Forgot password
    // Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');
    // Route::post('/forgot-password', [AuthController::class, 'postForgotPassword'])->name('forgot-password.post');

    // // Reset password
    // Route::get('/reset/{token}', [AuthController::class, 'reset'])->name('reset');
    // Route::post('/reset/{token}', [AuthController::class, 'postReset'])->name('reset.post');
});


// Route::get('/borrowB', [BookBorrowController::class, 'showBorrowForm'])->name('borrow.show');
// Route::post('/borrow/process', [BookBorrowController::class, 'processBorrow'])->name('borrow.process');


Route::middleware(['auth'])->group(function () {

    //profile
    Route::get('/profile', [UserProfileController::class, 'show'])->name('profile');
    Route::post('/profile/update', [UserProfileController::class, 'update'])->name('profile.update');
    Route::get('/current-user', [UserProfileController::class, 'getCurrentUser'])->name('current.user');

    // Book Borrowing Routes
    // Route::get('/books/borrow', [BookBorrowingController::class, 'showBorrowingPage'])
    //     ->name('books.borrow');

    // Route::post('/books/verify-barcode', [BookBorrowingController::class, 'verifyBookBarcode'])
    //     ->name('books.verify-barcode');

    // Route::post('/books/borrow', [BookBorrowingController::class, 'borrowBook'])
    //     ->name('books.borrow-book');

    Route::get('/borrow', [BorrowController::class, 'index'])->name('borrow.index');
    Route::post('/borrow', [BorrowController::class, 'store'])->name('borrow.store');

    //Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    //list of book
    Route::resource('books', BookController::class);

    // Book routes

    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
    Route::post('/books', [BookController::class, 'store'])->name('books.store');

    Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
});

Route::get('/admin/listUser', [AdminController::class, 'list'])->name('admin.listUser');
Route::get('/admin/listUser', [UserController::class, 'list'])->name('admin.listUser');

// Route::get('/', [AuthController::class, 'login'])->name('login');
// Route::post('/login', [AuthController::class, 'AuthLogin'])->name('login');

