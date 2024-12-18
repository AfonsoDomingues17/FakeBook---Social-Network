<div class="comment" id="comment-{{ $comment->id }}">
    <div class="comment-author" style="display: flex; align-items: center;">
        <img src="{{ route('userphoto', ['user_id' => $comment->user->id]) }}" alt="profile picture" width="30" height="30" style="border-radius: 50%; margin-right: 10px;">
        <small><a href="{{ route('profile', ['user_id' => $comment->user->id]) }}">{{ $comment->user->name }}</a></small>
    </div>

    <p>{{ $comment->content }}</p>
    <div class="interaction-bar">
        <div class="like-container" data-comment-id="{{ $comment->id }}" style="display: flex; flex-direction: row; gap:10px">
            <form class="comment-like-form" action="{{ route('comment.like') }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $comment->id }}">
                <button type="submit" class="like-button">
                    @if (Auth::check() && $comment->likedByUsers()->where('user_id', Auth::user()->id)->exists())
                        <i class="fa-solid fa-heart"></i>
                    @else
                        <i class="fa-regular fa-heart"></i>
                    @endif
                </button>
            </form>
            <span class="like-count">{{ $comment->likedByUsers()->count() }}</span>
        </div>
    </div>
    @if (Auth::check() && (Auth::user()->id == $comment->user->id || Auth::user()->isAdmin()))
        <div class="comment-options" style="display: none; justify-content: space-between; margin-top: 5px">
            <button class="edit-link btn btn-link" onclick="toggleEditForm({{ $comment->id }})">Edit</button>
            <form action="{{ route('comments.destroy', ['comment_id' => $comment->id]) }}" method="POST" onsubmit="deleteComment(event, {{ $comment->id }});">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete-link btn btn-link">Delete</button>
            </form>
        </div>
        <div id="edit-form-{{ $comment->id }}" style="display: none; margin-top: 10px;">
            <form id="edit-comment-form-{{ $comment->id }}" action="{{ route('comments.update', ['comment_id' => $comment->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                <div class="form-group">
                    <textarea name="content" class="form-control" rows="3" required style="width: 100%">{{ $comment->content }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Update Comment</button>
            </form>
        </div>
    @endif
    <hr>
</div>

<script src="{{ asset('js/edit-comment.js') }}"></script>