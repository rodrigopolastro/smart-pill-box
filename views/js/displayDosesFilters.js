const selectFilterDoses = document.getElementById("selectFilterDoses");
const selectPeopleInCare = document.getElementById("selectPeopleInCare");
const selectMedicines = document.getElementById("selectMedicines");
const dtDateDose = document.getElementById("dtDateDose");

selectFilterDoses.addEventListener("change", displayDosesFilter);
window.addEventListener("load", displayDosesFilter);

function displayDosesFilter() {
    selectPeopleInCare.classList.add("d-none");
    selectMedicines.classList.add("d-none");
    dtDateDose.classList.add("d-none");

    let filterBy = selectFilterDoses.value;

    if (filterBy == "person_in_care") {
        selectPeopleInCare.classList.remove("d-none");
    } else if (filterBy == "medicine") {
        selectMedicines.classList.remove("d-none");
    } else if (filterBy == "due_datetime") {
        dtDateDose.classList.remove("d-none");
    }
}
