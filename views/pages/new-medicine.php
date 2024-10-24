<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/smart-pill-box/helpers/full-path.php';
require_once fullPath('scripts/session-authentication.php');
require_once fullPath('views/components/header.php');
require_once fullPath('controllers/medicine-attributes.php');

$medicine_types    = controllerMedicineAttributes('select_medicine_types');
$frequency_types   = controllerMedicineAttributes('select_frequency_types');
$measurement_units = controllerMedicineAttributes('select_measurement_units');
?>

<div class="container">
    <div class="my-2">
        <div class="row my-3">
            <div class="col-3"></div>
            <div class="col-6 bg-light rounded-4 p-3">
                <h3 class="text-center">Novo Medicamento</h3>
                <form action="../../controllers/medicines.php" method="POST">
                    <input type="hidden" name="action" value="insert_medicine">
                    <div class="mb-3">
                        <label for="txtMedicineName" class="form-label">Nome do medicamento</label>
                        <input required type="text" id="txtMedicineName" name="medicine_name" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="txtMedicineDescription" class="form-label">Descrição</label>
                        <input type="text" id="txtMedicineDescription" name="medicine_description" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="selMedicineType" class="form-label">Tipo de medicamento</label>
                        <select required id="selMedicineType" name="medicine_type_id" class="form-control">
                            <?php foreach ($medicine_types as $medicine_type) : ?>
                                <option value='<?= $medicine_type['medicine_type_id'] ?>'>
                                    <?= ucfirst($medicine_type['portuguese_name']) ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="selFrequencyType" class="form-label">Frequência de utilização</label>
                        <!-- <select required id="selFrequencyType" name="medicine_type" class="form-control">
                            <?php foreach ($frequency_types as $frequency_type) : ?>
                                <option value='<?= $frequency_type['frequency_type_id'] ?>'>
                                    <?= ucfirst($frequency_type['portuguese_name']) ?>
                                </option>
                            <?php endforeach ?>
                        </select> -->
                        <select required id="selFrequencyType" name="frequency_type_id" class="form-control">
                            <option value='1'>Diário</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="numTotalUsageDays" class="form-label">Duração do Tratamento (dias)</label>
                        <input required type="number" id="numTotalUsageDays" name="total_usage_days" class="form-control" value="1" min="1">
                    </div>
                    <!-- Allow custom time for doses -->
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
                        <input required type="time" name="dose_time_1" id="firstDoseTime" value="05:00" class="form-control w-100">
                        <input readonly hidden disabled type="time" name="dose_time_2" id="secondDoseTime" class="form-control w-50 bg-secondary">
                        <input readonly hidden disabled type="time" name="dose_time_3" id="thirdDoseTime" class="form-control w-50 bg-secondary">
                        <input readonly hidden disabled type="time" name="dose_time_4" id="fourthDoseTime" class="form-control w-50 bg-secondary">
                    </div>
                    <div class="mb-3">
                        <label for="selMeasurementUnit" class="form-label">Unidade de medida</label>
                        <select required id="selMeasurementUnit" name="measurement_unit_id" class="form-control">
                            <?php foreach ($measurement_units as $measurement_unit) : ?>
                                <option value='<?= $measurement_unit['measurement_unit_id'] ?>'>
                                    <?= $measurement_unit['portuguese_name'] == 'mL' ? 'mL' : ucfirst($measurement_unit['portuguese_name']) ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="numQuantityPerDose" class="form-label">Quantidade por dose</label>
                        <input required type="number" id="numQuantityPerDose" name="quantity_per_dose" class="form-control" value="1" min="1">
                    </div>
                    <div class="mb-3">
                        <label for="dtTreatmentStartDate" class="form-label">Início do Tratamento</label>
                        <input required type="date" id="dtTreatmentStartDate" name="treatment_start_date" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">ADICIONAR</button>
                </form>
            </div>
        </div>
        <div class="col-3"></div>
    </div>
</div>
<script src="../js/displayDosesTimesInputs.js"></script>
<?php
require_once '../components/footer.php';
?>