function filterFollowersByCountry() {
    const searchValue = document.getElementById('countrySearch').value.toLowerCase();
    const chartContainer = document.querySelector('.chart-container[data-chart-type="followersByCountry"]');
    const chartData = JSON.parse(chartContainer.getAttribute('data-chart-data'));

    const filteredData = chartData.filter(item => item.country.toLowerCase().includes(searchValue));
    updateChartWithFilteredData('followersByCountry', filteredData);
}

function filterFollowersByAge() {
    const searchValue = document.getElementById('ageSearch').value;
    const chartContainer = document.querySelector('.chart-container[data-chart-type="followersByAge"]');
    const chartData = JSON.parse(chartContainer.getAttribute('data-chart-data'));

    const filteredData = chartData.filter(item => item.age == searchValue);
    updateChartWithFilteredData('followersByAge', filteredData);
}

function updateChartWithFilteredData(chartType, filteredData) {
    let labels = filteredData.map(item => item[chartType === 'followersByCountry' ? 'country' : 'age']);
    let counts = filteredData.map(item => item.count);

    let chart = window[chartType + 'Chart'];
    chart.data.labels = labels;
    chart.data.datasets[0].data = counts;
    chart.update();
}