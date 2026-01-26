/*Customized select
const topOption = document.querySelector(".topOption");
const options = document.querySelector(".options");
const hiddenInput = document.getElementById("animalTypeInput");
const arrow = document.querySelector(".downwardArrow");

/*topOption.addEventListener("click", () => {
    options.style.display = options.style.display == "block" ? "none" : "block";
});

options.querySelectorAll("li").forEach(option => {
    option.addEventListener("click", () => {
        topOption.textContent = option.textContent + arrow.textContent;
        hiddenInput.value = option.dataset.value;
        options.style.display = "none";
    });
});

document.addEventListener("click", function(e){
    if(!e.target.closest(".selectOption")){
        options.style.display = "none";
    }
});*/