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