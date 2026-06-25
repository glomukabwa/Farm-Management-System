const salesChart = document.getElementById("salesChart");

const salesContent = document.querySelector(".salesContent");
const salesLabels = JSON.parse(salesContent.dataset.labels);
const salesData = JSON.parse(salesContent.dataset.values);

/*A JSON file is used for storing and exchanging data. Rn JS need the data from PHP but it doesn't understand it so
  JSON file comes in. It is a text-based representation of objects and arrays that can be understood by both PHP and JS.
  This means that sth from JS can also be converted to JSON format so that it can be read by PHP
  Sth I've learnt too is that PHP lives on the server while JS exists on the browser. So I'm guessing this is how they can
  communicate with one another.

  Above, JSON.parse() converts the JSON text to JS
*/

new Chart(salesChart, {
    type: 'bar',
    data: {
        labels: salesLabels,
        datasets: [
            {
                label: 'Ksh',
                data: salesData,
                borderWidth: 2
            }
        ]
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