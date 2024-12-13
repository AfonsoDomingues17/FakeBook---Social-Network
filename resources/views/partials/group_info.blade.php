<div id="group_info" sty>
    <div id="group-header">
        <div id="top_bar">
        <i class="fa-solid fa-xmark"></i>
        <p>Group Details</p>
        </div>
        <img src="{{route('groupPhoto', ['group_id' => $group->id])}}" alt="group profile picture" width="400" height="220">
        <p id="gname">{{ $group->name }}</p>
        <p>{{ $group->description }}</p>
        <p>Nummber of mmbers</p>
    </div>
    <div id="group-members">
        <p>Group Members</p>
        <ul>
            <div id="owner">
                <img src="{{ route('userphoto', ['user_id' => $group->owner_id]) }}" alt="" width="70" height="70">
                <p>Me</p>
            </div>
            @if(!$group->participants->isEmpty())
            @foreach ($group->participants as $groupMember)
                <div id="member">
                    <img src="{{ route('userphoto', ['user_id' => $groupMember->id]) }}" alt="">
                    <p>{{ $groupMember->name }}</p>
                </div>
            @endforeach
            @endif
               
         </ul>   
            
    </div>
    <div id="group-footer">
        <button id="leave-group">Leave Group</button>
        <button id="delete_group">Delete Group</button>
    </div>   
</div>