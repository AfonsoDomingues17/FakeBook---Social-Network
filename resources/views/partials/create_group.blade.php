@if(Auth::check())
<div class="modal fade" id="groupCreationModal" tabindex="-1" aria-labelledby="groupCreationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="groupCreationModalContent">
            <div class="modal-header">
                <h5 class="modal-title" id="groupCreationModalLabel">Create Group</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <section id="pageUsers">
                    <h3>Add members to the group</h3>
                    <form id="search-form" action="{{ route('search') }}" method="GET">
                        <div>
                            <input id="searchUsers" type="text" name="query" placeholder="Search for users" value="">
                            <input type="hidden" name="type" value="users">
                            <div id="real-time-search-group"></div> <!-- Updated ID for search results -->
                        </div>
                    </form>
                    <div id="initialGroup">
                        @if (count(Auth::user()->following) == 0)
                            <p>You don't follow anyone to add to the group</p>
                        @else
                             <div id="followingContainer" style="max-height: 350px; overflow-y: auto; -ms-overflow-style: none; scrollbar-width: none;">
                                @foreach (Auth::user()->following->take(10) as $follower)
                                    <div class="user">
                                        <section id="info">
                                            <img src="{{ route('userphoto', ['user_id' => $follower->id]) }}" width="70" height="70" alt="user profile picture">
                                            <div class="user-info">
                                                <span id="user"><p>{{$follower->username}}</p></span>
                                                <span id="nome"><p>{{$follower->name}}</p></span>
                                            </div>
                                            <button type="button" id="AddMember" class="add-member-btn btn btn-secondary" data-user-id="{{ $follower->id }}">Add</button>
                                        </section>
                                    </div>
                                @endforeach
                            </div>
                            <div id="loadingFollowers" style="display: none;">Loading...</div>
                        @endif
                    </div>
                </section>
                <section id="CreationPage" style="display: none;">
                    <form id="create-group-form" action="{{route('group.create')}}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div id="form-image">
                            <img id="g_picture_review" src="{{Storage::url('public/DEFAULT_GROUP.png')}}" alt="preview of group picture" width="200" height="200" style="border-radius: 50%;">
                            <input id="photo_url" type="file" name="photo_url" accept="image/*" onchange="previewGroupPicture(event)" class="form-control">
                            @if($errors->has('photo_url'))
                                <span class="error">{{ $errors->first('photo_url') }} <i class="fa-solid fa-circle-exclamation"></i></span>
                            @endif
                        </div>
                        <div id="form-group">
                            <label for="name">Name<em style="color: red;">*</em></label>
                            <input id="name" type="text" name="name" value="{{old('name')}}" placeholder="Name for the group" required class="form-control">
                            @if($errors->has('name'))
                                <span class="error">{{ $errors->first('name') }} <i class="fa-solid fa-circle-exclamation"></i></span>
                            @endif
                        </div>
                        <div id="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="group_description" placeholder="Group description" class="form-control">{{old('description')}}</textarea>
                            @if($errors->has('description'))
                                <span class="error">{{ $errors->first('description') }} <i class="fa-solid fa-circle-exclamation"></i></span>
                            @endif
                        </div>
                        <p><em style="color: red;">*</em> Fields are required.</p>
                        <input type="hidden" name="selected_users" id="selected_users">
                        <button type="button" class="btn btn-secondary" id="backButton">Back</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </section>
            </div>
            <div id="modal-footer">
                <i id="nextButton" class="fa-solid fa-check"></i>
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
<script src="{{ asset('js/searchGroup.js') }}"></script>
