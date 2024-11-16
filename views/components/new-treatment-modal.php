<div class="modal fade" id="modalNewTreatment"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= fullPath('controllers/treatments.php', true) ?>" method="POST">
                <input type="hidden" name="treatments_action" value="create_treatment">
                <input
                    type="hidden" id="hiddenPersonInCareId"
                    name="person_in_care_id" value="<?= $_GET['id'] ?>">
                <input
                    type="hidden" id="hiddenMedicineId"
                    name="medicine_id" value="">
                <input
                    type="hidden" id="hiddenSlotName"
                    name="slot_name" value="">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Cadastrar Tratamento</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- "ui-front" class used to make autocomplete options appear over the modal -->
                    <div class="mb-3 ui-front">
                        <label for="txtMedicineName" class="form-label">Nome do medicamento</label>
                        <input type="text" id="txtMedicineName" name="" class="form-control" required>
                        <p>Não encontrou algum medicamento?
                            <a href="<?= fullPath('views/pages/new-medicine.php', true) ?>">Cadastre um novo</a>
                        </p>
                    </div>
                    <div class="mb-3 ui-front">
                        <label for="txtReason" class="form-label">Motivo da utilização</label>
                        <input type="text" id="txtReason" name="reason" class="form-control" autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label for="seUsageFrequency" class="form-label">Frequência de utilização</label>
                        <select required id="seUsageFrequency" name="usage_frequency" class="form-control">
                            <option value='daily'>Diário</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="dtStartDate" class="form-label">Início do Tratamento</label>
                        <input required type="date" id="dtTreatmentStartDate" name="start_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="numTotalUsageDays" class="form-label">Duração do Tratamento (dias)</label>
                        <input required type="number" id="numTotalUsageDays" name="total_usage_days" class="form-control" value="1" min="1">
                    </div>
                    <!-- TODO: Allow custom time for doses -->
                    <!-- <div class="mb-3">
                        <label for="numDosesPerDay" class="form-label">Doses por dia</label>
                        <input type="number" id="numDosesPerDay" name="doses_per_day" class="form-control" value="1" min="1">
                    </div> -->
                    <div class="mb-3">
                        <label class="form-label">Intervalo entre as doses</label><br>

                        <input type="radio" name="doses_hours_interval" id="everySixHours" value="6" class="btn-check">
                        <label class="btn btn-outline-primary" for="everySixHours">6 em 6 horas</label>

                        <input type="radio" name="doses_hours_interval" id="everyEightHours" value="8" class="btn-check">
                        <label class="btn btn-outline-primary" for="everyEightHours">8 em 8 horas</label>

                        <input type="radio" name="doses_hours_interval" id="everyTwelveHours" value="12" class="btn-check">
                        <label class="btn btn-outline-primary" for="everyTwelveHours">12 em 12 horas</label>

                        <input checked type="radio" name="doses_hours_interval" id="everyTwentyFourHours" value="24" class="btn-check">
                        <label class="btn btn-outline-primary" for="everyTwentyFourHours">1 vez ao dia</label>
                    </div>
                    <label class="form-label">Horários das Doses</label>
                    <div id="dosesTimesDiv" class="">
                        <input required type="time" name="doses_times[0]" id="firstDoseTime" value="05:00" class="form-control w-100">
                        <input readonly hidden disabled type="time" name="doses_times[1]" id="secondDoseTime" class="form-control w-50 bg-secondary">
                        <input readonly hidden disabled type="time" name="doses_times[2]" id="thirdDoseTime" class="form-control w-50 bg-secondary">
                        <input readonly hidden disabled type="time" name="doses_times[3]" id="fourthDoseTime" class="form-control w-50 bg-secondary">
                    </div>
                    <div class="mb-3">
                        <label for="numPillsPerDose" class="form-label">Comprimidos por dose</label>
                        <input required type="number" id="numPillsPerDose" name="pills_per_dose" class="form-control" value="1" min="1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Cadastrar</button>
                </div>
            </form>
        </div>
    </div>
</div>