<div id="group_info">
    <div id="group-header">
        <div id="top_bar">
            <i id="close" class="fa-solid fa-xmark"></i>
            <p>Group Details</p>
        </div>
        <img src="{{ route('groupPhoto', ['group_id' => $group->id]) }}" alt="group profile picture" width="250" height="250">
        <p id="gname">{{ $group->name }}</p>
        <p>Group: {{ $group->participants->count() + 1 }} members</p>
    </div>
    <div id="additional_info">
        <p id="description">{{ $group->description }}</p>
        <p>Created by {{ $group->owner->name }}, on {{ $group->created_at->format('d/m/Y') }}</p>
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
                    <div id="member">
                        <img src="{{ route('userphoto', ['user_id' => $groupMember->id]) }}" alt="">
                        <p>{{ $groupMember->name }}</p>
                    </div>
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