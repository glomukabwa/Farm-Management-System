const triggerEdit = document.getElementById("triggerEdit");
const triggerDelete = document.getElementById("triggerDelete");

if(triggerEdit && triggerDelete){/*In case the female_cows table is empty */

    const editOverlay = document.querySelector(".editOverlay");
    editOverlayInputs = editOverlay.querySelectorAll("input");
    editOverlayInputs.forEach(input => input.disabled = true);
    const closeOverlay = document.getElementById("closePopup");
    const popupDeleteBtn = document.getElementById("deleteBtn");
    const deleteRowOverlay = document.querySelector(".deleteRowOverlay");
    const cancelDeleteRow = document.getElementById("cancelDeleteRow");

    /*Showing edit popup */
    triggerEdit.onclick = function() {
        editOverlay.classList.add("show");
        editOverlayInputs.forEach(input => input.disabled = false);
    }

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
    triggerDelete.onclick = function(){
        deleteRowOverlay.classList.add("show");
    }

    /*Cancel delete */
    cancelDeleteRow.onclick = function(){
        deleteRowOverlay.classList.remove("show");
    }

}