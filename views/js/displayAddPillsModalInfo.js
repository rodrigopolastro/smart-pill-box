const modalAddPillsToSlot = document.getElementById("modalAddPillsToSlot");

modalAddPillsToSlot.addEventListener("show.bs.modal", async (e) => {
    let slotName = e.relatedTarget.dataset.slotName;
    document.getElementById("spanAddPillsSlotName").textContent = slotName;
    document.getElementById("hiddenAddPillSlotName").textContent = slotName;

    let personInCareId = document.getElementById(
        "hiddenAddPillsPersonInCareId"
    ).value;
    const personInCare = await getPersonInCare(personInCareId);
    document.getElementById("txtAddPillsPersonName").value =
        personInCare.PIC_first_name + " " + personInCare.PIC_last_name;

    let medicineId = e.relatedTarget.dataset.medicineId;
    const medicine = await getMedicine(medicineId);
    document.getElementById("hiddenAddPillsMedicineId").value = medicine.MED_id;
    document.getElementById("txtAddPillsMedicineName").value =
        medicine.MED_name;
    document.getElementById("numPillsInStock").value =
        medicine.MED_quantity_pills;
    document.getElementById("numPillsAdded").max = medicine.MED_quantity_pills;
});

async function getMedicine(medicineId) {
    return fetch("../../controllers/medicines.php", {
        method: "POST",
        mode: "same-origin",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: JSON.stringify({
            medicines_action: "get_medicine",
            params: {
                medicine_id: medicineId,
            },
        }),
    })
        .then((response) => response.json())
        .then((medicine) => {
            return medicine;
        })
        .catch((erro) => {
            return erro;
        });
}
async function getPersonInCare(personInCareId) {
    return fetch("../../controllers/people-in-care.php", {
        method: "POST",
        mode: "same-origin",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: JSON.stringify({
            people_in_care_action: "get_person_in_care",
            params: {
                person_in_care_id: personInCareId,
            },
        }),
    })
        .then((response) => response.json())
        .then((personInCare) => {
            return personInCare;
        })
        .catch((erro) => {
            return erro;
        });
}
