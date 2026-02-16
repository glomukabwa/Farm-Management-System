const salesChart = document.getElementById("salesChart");

const salesContent = document.querySelector(".salesContent");
const salesLabels = JSON.parse(salesContent.dataset.labels);
const salesData = JSON.parse(salesContent.dataset.values);

/*A JSON file is used for storing and exchanging data. Rn JS need the data from PHP but it doesn't understand it so
  JSON file comes in. It is a text-based representation of objects and arrays that can be understood by both PHP and JS.
  This means that sth from JS can also be converted to FSON format so that it can be read by PHP
  Sth I've learnt too is that PHP lives on the server while JS exists on the browser. So I'm guessing this is how they can
  communicate with one another.

  Above, JSON.parse() converts the JSON text to JS
*/

new Chart(salesChart, {
    type: 'line',
    data: {
        labels: salesLabels,
        datasets: [
            {
                label: 'Weekly Sales',
                data: salesData,
                borderWidth: 2,
                tension:0.2,
                borderColor: 'black',
                backgroundColor: 'black', //Fils the nodes on the chart and the legend with this color
                pointRadius: 0 //Removes the nodes
            }
        ],
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                // Legend-level options
                align: 'end', //Makes it move to the right

                // Options specifically for the **text and symbol** in the legend
                labels: {
                    usePointStyle: true,   // makes the legend symbol a shape (circle, triangle, etc.)
                    pointStyle: 'circle',  // options: 'circle', 'triangle', 'rect', 'rectRounded', 'rectRot', 'cross', 'star', 'line', 'dash'
                    color: 'black',     // legend text color
                    //pointStyleWidth: 15,   //Resizes the circle width
                    pointStyleWidth: 12, // circle width
                    boxWidth: 12, //This is the space allocated to the circle so this is the only way to controll the height
                    font: { size: 10 },
                    //padding: 10            // space around the legend items
                }
            }
        },
        scales: {
            x: {
                offset: true,// Makes the labelse not begind and end at the edge. Chat says it gives the axis padding to the left and right
                border: {
                    display: true,
                    color: 'black',
                    width: 2
                },
                grid: {
                    display: true, //false removes the vertical grid lines
                    tickLength: 10, //Ticks are the lines after the axes that point to the label
                    tickWidth: 2,
                    tickColor: 'black' //This affects the color of the ticks
                },
                ticks: {
                    color: 'black' // This affects the color of the label themselves
                }
            },
            y: {
                offset: true,
                border: {
                    display: true,
                    color: 'black',
                    width: 2
                },
                grid: {
                    display: true, //false removes the horizontal grid lines
                    tickColor: 'black',
                    tickWidth: 2,
                    tickLength: 10   
                },
                ticks: {
                    color: 'black'
                },
                beginAtZero:true,
                suggestedMax: 1000000, // There's max and suggestedMax. max is fixed while suggestedMax is better cz it allows chart to exceed 1M if needed
                //min: 0 // This is supposed to ensure the line doesn't bend even slightly below zero but for some reason, it isn't working. The tension is what is making it so flexible so I'm gonna reduce the tension instead
            }
        }
    }
});

const overLay = document.querySelector(".editTasksOverLay");
const openPopUp = document.getElementById("editTasks");
const closePopUp = document.getElementById("closeEditTasks");

openPopUp.onclick = () => {
    overLay.classList.add("show");
};

closePopUp.onclick = () => {
    overLay.classList.remove("show");
};

overLay.onclick = (e) => {
    if(e.target === overLay){
        overLay.classList.remove("show");
    }/*This part means that if you click the overlay which is outside the popup, then exit*/
};