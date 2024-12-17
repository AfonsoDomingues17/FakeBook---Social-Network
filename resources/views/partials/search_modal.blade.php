<div class="modal fade" id="advancedSearchModal" tabindex="-1" aria-labelledby="advancedSearchModalLabel" aria-hidden="true" style="display: none">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="advancedSearchModalLabel">Search</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="advancedSearchForm" action="{{ route('advancedSearch') }}" method="GET" enctype="multipart/form-data">
                    <div>
                        <label for="search-type">Search type</label>
                        <select id="search-type" name="type" required onchange="toggleCategory()"> 
                            <option value="" disabled selected>-----</option>
                            <option value="users">User</option>
                            <option value="posts">Post</option>
                            <option value="groups">Group</option>
                        </select>
                    </div>
                    <div id="user-div" style="display: none">
                        <label for="search-country">User's country</label>
                        <select id="search-country" name="user_country">
                            <option value="" disabled selected>-----</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                        <br>
                        <label for="search-user-fullname">Full name</label>
                        <input id="search-user-fullname" type="text" name="user_fullname" placeholder="search here...">
                        <br>
                        <label for="search-user-username">Username</label>
                        <input id="search-user-username" type="text" name="user_username" placeholder="search here...">
                    </div>
                    <div id="post-div" style="display: none">
                        <label for="search-category">Post category</label>
                        <select id="search-category" name="post_category">
                            <option value="" disabled selected>-----</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <br>
                        <label for="search-post-description">Post description</label>
                        <input id="search-post-description" type="text" name="post_description" placeholder="search here...">
                        <br>
                        <label for="search-post-type">Post type</label>
                        <select id="search-post-type" name="post_type"> 
                            <option value="" disabled selected>-----</option>
                            <option value="TEXT">Text</option>
                            <option value="MEDIA">Media</option>
                        </select>
                    </div>
                    <div id="group-div" style="display: none">
                        <label for="search-group-name">Group name</label>
                        <input id="search-group-name" type="text" name="group_name" placeholder="search here...">
                        <br>
                        <label for="search-group-description">Group description</label>
                        <input id="search-group-description" type="text" name="group_description" placeholder="search here...">
                    </div>    
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('advancedSearchForm').addEventListener('submit', function(event) {
        const inputs = this.querySelectorAll('input, select');
        inputs.forEach(input => {
            if (!input.value) {
                input.disabled = true;
            }
        });
    });
</script>
<script type="text/javascript" src={{ url('js/advancedSearch.js') }} defer></script>
