@if(Auth::check())
<div class="modal fade" id="groupParticipantsModal" tabindex="-1" aria-labelledby="groupParticipantsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="groupParticipantsModalLabel">Add Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <section id="pageUsers">
                    <input type="text" id="searchUsers" placeholder="Search for users">
                    @if (count(Auth::user()->following) == 0)
                        <p>You don't have any followers to add to the group</p>
                      
                    @else
                    @foreach (Auth::user()->following->take(5) as $follower) 
                        <div class="user">
                            <section id="info">
                            <img src="{{ route('userphoto', ['user_id' => $follower->id]) }}" width="70"  height="70" alt="user profile picture">
                                <div class="user-info">
                                    <span id="user"><p>{{$follower->username}}</p></span>
                                    <span id="nome"><p>{{$follower->name}}</p></span>
                                </div>
                                <button id="AddMember">Add</button>
                            </section>
                            
                        </div> 
                    @endforeach
                    @endif
                    
                </section>
            </div>
            <div id="modal-footer">
                <i id="Confirm" class="fa-solid fa-check"></i>
            </div>
            
        </div>
    </div>
</div>
@endif

<script>
function previewGroupPicture(event) {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById('g_picture_review');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>