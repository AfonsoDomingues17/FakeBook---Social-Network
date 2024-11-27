<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\Group;
use App\Models\Watchlist;
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

            $users = $usersQuery->map(function ($user) {
                $isInWatchlist = false;
                if (Auth::check() && Auth::user()->isAdmin()) {
                    $isInWatchlist = Watchlist::where('admin_id', Auth::id())->where('user_id', $user->id)->exists();
                }
                $user->isInWatchlist = $isInWatchlist;
                return $user;
            });
        } elseif ($type === 'posts') {
            $sanitizedQuery = preg_replace('/[^\w\s]/', ' ', $query);
            $tsQuery = str_replace(' ', ' OR ', $sanitizedQuery);

            $posts = Post::where(function($query) use ($tsQuery) {
                $query->whereRaw("tsvectors @@ websearch_to_tsquery('english', ?)", [$tsQuery])
                    ->orWhereRaw("similarity(description, ?) > 0.3", [$tsQuery]);
            })->paginate(10, ['*'], 'page', $page);
        } elseif ($type === 'groups') {
            $sanitizedQuery = preg_replace('/[^\w\s]/', ' ', $query);
            $tsQuery = str_replace(' ', ' OR ', $sanitizedQuery);
            $groups = Group::where(function($query) use ($tsQuery) {
                $query->whereRaw("tsvectors @@ websearch_to_tsquery('english', ?)", [$tsQuery])
                    ->orWhereRaw("similarity(name, ?) > 0.3", [$tsQuery]);
            })->paginate(10, ['*'], 'page', $page);
        }
        Log::info($query);
        Log::info($users);
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