<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\DirectChatController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\StaticPageController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\GroupParticipantController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\PostLikesController;
use App\Http\Controllers\CommentLikesController;


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
Route::get('/influencer/{user_id}', [UserController::class, 'showInfluencerPage'])->name('influencer.page');
// Posts
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
Route::post('/posts/create', [PostController::class, 'store'])->name('posts.store'); 
Route::get('/posts/{post_id}', [PostController::class, 'show'])->name('posts.show');
Route::get('/posts/{post_id}/edit', [PostController::class, 'edit'])->name('posts.edit');
Route::put('/posts/{post_id}/edit', [PostController::class, 'update'])->name('posts.update');
Route::delete('/posts/{post_id}/delete', [PostController::class, 'destroy'])->name('posts.destroy');
Route::post('/post/like', [PostLikesController::class, 'like'])->name('post.like');

//Groups
Route::post('/groups/create', [GroupController::class, 'createGroup'])->name('group.create');
Route::get('/groups/{group_id}/photo', [GroupController::class, 'getPhoto'])->name('groupPhoto');
Route::get('/groups/{group_id}', [GroupController::class, 'show'])->name('group.show');
Route::get('/groups/{group_id}/info', [GroupController::class, 'groupInfo'])->name('group.info');
Route::post('/groups/{group_id}/leave', [GroupController::class, 'leaveGroup'])->name('group.leave');
Route::delete('/groups/{group_id}/delete', [GroupController::class, 'deleteGroup'])->name('group.delete');
Route::post('/groups/{group_id}/update', [GroupController::class, 'updateGroup'])->name('group.update');
Route::get('/groups/{group_id}/get-members', [GroupController::class, 'getMembers'])->name('group.getMembers');
Route::get('/groups/{group_id}/add-member/{user_id}', [GroupController::class, 'addMember'])->name('group.addMember');
Route::delete('/groups/{group_id}/remove-member', [GroupParticipantController::class, 'removeMember'])->name('group.removeMember');
// Comments
Route::get('/posts/{post_id}/comments', [CommentController::class, 'getPostComments'])->name('comments.fetch');
Route::post('/comments/store', [CommentController::class, 'store'])->name('comments.store');
Route::put('/comments/{comment_id}', [CommentController::class, 'update'])->name('comments.update');
Route::delete('/comments/{comment_id}', [CommentController::class, 'destroy'])->name('comments.destroy');
Route::post('/comment/like', [CommentLikesController::class, 'like'])->name('comment.like');

// Admin
Route::middleware(['admin'])->group(function () {
    Route::get('/admin', [UserController::class, 'adminPage'])->name('admin.page');
    Route::post('/admin/watchlist/add/{user_id}', [UserController::class, 'addToWatchlist'])->name('admin.watchlist.add');
    Route::post('/admin/watchlist/remove/{user_id}', [UserController::class, 'removeFromWatchlist'])->name('admin.watchlist.remove');
    Route::post('admin/users/create', [UserController::class, 'createUserbyAdmin'])->name('admin.create');
    Route::post('admin/banlist/add/{user_id}', [UserController::class, 'banUser'])->name('admin.banlist.add');
    Route::post('admin/banlist/remove/{user_id}', [UserController::class, 'unbanUser'])->name('admin.banlist.remove');
    Route::post('admin/unban/request/{id}', [UserController::class, 'acceptUnbanRequest'])->name('admin.unban.request');
    Route::post('/admin/solveReports', [UserController::class, 'solveReports'])->name('admin.solveReports');
});

// Reports
Route::get('/reports', [ReportController::class, 'showReports'])->name('reports');
Route::post('/report/users/{user_id}', [ReportController::class, 'reportUser'])->name('report.user');
Route::post('/report/posts/{post_id}', [ReportController::class, 'reportPost'])->name('report.post');
Route::post('/report/comments/{comment_id}', [ReportController::class, 'reportComment'])->name('report.comment');
Route::get('/reports/user/{user_id}', [ReportController::class, 'getUserReports'])->name('reports.user');
Route::get('/reports/post/{post_id}', [ReportController::class, 'getPostReports'])->name('reports.post');
Route::get('/reports/comment/{comment_id}', [ReportController::class, 'getCommentReports'])->name('reports.comment');

// Media
Route::get('/media/{post_id}', [MediaController::class, 'show'])->name('media.show');


// Search
Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/advsearch', [SearchController::class, 'advancedSearch'])->name('advancedSearch');
Route::get('/following', [UserController::class, 'followingUsers'])->name('following');

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
    Route::get('/groups/{group_id}', [GroupController::class, 'show'])->name('group.show');
});

// Conversations


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