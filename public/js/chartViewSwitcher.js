function showView(view) {
    const followersView = document.getElementById('followers-view');
    const postsView = document.getElementById('posts-view');
    const followersButton = document.querySelector('button[onclick="showView(\'followers\')"]');
    const postsButton = document.querySelector('button[onclick="showView(\'posts\')"]');
    
    if (view === 'followers') {
        followersView.style.display = 'block';
        postsView.style.display = 'none';
        followersButton.classList.add('btn-primary');
        followersButton.classList.remove('btn-secondary');
        postsButton.classList.add('btn-secondary');
        postsButton.classList.remove('btn-primary');
    } else if (view === 'posts') {
        followersView.style.display = 'none';
        postsView.style.display = 'block';
        postsButton.classList.add('btn-primary');
        postsButton.classList.remove('btn-secondary');
        followersButton.classList.add('btn-secondary');
        followersButton.classList.remove('btn-primary');
    }
}