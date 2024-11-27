<script src="{{ asset('js/post-carousel.js') }}"></script>
<article class="post">
    <div class="post_author">
        <img src="{{route('userphoto', ['user_id' => $post->owner_id])}}" alt="profile picture" width="70" height="70">
        @if (!(request()->routeIs('profile') && request()->route('user_id') == $post->owner_id))
            <p><a href="{{ route('profile', ['user_id' => $post->owner_id]) }}">{{ $post->owner->username }}</a></p>
        @endif
        <p id="date">{{ \Carbon\Carbon::parse($post->datecreation)->format('m/d/Y') }}</p>
    </div>
    @if ($post->typep === 'TEXT')
        <p id="postContent">{{ $post->description }}</p>

    @else
        <p id="postdescription">{{ $post->description }}</p>
        <div class="media">
        @if ($post->typep === 'MEDIA')
                <img id="media-image-{{ $post->id }}"
                    src="{{ $post->media->isNotEmpty() ? route('media.show', $post->media->first()->id) : Storage::url('DEFAULT-POST.jpg') }}"
                    alt="Media">
                @if ($post->media->count() > 1)
                    <div class="post-media-controls">
                        <button id="media-prev-{{ $post->id }}">Previous</button>
                        <button id="media-next-{{ $post->id }}">Next</button>
                    </div>
                @endif
            @endif
        </div>
    @endif
    <div class="post_counts">
        <p><i class="fa-regular fa-heart"> 20</i></p>
        <div id="r">
            <p><i class="fa-solid fa-share"> 33</i></p>
            <p ><i class="fa-regular fa-comment"> 15</i></p>
        </div>
    </div>
    <div class="interaction-bar">
        <i class="fa-regular fa-heart"> Like</i>
        <i class="fa-regular fa-comment"> Comment</i>
        <i class="fa-solid fa-share"> Share</i>

    </div>
    @if ($post->media->count() > 0)
    <script>
        const mediaUrls{{ $post->id }} = [
            @foreach ($post->media as $media)
                "{{ route('media.show', $media->id) }}",
            @endforeach
        ];
        initMediaCarousel({{ $post->id }}, mediaUrls{{ $post->id }});
    </script>
    @endif
    <div class="post-control-options">
        @if(Auth::check())
            @if (Auth::user()->isAdmin() || Auth::user()->id == $post->owner_id)
                <div class="post-control-button-container">
                    <button type="button" class="btn btn-primary edit-post-btn" data-bs-toggle="modal" data-bs-target="#editPostModal-{{ $post->id }}" data-post-id="{{ $post->id }}" data-current-url="{{ url()->current() }}">
                        Edit Post
                    </button>
                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="previous_url" value="{{ url()->previous() }}">
                        <button type="submit" class="btn btn-danger delete-post-btn">Delete</button>
                    </form>
                </div>
            @endif
        @endif
    </div>
</article>

@include('partials.edit_post', ['post' => $post, 'modalId' => 'editPostModal-' . $post->id])