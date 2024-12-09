<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\DirectChatController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\StaticPageController;
use App\Http\Controllers\GroupController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordResetController;


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
Route::post('/block/users/{user_id}', [UserController::class, 'blockUser'])->name('block');
Route::delete('/unblock/users/{user_id}', [UserController::class, 'unblockUser'])->name('unblock');
// Posts
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
Route::post('/posts/create', [PostController::class, 'store'])->name('posts.store'); 
Route::get('/posts/{post_id}', [PostController::class, 'show'])->name('posts.show');
Route::get('/posts/{post_id}/edit', [PostController::class, 'edit'])->name('posts.edit');
Route::put('/posts/{post_id}/edit', [PostController::class, 'update'])->name('posts.update');
Route::delete('/posts/{post_id}/delete', [PostController::class, 'destroy'])->name('posts.destroy');
Route::post('/post/like', [PostController::class, 'like'])->name('post.like');

//Groups
Route::post('/groups/create', [GroupController::class, 'createGroup'])->name('group.create');


// Admin
Route::get('/admin', [UserController::class, 'adminPage'])->name('admin.page');
Route::post('/admin/watchlist/add/{user_id}', [UserController::class, 'addToWatchlist'])->name('admin.watchlist.add');
Route::post('/admin/watchlist/remove/{user_id}', [UserController::class, 'removeFromWatchlist'])->name('admin.watchlist.remove');
Route::post('admin/users/create', [UserController::class, 'createUserbyAdmin'])->name('admin.create');


// Media
Route::get('/media/{post_id}', [MediaController::class, 'show'])->name('media.show');


// Search

Route::get('/search', [SearchController::class, 'search'])->name('search');

//Connections
Route::post('/follow/users/{user_id}', [UserController::class, 'follow'])->name('follow');
Route::delete('/unfollow/users/{user_id}', [UserController::class, 'unfollow'])->name('unfollow');
Route::post('/follow/accept/users/{user_id}', [UserController::class, 'acceptFollowRequest'])->name('acceptfollow');
Route::delete('/follow/decline/notifications/{notification_id}', [UserController::class, 'declineFollowRequest'])->name('declinefollow');
Route::delete('/follow/request/delete/{user_id}', [UserController::class, 'deleteFollowRequest'])->name('deletefollowrequest');


// Messages

Route::middleware('auth')->group(function () {
    Route::get('/direct-chats', [DirectChatController::class, 'index'])->name('direct_chats.index');
    Route::get('/direct-chats/{id}', [DirectChatController::class, 'show'])->name('direct_chats.show');
    Route::post('/direct-chats', [DirectChatController::class, 'store'])->name('direct_chats.store');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/image/{message_id}', [MessageController::class, 'show'])->name('messages.show');
    Route::delete('/messages/{message_id}', [MessageController::class, 'destroy'])->name('messages.destroy'); // Update this line

});


// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::post('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Password recovery related routes
Route::get('/forgot-password', [PasswordResetController::class, 'showRequestForm'])->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');
Route::get('/reset-not-found', function() { return view('errors.reset_not_found'); })->name('reset.not.found');

// Static pages related routes
Route::get('/help', [StaticPageController::class, 'showHelpPage'])->name('help');
Route::post('/help/form', [StaticPageController::class, 'sendHelpForm'])->name('help.form');
Route::post('/questions/{id}', [StaticPageController::class, 'sendQuestionResponse'])->name('question.response');
Route::get('/about', [StaticPageController::class, 'showAboutPage'])->name('about');
Route::get('/settings' , [StaticPageController::class, 'showSettingsPage'])->name('settings');