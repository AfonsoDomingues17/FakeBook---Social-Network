function previewProfilePicture(event) {
    var reader = new FileReader();
    reader.onload = function () {
        var output = document.getElementById('p_picture_review');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}

// Function to toggle country dropdown visibility
function toggleCountryDropdown() {
    const dropdown = document.getElementById('country');
    dropdown.style.display = 'block'; // Ensure it's visible when clicked
    updateDropdownSize();
}

// Function to select a country from the dropdown
function selectCountry(event) {
    const countrySearch = document.getElementById('country-search');
    countrySearch.value = event.target.value;

    // Hide the dropdown after selection
    document.getElementById('country').style.display = 'none';
}

// Close the dropdown if the user clicks outside of it
window.addEventListener('click', function(event) {
    const dropdown = document.getElementById('country');
    const countrySearch = document.getElementById('country-search');

    if (!countrySearch.contains(event.target) && !dropdown.contains(event.target)) {
        dropdown.style.display = 'none';
    }
});

// Function to filter countries based on user input
function filterCountries() {
    const searchInput = document.getElementById('country-search').value.toLowerCase();
    const countrySelect = document.getElementById('country');
    const options = countrySelect.options;

    // Reset all options to visible first
    for (let i = 0; i < options.length; i++) {
        options[i].style.display = '';
    }

    // Filter based on input
    let visibleCount = 0;
    for (let i = 0; i < options.length; i++) {
        const option = options[i];
        const text = option.textContent || option.innerText;

        if (text.toLowerCase().indexOf(searchInput) === -1) {
            option.style.display = 'none';
        } else {
            option.style.display = '';
            visibleCount++;
        }
    }

    // Adjust the dropdown size dynamically based on the number of visible options
    updateDropdownSize(visibleCount);
}

// Function to update the dropdown size dynamically
function updateDropdownSize(visibleCount) {
    const dropdown = document.getElementById('country');
    const maxSize = 5;
    // Set the dropdown size to the minimum of visibleCount and maxSize
    dropdown.size = Math.min(visibleCount, maxSize);
}
