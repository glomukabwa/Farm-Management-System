/*Email Validation */
function isEmailValid(email){
    const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/
    /*
    /^  -> start of the regex
    a-z -> small letter alphabets
    A-Z -> capital letter alphabets
    0-9 -> numbers
    ._%+- -> these specific characters are also allowed
    [a-zA-Z0-9._%+-]+ -> what is in the brackets is allowed and then the plus outside means 1 or more
    @ -> Then there must be an @
    [a-zA-Z0-9.-]+ -> Then 1 or more of what is in the brackets
    \. -> Then a literal dot 
    [a-zA-Z]{2,} -> Then whatever is in the brackets but with the constraint of at least two {2,}
    &/  -> end of the regex
    */

    return regex.test(email);/*Returns boolean value*/
}

const emailInput = document.getElementById('email');
const emailMessage = document.getElementById('emailMessage');

emailInput.addEventListener("input", function(){
    const email = this.value;/*You have to do this bcz emailInput is the input element itself not the value it 
    holds and the function works with the value of the input not the input element */

    if(!isEmailValid(email)){
        emailMessage.textContent = "Invalid Email";
        emailMessage.style.color = "red";
    }else{
        emailMessage.textContent = "Valid Email";
        emailMessage.style.color = "green";

        /*Email Duplication check */
    }
});

/*Customized dropdown
const selected = document.querySelector(".selected");
const options = document.querySelector(".options"); 
const hiddenInput = document.querySelector("#role");

// Toggle dropdown 
selected.addEventListener("click", () => {
     options.style.display = options.style.display === "block" ? "none" : "block"; 
}); 

// Handle selection 
options.querySelectorAll("li").forEach(option => {
    option.addEventListener("click", () => { 
        selected.textContent = option.textContent + " ▼"; 
        hiddenInput.value = option.dataset.value; 
        options.style.display = "none"; 
    }); }); 
    
// Close dropdown if clicked outside 
document.addEventListener("click", (e) => {
    if (!e.target.closest(".custom-select")) { 
        options.style.display = "none"; 
    } 
});*/