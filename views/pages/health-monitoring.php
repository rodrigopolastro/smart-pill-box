<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/smart-pill-box/helpers/full-path.php';
require_once fullPath('scripts/session-authentication.php');
require_once fullPath('views/components/header.php');
require_once fullPath('controllers/sensors-data.php');
?>

<div class="container my-5">
    <div class="row">
        <div class="col-5">
            <div class="d-flex justify-content-between align-items-center pb-3">
                <button id="captureBtn" class="btn btn-info">Capturar Dados Agora</button>
                <div class="form-check form-switch">
                    <input id="captureSwitch" class="form-check-input" type="checkbox" role="switch">
                    <label for="captureSwitch" class="form-check-label">Captura Automática de Dados</label>
                </div>
            </div>
            <div id="alertMessage" class="d-flex justify-content-center bg-danger rounded-4 py-3 d-none my-2">
                <span class="fw-bold text-center text-white w-100">ALERTA: Sinais Vitais atingiram níveis preocupantes!</span>
            </div>
            <div id="sensorsRecordsList">
                <!-- Loaded dinamically with javascript -->
            </div>
        </div>
        <div class="col-7">
            <div id="sensorsButtonsList" class="d-flex mb-4">
                <button type="button" data-sensor-name="heart_rate" class="ms-2 btn btn-danger">Batimentos por Minuto</button>
                <button type="button" data-sensor-name="body_temperature" class="ms-2 btn btn-warning">Temperatura Corporal</button>
                <button type="button" data-sensor-name="blood_oxygen" class="ms-2 btn btn-primary">Oxigênio no Sangue</button>
                <button type="button" data-sensor-name="blood_pressure" class="ms-2 btn btn-success">Pressão Sanguínea</button>
            </div>
            <div class="bg-white rounded-4 p-3">
                <canvas id="sensorsCanvas"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Biblioteca Chart.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>

<script src="../../helpers/formatTime.js"></script>
<script src="../js/sensorsGraph.js"></script>
<script src="../js/displaySensorsRecord.js"></script>
<script src="../js/listSensorsRecords.js"></script>
<script src="../js/captureSensorsData.js"></script>

<?php
require_once fullPath('views/components/footer.php');
?>