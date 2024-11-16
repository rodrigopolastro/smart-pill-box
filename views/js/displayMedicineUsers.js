const modalMedicineUsers = document.getElementById("modalMedicineUsers");

modalMedicineUsers.addEventListener("show.bs.modal", async (e) => {
    let medicineId = e.relatedTarget.dataset.medicineId;
    let medicineName = e.relatedTarget.dataset.medicineName;
    let medicineDescription = e.relatedTarget.dataset.medicineDescription;

    document.getElementById("modalMedicineUsersTitle").textContent =
        "Usuários de " + medicineName + " - " + medicineDescription;

    const users = await getMedicineUsers(medicineId);
    const ulMedicineUsers = document.getElementById("ulMedicineUsers");
    ulMedicineUsers.innerHTML = "";
    users.forEach((user) => {
        let userLi = document.createElement("li");
        userLi.textContent =
            user.PIC_first_name +
            " " +
            user.PIC_last_name +
            " - Motivo: " +
            user.TTM_reason;
        ulMedicineUsers.appendChild(userLi);
    });
});

async function getMedicineUsers(medicineId) {
    return fetch("../../controllers/people-in-care.php", {
        method: "POST",
        mode: "same-origin",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: JSON.stringify({
            people_in_care_action: "get_medicine_users",
            params: {
                medicine_id: medicineId,
            },
        }),
    })
        .then((response) => response.json())
        .then((users) => {
            return users;
        })
        .catch((erro) => {
            return erro;
        });
}
