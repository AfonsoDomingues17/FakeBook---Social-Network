        <div id="chat-header">
            <h1><a href="{{route('profile',['user_id' => $directChat->user1_id == Auth::id() ? $directChat->user2->id : $directChat->user1->id])}}">{{ $directChat->user1_id == Auth::id() ? $directChat->user2->name : $directChat->user1->name }}</a></h1>
        </div>
        <div id="messages">
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
        <div id="inputform">
            <form id="message-form" action="{{ route('messages.store') }}" method="POST" enctype="multipart/form-data" style="margin-top: 10px;">
                @csrf
                <input type="hidden" name="direct_chat_id" value="{{ $directChat->id }}">
                <textarea name="content" placeholder="Type your message"></textarea>
                <input type="file" name="image" accept="image/*">
                <button type="submit">Send</button>
            </form>
        </div>