const form = document.getElementById("loginForm");
const emailInput = document.getElementById("email");
const emailMessage = document.getElementById("emailMessage");
const passInput = document.getElementById("password");
const passMessage = document.getElementById("passwordMessage");
const allInputs = document.querySelectorAll("input[required]");
const submitButton = document.getElementById("submitButton");
const btnMessage = document.getElementById("btnMessage");

/*Disabling the submit button unless all inputs are filled 
function checkIfFilled(){
    const allFilled = Array.from(allInputs).every(input => input.value.trim() !== "");
    /* every() is not similar to forEach(). every() tests whether all elements in the array pass a condition (predicate function).
    It returns a boolean (true or false). forEach() ensures that a condition is applied in every single element
    submitButton.disabled = !allFilled; /*So here we are saying that the button is disabled if allFilled = false 
}

allInputs.forEach(input => input.addEventListener("input", checkIfFilled));
/*Above, allInputs is a NodeList (like an array of inputs). You can’t attach one listener to the whole NodeList. You need to loop so son't say
allInputs.addEventListener("input", checkIfFilled); */
/*Had to comment out the above block of code cz the messages below were only showing when both fields were empty and I hadn't tried to type(the
  blocking above is only triggered on input). If I had entered one but hadn't entered the other, the Log In button would be blocked so even though
  one field had not been entered, the message for that field would not show cz the event listener below would not be triggered.
  It was either this option or displaying the error messages on input and I find that irritating in forms*/

/*Displaying custom message incase field is empty*/
form.addEventListener("submit", function(event) {
    let valid = true;
    /*Above, submit events fire on the form, not on the button. If you attach submit to the button, it never runs. */
    if(emailInput.value.trim() === ''){
        emailMessage.textContent = "Please enter your email";
        valid = false;
    }else{
        emailMessage.textContent = "";
    }

    if(passInput.value.trim() === ''){
        passMessage.textContent = "Please enter your password";
        valid = false;
    }else{
        passMessage.textContent = "";
    }

    if(!valid){
        event.preventDefault();
        /*This prevents the default behavior of a browser. So for example for submitting a form, here is what usual happens:
          1. First the browser runs its own validation which means if you’ve used attributes like required, type="email", or pattern, the browser checks them first.
             If any rule fails, the browser shows its own popup message (e.g., “Please fill out this field”) and stops right there
          2. Then it submits the form data
          3. Then it either reloads the page if it has no where to go next or redirects to the next page
        novalidate in HTML stops step 1 and we do this cz we want to display our own messages, we don't want the default popup
        preventDefault() stops steps 2 and 3 from happening and the reason we want this is because if we don't, yes our messages can now be displayed cz novalidate 
        is now present but we may never see them cz the submission and redirection will be so fast that we'll only see a flash of the message or no messages at all.
        I know u're probably wondering, won't php block the submission of empty elements? Yes it will. I am explaining this with the assumption that you haven't put 
        this measure in php. I'm guessing this is why Chat insists that you don't rely on UI to check for empty elements, it's better to reinforce in backend
        Ok so now that the submission and reloading is blocked, they won't happen until all ur specified conditions are met*/
    }
});
