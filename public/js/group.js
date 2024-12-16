const nextButton = document.getElementById('nextButton');
const AddMemberButton = document.getElementById('AddMember');
const groupParticipantsModal = new bootstrap.Modal(document.getElementById('groupParticipantsModal'));
console.log(AddMemberButton);
console.log(groupCreationModal);
if(nextButton){
    nextButton.addEventListener('click', () => {
        console.log('click');
        const users = document.getElementById('pageUsers');
        const creation = document.getElementById('CreationPage');
        console.log(users);
        console.log(creation);
        if(users && creation){
            users.style.display = 'none';
            creation.style.display = 'block';
            nextButton.style.display = 'none';
        }
    });
}


document.addEventListener('click', (event) => {
    if(event.target.id === 'AddMember'){
        groupParticipantsModal.show(); 

    }
    else if(event.target.id === 'pencilEditGname'){
        const gname = document.getElementById('gname');
        const gnameInput = document.getElementById('gname_edit');
        gname.style.display = 'none';
        gnameInput.style.display = 'flex';
    }
    else if(event.target.id == 'pencilEditGdescription'){
        const gdescription = document.getElementById('gdescription');
        const gdescriptionInput = document.getElementById('gdescription_edit');
        gdescription.style.display = 'none';
        gdescriptionInput.style.display = 'flex';
    }
});
