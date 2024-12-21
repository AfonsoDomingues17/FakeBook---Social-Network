document.addEventListener('DOMContentLoaded', function() {
    const conversationLinks = document.querySelectorAll('.conversation-link');
    const chatContainer = document.getElementById('chat');
    const specialContainer = document.getElementById('special');
    let currentChannel = null;

    if (conversationLinks && chatContainer) {
        conversationLinks.forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                const conversationId = this.dataset.id;
                const conversationType = this.dataset.type;
                const url = conversationType === 'group' ? `/groups/${conversationId}` : `/direct-chats/${conversationId}`;

                // Remove background color from all info sections
                conversationLinks.forEach(link => link.querySelector('section#info').style.backgroundColor = '');

                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        chatContainer.innerHTML = html;
                        // Set background color of the clicked info section to white
                        this.querySelector('section#info').style.backgroundColor = '#F0F2F5';

                        // Unsubscribe from the previous channel if any
                        if (currentChannel) {
                            currentChannel.unsubscribe();
                        }

                        // Initialize Pusher for the loaded chat
                        currentChannel = initializePusher(conversationId, conversationType);
                    })
                    .catch(error => console.error('Error fetching chat:', error));
            });
        });
    }

    function attachGroupInfoEventListeners() {
        const addMemberSpan = document.getElementById('AddMembers');

        if (addMemberSpan) {
            addMemberSpan.addEventListener('click', function() {
                console.log('Add members span clicked');
            });
        } else {
            console.log('Add members span not found');
        }
    }

    function initializeGroupLazyScroll() {
        const limit = 10;

        const loadingAddToGroup = document.getElementById('loadingAddToGroup');
        let noMoreAddToGroup = false; // Flag to indicate no more followers
        let fetchingAddToGroup = false; // Flag to prevent multiple requests
        let pageAddToGroup = 2; // Start from the second page since the first page is already loaded
        const addToGroupContainer = document.getElementById('addToGroupContainer');
        let groupId = null;
        if (addToGroupContainer) {
            groupId = addToGroupContainer.getAttribute('data-group-id');
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
        }

        function loadMoreAddToGroup() {
            loadingAddToGroup.style.display = 'block';
            fetchingAddToGroup = true;
            fetch(`/groups/${groupId}/get-members?page=${pageAddToGroup}`)
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => { throw new Error(text); });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.length === 0) {
                        noMoreAddToGroup = true; // Set flag if no more followers
                    } else {
                        console.log('Data received:', data); // Log the received data
                        data.forEach(follower => { // Access the data array from the paginated response
                            const userDiv = document.createElement('div');
                            userDiv.classList.add('user');
                            userDiv.innerHTML = `
                                <section id="info">
                                    <img src="/users/${follower.id}/photo" width="70" height="70" alt="user profile picture">
                                    <div class="user-info">
                                        <span id="user"><p>${follower.username}</p></span>
                                        <span id="nome"><p>${follower.name}</p></span>
                                    </div>
                                    <button type="button" id="AddToGroup" class="add-member-btn btn btn-secondary" data-user-id="${follower.id} data-group-id="${$group.id}">Add</button>
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
    }

    function initializeAddToGroupButtons(containerSelector) {
        const container = document.querySelector(containerSelector);
        if (container) {
            const addToGroupButtons = container.querySelectorAll('.add-member-btn');
            addToGroupButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const userId = this.dataset.userId;
                    const groupId = document.getElementById('group_info').getAttribute('data-group-id');
                    fetch(`/groups/${groupId}/add-member/${userId}`, {
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => { throw new Error(text); });
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Member added:', data);
                        this.closest('.user').remove();
                    })
                    .catch(error => {
                        console.error('Error adding member:', error);
                    });
                });
            });
        }
    }

    function initializePusher(conversationId, conversationType) {
        Pusher.logToConsole = true;

        let pusher = new Pusher(pusherAppKey, {
            cluster: pusherCluster,
            encrypted: true
        });

        let channelName = conversationType === 'group' ? 'group-chat-' + conversationId : 'direct-chat-' + conversationId;
        let channel = pusher.subscribe(channelName);

        channel.bind('new-message', function(data) {
            let message = data.message;
            if (!document.querySelector('.message[data-message-id="' + message.id + '"]')) {
                const messageElement = `
                    <div class="message ${message.author_id === parseInt(currentUserId) ? 'my-message' : ''}" data-message-id="${message.id}">
                        <span id="Mymessage"><p>${message.content || ''}</p></span>
                        ${message.image_url ? `<img src="/messages/image/${message.id}" alt="Image">` : ''}
                        ${message.author_id === parseInt(currentUserId) 
                            ? '<button class="delete-message btn btn-danger btn-sm" style="display: none;">Delete</button>' 
                            : ''}
                    </div>`;
                document.getElementById('messages').insertAdjacentHTML('beforeend', messageElement);
                scrollToBottom();
            }
        });

        channel.bind('delete-message', function(data) {
            let messageId = data.message_id;
            let messageElement = document.querySelector('.message[data-message-id="' + messageId + '"]');
            if (messageElement) {
                messageElement.remove();
            }
        });

        const messageForm = document.getElementById('message-form');
        if (messageForm) {
            messageForm.addEventListener('submit', function(event) {
                event.preventDefault();
                let formData = new FormData(this);
                let content = formData.get('content').trim();
                let image = formData.get('image');

                // Validate that either content or image is provided
                if (!content && !image) {
                    alert('Message content or image is required.');
                    return;
                }

                const maxSize = 2 * 1024 * 1024; // 2MB
                if (image && image.size > maxSize) {
                    alert('Image size should not exceed 2MB.');
                    return;
                }

                fetch(this.action, {
                    method: this.method,
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (response.ok) {
                        return response.json(); // Only call .json() if the response is OK
                    } else {
                        return response.text().then(text => {
                            throw new Error(text); // Handle non-200 responses
                        });
                    }
                })
                .then(data => {
                    var message = data.message;
                    if (!document.querySelector('.message[data-message-id="' + message.id + '"]')) {
                        var messageElement = `
                            <div class="message ${message.author_id === parseInt(currentUserId) ? 'my-message' : ''}" data-message-id="${message.id}">
                        <span id="Mymessage"><p>${message.content || ''}</p></span>
                        ${message.image_url ? `<img src="/messages/image/${message.id}" alt="Image">` : ''}
                        ${message.author_id === parseInt(currentUserId) 
                            ? '<button class="delete-message btn btn-danger btn-sm" style="display: none;">Delete</button>' 
                            : ''}
                    </div>`;
                        document.getElementById('messages').insertAdjacentHTML('beforeend', messageElement);
                        scrollToBottom();
                    }
                    this.reset();
                    const imagePreview = document.getElementById('image-preview');
                    imagePreview.src = '#';
                    imagePreview.style.display = 'none';
                })
                .catch(error => {
                    alert('Error: ' + error.message);
                });
            });
        }

        document.getElementById('messages').addEventListener('mouseover', function(event) {
            const messageElement = event.target.closest('.message');
            if (messageElement) {
                const deleteButton = messageElement.querySelector('.delete-message');
                if (deleteButton) {
                    deleteButton.style.display = 'block';
                }
            }
        });

        document.getElementById('messages').addEventListener('mouseout', function(event) {
            const messageElement = event.target.closest('.message');
            if (messageElement) {
                const deleteButton = messageElement.querySelector('.delete-message');
                if (deleteButton) {
                    deleteButton.style.display = 'none';
                }
            }
        });

        document.getElementById('messages').addEventListener('click', function(event) {
            if (event.target.classList.contains('delete-message')) {
                var messageElement = event.target.closest('.message');
                var messageId = messageElement.getAttribute('data-message-id');
                fetch('/messages/' + messageId, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json().catch(() => response.text().then(text => { throw new Error(text); })))
                .then(data => {
                    messageElement.remove();
                })
                .catch(error => {
                    alert('Error: ' + error.message);
                });
            }
        });

        function scrollToBottom() {
            const messages = document.getElementById('messages');
            messages.scrollTop = messages.scrollHeight;

            // Handle newly added images
            const images = messages.querySelectorAll('img');
            if (images.length > 0) {
                const lastImage = images[images.length - 1];
                lastImage.addEventListener('load', () => {
                    messages.scrollTop = messages.scrollHeight;
                });
            }
        }

        // Scroll to bottom on page load
        scrollToBottom();

        return channel; // Return the channel so we can unsubscribe later
    }

    if (specialContainer) {
        document.addEventListener('click', function(event) {
            const target = event.target.closest('#chat-header');
            const chat = event.target.closest('#chat');
            const close = event.target.closest('#close');
            if (target && target.dataset.id) {
                const groupInfo = document.querySelector('#group_info');
                if (groupInfo && groupInfo.dataset.groupId == target.dataset.id) {
                    // Close group info if already open
                    specialContainer.innerHTML = '';
                    specialContainer.appendChild(chatContainer);
                } else if (target.dataset.type === 'group'){
                    fetch(`/groups/${target.dataset.id}/info`)
                        .then(response => response.text())
                        .then(html => {
                            specialContainer.innerHTML = html;
                            attachGroupInfoEventListeners();
                            initializeGroupLazyScroll(); // Reattach listeners for group info
                            initializeAddToGroupButtons('#addToGroupContainer');
                        })
                        .catch(error => console.error('Error fetching group info:', error));
                }
            }
            else if(close && !event.target.closest('#addMembersModal')){
                specialContainer.innerHTML = '';
                specialContainer.appendChild(chatContainer);
            }
        });

        // Close group info when clicking outside of it
        document.addEventListener('click', function(event) {
            if (!event.target.closest('#group_info') && !event.target.closest('#chat-header') && !event.target.closest('#addMembersModal')) {
                const close = document.querySelector('#close');
                if (close) {
                    close.click();
                }
            }
        });
    }     
});