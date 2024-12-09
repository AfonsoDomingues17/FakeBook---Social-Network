document.addEventListener('DOMContentLoaded', function() {
    Pusher.logToConsole = true;

    var pusher = new Pusher(pusherAppKey, {
        cluster: pusherCluster,
        encrypted: true
    });

    var channel = pusher.subscribe('direct-chat-' + directChatId);
    channel.bind('new-message', function(data) {
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
    });
    
    channel.bind('delete-message', function(data) {
        var messageId = data.message_id;
        var messageElement = document.querySelector('.message[data-message-id="' + messageId + '"]');
        if (messageElement) {
            messageElement.remove();
        }
    });

    document.getElementById('message-form').addEventListener('submit', function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        var content = formData.get('content').trim();
        var image = formData.get('image');

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
        })
        .catch(error => {
            alert('Error: ' + error.message);
        });
    });

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
});