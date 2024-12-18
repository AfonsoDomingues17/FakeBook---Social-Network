document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchUsers');
    const realTime = document.getElementById('real-time-search-group'); // Updated ID for search results
    const searchForm = document.getElementById('search-form');
    const paginatedResults = document.getElementById('paginated-results');
    const initialGroup = document.getElementById('initialGroup');

    const newButton = document.createElement('button');
    newButton.id = 'AddMember';
    newButton.classList.add('add-member-btn', 'btn', 'btn-secondary');
    newButton.textContent = 'Add';

    searchInput.addEventListener('input', function() {
        const query = searchInput.value;
        if (query.length > 0) {
            initialGroup.style.display = 'none';
            const url = `/search?query=${query}&type=users&group=true`;
            fetch(url)
                .then(response => response.text())
                .then(data => {
                    realTime.innerHTML = '';
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = data;
                    const searchResults = tempDiv.querySelector('#search-results-container');
                    if (searchResults) {
                        const elements = Array.from(searchResults.querySelectorAll('.user')).slice(0, 5);
                        elements.forEach(element => {
                            const item = document.createElement('div');
                            item.classList.add('user');
                            item.innerHTML = element.innerHTML;
                            realTime.appendChild(item);
                            const info = item.querySelector('#info');
                            const oldButton = info.querySelector('button');
                            const data_user_id = oldButton.getAttribute('data-user-id');    
                            oldButton.replaceWith(newButton.cloneNode(true));
                            const clonedButton = item.querySelector('button');
                            clonedButton.setAttribute('data-user-id', data_user_id);
                        });
                        realTime.style.display = 'block'; // Show the dropdown
                    } else {
                        realTime.style.display = 'none'; // Hide the dropdown if no results
                    }
                })
                .catch(error => {
                    console.error('Error fetching search results:', error);
                    realTime.classList.remove('show'); // Hide the dropdown on error
                });
        } else {
            realTime.innerHTML = '';
            realTime.style.display = 'none'; // Hide the dropdown if the query is empty
            initialGroup.style.display = 'block';
        }
    });

    // Prevent form submission on 'ENTER' key press
    searchForm.addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
        }
    });

    // Handle form submission to stay in the modal and display paginated results
    searchForm.addEventListener('submit', function(event) {
        event.preventDefault();
    });

    // Hide the dropdown when clicking outside the search input
    document.addEventListener('click', function(event) {
        if (!searchInput.contains(event.target) && !realTime.contains(event.target)) {
            realTime.classList.remove('show');
            realTime.style.display = 'none';
        }
        else {
            realTime.style.display = 'block';
        }
    });
});