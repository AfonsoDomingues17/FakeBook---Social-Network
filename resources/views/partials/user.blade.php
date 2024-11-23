<article class="user" data-id="{{ $user->id }}">
    <a href="{{route('profile',['user_id' => $user->id])}}">
    <section id="info">
        <img src="{{ route('userphoto', ['user_id' => $user->id]) }}" width="100" alt="">
        <span id="user"><p>{{$user->username}}</p></span>
        <span id="nome"><p>{{$user->name}}</p></span>
    </section>
    </a>
    @if (Auth::check())
    <button>Follow</button>
    @endif
</article>