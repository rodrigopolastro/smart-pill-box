<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/pi3-smart-pill-box/helpers/full-path.php';
require_once fullPath('scripts/session-authentication.php');
require_once fullPath('views/components/header.php');
require_once fullPath('controllers/medicines.php');

# conecta com banco de dados aqui
# sql: 'select * from medicines';
#busca no banco de dados

$medicines = controllerMedicines('select_medicines');

?>

<div class="container-fluid my-5">
    <div class="row px-5">
        <div class="col-8">
            <div class="bg-white rounded-4 p-3">
                <div class="d-flex justify-content-between mb-3">
                    <div class="">
                        <h3>
                            Lista de Medicamentos
                        </h3>
                    </div>
                    <div class="">
                        <a class="btn btn-primary" href="./new-medicine.php">
                            Novo Medicamento
                        </a>
                    </div>
                </div>
                <table class="table table-striped">
                    <thead>
                        <th>Nome</th>
                        <!-- <th>Descrição</th> -->
                        <th>Início do Tratamento</th>
                        <th>Frequência</th>
                        <th>Doses por dia</th>
                        <th>Porção da Dose</th> <!-- Quantity + Measurement Unit -->
                    </thead>
                    <tbody>
                        <?php foreach ($medicines as $medicine) : ?>
                            <tr>
                                <td><?= $medicine['medicine_name'] ?></td>
                                <!-- <td><?= $medicine['medicine_description'] ?></td> -->
                                <?php
                                $date = DateTimeImmutable::createFromFormat(
                                    'Y-m-d',
                                    $medicine['treatment_start_date'],
                                    new DateTimeZone('America/Sao_Paulo')
                                );

                                ?>
                                <td><?= $date->format("d/m/Y") ?></td>
                                <td><?= ucfirst($medicine['frequency_type']) ?></td>
                                <td><?= $medicine['doses_per_day'] ?> </td>
                                <td><?= $medicine['quantity_per_dose'] . ' ' . ucfirst($medicine['measurement_unit']) ?></td>
                                <td>
                                    <button data-medicine-id="<?= $medicine['medicine_id'] ?>" name="btnViewDoses" class="btn btn-primary">
                                        Visualizar Doses
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-4 ">
            <div class="bg-white rounded-4 p-3">
                <div class="d-flex justify-content-between mb-3">
                    <div class="">
                        <h3>Doses</h3>
                    </div>
                    <div class="">
                        <button id="btnShowUpcomingDoses" class="btn btn-primary">Visualizar Próximas Doses</button>
                    </div>
                </div>

                <div id="divDosesCounter" class="d-none">
                    <p id="dosesCounter" class="fw-bold text-primary text-center">
                        <span id="takenDosesCounter"></span>
                        /
                        <span id="totalDosesCounter"></span>
                        Doses Tomadas
                    </p>
                </div>

                <div>
                    <!-- The doses lists are generated dinamically by javascript -->
                    <div id="nextDosesDiv" class="overflow-y-scroll mb-3">
                        <h4>Próximas Doses</h4>
                        <div id="nextDosesList"></div>
                    </div>
                    <div id="takenDosesDiv" class="overflow-y-scroll">
                        <hr>
                        <h4>Doses Tomadas</h4>
                        <div id="takenDosesList"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../../helpers/formatDate.js"></script>
<script src="../js/displayDosesNumber.js"></script>
<script src="../js/listDoses.js"></script>

<?php
require_once '../components/footer.php';
?>