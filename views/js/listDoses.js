window.addEventListener("load", () => {
    let params = new URLSearchParams({
        action: "select_all_doses",
    });
    listDoses(params);
});

document
    .getElementById("btnShowUpcomingDoses")
    .addEventListener("click", () => {
        document.getElementById("divDosesCounter").classList.add("d-none");

        let params = new URLSearchParams({
            action: "select_all_doses",
        });
        listDoses(params);
    });

document.getElementsByName("btnViewDoses").forEach((viewDosesBtn) => {
    viewDosesBtn.addEventListener("click", () => {
        let params = new URLSearchParams({
            action: "select_medicine_doses",
            medicine_id: viewDosesBtn.dataset.medicineId,
        });
        listDoses(params);
    });
});

let nextDosesList = document.getElementById("nextDosesList");
let takenDosesList = document.getElementById("takenDosesList");
async function listDoses(params) {
    try {
        fetch("../../controllers/doses.php", {
            method: "POST",
            mode: "same-origin",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: params,
        })
            .then((response) => response.json())
            .then((doses) => {
                console.log(doses);

                nextDosesList.innerHTML = "";
                doses.not_taken_doses.forEach((dose) =>
                    displayDoseDiv(nextDosesList, dose)
                );

                if (doses.taken_doses) {
                    document
                        .getElementById("takenDosesDiv")
                        .classList.remove("d-none");
                    takenDosesList.innerHTML = "";
                    doses.taken_doses.forEach((dose) => {
                        displayDoseDiv(takenDosesList, dose);
                    });
                } else {
                    document
                        .getElementById("takenDosesDiv")
                        .classList.add("d-none");
                }
            });
    } catch (error) {
        console.error("Error in Request:", error);
    }
}

function displayDoseDiv(parentElement, dose) {
    let doseHtml = `
        <div class="">
            <p>was taken: ${dose.was_taken}</p>
            <p>${dose.due_date} - ${dose.due_time}</p>
            <h6>${dose.medicine_name}: ${dose.quantity_per_dose} ${dose.measurement_unit}</h6>
        </div>
    `;

    parentElement.innerHTML += doseHtml;
}
