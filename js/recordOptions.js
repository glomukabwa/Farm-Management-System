const searchInput = document.getElementById("search");
const links = document.querySelectorAll(".link-container .link");

searchInput.addEventListener("input", function() {
    const input = this.value.toLowerCase();//I'm making the search case-insensitive to easen searching

    links.forEach(link => {
        const linkText = link.querySelector("p").textContent.toLowerCase();

        if(linkText.includes(input)){
            link.style.display = "block";
        }else{
            link.style.display = "none";
        }
    });
});