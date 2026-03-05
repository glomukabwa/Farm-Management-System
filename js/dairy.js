const triggerEdits = document.querySelectorAll(".triggerEdit");
const triggerDeletes = document.querySelectorAll(".triggerDelete");

if(triggerEdits && triggerDeletes){/*In case the female_cows table is empty */

    const editOverlay = document.querySelector(".editOverlay");
    const editOverlayInputs = editOverlay.querySelectorAll("input, select");
    editOverlayInputs.forEach(input => input.disabled = true);
    const closeOverlay = document.getElementById("closePopup");
    const popupDeleteBtn = document.getElementById("deleteBtn");
    const deleteRowOverlay = document.querySelector(".deleteRowOverlay");
    const cancelDeleteRow = document.getElementById("cancelDeleteRow");
    const actualEdit = document.querySelector(".actualEdit");

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

            console.log(data.healthStatus)
            editOverlayInputs[0].value = data.name ?? 'undefined';
            editOverlayInputs[1].value = data.healthStatus;
            editOverlayInputs[2].value = Number(data.milkProduction ?? 0).toFixed(2);
            editOverlayInputs[3].value = data.isPreg;
            editOverlayInputs[4].value = data.lifeStatus;
        });

        /*Actual editing */
        actualEdit.onclick = function(){
            const tagName = editOverlayInputs[0].value;
            const health = editOverlayInputs[1].value;
            const milk = editOverlayInputs[2].value;
            const preg = editOverlayInputs[3].value;
            const life = editOverlayInputs[4].value;

            console.log({rowId, tagName, health, milk, preg, life});
            fetch('editDairyTable.php', {
                method : 'POST',
                headers : {
                    'Content-Type' : 'application/x-www-form-urlencoded'
                },
                body : new URLSearchParams({
                    rowId,
                    tagName,
                    health,
                    milk,
                    preg,
                    life
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
    });

    /*Cancel delete */
    cancelDeleteRow.onclick = function(){
        deleteRowOverlay.classList.remove("show");
    }

}