document.addEventListener('DOMContentLoaded', function() {
    const orderRadios = document.querySelectorAll('input[id^="order-"]');
    const orderButton = document.getElementById('feed-order');

    orderRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            orderRadios.forEach(r => {
                if (r !== radio) {
                    r.checked = false;
                }
            });
        });
    });

    if (orderButton) {
        orderButton.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the form from submitting
            const selectedOrder = Array.from(orderRadios).find(radio => radio.checked).id;
            let orderValue;

            switch (selectedOrder) {
                case 'order-1':
                    orderValue = undefined;
                    break;
                case 'order-2':
                    orderValue = 'DESC';
                    break;
                case 'order-3':
                    orderValue = 'ASC';
                    break;
                default:
                    orderValue = '';
                    break;
            }

            const searchQuery = document.querySelector('input[name="query"]').value;
            updateSearchResults('posts', searchQuery, orderValue);
        });
    }

    function updateSearchResults(type, query, order) {
        document.getElementById('loading').style.display = 'block';
        document.getElementById('search-results-container').style.display = 'none';
        var url = `${searchUrl}?type=${type}&query=${query}`;
        if (order) url += `&order=${order}`;
        fetch(url)
            .then(response => response.text())
            .then(data => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(data, 'text/html');
    
                let classToSearch; 
                switch (type) {
                    case 'users':
                        classToSearch = 'article.user';
                        break;
                    case 'posts':
                        classToSearch = 'article.post';
                        break;
                    default:
                        classToSearch = '';
                        break;
                }
    
                const elements = doc.querySelectorAll(classToSearch);
                const searchResults = document.getElementById('search-results-container');
                searchResults.innerHTML = ''; // Clear previous results
    
                elements.forEach(element => {
                    searchResults.appendChild(element);
                });
    
                document.querySelector('#search-results h2').innerHTML = `Search results (${type}) for "${query}"`;
                document.getElementById('loading').style.display = 'none';
                document.getElementById('search-results-container').style.display = 'block';
            })
            .catch(error => {
                console.error('Error fetching search results:', error);
                document.getElementById('loading').style.display = 'none';
            });
    }
});