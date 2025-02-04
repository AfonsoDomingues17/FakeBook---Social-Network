@extends('layouts.app')

@section('body-class', 'direct-chats-page')

@section('content')
    <div id="Conversas">
        <h1>Conversations</h1>
        <ul>
        <a class="auth" href="#" data-bs-toggle="modal" data-bs-target="#groupCreationModal" aria-label="Create a new group">
            <span id="icons" style="width:60px; height:60px;"><i class="fa-solid fa-user-group" aria-hidden="true"></i></span>
            <p>Create Group</p>
        </a> 

            @foreach(Auth::user()->groups() as $group)
                <a href="#" class="conversation-link" data-type="group" data-id="{{ $group->id }}">
                    <section id="info">
                        <img src="{{ route('groupPhoto', ['group_id' => $group->id]) }}" width="60" height="60" alt="group profile picture" style=" object-fit: cover;">
                        <div class="group-info">
                            <span id="groupName"><p>{{ $group->name }}</p></span>
                            <span id="groupLastMessage"><p>{{ $group->messages->first() ? $group->messages->first()->content : 'No messages yet' }}</p></span>
                        </div>
                        <p id="timeMessage">17:17</p>
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
                            <img src="{{ route('userphoto', ['user_id' => $otherUser->id]) }}" width="100" height="100" alt="user profile picture" style=" object-fit: cover;">
                            <div class="user-info">
                                <span id="user"><p>{{ $otherUser->username }}</p></span>
                                <span id="groupLastMessage"><p>{{ $lastMessage ? $lastMessage->content : 'No messages yet' }}</p></span>
                            </div>
                            <p id="timeMessage">17:17</p>
                        </section>
                    </a>
                </article>
            @endforeach
        </ul>
    </div>
    
    <div id="special" class="container">
        <div id="chat" >
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