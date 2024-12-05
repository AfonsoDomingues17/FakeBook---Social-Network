@extends('layouts.app')

@section('content')
<div class="container" style="display: flex; flex-direction: column; height: 80vh;">
    <h2 style="flex-shrink: 0;">Chat with {{ $directChat->user1_id == Auth::id() ? $directChat->user2->name : $directChat->user1->name }}</h1>
    <div id="messages" style="overflow-y: auto; flex-grow: 1;">
        @foreach($directChat->messages as $message)
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
    <form id="message-form" action="{{ route('messages.store') }}" method="POST" enctype="multipart/form-data" style="margin-top: 10px;">
        @csrf
        <input type="hidden" name="direct_chat_id" value="{{ $directChat->id }}">
        <textarea name="content" placeholder="Type your message"></textarea>
        <input type="file" name="image" accept="image/*">
        <button type="submit">Send</button>
    </form>
</div>
<script>
    var directChatId = '{{ $directChat->id }}';
    var currentUserId = {{ Auth::id() }};
</script>
<script src="{{ asset('js/direct_chat.js') }}" defer></script>
@endsection