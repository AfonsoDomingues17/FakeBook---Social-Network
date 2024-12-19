document.addEventListener('DOMContentLoaded', function() {
    const nextButton = document.getElementById('nextButton');
    const backButton = document.getElementById('backButton');
    const groupCreationModal = document.getElementById('groupCreationModal');
    const selectedUsersInput = document.getElementById('selected_users');

    let selectedUsers = [];

    if (groupCreationModal) {
        groupCreationModal.addEventListener('hidden.bs.modal', function() {
            // Reset modal to initial state
            document.getElementById('pageUsers').style.display = 'block';
            document.getElementById('CreationPage').style.display = 'none';
            nextButton.style.display = 'block';
            selectedUsers = [];
            document.querySelectorAll('.add-member-btn').forEach(button => {
                button.classList.remove('btn-success');
                button.classList.add('btn-secondary');
                button.textContent = 'Add';
            });
        });
    }

    if (nextButton) {
        nextButton.addEventListener('click', () => {
            document.getElementById('pageUsers').style.display = 'none';
            document.getElementById('CreationPage').style.display = 'block';
            nextButton.style.display = 'none';
            selectedUsersInput.value = selectedUsers.join(',');
        });
    }

    if (backButton) {
        backButton.addEventListener('click', () => {
            document.getElementById('pageUsers').style.display = 'block';
            document.getElementById('CreationPage').style.display = 'none';
            nextButton.style.display = 'block';
        });
    }

    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('add-member-btn')) {
            const button = event.target;
            const userId = button.getAttribute('data-user-id');
            if (selectedUsers.includes(userId)) {
                selectedUsers = selectedUsers.filter(id => id !== userId);
                button.classList.remove('btn-success');
                button.classList.add('btn-secondary');
                button.textContent = 'Add';
            } else {
                selectedUsers.push(userId);
                button.classList.remove('btn-secondary');
                button.classList.add('btn-success');
                button.textContent = 'Added';
            }
        } else if (event.target.classList.contains('remove-member-btn') || event.target.closest('.remove-member-btn')) {
            const button = event.target.closest('.remove-member-btn');
            const groupId = document.querySelector('#group_info').dataset.groupId;
            const userId = button.getAttribute('data-user-id');
            fetch(`/groups/${groupId}/remove-member`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ user_id: userId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Member removed successfully');
                    event.target.closest('#member').remove();
                }
            })
            .catch(error => console.error('Error removing member:', error));
        } else if (event.target.id === 'pencilEditGname') {
            const gname = document.getElementById('gname');
            const gnameInput = document.getElementById('gname_edit');
            gname.style.display = 'none';
            gnameInput.style.display = 'flex';
        } else if (event.target.id == 'pencilEditGdescription') {
            const gdescription = document.getElementById('gdescription');
            const gdescriptionInput = document.getElementById('gdescription_edit');
            gdescription.style.display = 'none';
            gdescriptionInput.style.display = 'flex';
        } else if (event.target.classList.contains('fa-check') && event.target.closest('#gname_edit, #gdescription_edit')) {
            const input = event.target.previousElementSibling;
            const value = input.value;
            const field = input.name;
            const groupId = document.querySelector('#group_info').dataset.groupId;

            fetch(`/groups/${groupId}/update`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ [field]: value })
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        try {
                            const errorData = JSON.parse(text);
                            throw new Error(errorData.message || 'Unknown error');
                        } catch (e) {
                            throw new Error(text);
                        }
                    });
                }
                return response.json();
            })
            .then(data => {
                if (field === 'group_name') {
                    document.getElementById('gname').querySelector('p').textContent = data.name;
                } else if (field === 'group_description') {
                    document.getElementById('gdescription').querySelector('p').textContent = data.description;
                }
                input.parentElement.style.display = 'none';
                input.parentElement.previousElementSibling.style.display = 'block';
            })
            .catch(error => {
                console.error('Group name or description update error:', error);
            });
        }
    });

    // Revert input fields back to pencil icon when clicking outside of them
    document.addEventListener('click', function(event) {
        const gname = document.getElementById('gname');
        const gnameInput = document.getElementById('gname_edit');
        const gdescription = document.getElementById('gdescription');
        const gdescriptionInput = document.getElementById('gdescription_edit');

        if (gname && gnameInput && !event.target.closest('#gname_edit') && !event.target.closest('#pencilEditGname')) {
            if (gnameInput.style.display === 'flex') {
                gnameInput.style.display = 'none';
                gname.style.display = 'block';
            }
        }

        if (gdescription && gdescriptionInput && !event.target.closest('#gdescription_edit') && !event.target.closest('#pencilEditGdescription')) {
            if (gdescriptionInput.style.display === 'flex') {
                gdescriptionInput.style.display = 'none';
                gdescription.style.display = 'block';
            }
        }
    });
});