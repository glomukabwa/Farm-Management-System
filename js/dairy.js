const triggerEdit = document.getElementById("triggerEdit");
const triggerDelete = document.getElementById("triggerDelete");

if(triggerEdit && triggerDelete){/*In case the female_cows table is empty */

    const editOverlay = document.querySelector(".editOverlay");
    const editOverlayInputs = editOverlay.querySelectorAll("input");
    editOverlayInputs.forEach(input => input.disabled = true);
    const closeOverlay = document.getElementById("closePopup");
    const popupDeleteBtn = document.getElementById("deleteBtn");
    const deleteRowOverlay = document.querySelector(".deleteRowOverlay");
    const cancelDeleteRow = document.getElementById("cancelDeleteRow");
    const actualEdit = document.querySelector(".actualEdit");

    /*Showing edit popup */
    triggerEdit.onclick = function() {
        editOverlay.classList.add("show");
        editOverlayInputs.forEach(input => input.disabled = false);

        const rowId = triggerEdit.value;
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

            let hlNum = data.healthStatus;
            let hlStatus = '';
            if(hlNum == 1){
                hlStatus = 'Healthy';
            }else if(hlNum == 2){
                hlStatus = 'Sick';
            }else if(hlNum == 3){
                hlStatus = 'Quarantined';
            }

            let pregNum =  data.isPreg;
            let isPreg = '';
            if(pregNum == 0){
                isPreg = 'Not pregnant';
            }else{
                isPreg = 'Pregnant';
            }

            let lfNum = data.lifeStatus;
            let lfStatus = '';
            if(lfNum == 1){
                lfStatus = 'Alive in the farm';
            }else if(lfNum == 2){
                lfStatus = 'Sold';
            }else if(lfNum == 3){
                lfStatus = 'Dead';
            }

            editOverlayInputs[0].value = data.name ?? 'undefined';
            editOverlayInputs[1].value = hlStatus;
            editOverlayInputs[2].value = (data.milkProduction ?? 0).toFixed(2);
            editOverlayInputs[3].value = isPreg;
            editOverlayInputs[4].value = lfStatus;
        });


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

    /*Actual editing */
    actualEdit.onclick = function(){
        
    }

}