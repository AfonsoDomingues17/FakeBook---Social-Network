<div id="chat-header" data-id="{{ $chat->id }}" data-type="{{ $type }}">
    @if($type === 'group')
        <img src="{{ route('groupPhoto', ['group_id' => $chat->id]) }}" width="60" height="60" alt="group profile picture" style=" object-fit: cover;">
        <div>
            <span id="ChatName"><p>{{ $chat->name }}</p></span>
            <p id="details">Click here to see more details</p>
        </div>
    @else
        <img src="{{ route('userphoto', ['user_id' => $chat->otherUser->id]) }}" width="70" height="70" alt="user profile picture">
        <span id="ChatName"><p>{{ $chat->otherUser ? $chat->otherUser->username : 'Unknown User' }}</p></spa>
    @endif
</div>
<div id="messages">
    @foreach($chat->messages as $message)
        <div class="message {{ Auth::id() === $message->author_id ? 'my-message' : 'other-message' }}" data-message-id="{{ $message->id }}">
            <span id="Mymessage"><p>{{ $message->content }}</span>
            @if($message->image_url)
                <img src="{{ route('messages.show', ['message_id' => $message->id]) }}" alt="Image sent">
            @endif
            @if(Auth::id() === $message->author_id)
                <button class="delete-message btn btn-danger btn-sm" style="display: none;">Delete</button>
            @endif
        </div>
    @endforeach
</div>
<div id="inputform">
    <form id="message-form" action="{{ route('messages.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <img id="image-preview" src="#" alt="Image Preview" style="display: none;">
        <input type="hidden" name="{{ $type === 'group' ? 'group_id' : 'direct_chat_id' }}" value="{{ $chat->id }}">
        <input type="file" id="image" name="image" accept="image/*" style="display: none;" onchange="previewSentPicture(event)">
        @if($errors->has('image'))
            <span class="error">{{ $errors->first('image') }} <i class="fa-solid fa-circle-exclamation"></i></span>
        @endif
        <label for="image" class="file-input-label" aria-label="Upload an image">
            <i class="fa-solid fa-upload" aria-hidden="true"></i>
            <span class="sr-only">Upload an image</span>
        </label>
        <textarea id="content" name="content" placeholder="Type your message"></textarea>
        @if($errors->has('content'))
            <span class="error">{{ $errors->first('content') }} <i class="fa-solid fa-circle-exclamation"></i></span>
        @endif
        
        <button type="submit">
            <i id="send" class="fa-solid fa-right-to-bracket"></i>
        </button>
        
    </form>
</div>
<script>
    const chatId = {{ $chat->id }};
    const chatType = '{{ $type }}';
    const currentUserId = {{ Auth::id() }};
</script>
<script src="{{ asset('js/conversations.js') }}" defer></script>