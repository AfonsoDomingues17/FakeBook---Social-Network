<div class="comment" id="comment-{{ $comment->id }}">
    <div class="comment-author" style="display: flex; align-items: center;">
        <img src="{{ route('userphoto', ['user_id' => $comment->user->id]) }}" alt="profile picture" width="30" height="30" style="border-radius: 50%; margin-right: 10px;">
        <small><a href="{{ route('profile', ['user_id' => $comment->user->id]) }}">{{ $comment->user->name }}</a></small>
    </div>

    <p>{{ $comment->content }}</p>
    @if (Auth::check() && Auth::user()->id == $comment->user->id)
        <div class="comment-options" style="display: none; justify-content: space-between; margin-top: 5px">
            <button class="edit-link btn btn-link" onclick="toggleEditForm({{ $comment->id }})">Edit</button>
            <p class="delete-link">Delete</p>
        </div>
        <div id="edit-form-{{ $comment->id }}" style="display: none; margin-top: 10px;">
            <form id="edit-comment-form-{{ $comment->id }}" action="{{ route('comments.update', ['comment_id' => $comment->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                <div class="form-group">
                    <textarea name="content" class="form-control" rows="3" hint="" required>{{ $comment->content }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Update Comment</button>
            </form>
        </div>
    @endif
    <hr>
</div>

<script src="{{ asset('js/edit-comment.js') }}"></script>