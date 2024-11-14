async function getMedicines() {
    try {
        const response = await fetch("../../controllers/medicines.php", {
            method: "POST",
            mode: "same-origin",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: JSON.stringify({
                medicines_action: "get_all_medicines",
            }),
        });
        const medicines = await response.json();
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
    } catch (erro) {
        return erro;
    }
}

async function createMedicinesAutocomplete() {
    const response = await getMedicines();
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
            value: "Nenhum Medicamento Cadastrado",
        });
        $("#hiddenMedicineId").prop({
            value: "",
        });
    }
}

window.addEventListener("load", createMedicinesAutocomplete);
