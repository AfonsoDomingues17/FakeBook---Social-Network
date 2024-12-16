document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const realTime = document.getElementById('real-time-search');

    searchInput.addEventListener('input', function() {
        const query = searchInput.value;
        if (query.length > 0) {
            fetch(`/search?query=${query}&type=users`)
                .then(response => response.text())
                .then(data => {
                    realTime.innerHTML = '';
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = data;
                    const searchResults = tempDiv.querySelector('#search-results-container');
                    if (searchResults) {
                        const elements = Array.from(searchResults.querySelectorAll('.user')).slice(0, 5);
                        elements.forEach(element => {
                            const item = document.createElement('li');
                            item.classList.add('user');
                            item.innerHTML = element.innerHTML;
                            realTime.appendChild(item);
                        });
                        realTime.classList.add('show'); // Show the dropdown
                    } else {
                        realTime.classList.remove('show'); // Hide the dropdown if no results
                    }
                })
                .catch(error => {
                    console.error('Error fetching search results:', error);
                    realTime.classList.remove('show'); // Hide the dropdown on error
                });
        } else {
            realTime.innerHTML = '';
            realTime.classList.remove('show'); // Hide the dropdown if the query is empty
        }
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