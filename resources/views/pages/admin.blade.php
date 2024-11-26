@extends('layouts.app')

@section('content')
<section class="admin-layout">
    <section class="left-sidebar">
        <h1>Admin Page</h1>
    </section>
    <section id="watchlist">
        <h2>Watchlist</h2>
        <section id="watchlist-container">
            @if($users->isEmpty())
                <p>No users in the watchlist.</p>
            @else
                @foreach($users as $user)
                    @include('partials.user', ['user' => $user, 'isInWatchlist' => $user->isInWatchlist])
                @endforeach
            @endif
        </section>
    </section>
</section>
@endsection