<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FaceLoginController;
use App\Http\Controllers\FaceRegistrationController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\BookScanController;
use App\Http\Controllers\ContactController;


Route::middleware(['auth','role:admin'])->prefix('/admin')->group(function(){
    // Route::get('/dashboard', function(){
    //     return view('admin.dashboard');
    //     // return "secret admin page";
    // });
    
    //admin dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    //list user
    Route::get('/list-user', [UserController::class, 'index'])->name('users');

    //list of book
    Route::resource('books', BookController::class);
    
    // Book routes
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
    Route::post('/books', [BookController::class, 'store'])->name('books.store');

    //admin show all borrow records
    Route::get('/borrow-records', [BorrowController::class, 'adminBorrowRecords'])->name('admin.borrow-records');
    Route::post('/borrow-records/return/{id}', [BorrowController::class, 'markReturned']);
    // Route::post('/admin/borrow-records/return/{id}', [BorrowController::class, 'markReturned'])->name('admin.borrow-records.return');

    Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
    Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('/admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    // Add more admin routes here
});

// User routes
Route::middleware(['auth', 'user'])->prefix('/users')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('users');
    // Add more user routes here
});


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

    Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
    Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

});


Route::middleware(['auth'])->group(function () {

    Route::get('/about-us', function () {
        return view('about-us');
    });

    //profile
    Route::get('/profile', [UserProfileController::class, 'show'])->name('profile');
    Route::post('/profile/update', [UserProfileController::class, 'update'])->name('profile.update');
    Route::get('/current-user', [UserProfileController::class, 'getCurrentUser'])->name('current.user');


    //new update 15.12.24
    Route::get('/scan', [BookScanController::class, 'showScanner'])->name('books.scan');
    Route::post('/scan', [BookScanController::class, 'fetchBook'])->name('books.fetch');

    Route::post('/books/borrow', [BookController::class, 'borrowBooks'])->name('books.borrow');

    Route::post('/borrow', [BorrowController::class, 'store'])->name('borrow.store');

    Route::get('/return', [BorrowController::class, 'showReturn'])->name('books.return');
    Route::get('/books/borrowed', [BorrowController::class, 'showBorrowed'])->name('books.borrowed');
    Route::post('/books/return', [BorrowController::class, 'returnBook'])->name('books.return');

    Route::get('/users/borrow-records', [BorrowController::class, 'showUserBorrowRecords'])->name('users.borrow.records');

    Route::get('/catalog', [BookController::class, 'catalog'])->name('books.catalog');

    //Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // //list of book
    // Route::resource('books', BookController::class);
    
    // // Book routes
    // Route::get('/books', [BookController::class, 'index'])->name('books.index');
    // Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
    // Route::post('/books', [BookController::class, 'store'])->name('books.store');

    Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});


// Route::get('/admin/listUser', [AdminController::class, 'list'])->name('admin.listUser');
// Route::get('/admin/listUser', [UserController::class, 'list'])->name('admin.listUser');

// Route::get('/', [AuthController::class, 'login'])->name('login');
// Route::post('/login', [AuthController::class, 'AuthLogin'])->name('login');
