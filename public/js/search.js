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
                    console.log(searchResults);
                    const elements = Array.from(searchResults.querySelectorAll('.user')).slice(0, 5);
                    elements.forEach(element => {
                        const item = document.createElement('li');
                        item.classList.add('dropdown-item');
                        item.innerHTML = element.innerHTML;
                        realTime.appendChild(item);
                    });
                });
        } else {
            realTime.innerHTML = '';
        }
    });
});