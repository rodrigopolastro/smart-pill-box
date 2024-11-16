async function getPersonUnusedMedicines(personInCareId) {
    return fetch("../../controllers/medicines.php", {
        method: "POST",
        mode: "same-origin",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: JSON.stringify({
            medicines_action: "get_person_unused_medicines",
            params: {
                person_in_care_id: personInCareId,
            },
        }),
    })
        .then((response) => response.json())
        .then((medicines) => {
            console.log(medicines);
            if (medicines.length > 0) {
                return {
                    hasRecords: true,
                    medicines: medicines,
                };
            } else {
                return {
                    hasRecords: false,
                };
            }
        })
        .catch((erro) => {
            return erro;
        });
}

async function createMedicinesAutocomplete() {
    let personInCareId = document.getElementById("hiddenPersonInCareId").value;
    const response = await getPersonUnusedMedicines(personInCareId);
    if (response.hasRecords) {
        const medicinesForAutocomplete = response.medicines.map((medicine) => {
            return {
                label: medicine.MED_name + " - " + medicine.MED_description,
                value: medicine.MED_name,
                id: medicine.MED_id,
                data: medicine,
            };
        });

        $("#txtMedicineName").autocomplete({
            source: medicinesForAutocomplete,
            select: (e, ui) => {
                $("#txtMedicineName").val(ui.item.label);
                $("#hiddenMedicineId").val(ui.item.id);
            },
            minLength: 0,
        });

        $("#txtMedicineName").focus(function () {
            $(this).autocomplete("search");
        });

        $("#txtMedicineName").prop({
            disabled: false,
            value: "",
        });

        $("#hiddenMedicineId").prop({
            value: "",
        });
    } else {
        $("#txtMedicineName").prop({
            disabled: true,
            value: "Nenhum Medicamento Disponível",
        });
        $("#hiddenMedicineId").prop({
            value: "",
        });
    }
}

window.addEventListener("load", createMedicinesAutocomplete);
