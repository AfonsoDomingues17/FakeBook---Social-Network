let currentOrder = 'desc';

function updateChart(chartType, filteredData = null) {
    let range = document.getElementById(chartType + 'Range').value;
    let chartContainer = document.querySelector(`.chart-container[data-chart-type="${chartType}"]`);
    let chartData = JSON.parse(chartContainer.getAttribute('data-chart-data'));

    let data = filteredData || chartData;
    if (currentOrder === 'asc') {
        data = data.sort((a, b) => a.count - b.count);
    } else {
        data = data.sort((a, b) => b.count - a.count);
    }

    if (range !== 'all' && !filteredData) {
        data = data.slice(0, range);
    }

    let labels = data.map(item => item[chartType === 'followersByCountry' ? 'country' : chartType === 'followersByAge' ? 'age' : 'gender']);
    let counts = data.map(item => item.count);

    let chart = window[chartType + 'Chart'];
    chart.data.labels = labels;
    chart.data.datasets[0].data = counts;
    chart.update();
}

function filterFollowersByCountry() {
    const searchValue = document.getElementById('countrySearch').value.toLowerCase();
    const rangeSelect = document.getElementById('followersByCountryRange');
    const chartContainer = document.querySelector('.chart-container[data-chart-type="followersByCountry"]');
    const chartData = JSON.parse(chartContainer.getAttribute('data-chart-data'));

    if (searchValue) {
        rangeSelect.style.visibility = 'hidden';
        const filteredData = chartData.filter(item => item.country.toLowerCase().includes(searchValue));
        updateChart('followersByCountry', filteredData);
    } else {
        rangeSelect.style.visibility = 'visible';
        rangeSelect.value = 'all';
        updateChart('followersByCountry');
    }
}

function filterFollowersByAge() {
    const searchValue = document.getElementById('ageSearch').value;
    const rangeSelect = document.getElementById('followersByAgeRange');
    const chartContainer = document.querySelector('.chart-container[data-chart-type="followersByAge"]');
    const chartData = JSON.parse(chartContainer.getAttribute('data-chart-data'));

    if (searchValue) {
        rangeSelect.style.visibility = 'hidden';
        const filteredData = chartData.filter(item => item.age == searchValue);
        updateChart('followersByAge', filteredData);
    } else {
        rangeSelect.style.visibility = 'visible';
        rangeSelect.value = 'all';
        updateChart('followersByAge');
    }
}

function toggleOrder(chartType) {
    currentOrder = currentOrder === 'asc' ? 'desc' : 'asc';
    const orderButton = document.getElementById(`order${chartType.charAt(0).toUpperCase() + chartType.slice(1)}`);
    orderButton.innerHTML = `Toggle Order ${currentOrder === 'asc' ? '↑' : '↓'}`;
    const searchValue = chartType === 'followersByCountry' ? document.getElementById('countrySearch').value.toLowerCase() : document.getElementById('ageSearch').value;
    if (searchValue) {
        chartType === 'followersByCountry' ? filterFollowersByCountry() : filterFollowersByAge();
    } else {
        updateChart(chartType);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('countrySearch').addEventListener('input', filterFollowersByCountry);
    document.getElementById('ageSearch').addEventListener('input', filterFollowersByAge);
    document.getElementById('followersByCountryRange').addEventListener('change', () => updateChart('followersByCountry'));
    document.getElementById('followersByAgeRange').addEventListener('change', () => updateChart('followersByAge'));
    document.getElementById('orderFollowersByCountry').addEventListener('click', () => toggleOrder('followersByCountry'));
    document.getElementById('orderFollowersByAge').addEventListener('click', () => toggleOrder('followersByAge'));
});