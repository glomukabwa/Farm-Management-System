const salesChart = document.getElementById("salesChart");

new Chart(salesChart, {
    type: 'line',
    data: {
        labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
        datasets: [
            {
                label: 'Target Sales',
                data: [5000, 7000, 6500, 8000],//You'll notice that this is respective to the weeks
                borderWidth:2, // This is the width of the line
                tension: 0.4 //makes the line smooth/wavy. The less it is the more static the line looks
            },
            {
                label: 'Actual Sales',
                data: [4500, 7200, 6000, 9000],
                borderWidth: 2,
                tension: 0.4
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top' //This makes the 'keys' appear at the top
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});