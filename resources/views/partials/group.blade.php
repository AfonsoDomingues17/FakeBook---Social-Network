<article class="group" data-id="{{ $group->id }}">
    <section id="info">
        <img src="{{ $group->photo_url ? Storage::url($group->photo_url) : Storage::url('DEFAULT-GROUP.png') }}" width="100" alt="">
        <span id="user"><p>{{$group->name}}</p></span>
        <span id="nome"><p>{{$group->description}}</p></span>
    </section>
    </a>
    @if (Auth::check())
    <button>Join</button>
    @endif
</article>