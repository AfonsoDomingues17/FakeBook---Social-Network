<article class="user" data-id="{{ $user->id }}">
    <a href="{{route('profile',['user_id' => $user->id])}}">
    <section id="info">
        <img src="{{ route('userphoto', ['user_id' => $user->id]) }}" width="100" alt="">
        <span id="user"><p>{{$user->username}}</p></span>
        <span id="nome"><p>{{$user->name}}</p></span>
    </section>
    </a>
    @if (Auth::check() && !Auth::user()->isAdmin())
    <button>Follow</button>
    @endif
    @if (Auth::check() && Auth::user()->isAdmin())
        @if ($isInWatchlist)
        <form action="{{ route('admin.watchlist.remove') }}" method="POST">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <button type="submit">Remove from Watchlist</button>
        </form>
        @else
        <form action="{{ route('admin.watchlist.add') }}" method="POST">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <button type="submit">Add to Watchlist</button>
        </form>
        @endif
    @endif
</article>