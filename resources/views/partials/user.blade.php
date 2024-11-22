<article class="user" data-id="{{ $user->id }}">
    <a href="{{route('profile',['user_id' => $user->id])}}">
    <section id="info">
        <img src="{{ $user->photo_url ? Storage::url($user->photo_url) : Storage::url('DEFAULT-USER.png') }}" alt="">
        <span id="user"><p>{{ '@' . $user->username }}</p></span>
        <span id="nome"><p>{{$user->name}}</p></span>
    </section>
    </a>
    @if (Auth::check())
    <button>Follow</button>
    @endif
</article>