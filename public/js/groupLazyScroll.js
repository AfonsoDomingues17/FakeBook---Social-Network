document.addEventListener('DOMContentLoaded', function() {
    let page = 2; // Start from the second page since the first page is already loaded
    const limit = 10;
    const followersContainer = document.getElementById('followingContainer');
    const loading = document.getElementById('loading');
    let fetching = false; // Flag to prevent multiple requests
    let noMoreFollowers = false; // Flag to indicate no more followers

    followersContainer.addEventListener('scroll', () => {
        if (window.innerHeight + window.scrollY >= document.body.offsetHeight && !noMoreFollowers && !fetching) {
            loadMoreFollowers();
        }
    });

    followersContainer.addEventListener("wheel", function () {
        if (document.documentElement.scrollHeight <= window.innerHeight && !noMoreFollowers && !fetching) {
            loadMoreFollowers();
        }
    });

    function loadMoreFollowers() {
        loading.style.display = 'block';
        fetching = true;
        fetch(`/following?page=${page}`)
            .then(response => response.json())
            .then(data => {
                if (data.data.length === 0) {
                    noMoreFollowers = true; // Set flag if no more followers
                } else {
                    data.data.forEach(follower => { // Access the data array from the paginated response
                        const userDiv = document.createElement('div');
                        userDiv.classList.add('user');
                        userDiv.innerHTML = `
                            <section id="info">
                                <img src="/users/${follower.id}/photo" width="70" height="70" alt="user profile picture">
                                <div class="user-info">
                                    <span id="user"><p>${follower.username}</p></span>
                                    <span id="nome"><p>${follower.name}</p></span>
                                </div>
                                <button type="button" id="AddMember" class="add-member-btn btn btn-secondary" data-user-id="${follower.id}">Add</button>
                            </section>
                        `;
                        followersContainer.appendChild(userDiv);
                    });
                    page += 1;
                }
                loading.style.display = 'none';
                fetching = false;
            })
            .catch(error => {
                console.error('Error loading more followers:', error);
                loading.style.display = 'none';
            });
    }
});