@extends('layouts.app')

@section('body-class', 'direct-chats-page')

@section('content')
    <div id="Conversas">
        <h1>Conversas</h1>
        <ul>
            @foreach(Auth::user()->groups() as $group)
                <a href="#" class="conversation-link" data-type="group" data-id="{{ $group->id }}">
                    <section id="info">
                        <img src="{{ route('groupPhoto', ['group_id' => $group->id]) }}" width="50" height="50" alt="group profile picture">
                        <div class="group-info">
                            <span id="groupName"><p>{{ $group->name }}</p></span>
                            <span id="groupLastMessage"><p>{{ $group->messages->first() ? $group->messages->first()->content : 'No messages yet' }}</p></span>
                        </div>
                    </section>
                </a>
            @endforeach
            @foreach($directChats as $directChat)
                @php
                    $otherUser = $directChat->user1_id == Auth::id() ? $directChat->user2 : $directChat->user1;
                    $lastMessage = $directChat->messages->first();
                @endphp
                <article class="user" data-id="{{ $otherUser->id }}">
                    <a href="#" class="conversation-link" data-type="direct" data-id="{{ $directChat->id }}">
                        <section id="info">
                            <img src="{{ route('userphoto', ['user_id' => $otherUser->id]) }}" width="100" height="100" alt="user profile picture">
                            <div class="user-info">
                                <span id="user"><p>{{ $otherUser->username }}</p></span>
                                <span id="groupLastMessage"><p>{{ $lastMessage ? $lastMessage->content : 'No messages yet' }}</p></span>
                            </div>
                        </section>
                    </a>
                </article>
            @endforeach
        </ul>
    </div>
    
    <div id="special" class="container">
        <div id="chat" class="container">
            <div id="initial">
                <img id="logo" src="{{ Storage::url('public/LOGO.png') }}" alt="FakeBook Logo" width="200" height="200">
                <h1>Your conversations!</h1>
                <p>Send photos and messages to friends or groups</p>
            </div>
        </div>
    </div>
<script src="{{ asset('js/conversations.js') }}" defer></script>
<script>
    const currentUserId = {{ Auth::id() }};
</script>
@endsection