/*SEARCH FUNCTIONALITY*/
const searchForm = document.getElementById("searchForm");
const searchCriteria = document.getElementById("searchCriteria");
const searchInput = document.getElementById("searchValue");
const searchBtn = document.getElementById("searchSthBtn");
const tableBody = document.getElementById("table-body");

function updateBtnState(){
    if(searchCriteria.value === "" || searchInput.value.trim() === ""){/*OR cz if you use AND then if one of them
        is filled and the other isn't, the button will still work cz AND is strictly both */
        searchBtn.disabled = true;
    }else{
        searchBtn.disabled = false;
    }
}

searchCriteria.addEventListener("change", updateBtnState);/*Cz getting the values above only happens when the
page first reloads so you want to track any kind of changes to the criteria or the input */
searchInput.addEventListener("input", updateBtnState);

searchForm.addEventListener("submit", function(e){
    e.preventDefault();

    const criteriaOption = searchCriteria.value;
    const searchValue = searchInput.value;

    fetch('dairyTableSearch.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            criteriaOption: criteriaOption,
            searchValue: searchValue,
            limit: document.getElementById("limit").value,
            page: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        tableBody.innerHTML = data.rows;/*Cz data is an array so u need to target the specific elements u want*/

        document.querySelector(".arrows span").textContent =
        `Page 1 of ${data.totalPages}`;
    });
});

updateBtnState();/* This sets the button to the correct state on page load cz when the form is submtted,
                    it'll reload and the button will be enabled again. The disabling can only happend if there
                    is change in input or criteria but we want to set the initial state of the btn as disabled
                    even if the user hasn't interacted with the inputs*/



/*MORE OPTIONS*/
const moreOptions = document.getElementById("moreOptions");
const optionsMenuBar = document.querySelector(".optionsMenuBar");

moreOptions.onclick = function(e){
    optionsMenuBar.classList.add("showoptionsMenuBar");
};

const menuBarItems = document.querySelectorAll(".optionsMenuBar li");
const addNewCow = menuBarItems[0];
const selectAll = menuBarItems[1];
const dltSelectedRow = menuBarItems[2];
const addAnimalOverlay = document.querySelector(".addAnimalOverlay");
const closeAddAnimal = document.getElementById("closeAddAnimal");

addNewCow.onclick = function(){
    addAnimalOverlay.classList.add("show");
}

closeAddAnimal.onclick = function(){
    addAnimalOverlay.classList.remove("show");
}

document.addEventListener("click", function(e){
    const menuClicked = optionsMenuBar.contains(e.target);
    /*e.target contains the location of the click so you are basically saying: check if the menu bar
    is the location indicated by e.target. If it is, menuClicked will be true*/

    const optBtnClicked = moreOptions.contains(e.target);

    if(!menuClicked && !optBtnClicked){
        optionsMenuBar.classList.remove("showoptionsMenuBar");
    }
});

/*Actually adding a new cow*/
const newAnimalInputs = addAnimalOverlay.querySelectorAll("input, select");
const enterNewAnimal = document.getElementById("enterNewAnimal");
let successState = false;

enterNewAnimal.onclick = function(e){
    e.preventDefault();/*Prevents the btn from submitting so that the JS can handle the submission.
    type='submit' would do the default submission before and we haven't specified where the data is 
    to be sent in php plus we have condistions we are setting here in JS*/
    const aniQuantity = newAnimalInputs[0].value;
    const aniBreed = newAnimalInputs[1].value;
    const aniHealth = newAnimalInputs[2].value;
    const aniDate = newAnimalInputs[3].value;

    fetch('enterNewFemCow.php', {
        method: 'POST',
        headers: {
            'Content-Type' : 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            aniQuantity,
            aniBreed,
            aniHealth,
            aniDate
        })
    })
    .then(response => response.json())
    .then(data => {
        successState = data;

        if(successState.textContent){
            const successMessage = document.getElementById("successMessage");
            successMessage = "Animal added successfully!";

            if(successMessage){
                if(successMessage.textContent){/*Returns true if textContent is set*/
                    setTimeout(() => {
                    successMessage.style.opacity = '0';
                    }, 2000);
                }
            }
        }
    });

}


/*TABLE SECTION*/
const triggerEdits = document.querySelectorAll(".triggerEdit");
const triggerDeletes = document.querySelectorAll(".triggerDelete");

if(triggerEdits && triggerDeletes){/*In case the female_cows table is empty */

    const editOverlay = document.querySelector(".editOverlay");
    const editOverlayInputs = editOverlay.querySelectorAll("input, select");
    editOverlayInputs.forEach(input => input.disabled = true);
    const closeOverlay = document.getElementById("closeEditPopup");
    const popupDeleteBtn = document.getElementById("deleteBtn");
    const deleteRowOverlay = document.querySelector(".deleteRowOverlay");
    const cancelDeleteRow = document.getElementById("cancelDeleteRow");
    const actualEdit = document.querySelector(".actualEdit");
    const actualDelete = document.getElementById("actualDelete");
    const dustbin = document.getElementById("dustbin");

    /*Showing edit popup */
    triggerEdits.forEach(button => button.onclick = function() {
        const rowId = button.value;
        editOverlay.classList.add("show");
        editOverlayInputs.forEach(input => input.disabled = false);

        fetch('getRowData.php', {
            method: 'POST',
            headers: {
                'Content-Type' : 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({/*URLSearchParams does not expect a single value. It expects an object
                                        or key-value pairs */
                RowId: rowId
            })
        })
        .then(response => response.json())
        .then(data => {

            console.log(data.breed);
            console.log(data.isPreg);
            editOverlayInputs[0].value = data.name ?? 'Undefined';
            editOverlayInputs[1].value = data.breed ?? '';
            editOverlayInputs[2].value = data.healthStatus;
            editOverlayInputs[3].value = Number(data.milkProduction ?? 0).toFixed(2);
            editOverlayInputs[4].value = data.isPreg;
            editOverlayInputs[5].value = data.lifeStatus;
        });

        /*Actual editing */
        actualEdit.onclick = function(){
            const tagName = editOverlayInputs[0].value;
            const breedId = editOverlayInputs[1].value;
            const health = editOverlayInputs[2].value;
            const milk = editOverlayInputs[3].value;
            const preg = editOverlayInputs[4].value;
            const life = editOverlayInputs[5].value;

            console.log({rowId, tagName, breedId, health, milk, preg, life});
            fetch('editDairyTable.php', {
                method : 'POST',
                headers : {
                    'Content-Type' : 'application/x-www-form-urlencoded'
                },
                body : new URLSearchParams({
                    rowId,
                    tagName,
                    breedId,
                    health,
                    milk,
                    preg,
                    life
                })
            })
        }

        /*Actual Deletion*/
        dustbin.onclick = function(){
            fetch('deleteRowDairy.php', {
                method: 'POST',
                headers: {
                    'Content-Type' : 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    rowId : rowId
                })
            })
        }



    });

    /*Removing the editPopup */
    closeOverlay.onclick = function(){
        editOverlay.classList.remove("show");
        editOverlayInputs.forEach(input => input.disabled = true);
    }

    /*Showing delete popup from edit popup */
    popupDeleteBtn.onclick = function(){
        deleteRowOverlay.classList.add("show");
    }

    /*Showing delete popup from table */
    triggerDeletes.forEach(button => button.onclick = function(){
        deleteRowOverlay.classList.add("show");
        const rowId = button.value;

        /*Actual Deletion*/
        actualDelete.onclick = function(){
            fetch('deleteRowDairy.php', {
                method: 'POST',
                headers: {
                    'Content-Type' : 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    rowId : rowId
                })
            })
        }
    });

    /*Cancel delete */
    cancelDeleteRow.onclick = function(){
        deleteRowOverlay.classList.remove("show");
    }

}