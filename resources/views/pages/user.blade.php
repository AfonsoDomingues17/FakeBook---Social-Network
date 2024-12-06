@include('partials.create_post')

@extends('layouts.app')

@section('content')

<div class="profile">
   
    @if(isset($error_message))
        <div class="alert alert-danger">
            {{ $error_message }}
        </div>
    @else
    <section id="profile">

        <img src="{{ route('userphoto', ['user_id' => $user->id]) }}" alt="profile picture" width="200" height="200"><br>

        <div class="info">
            <div class="p1">
                <span id="username"><p>{{$user->username}}</p></span> 
                @if(Auth::check())
                    @if (Auth::user()->isAdmin() || $user->id == Auth::user()->id)
                        <a href="{{route('editprofile',['user_id' => $user->id])}}">Edit Profile</a> 
                    @endif
                    @if (Auth::user()->isAdmin())
                        <form action="{{ route('deleteuser', ['user_id' => $user->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this account? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete account</button>
                        </form>
                        <div id="watchlist-actions-{{ $user->id }}" data-user-id="{{ $user->id }}">
                            @if ($user->isInWatchlist)
                                <form id="remove-watchlist-form-{{ $user->id }}" action="{{ route('admin.watchlist.remove', ['user_id' => $user->id]) }}" method="POST" data-user-id="{{ $user->id }}">
                                    @csrf
                                    <button type="submit">Remove from Watchlist</button>
                                </form>
                            @else
                                <form id="add-watchlist-form-{{ $user->id }}" action="{{ route('admin.watchlist.add', ['user_id' => $user->id]) }}" method="POST" data-user-id="{{ $user->id }}">
                                    @csrf
                                    <button type="submit">Add to Watchlist</button>
                                </form>
                            @endif
                        </div>
                    @endif
                    @if (!Auth::user()->isAdmin() && Auth::user()->id != $user->id)
                        @if ($isBlocked)
                            <p>You have blocked this user.</p>
                            <form action="{{ route('unblock', ['user_id' => $user->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Unblock</button>
                            </form>
                        @else
                            @if(Auth::user()->isFollowing($user->id))
                                <button class="unfollow" id="unfollow" data-user-id="{{$user->id}}">Following</button>
                            @elseif(Auth::user()->hasSentFollowRequestTo($user->id))
                                <button class="pending" id="pending" data-user-id="{{$user->id}}">Pending</button>
                            @else
                                <button id="Follow" data-user-id="{{$user->id}}">Follow</button>
                            @endif
                            <form action="{{ route('direct_chats.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="recipient_id" value="{{ $user->id }}">
                                <button type="submit">Send Message</button>
                            </form>
                            <form action="{{ route('block', ['user_id' => $user->id]) }}" method="POST">
                                @csrf
                                <button type="submit">Block</button>
                            </form>
                        @endif
                    @endif
                @endif
            </div>
            <div class="numbers">
                <span><p>Publicações {{$n_posts}}</p></span>
                <span><p>Followers {{$n_followers}}</p></span>
                <span><p>Following {{$n_following}}</p></span>
            </div>
            <span id="realName"><p>{{$user->name}}</p></span>
            <span id="bio"><p>{{$user->bio}}</p></span><br>
        </div>
    </section>
    @if (!$isBlocked)
    <section id="user_posts">
        @if (Auth::check() && $user->id == Auth::user()->id) <!-- If the user is logged in and is the owner of the profile -->
            <section id="myposts">
                @if ($n_posts > 0)
                    @foreach ($posts as $post)
                        @include('partials.post', ['post' => $post])
                    @endforeach
                @else 
                    <p>You dont have any post! Post something!</p>
                @endif
            </section>
            <button id="addPost" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPostModal">
                        Add Post
            </button>
        @else
            @if ($user->is_public || (Auth::check() && Auth::user()->isAdmin()) || (Auth::check() && Auth::user()->isFollowing($user->id)))
                <section id="myposts">
                    @if ($n_posts > 0)
                        @foreach ($posts as $post)
                            @include('partials.post', ['post' => $post])
                        @endforeach
                    @else 
                        <p>This user has no posts!</p>
                    @endif
                
                </section>
            @else
                <section id="priavate_messages">
                    <p>This user profile is private!</p>
                    @if (Auth::check())
                        @if(Auth::user()->hasSentFollowRequestTo($user->id))
                            <button class="pending" id="pending" data-user-id="{{$user->id}}">Pending</button>
                        @else
                            <button id="Follow" data-user-id="{{$user->id}}">Follow</button>
                        @endif
                    @else
                        <p>Login to see more of this user</p>
                        <a href="{{url('/login')}}">Login</a>
                    @endif
                </section>
            @endif  
        @endif
    </section>
    @endif
    @endif
</div>

@include('partials.create_post')
@endsection
