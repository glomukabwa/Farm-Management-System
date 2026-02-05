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

if(emailInput && emailMessage){/*This checks that these elements exist. I am using one JS file for many files
    so that means that some files won't have some of the elements mentioned here. Unlike with CSS, this will
    result in an error. enterAnimal has no email so if you are using the page, the browser will throw an error cz it
    can't attach a listener to sth that doesn't exist in the page so always check if things exist if u are 
    using a shared JS file */
    emailInput.addEventListener("input", function(){
    if(isEmailValid(emailInput.value)){
        emailMessage.textContent = "Valid Email";
        emailMessage.style.color = "green";
    }else{
        emailMessage.textContent = "Invalid Email";
        emailMessage.style.color = "red";
    }
});
}

const successMessage = document.getElementById("successMessage");

if(successMessage){
    if(successMessage.textContent){/*Returns true if textContent is set*/
    setTimeout(() => {
        successMessage.style.opacity = '0';
        }, 2000);
    }
}


/*This is for total cost in enterSale.php */
const totalCost = document.getElementById("total-cost");
const unit = document.getElementById("unitCost");
const quantity = document.getElementById("quantity-input");

if(totalCost && unit && quantity){
    
    function calculateTotal(){
        totalCost.textContent = Number(quantity.value || 0) * Number(unit.value || 0);
        /*The OR operator (||) in JS is not just true/false — it returns the first “truthy” value it finds.
        A truthy value is anything that is considered valid/true in JS while a falsy value is considered “empty” or false-like.
        So it only kicks in if the quantity or unitCost is empty or undefined(when nothing has been entered yet which will really
        help cz I've added an event listener to both quantity and unit) */
    }

    quantity.addEventListener("input", calculateTotal);
    unit.addEventListener("input", calculateTotal);
}


/*This is for enterFeeds.php*/
const feedSelect = document.getElementById("feedSelect");
const existingFeed = document.querySelector(".existingFeed");
const newFeed = document.querySelector(".newFeed");

if(feedSelect && existingFeed && newFeed){
    feedSelect.addEventListener("change", function(){
        if(feedSelect.value == "New Feed"){
            existingFeed.style.display = "none";
            newFeed.style.display = "block";
        }else{
            existingFeed.style.display = "block";
            newFeed.style.display = "none";
        }
    });
        
}


