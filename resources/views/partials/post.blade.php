<script src="{{ asset('js/post-carousel.js') }}"></script>
<article class="post">
    <div class="post_author">
        <img src="{{route('userphoto', ['user_id' => $post->owner_id])}}" alt="profile picture" width="70" height="70">
        @if (!(request()->routeIs('profile') && request()->route('user_id') == $post->owner_id))
            <p><a href="{{ route('profile', ['user_id' => $post->owner_id]) }}">{{ $post->owner->username}}</a></p>
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
    <div class="interaction-bar">
        <form id="like-form" action="{{route('post.like')}}" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{$post->id}}">
            <button type="submit" class="like-button">
                <i class="fa-regular fa-heart"></i>
            </button>
        </form>
        <p><i class="fa-regular fa-comment"></i> 33</p>
        <p><i class="fa-solid fa-share"></i> 10</p>

    </div>
    <div class="action_buttons">
        @if(Auth::check())
            @if (Auth::user()->isAdmin() || Auth::user()->id == $post->owner_id)
                    <button type="button" id="editPostBtn" class="btn btn-primary edit-post-btn" data-bs-toggle="modal" data-bs-target="#editPostModal-{{ $post->id }}" data-post-id="{{ $post->id }}" data-current-url="{{ Request::fullUrl() }}">
                        Edit Post
                    </button>
                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="previous_url" value="{{ url()->current() }}">
                        <button type="submit" id="deletePostBtn" class="btn btn-danger delete-post-btn">Delete</button>
                    </form>
            @endif
        @endif
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
    @include('partials.edit_post', ['post' => $post, 'modalId' => 'editPostModal-' . $post->id])
</article>

