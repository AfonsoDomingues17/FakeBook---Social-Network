document.addEventListener('DOMContentLoaded', function() {
    console.log('group_participants.js loaded');
    const addMemberButtonsGroupParticipants = document.querySelectorAll('.add-member-btn-group-participants');
    const confirmButtonGroupParticipants = document.getElementById('confirmGroupParticipants');
    const groupParticipantsModal = document.getElementById('groupParticipantsModal');
    let selectedUsersGroupParticipants = [];

   

        addMemberButtonsGroupParticipants.forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                console.log('Add button clicked for user ID:', userId);
                if (selectedUsersGroupParticipants.includes(userId)) {
                    selectedUsersGroupParticipants = selectedUsersGroupParticipants.filter(id => id !== userId);
                    this.classList.remove('btn-success');
                    this.classList.add('btn-secondary');
                    this.textContent = 'Add';
                } else {
                    selectedUsersGroupParticipants.push(userId);
                    this.classList.remove('btn-secondary');
                    this.classList.add('btn-success');
                    this.textContent = 'Added';
                }
                console.log('Selected users:', selectedUsersGroupParticipants);
            });
        });

        if (groupParticipantsModal) {
            groupParticipantsModal.addEventListener('show.bs.modal', function() {
                const groupId = document.querySelector('#group_info').dataset.groupId;
                console.log('Modal shown');
                confirmButtonGroupParticipants.addEventListener('click', function() {
                    const addUserIds = selectedUsersGroupParticipants;
                    console.log('Confirm button clicked, user IDs to add:', addUserIds);

                    if (addUserIds.length > 0) {
                        fetch(`/groups/${groupId}/add-members`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ user_ids: addUserIds })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log('Members added successfully');
                                // Handle success
                                // Optionally, you can update the UI to reflect the added members
                            }
                        })
                        .catch(error => console.error('Error adding members:', error));
                    }

                    // Close the modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('groupParticipantsModal'));
                    modal.hide();
                });
            });

            groupParticipantsModal.addEventListener('hide.bs.modal', function() {
                console.log('Modal hidden');
                // Reset selected users list
                selectedUsersGroupParticipants = [];
                addMemberButtonsGroupParticipants.forEach(button => {
                    button.classList.remove('btn-success');
                    button.classList.add('btn-secondary');
                    button.textContent = 'Add';
                });
            });
        }
    

    // Ensure the confirm button does not interfere with other event listeners
    document.addEventListener('click', function(event) {
        if (event.target.id === 'confirmGroupParticipants') {
            event.stopPropagation();
        }
    });
});