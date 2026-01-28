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

const emailInput = document.getElementById("email");
const emailMessage = document.getElementById("emailMessage");

function isEmailValid(email){
    const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/
    return regex.test(email);/*Returns boolean value*/
}

emailInput.addEventListener("input", function(){
    if(isEmailValid(emailInput.value)){
        emailMessage.textContent = "Valid Email";
        emailMessage.style.color = "green";
    }else{
        emailMessage.textContent = "Invalid Email";
        emailMessage.style.color = "red";
    }
});

