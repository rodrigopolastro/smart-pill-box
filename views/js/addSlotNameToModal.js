const modalNewTreatment = document.getElementById("modalNewTreatment");

modalNewTreatment.addEventListener("show.bs.modal", (e) => {
    let slotName = e.relatedTarget.dataset.slotName;
    document.getElementById("hiddenSlotName").value = slotName;
    document.getElementById("txtMedicineName").value = "";
});
