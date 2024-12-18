function toggleCategory() {
    const searchType = document.getElementById('search-type').value;
    const userDiv = document.getElementById('user-div');
    const postDiv = document.getElementById('post-div');
    const groupDiv = document.getElementById('group-div');
    if (searchType === 'users') {
        userDiv.style.display = 'flex';
        postDiv.style.display = 'none';
        groupDiv.style.display = 'none';
    }
    else if (searchType === 'posts') {
        userDiv.style.display = 'none';
        postDiv.style.display = 'flex';
        groupDiv.style.display = 'none';
    } else if (searchType === 'groups') {
        userDiv.style.display = 'none';
        postDiv.style.display = 'none';
        groupDiv.style.display = 'flex';
    }
}