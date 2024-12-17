function toggleCategory() {
    var searchType = document.getElementById('search-type').value;
    var userDiv = document.getElementById('user-div');
    var postDiv = document.getElementById('post-div');
    var groupDiv = document.getElementById('group-div');
    if (searchType === 'users') {
        userDiv.style.display = 'block';
        postDiv.style.display = 'none';
        groupDiv.style.display = 'none';
    }
    else if (searchType === 'posts') {
        userDiv.style.display = 'none';
        postDiv.style.display = 'block';
        groupDiv.style.display = 'none';
    } else if (searchType === 'groups') {
        userDiv.style.display = 'none';
        postDiv.style.display = 'none';
        groupDiv.style.display = 'block';
    }
}