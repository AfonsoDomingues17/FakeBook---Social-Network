@extends('layouts.app')

@section('content')

<section class="homepage-layout">
    @if((Auth::check() && !Auth::user()->isBanned()) || !Auth::check())
        <section id="posts">
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
                
                    @each('partials.post', $posts, 'post')
                
                @endif
            
        </section>
        <section class="suggested-users">
            
            <h3>Users that you may know!</h3>
            @foreach($suggestedUsers as $suggestedUser)
                @include('partials.user', ['user' => $suggestedUser, 'isInWatchlist' => $suggestedUser->isInWatchlist])
            @endforeach
        </section>
    @else
        <h1>You are banned!</h1>
        <p>Write an unban request <a href="/help">here</a></p>
        <img src="{{ Storage::url('BANNED.png') }}" class="img-fluid" alt="Sad frog" style="width: 25vw;">    
    @endif
</section>

@endsection