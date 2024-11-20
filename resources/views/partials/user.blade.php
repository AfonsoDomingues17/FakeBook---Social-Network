<article class="user" data-id="{{ $user->id }}">
    <a href="{{route('profile',['user_id' => $user->id])}}"><section id="info">
    <img src="https://i.pinimg.com/736x/0d/64/98/0d64989794b1a4c9d89bff571d3d5842.jpg" alt="">
    <span id="user"><p>{{$user->username}}</p></span>
    <span id="nome"><p>{{$user->name}}</p></span>
    </section>
    </a>
    <button>Follow</button>
</article>