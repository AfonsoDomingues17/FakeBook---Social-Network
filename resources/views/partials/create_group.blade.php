<div class="modal fade" id="groupCreationModal" tabindex="-1" aria-labelledby="groupCreationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="groupCreationModalLabel">Create Group</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <section id="pageUsers">
                    <h3>Add members to the group</h3>
                      
                    @foreach (Auth::user()->followers as $follower)
                        
                        <div class="user">
                            <img src="{{ route('userphoto', ['user_id' => $follower->id]) }}" width="100"  height="100" alt="user profile picture">
                                <div class="user-info">
                                    <span id="user"><p>{{$follower->username}}</p></span>
                                    <span id="nome"><p>{{$follower->name}}</p></span>
                                </div>
                        </div>
                    
                    @endforeach
                    
                </section>
                <section id="CreationPage" style="display: none;">
                <form action="" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <img id="g_picture_review" src="https://www.diamorfosi.com.gr/app/wp-content/plugins/profilegrid-user-profiles-groups-and-communities/public/partials/images/default-group.png" alt="preview of profile picture" width="200" height="200">
                        <input id="photo_url" type="file" name="photo_url" accept="image/*" onchange="previewGroupPicture(event)" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input id="name" type="text" name="name" value="{{old('name')}}" placeholder="Name for the group" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="group_description" placeholder="Group description" class="form-control">{{old('description')}}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
                </section>
            </div>
            
        </div>
    </div>
</div>

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