const searchInput = document.getElementById("search");
const tableBody = document.getElementById("table-body");

let page = 1;/*The page and limit can't be const because they constantly change. If you declare them constant and
                someone searches and wants to see the remaining results in the next page, the arrows for changing
                pages won't work and will throw an error cz the user will be trying to change a constant variable.
                Same for limit, after seacrhing, the user also has to be allowed to change the number of rows they
                want to see for a search. I am setting them to 1 and 10 though cz you always need to set default
                values*/
let limit = 10;

searchInput.addEventListener("input", function(){
    page = 1; /*Despite the fact that we've set page above to 1, it's a mutable variable so lets say that the
                user is in page 3 and has started seraching. The value of the vpage variable has changed to 3.
                It is best practice to change it back to 1 cz maybe the value being searched is not in page 3, 
                it is in page 1. If not set to 1, the offset will be from the beginning of page 3 to the end of 
                the limit set and that means it will say that no records are found when they do exist*/ 

    fetch(`usersSearch.php?page=${page}&limit={limit}&searchInput=${encodeURIComponent(searchInput.value)}`)
    /*Notice I've used backticks(`) so that I don't use concatenation and instead used ${...} for variables*/
    .then(response => response.text())
    .then(data => {
        tableBody.innerHTML = data;
    })
});

/*I'm pausing here cz I am really distracted right now. Start from part 2 of the correction. Ask chat whether you
can use WHERE first_name = ? OR...  Ask it whether LIKE is necessary for multiple conditions */