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

/*Email Validation */
function isEmailValid(email){
    const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/
    /*
    /^  -> start of the regex
    a-z -> small letter alphabets
    A-Z -> capital letter alphabets
    0-9 -> numbers
    ._%+- -> these specific characters are also allowed
    [a-zA-Z0-9._%+-]+ -> what is in the brackets is allowed and then the plus outside means one or more
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
    /*In place of function, you can also write () => They will work the same however, this.value doesn't work when
    u use this. So for the next line where this is used to make a reference to the value of the element the event
    listener is assigned to, it wouldn't work. You use it when you don't have to use this like u'll see in the 
    password similarity check below*/
    const email = this.value;/*You have to do this bcz emailInput is the input element itself not the value it 
    holds and the function works with the value of the input not the input element */

    if(!isEmailValid(email)){
        emailMessage.textContent = "Invalid Email";
        emailMessage.style.color = "red";
    }else{
        emailMessage.textContent = "Valid Email";
        emailMessage.style.color = "green";

        /*Email Duplication check */
        fetch('emailDuplication.php?email=' + encodeURIComponent(email))
            /*The above statement is a string and the plus is concatenation. The encodeURIComponent(email) gets the email we got through
            getElementById and replaces the special characters in it like @,+,& etc with things that are URL friendly bcz the special 
            characters can break the URL. So for example if the email entered so far is test@example.com then the statement will be 
            emailDuplication.php?email=test%40example.com. This statement that we have gotten now is sent as a GET request(since email is 
            not private info, GET is allowed) to emailDuplication.php. The php file receives the URL and uses it to check for duplication
            ($_GET is a PHP superglobal that reads query parameters from the URL). There is a way to send requests using POST method for 
            fetch but its longer and since email is not private info, its safe to use GET to fetch.(Ask chat to help u out with the POST 
            version when you need it).I put "input" as the event for this eventListener and it is triggered 
            every time a change is made in the input field. This mean that every time a character is entered, it is sent to 
            emailDuplication.php and the check happens continuously. AJAX is what is allowing you to interact with the server without 
            submitting the form.

            Proper definition of AJAX: AJAX = talking to the server without refreshing the page. Think of it like WhatsApp. You send a 
            message, you get a reply, the app does not restart. fetch() is what indicates that you are using AJAX bz you are interacting 
            with the server without refreshing the page(without the refreshing that comes after submission)
            */
           .then(response => response.text())/*This reads the response from emailDuplication as text. When it arrives, it comes with other info 
                                               but this reads all that info and extracts the response we want which is either 'exists' or 'valid
                                               Example of how the response comes:
                                                    HTTP/1.1 200 OK
                                                    Content-Type: text/html; charset=UTF-8
                                                    Content-Length: 6

                                                    exists
                                                response.text() converts it to just text so here we'd only get exists. Plz note that in place of
                                                response you can put other words like res, respo etc but follow the structure
                                                Once the response has been read the value is stored in data which is what we use next */
           .then(data => {/*data contains the result of php so we use it to determine what message we want to output */
            if (data === 'exists') {
                emailMessage.textContent = 'Email is already taken';
                emailMessage.style.color = 'red';
            }else{
                emailMessage.textContent = 'Valid Email';
                emailMessage.style.color = 'green';
            }
           });
           
    }
    
});


/*Enforcing password strength*/
function isPasswordStrong(password){
    const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_\-+=?])[a-zA-Z0-9!@#$%^&*_\-+=?]{8,}$/
    /*regex meaning:
    ^                           start
    (?=.*[A-Za-z])              at least one letter
    (?=.*\d) or (?=.*[0-9])     at least one number
    (?=.*[!@#$%^&*_])           at least one special char
    [A-Za-z\d!@#$%^&*_]{8,}     allowed characters, min 8
    $                           end
    */
   return regex.test(password);
}

const newPassword = document.getElementById('newPassword');
const strengthMessage = document.getElementById('pStrengthMessage');

newPassword.addEventListener("input", function() {
    const Pass = this.value;

    if(!isPasswordStrong(Pass)){
        strengthMessage.textContent = 'Weak Password';
        strengthMessage.style.color = 'red';
    }else{
        strengthMessage.textContent = 'Strong Password';
        strengthMessage.style.color = 'green';
    }
})


/*Checking if the passwords entered are similar*/
const confirmPassword = document.getElementById('confirmPassword');
const confirmMessage = document.getElementById('confirmMessage');

confirmPassword.addEventListener("input", () => {
    if(newPassword.value == '' && confirmPassword.value == ''){
        confirmMessage.textContent = '';
        return; /*This is almost like exit in php. The function will stop here and we'll just continue with the
        code outside the function*/
    }
    if(newPassword.value === confirmPassword.value){
        confirmMessage.textContent = 'Password matches';
        confirmMessage.style.color = 'green';
    }else{
        confirmMessage.textContent = 'Password does not match';
        confirmMessage.style.color = 'red';
    }
});       

/*Preventing ruining of the listeners every time the form reloads after submission */
/*const signupForm = document.querySelector("form");/*This selects the first form it finds and since there's only 1, its okay
signupForm.addEventListener("submit", function(e){
    e.preventDefault(); /*When you submit the form, the browser reloads the page (default HTML form behavior). All
                          JS variables, event listeners, messages, states are destroyed. 
                          When the page reloads fresh → your JS has not run again yet (or runs before DOM is ready)
                          So after the first submission, typing again appears to “do nothing” but in reality, the
                          page was refreshed and JS is no longer attached properly
                          So we preventDefault befavior witch is reloading to avoid lack of response from the 
                          listeners
});*/