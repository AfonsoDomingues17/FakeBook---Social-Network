<div id="group_info" data-group-id="{{ $group->id }}">
    <div id="group-header">
        <div id="top_bar">
        <i id="close" class="fa-solid fa-xmark"></i>
        <p>Group Details</p>
        </div>
        <img src="{{route('groupPhoto', ['group_id' => $group->id])}}" alt="group profile picture" width="250" height="250">
        <span id="gname">
            <p>{{ $group->name }}</p>
            @if($group->owner_id == Auth::user()->id)
                <i id="pencilEditGname"  class="fa-solid fa-pencil"></i>
            @endif          
        </span>
        <span id="gname_edit" class="gedit" style="display: none;">
            <input type="text" name="group_name" id="group_name" value="{{ $group->name }}"></input>
            <i class="fa-solid fa-check"></i>    
        </span>
        <p>Group: {{$group->participants->count()}} members</p>
    </div>
    <div id="additional_info">
        <span id="gdescription">
            <p>{{ $group->description }}</p>
            @if($group->owner_id == Auth::user()->id)
                <i id="pencilEditGdescription"  class="fa-solid fa-pencil"></i>
            @endif
        </span>
        <span id="gdescription_edit" class="gedit" style="display: none;">
            <input type="text" name="group_description" id="group_description" value="{{ $group->description }}"></input>
            <i class="fa-solid fa-check"></i>    
        </span>
        <p>Created by {{$group->owner->name}}, em 23/03/2004</p>
    </div>
    <div id="group-members">
        <h2>Group Members</h2>
        <span id="AddMember">
            <span><i class="fa-solid fa-user-plus"></i></span>
            <p>Add member</p>
        </span>
        <ul>
            <div id="owner">
            <img src="{{ route('userphoto', ['user_id' => $group->owner_id]) }}" alt="Owner profile picture" width="60" height="60">
            <p>{{ $group->owner->name }}</p>
            </div>
            @if(!$group->participants->isEmpty())
                @foreach ($group->participants as $groupMember)
                    @if($groupMember->id != $group->owner_id)
                        <div id="member">
                            <img src="{{ route('userphoto', ['user_id' => $groupMember->id]) }}" alt="group member profile pic" width="60" height="60">
                            <p>{{ $groupMember->name }}</p>
                        </div>
                    @endif
                @endforeach
            @endif
        </ul>   
            
    </div>
    <div id="group-footer">
        <form action="{{ route('group.leave', ['group_id' => $group->id]) }}" method="POST">
            @csrf
            <button type="submit" id="leave-group">
                <span><i class="fa-solid fa-arrow-right-from-bracket"></i></span>
                <p>Leave Group</p>
            </button>
        </form>
        @if(Auth::id() == $group->owner_id)
            <form action="{{ route('group.delete', ['group_id' => $group->id]) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" id="delete_group">
                    <span><i class="fa-solid fa-trash"></i></span>
                    <p>Delete Group</p>
                </button>
            </form>
        @endif
    </div>
</div>