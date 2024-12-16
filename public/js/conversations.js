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
                            <div class="message" data-message-id="${message.id}" style="position: relative;">
                                <strong>${message.author.name}:</strong>
                                <p>${message.content || ''}</p>
                                ${message.image_url ? `<img src="/messages/image/${message.id}" alt="Image">` : ''}
                                ${message.author_id === parseInt(currentUserId) 
                                    ? '<button class="delete-message btn btn-danger btn-sm" style="position: absolute; top: 0; right: 0; display: none;">Delete</button>' 
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
            const close = event.target.closest('#close');
            if (target && target.dataset.id) {
                fetch(`/groups/${target.dataset.id}/info`)
                    .then(response => response.text())
                    .then(html => {
                        if (window.innerWidth < 1300) { // Replace content if screen is too small
                            specialContainer.innerHTML = html;
                        } else { // Append content if screen is large enough
                            specialContainer.innerHTML += html;
                        }
                    })
                    .catch(error => console.error('Error fetching group info:', error));
            }
            else if(close){
                console.log(chatContainer);
                specialContainer.innerHTML = '';
                specialContainer.appendChild(chatContainer);
            }
        });
    }
});

