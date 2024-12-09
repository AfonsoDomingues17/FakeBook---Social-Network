const nextButton = document.getElementById('nextButton');
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