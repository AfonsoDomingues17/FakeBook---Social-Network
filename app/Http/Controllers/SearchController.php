<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SearchRequest;
use App\Models\User;
use App\Models\Post;
use App\Models\Group;
use App\Models\Watchlist;
use App\Models\PostCategory;
use Illuminate\Support\Facades\Auth;
use Log;
use App\Models\Category;
use App\Models\Connection;

class SearchController extends Controller
{
    public function search(SearchRequest $request)
    {
        // Extract the type and query from the query string
        $query = $request->input('query');
        $type = $request->input('type');
        $page = $request->input('page', 1); // Get the current page or default to 1

        // Initialize an empty collection for results
        $users = collect();
        $posts = collect();
        $groups = collect();

        if ($type === 'users') {
            $countries = request()->query('countries');
            $group = request()->query('group');
        
            if ($countries) {
                $countries = explode(',', $countries);
            }
        
            $usersQuery = User::where(function($q) use ($query) { 
                $q->where('name', 'ILIKE', '%' . $query . '%')
                    ->orWhere('email', 'ILIKE', '%' . $query . '%')
                    ->orWhere('username', 'ILIKE', '%' . $query . '%');
            })
            ->where('name', '!=', 'Anonymous');
        
            if ($countries) {
                $usersQuery = $usersQuery->whereIn('country_id', $countries);
            }
        
            $usersPaginated = $usersQuery->paginate(15, ['*'], 'page', $page);
        
            $usersWatchlist = $usersPaginated->map(function ($user) {
                $isInWatchlist = false;
                if (Auth::check() && Auth::user()->isAdmin()) {
                    $isInWatchlist = Watchlist::where('admin_id', Auth::user()->id)->where('user_id', $user->id)->exists();
                }
                $user->isInWatchlist = $isInWatchlist;
                return $user;
            });
        
            $usersFiltered = $usersWatchlist->where('typeu', '!=', 'ADMIN')->where('id', '!=', Auth::id());
        
            if ($group) {
                $users = $usersFiltered->filter(function ($user) {
                    return Connection::where('initiator_user_id', Auth::id())
                        ->where('target_user_id', $user->id)
                        ->exists();
                });
            } else {
                $users = $usersFiltered;
            }
        } elseif ($type === 'posts') {
            $categories = request()->query('categories'); 
            $order = request()->query('order', 'relevance');
        
            if ($categories) {
                $categories = explode(',', $categories);
            }
        
            if ($query) {
                $sanitizedQuery = preg_replace('/[^\w\s]/', ' ', $query);
                $tsQuery = str_replace(' ', ' OR ', $sanitizedQuery);
                if ($order !== 'relevance') {
                    $postQuery = Post::where(function($query) use ($tsQuery) {
                        $query->whereRaw("tsvectors @@ websearch_to_tsquery('english', ?)", [$tsQuery])
                            ->orWhereRaw("similarity(description, ?) > 0.3", [$tsQuery]);
                    })->orderBy('datecreation', $order)->paginate(10, ['*'], 'page', $page);
                } else {
                    $postQuery = Post::where(function($query) use ($tsQuery) {
                        $query->whereRaw("tsvectors @@ websearch_to_tsquery('english', ?)", [$tsQuery])
                            ->orWhereRaw("similarity(description, ?) > 0.3", [$tsQuery]);
                    })->paginate(10, ['*'], 'page', $page);
                }
            } else {
                // If query is null, return all posts
                if ($order !== 'relevance') {
                    $postQuery = Post::orderBy('datecreation', $order)->paginate(10, ['*'], 'page', $page);
                } else {
                    $postQuery = Post::paginate(10, ['*'], 'page', $page);
                }
            }
        
            if (Auth::check()) {
                $blockedUserIds = Auth::user()->blockedUsers()->pluck('target_user_id')->merge(Auth::user()->blockedBy()->pluck('initiator_user_id'));
                $postQuery->whereNotIn('owner_id', $blockedUserIds)
                         ->where('owner_id', '!=', Auth::id())
                         ->where('is_public', 'true');
            }
        
            if ($categories) {
                $postCategorized = collect($postQuery->items())->filter(function ($post) use ($categories) {
                    return PostCategory::where('post_id', $post->id)->whereIn('category_id', $categories)->exists();
                });
            } else {
                $postCategorized = $postQuery;
            }

            $posts = $postCategorized->filter(function ($post) {
                if ($post->is_public) {
                    return true;
                }
                else 
                    return ((Auth::check() && $post->owner_id === Auth::id()) || Auth::check() && Auth::user()->isAdmin());
            });
        
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

    public function advancedSearch(SearchRequest $request)
    {
        $type = $request->input('type');
        $page = $request->input('page', 1);
                
        // Initialize an empty collection for results
        $users = collect();
        $posts = collect();
        $groups = collect();

        if ($type === 'users') {
            $country = $request->input('user_country');
            $fullname = $request->input('user_fullname');
            $username = $request->input('user_username');

            $users = User::query();

            if ($country) {
                $users->where('country_id', $country);
            }

            if ($fullname) {
                $users->where('name', 'ILIKE', '%' . $fullname . '%');
            }

            if ($username) {
                $users->where('username', 'ILIKE', '%' . $username . '%');
            }
            $users = $users->where('name', '!=', 'Anonymous');
            $users = $users->paginate(15, ['*'], 'page', $page);
            

            $usersWatchlist = $users->map(function ($user) {
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
            $category = $request->input('post_category');
            $description = $request->input('post_description');
            $postType = $request->input('post_type');

            $posts = Post::query();

            if ($description) {
                $posts->where('description', 'ILIKE', '%' . $description . '%');
            }

            if ($type) {
                $posts->where('typep', 'ILIKE', '%' . $postType . '%');
            }

            $posts = $posts->paginate(10, ['*'], 'page', $page);

            if ($category) {
                $postCategorized = collect($posts->items())->filter(function ($post) use ($category) {
                    return PostCategory::where('post_id', $post->id)->where('category_id', $category)->exists();
                });
                $posts = $postCategorized;
            }

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
            return view('pages.advancedsearchpage', compact('users', 'posts', 'groups', 'type'));
        }
    }
}