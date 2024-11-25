<script src="{{ asset('js/watchlist.js') }}"></script>
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
    <div id="watchlist-actions-{{ $user->id }}" data-user-id="{{ $user->id }}">
            @if ($isInWatchlist)
                <form id="remove-watchlist-form-{{ $user->id }}" action="{{ route('admin.watchlist.remove', ['user_id' => $user->id]) }}" method="POST">
                    @csrf
                    <button type="submit">Remove from Watchlist</button>
                </form>
            @else
                <form id="add-watchlist-form-{{ $user->id }}" action="{{ route('admin.watchlist.add', ['user_id' => $user->id]) }}" method="POST">
                    @csrf
                    <button type="submit">Add to Watchlist</button>
                </form>
            @endif
        </div>
    @endif
</article>