<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\ItemController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home

Route::get('/', function (Request $request, UserController $userController, PostController $postController) {
    $type = $request->input('type', 'public');
    $request->merge(['type' => $type]);
    $suggestedUsers = $userController->getSuggestedUsers();
    $posts = $postController->getPosts($request);
    return view('pages.homepage', ['suggestedUsers' => $suggestedUsers, 'posts' => $posts, 'type' => $type]);
})->name('homepage');


// User
Route::get('/users/{user_id}/edit', [UserController::class, 'showEditProfileForm'])->name('editprofile');
Route::put('/users/{user_id}/edit', [UserController::class, 'updateProfile'])->name('updateprofile');
Route::get('/users/{user_id}', [UserController::class, 'showProfile'])->name('profile');
Route::get('/users/{user_id}/photo', [UserController::class, 'getPhoto'])->name('userphoto');
Route::delete('/users/{user_id}/delete', [UserController::class, 'destroy'])->name('deleteuser');
// Posts
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
Route::post('/posts/create', [PostController::class, 'store'])->name('posts.store'); 
Route::get('/posts/{post_id}', [PostController::class, 'show'])->name('posts.show');
Route::get('/posts/{post_id}/edit', [PostController::class, 'edit'])->name('posts.edit');
Route::put('/posts/{post_id}/edit', [PostController::class, 'update'])->name('posts.update');
Route::delete('/posts/{post_id}/delete', [PostController::class, 'destroy'])->name('posts.destroy');


// Admin

Route::get('/admin', [UserController::class, 'adminPage'])->name('admin.page');
Route::post('/admin/watchlist/add/{user_id}', [UserController::class, 'addToWatchlist'])->name('admin.watchlist.add');
Route::post('/admin/watchlist/remove/{user_id}', [UserController::class, 'removeFromWatchlist'])->name('admin.watchlist.remove');

// Media
Route::get('/media/{media_id}', [MediaController::class, 'show'])->name('media.show');


// Search

Route::controller(SearchController::class)->group(function () {
    Route::get('/search', 'search')->name('search');
});

// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});
