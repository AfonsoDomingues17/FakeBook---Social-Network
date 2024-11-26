@extends('layouts.app')

@section('content')

<section class="homepage-layout">
            
    <section id="feed-posts">
        <h2>
            @if($type === 'public')
                Public Posts
            @else
                Following Posts
            @endif
        </h2>
        @if($type === 'following' && !Auth::check())
            <h3>Login to see posts from accounts you follow</h3>
            <a href="{{ route('login') }}">
                <button id="follow-redirect-login">Login</button>
            </a>
        @else
        <div class="feed-posts-container">
            @each('partials.post', $posts, 'post')
            </div>
        @endif
    </section>
    <section class="suggested-users">
        <h2>Users that you may know!</h2>
        @foreach($suggestedUsers as $suggestedUser)
            @include('partials.user', ['user' => $suggestedUser, 'isInWatchlist' => $suggestedUser->isInWatchlist])
        @endforeach
    </section>
    
</section>

@endsection