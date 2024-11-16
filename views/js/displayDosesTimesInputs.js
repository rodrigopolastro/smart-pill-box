const HOURS_IN_A_DAY = 24;
const HOUR_IN_MILISECONDS = 60 * 60 * 1000;
var selectedHoursInterval, dosesTimesInputs, dosesPerDay;
const firstDoseTime = document.getElementById("firstDoseTime");

window.addEventListener("load", displayDosesTimesInputs);
function displayDosesTimesInputs() {
    const dosesTimesDiv = document.getElementById("dosesTimesDiv");
    dosesTimesInputs = Array.from(dosesTimesDiv.querySelectorAll("input"));
    dosesTimesInputs.shift(); //First input is fixed
    const radiosDosesPerDay = document.getElementsByName("doses_per_day");

    radiosDosesPerDay.forEach((radioInput) => {
        radioInput.addEventListener("change", () => {
            dosesPerDay = parseInt(radioInput.value);

            hideAllInputs(dosesTimesInputs);
            for (let i = 0; i < dosesPerDay - 1; i++) {
                dosesTimesInputs[i].hidden = false;
                dosesTimesInputs[i].disabled = false;
            }

            if (firstDoseTime.value) {
                updateDosesTimes();
            }
        });
    });
}

function hideAllInputs(dosesTimesInputs) {
    dosesTimesInputs.forEach((input) => {
        input.value = "";
        input.hidden = true;
        input.disabled = true;
    });
}

firstDoseTime.addEventListener("change", updateDosesTimes);
function updateDosesTimes() {
    let firstDoseHours = parseInt(firstDoseTime.value.split(":")[0]);
    let firstDoseMinutes = parseInt(firstDoseTime.value.split(":")[1]);
    let dateTime = new Date(0);
    dateTime.setHours(firstDoseHours);
    dateTime.setMinutes(firstDoseMinutes);
    for (let i = 1; i <= dosesPerDay - 1; i++) {
        let hoursToAdd = (HOURS_IN_A_DAY / dosesPerDay) * i;
        let milisecondsToAdd = hoursToAdd * HOUR_IN_MILISECONDS;
        let nextDateTime = new Date(dateTime.getTime() + milisecondsToAdd);

        let hours = nextDateTime.getHours();
        let finalHours = hours < 10 ? "0" + hours : hours;
        let minutes = nextDateTime.getMinutes();
        let finalMinutes = minutes < 10 ? "0" + minutes : minutes;
        dosesTimesInputs[i - 1].value = finalHours + ":" + finalMinutes;
    }
}
