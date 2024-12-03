@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Chat with {{ $directChat->user1_id == Auth::id() ? $directChat->user2->name : $directChat->user1->name }}</h1>
    <div id="messages">
        @foreach($directChat->messages as $message)
            <div class="message">
                <strong>{{ $message->author->name }}:</strong>
                <p>{{ $message->content }}</p>
                @if($message->image_url)
                    <img src="{{ asset('storage/' . $message->image_url) }}" alt="Image">
                @endif
            </div>
        @endforeach
    </div>
    <form action="{{ route('messages.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="direct_chat_id" value="{{ $directChat->id }}">
        <textarea name="content" placeholder="Type your message"></textarea>
        <input type="file" name="image">
        <button type="submit">Send</button>
    </form>
</div>
<script>
    var directChatId = '{{ $directChat->id }}';
</script>
<script src="{{ asset('js/direct_chat.js') }}" defer></script>
@endsection