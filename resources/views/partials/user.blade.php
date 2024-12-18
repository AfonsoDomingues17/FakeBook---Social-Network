<article class="user" data-id="{{ $user->id }}">
    <a href="{{route('profile',['user_id' => $user->id])}}">
        <section id="info">
            <img src="{{ route('userphoto', ['user_id' => $user->id]) }}" width="100"  height="100" alt="user profile picture">
            <div class="user-info">
                <span id="user"><p>{{$user->username}}</p></span>
                <span id="nome"><p>{{$user->name}}</p></span>
            </div>
        
    
            @if (Auth::check() && !Auth::user()->isAdmin())
                @if(Auth::user()->isFollowing($user->id))
                    <button class="unfollow" id="unfollow" data-user-id="{{$user->id}}">Following</button>
                @elseif(Auth::user()->hasSentFollowRequestTo($user->id))
                    <button class="pending" id="pending" data-user-id="{{$user->id}}">Pending</button>
                @else
                    <button id="Follow" data-user-id="{{$user->id}}">Follow</button>
                @endif    
            @endif
            @if (Auth::check() && Auth::user()->isAdmin())
            <div class="watchList" id="watchlist-actions-{{ $user->id }}" data-user-id="{{ $user->id }}">
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
        </section>
        </a> 
    
</article>