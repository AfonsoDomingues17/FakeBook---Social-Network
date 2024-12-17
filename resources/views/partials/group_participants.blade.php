@if(Auth::check())
<div class="modal fade" id="groupParticipantsModal" tabindex="-1" aria-labelledby="groupParticipantsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="groupParticipantsModalLabel">Add Group Members</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <section id="pageUsersGroupParticipants">
                    <input type="text" id="searchUsersGroupParticipants" placeholder="Search for users">
                    @if (count(Auth::user()->following) == 0)
                        <p>You don't follow anyone to add to the group</p>
                    @else
                        @isset($group)
                            @foreach (Auth::user()->following as $follower)
                                @if (!$group->participants->contains($follower->id))
                                    <div class="user">
                                        <section id="info">
                                            <img src="{{ route('userphoto', ['user_id' => $follower->id]) }}" width="70" height="70" alt="user profile picture">
                                            <div class="user-info">
                                                <span id="user"><p>{{$follower->username}}</p></span>
                                                <span id="nome"><p>{{$follower->name}}</p></span>
                                            </div>
                                            <button class="add-member-btn-group-participants" data-user-id="{{ $follower->id }}">Add</button>
                                        </section>
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <p>No group selected.</p>
                        @endisset
                    @endif
                </section>
            </div>
            <div id="modal-footer-group-participants">
                <i id="confirmGroupParticipants" class="fa-solid fa-check"></i>
            </div>
        </div>
    </div>
</div>
@endif