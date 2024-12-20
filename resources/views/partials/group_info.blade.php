<div id="group_info" data-group-id="{{ $group->id }}">
    <div id="group-header">
        <div id="top_bar">
        <i id="close" class="fa-solid fa-xmark"></i>
        <p>Group Details</p>
        </div>
        <img src="{{route('groupPhoto', ['group_id' => $group->id])}}" alt="group profile picture" width="250" height="250" style="border-radius: 50%; object-fit: cover;">
        <span id="gname">
            <p>{{ $group->name }}</p>
            @if($group->owner_id == Auth::user()->id)
                <i id="pencilEditGname" class="fa-solid fa-pencil" aria-label="Edit group name" role="button" tabindex="0"></i>
            @endif          
        </span>
        <span id="gname_edit" class="gedit" style="display: none;">
            <input type="text" name="group_name" id="group_name" value="{{ $group->name }}" aria-label="Edit group name"></input>
            <i class="fa-solid fa-check" aria-label="Submit group name edit" role="button" tabindex="0"></i>    
        </span>
        <p>Group: {{$group->participants->count()}} members</p>
    </div>
    <div id="additional_info">
        <span id="gdescription">
            <p>{{ $group->description }}</p>
            @if($group->owner_id == Auth::user()->id)
                <i id="pencilEditGdescription"  class="fa-solid fa-pencil" aria-label="Edit group description" role="button" tabindex="0"></i>
            @endif
        </span>
        <span id="gdescription_edit" class="gedit" style="display: none;">
            <input type="text" name="group_description" id="group_description" value="{{ $group->description }}" aria-label="Edit group description"></input>
            <i class="fa-solid fa-check" aria-label="Submit group description edit" role="button" tabindex="0"></i>    
        </span>
        <p>Created by {{$group->owner->name}}, em 23/03/2004</p>
    </div>
    <div id="group-members">
        <h2>Group Members</h2>
        @if($group->owner_id == Auth::user()->id)
        <span id="AddMembers" class="add-member-span" data-bs-toggle="modal" data-bs-target="#addMembersModal">
            <span><i class="fa-solid fa-user-plus" aria-label="Add user to group" role="button" tabindex="0"></i></span>
            <p>Add members</p>
        </span>
        @endif
        <ul>
            <div id="owner">
            <img src="{{ route('userphoto', ['user_id' => $group->owner_id]) }}" alt="Owner profile picture" width="60" height="60">
            <p>{{ $group->owner->name }}</p>
            </div>
            @if(!$group->participants->isEmpty())
                @foreach ($group->participants as $groupMember)
                    @if($groupMember->id != $group->owner_id)
                        <div id="member">
                            <img src="{{ route('userphoto', ['user_id' => $groupMember->id]) }}" alt="group member profile picture" width="60" height="60">
                            <p>{{ $groupMember->name }}</p>
                            @if($group->owner_id == Auth::user()->id)
                            <button data-user-id="{{ $groupMember->id }}" aria-label="Remove group member"><p>Remove</p></button>
                            @endif
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
                <span><i class="fa-solid fa-arrow-right-from-bracket" aria-label="Leave Group" role="button" tabindex="0"></i></span>
                <p>Leave Group</p>
            </button>
        </form>
        @if(Auth::id() == $group->owner_id)
            <form action="{{ route('group.delete', ['group_id' => $group->id]) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" id="delete_group">
                    <span><i class="fa-solid fa-trash" aria-label="Delete Group" role="button" tabindex="0"></i></span>
                    <p>Delete Group</p>
                </button>
            </form>
        @endif
    </div>
</div>

<div class="modal fade" id="addMembersModal" tabindex="-1" aria-labelledby="addMembersModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMembersModalLabel">Add Members</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if($followersNotInGroup->isEmpty())
                    <p>You don't follow anyone who is not already in the group.</p>
                @else
                    <h3>Select users to add to the group:</h3>
                    <div id="addToGroupContainer" data-group-id="{{ $group->id }}" style="max-height: 350px; overflow-y: auto; -ms-overflow-style: none; scrollbar-width: none;">
                        @foreach ($followersNotInGroup->take(10) as $follower)
                            <div class="user">
                                <section id="info">
                                    <img src="{{ route('userphoto', ['user_id' => $follower->id]) }}" width="70" height="70" alt="user profile picture">
                                    <div class="user-info">
                                        <span id="user"><p>{{$follower->username}}</p></span>
                                        <span id="nome"><p>{{$follower->name}}</p></span>
                                    </div>
                                    <button type="button" id="AddToGroup" class="add-member-btn btn btn-secondary" data-user-id="{{ $follower->id }}" data-group-id="{{ $group->id }}">Add</button>
                                </section>
                            </div>
                        @endforeach
                    </div>
                    <div id="loadingAddToGroup" style="display: none;">Loading...</div>
                @endif
            </div>
        </div>
    </div>
</div>

