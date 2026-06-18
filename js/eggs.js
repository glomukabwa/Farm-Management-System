const eggsProdChart = document.getElementById("eggsProdChart");

const eggsProdChartContent = document.querySelector(".eggsProdChartContent");
const eggsProdLabels = JSON.parse(eggsProdChartContent.dataset.labels);
const eggsProdValues = JSON.parse(eggsProdChartContent.dataset.values);

new Chart(eggsProdChart, {
    type: 'bar',
    data: {
        labels: eggsProdLabels,
        datasets: [{
            data: eggsProdValues,
            label: 'Trays'
        }]
    }
});

const eggsSalesChart = document.getElementById("eggsSalesChart");

const eggsSalesChartContent = document.querySelector(".eggsSalesChartContent");
const eggsSalesLabels = JSON.parse(eggsSalesChartContent.dataset.labels);
const eggsSalesValues = JSON.parse(eggsSalesChartContent.dataset.values);

new Chart(eggsSalesChart, {
    type: 'bar',
    data: {
        labels: eggsSalesLabels,
        datasets: [{
            label: 'Ksh',
            data: eggsSalesValues
        }]
    }
})