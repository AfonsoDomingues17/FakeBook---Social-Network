<article class="post">
    <h2>{{ $post->owner->name }}</h2>
    <p>{{ $post->description }}</p>
    <p>Type: {{ $post->typep }}</p>
    <p>Created at: {{ $post->datecreation }}</p>
    <p>Public: {{ $post->is_public ? 'Yes' : 'No' }}</p>
    @if ($post->media->count() > 0)
        <div class="media">
            @foreach ($post->media as $media)
                <img src="{{ asset('storage/' . $media->photo_url) }}" alt="Media">
            @endforeach
        </div>
    @endif
    @if (Auth::check() && Auth::user()->id == $post->owner_id)
        <a href="{{ route('posts.edit', $post) }}">Edit</a>
        <form action="{{ route('posts.destroy', $post) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit">Delete</button>
        </form>
    @endif
</article>