document.addEventListener('DOMContentLoaded', function() {
    const limit = 10;
    
    const loadingFollowers = document.getElementById('loadingFollowers');
    let noMoreFollowers = false; // Flag to indicate no more followers
    let fetchingFollowers = false; // Flag to prevent multiple requests
    let pageFollowers = 2; // Start from the second page since the first page is already loaded
    const followersContainer = document.getElementById('followingContainer');

    const loadingAddToGroup = document.getElementById('loadingAddToGroup');
    let noMoreAddToGroup = false; // Flag to indicate no more followers
    let fetchingAddToGroup = false; // Flag to prevent multiple requests
    let pageAddToGroup = 2; // Start from the second page since the first page is already loaded
    const addToGroupContainer = document.getElementById('addToGroupContainer');
    let groupId = null;
    if (addToGroupContainer) {
        groupId = addToGroupContainer.getAttribute('data-group-id');
    }

    if (followersContainer) {
        console.log('followersContainer exists');
        followersContainer.addEventListener('scroll', () => {
            console.log('scroll event triggered');
            if (followersContainer.scrollTop + followersContainer.clientHeight >= followersContainer.scrollHeight - 100 && !noMoreFollowers && !fetchingFollowers) {
                console.log('loadMoreFollowers condition met');
                loadMoreFollowers();
            }
        });

        followersContainer.addEventListener("wheel", function () {
            console.log('wheel event triggered');
            if (document.documentElement.scrollHeight <= window.innerHeight && !noMoreFollowers && !fetchingFollowers) {
                console.log('loadMoreFollowers condition met on wheel');
                loadMoreFollowers();
            }
        });
    } else {
        console.log('followersContainer does not exist');
    }

    if (addToGroupContainer) {
        addToGroupContainer.addEventListener('scroll', () => {
            if (addToGroupContainer.scrollTop + addToGroupContainer.clientHeight >= addToGroupContainer.scrollHeight - 100 && !noMoreAddToGroup && !fetchingAddToGroup) {
                loadMoreAddToGroup();
            }
        });

        addToGroupContainer.addEventListener("wheel", function () {
            if (document.documentElement.scrollHeight <= window.innerHeight && !noMoreAddToGroup && !fetchingAddToGroup) {
                loadMoreAddToGroup();
            }
        });
    } else {
        console.log('addToGroupContainer does not exist');
    }

    function loadMoreFollowers() {
        console.log('loadMoreFollowers called');
        loadingFollowers.style.display = 'block';
        fetchingFollowers = true;
        fetch(`/following?page=${pageFollowers}`)
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
                    pageFollowers += 1;
                }
                loadingFollowers.style.display = 'none';
                fetchingFollowers = false;
            })
            .catch(error => {
                console.error('Error loading more followers:', error);
                loadingFollowers.style.display = 'none';
            });
    }

    function loadMoreAddToGroup() {
        loadingAddToGroup.style.display = 'block';
        fetchingAddToGroup = true;
        fetch(`/groups/${groupId}/get-members?page=${pageAddToGroup}`)
            .then(response => response.json())
            .then(data => {
                if (data.data.length === 0) {
                    noMoreAddToGroup = true; // Set flag if no more followers
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
                                <button type="button" id="AddToGroup" class="add-member-btn btn btn-secondary" data-user-id="${follower.id}">Add</button>
                            </section>
                        `;
                        addToGroupContainer.appendChild(userDiv);
                    });
                    pageAddToGroup += 1;
                }
                loadingAddToGroup.style.display = 'none';
                fetchingAddToGroup = false;
            })
            .catch(error => {
                console.error('Error loading more followers:', error);
                loadingAddToGroup.style.display = 'none';
            });
    }
});