@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Direct Chats</h1>
    <ul>
        @foreach($directChats as $directChat)
            <li>
                <a href="{{ route('direct_chats.show', $directChat->id) }}">
                    Chat with {{ $directChat->user1_id == Auth::id() ? $directChat->user2->name : $directChat->user1->name }}
                </a>
            </li>
        @endforeach
    </ul>
</div>
@endsection