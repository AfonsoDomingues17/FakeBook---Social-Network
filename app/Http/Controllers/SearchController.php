<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\Group;
use App\Models\Watchlist;
use App\Models\PostCategory;
use Illuminate\Support\Facades\Auth;
use Log;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        Log::info('Entrou no search');
        Log::info($request);
        // Extract the type and query from the query string
        $query = $request->input('query');
        $type = $request->input('type');
        $page = $request->input('page', 1); // Get the current page or default to 1

        // Initialize an empty collection for results
        $users = collect();
        $posts = collect();
        $groups = collect();

        if ($type === 'users') {
            $usersQuery = User::where('name', 'ILIKE', '%' . $query . '%')
                        ->orWhere('email', 'ILIKE', '%' . $query . '%')
                        ->orWhere('username', 'ILIKE', '%' . $query . '%')
                        ->paginate(10, ['*'], 'page', $page);
            
            $usersWatchlist = $usersQuery->map(function ($user) {
                $isInWatchlist = false;
                if (Auth::check() && Auth::user()->isAdmin()) {
                    $isInWatchlist = Watchlist::where('admin_id', Auth::id())->where('user_id', $user->id)->exists();
                }
                $user->isInWatchlist = $isInWatchlist;
                return $user;
            });
            
            $usersFiltered = $usersWatchlist->where('typeu', '!=', 'ADMIN')->where('id', '!=', Auth::id());

            $users = $usersFiltered;
        } elseif ($type === 'posts') {
            $categories = request()->query('categories', 'all'); 
            $order = request()->query('order', 'relevance');
            if ($categories !== 'all') {
                $categories = explode(',', $categories);
            }

            $sanitizedQuery = preg_replace('/[^\w\s]/', ' ', $query);
            $tsQuery = str_replace(' ', ' OR ', $sanitizedQuery);
            if($order !== 'relevance')
                $postQuery = Post::where(function($query) use ($tsQuery) {
                    $query->whereRaw("tsvectors @@ websearch_to_tsquery('english', ?)", [$tsQuery])
                        ->orWhereRaw("similarity(description, ?) > 0.3", [$tsQuery]);
                })->orderBy('datecreation', $order)->paginate(10, ['*'], 'page', $page);
            else
                $postQuery = Post::where(function($query) use ($tsQuery) {
                    $query->whereRaw("tsvectors @@ websearch_to_tsquery('english', ?)", [$tsQuery])
                        ->orWhereRaw("similarity(description, ?) > 0.3", [$tsQuery]);
                })->paginate(10, ['*'], 'page', $page);
            
            if (Auth::check()) {
                $blockedUserIds = Auth::user()->blockedUsers()->pluck('target_user_id')->merge(Auth::user()->blockedBy()->pluck('initiator_user_id'));
                $postQuery->whereNotIn('owner_id', $blockedUserIds)
                         ->where('owner_id', '!=', Auth::id())
                         ->where('is_public', 'true');
            }

            if ($categories !== 'all') {
                $postCategorized = collect($postQuery->items())->filter(function ($post) use ($categories) {
                    return PostCategory::where('post_id', $post->id)->whereIn('category_id', $categories)->exists();
                });
            } else {
                $postCategorized = $postQuery;
            }

            $posts = $postCategorized;
        } elseif ($type === 'groups') {
            $sanitizedQuery = preg_replace('/[^\w\s]/', ' ', $query);
            $tsQuery = str_replace(' ', ' OR ', $sanitizedQuery);
            $groups = Group::where(function($query) use ($tsQuery) {
                $query->whereRaw("tsvectors @@ websearch_to_tsquery('english', ?)", [$tsQuery])
                    ->orWhereRaw("similarity(name, ?) > 0.3", [$tsQuery]);
            })->paginate(10, ['*'], 'page', $page);
        }
        if ($request->ajax()) {
            if ($type === 'users') {
                return view('partials.user', compact('users'))->render();
            } elseif ($type === 'posts') {
                return view('partials.post', compact('posts'))->render();
            } elseif ($type === 'groups') {
                return view('partials.group', compact('groups'))->render();
            }
        } else {
            return view('pages.searchpage', compact('users', 'posts', 'groups', 'type', 'query'));
        }
    }
}