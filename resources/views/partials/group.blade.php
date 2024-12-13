<div id="chat-header" data-id="{{$group->id}}">
        <img src="{{route('groupPhoto', ['group_id' => $group->id])}}" width="50"  height="50" alt="group profile picture">
        <div>
        <p id="gname">{{$group->name}}</p>
        <p>Click here to see more details</p>
        </div>
</div>
        <div id="messages">
            
        </div>
        <div id="inputform">
            <form id="message-form" action="" method="POST" enctype="multipart/form-data" style="margin-top: 10px;">
                @csrf
                <input type="hidden" name="direct_chat_id" value="">
                <textarea name="content" placeholder="Type your message"></textarea>
                <input type="file" name="image" accept="image/*">
                <button type="submit">Send</button>
            </form>
        </div>