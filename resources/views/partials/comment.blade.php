<div class="comment">
    <p>{{ $comment->content }}</p>
    <small>By <a href="{{route('profile',['user_id' => $comment->user->id])}}">{{ $comment->user->name }}</a></small>
</div>