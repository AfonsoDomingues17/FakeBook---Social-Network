<div id="chat-header" data-id="{{ $chat->id }}">
    @if($type === 'group')
        <img src="{{ route('groupPhoto', ['group_id' => $chat->id]) }}" width="50" height="50" alt="group profile picture">
        <div>
            <h1>{{ $chat->name }}</h1>
            <p>Click here to see more details</p>
        </div>
    @else
        <h1>{{ $chat->otherUser ? $chat->otherUser->username : 'Unknown User' }}</h1>
    @endif
</div>
<div id="messages">
    @foreach($chat->messages as $message)
        <div class="message" data-message-id="{{ $message->id }}" style="position: relative;">
            <strong>{{ $message->author->name }}:</strong>
            <p>{{ $message->content }}</p>
            @if($message->image_url)
                <img src="{{ route('messages.show', ['message_id' => $message->id]) }}" alt="Image">
            @endif
            @if(Auth::id() === $message->author_id)
                <button class="delete-message btn btn-danger btn-sm" style="position: absolute; top: 0; right: 0; display: none;">Delete</button>
            @endif
        </div>
    @endforeach
</div>
<div id="inputform">
    <form id="message-form" action="{{ route('messages.store') }}" method="POST" enctype="multipart/form-data" style="margin-top: 10px;">
        @csrf
        <input type="hidden" name="{{ $type === 'group' ? 'group_id' : 'direct_chat_id' }}" value="{{ $chat->id }}">
        <textarea name="content" placeholder="Type your message"></textarea>
        <input type="file" name="image" accept="image/*">
        <button type="submit">Send</button>
    </form>
</div>
<script>
    const chatId = {{ $chat->id }};
    const chatType = '{{ $type }}';
    const currentUserId = {{ Auth::id() }};
</script>
<script src="{{ asset('js/conversations.js') }}" defer></script>