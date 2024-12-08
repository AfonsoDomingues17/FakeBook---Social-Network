function updateChart(chartType) {
    let range = document.getElementById(chartType + 'Range').value;
    let chartContainer = document.querySelector(`.chart-container[data-chart-type="${chartType}"]`);
    let chartData = JSON.parse(chartContainer.getAttribute('data-chart-data'));

    let data = chartData;
    if (range !== 'all') {
        data = data.slice(0, range);
    }

    let labels = data.map(item => item[chartType === 'followersByCountry' ? 'country' : chartType === 'followersByAge' ? 'age' : 'gender']);
    let counts = data.map(item => item.count);

    let chart = window[chartType + 'Chart'];
    chart.data.labels = labels;
    chart.data.datasets[0].data = counts;
    chart.update();
}
