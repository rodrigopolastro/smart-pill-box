<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/smart-pill-box/helpers/full-path.php';
require_once fullPath('scripts/session-authentication.php');
require_once fullPath('helpers/calc-age.php');
require_once fullPath('controllers/people-in-care.php');

$personInCare = peopleInCareController(
    'get_person_in_care',
    ['person_in_care_id' => $_GET['id']]
);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/general.css" rel="stylesheet">
    <link href="../../assets/css/jquery-ui.min.css" rel="stylesheet">
    <title><?= 'PIC - ' . $personInCare['PIC_first_name'] ?></title>
</head>

<body>
    <?php
    require_once fullPath('views/components/header.php');
    require_once fullPath('views/components/new-treatment-modal.php');
    ?>
    <main class="">
        <div class="container py-4">
            <div class="d-flex justify-content-center">
                <div class="row p-3 bg-white rounded-4 w-75">
                    <div class="col-3">
                        <div class="d-flex justify-content-center h-100 align-items-center">
                            <img src="../../assets/images/pic-image-placeholder.webp" alt="" class="w-100">
                        </div>
                    </div>
                    <div class="col-9">
                        <h3>
                            <?= $personInCare['PIC_first_name'] . ' ' . $personInCare['PIC_last_name'] ?>
                        </h3>
                        <p>
                            <strong>Data de Nascimento:</strong>
                            <?= (new DateTimeImmutable($personInCare['PIC_birth_date']))->format('d/m/Y') ?>
                            <?= '(' . calcAge($personInCare['PIC_birth_date']) . ' anos)' ?>
                        </p>
                        <p>
                            <strong>Altura:</strong>
                            <?= $personInCare['PIC_height'] ? $personInCare['PIC_height'] . ' cm' : 'Não informada' ?>
                        </p>
                        <p>
                            <strong>Peso:</strong>
                            <?= $personInCare['PIC_weight'] ? $personInCare['PIC_weight'] . ' kg' : 'Não informado' ?>
                        </p>
                        <p>
                            <strong>Alergias</strong>
                            <?= $personInCare['PIC_allergies'] ?? 'Não possui' ?>
                        </p>
                        <p>
                            <strong>Notas:</strong>
                            <?= $personInCare['PIC_notes'] ?? '...' ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="row py-3">
                <div class="col-8">
                    <div class="row">
                        <div class="px-2 pb-2 col-4">
                            <div class="bg-white rounded-4 h-100 py-5">
                                <div class="text-center"><span>Compartimento "A"<span></span></div>
                                <div class="text-center"><span>Vazio</span></div>
                                <div class="d-flex justify-content-center">
                                    <button type="button" class="btn btn-success"
                                        data-bs-toggle="modal" data-bs-target="#modalNewMedicine">
                                        Cadastrar medicamento
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="px-2 pb-2 col-4">
                            <div class="bg-white rounded-4 h-100 py-5">
                                <div class="text-center"><span>Compartimento "B"</span></div>
                                <div class="text-center"><span>Nome do Medicamento</span></div>
                            </div>
                        </div>
                        <div class="px-2 pb-2 col-4">
                            <div class="bg-white rounded-4 h-100 py-5">
                                <div class="text-center"><span>Compartimento "C"</span></div>
                                <div class="text-center"><span>Nome do Medicamento</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="px-2 pb-2 col-4">
                            <div class="bg-white rounded-4 h-100 py-5">
                                <div class="text-center"><span>Compartimento "D"</span></div>
                                <div class="text-center"><span>Nome do Medicamento</span></div>
                            </div>
                        </div>
                        <div class="px-2 pb-2 col-4">
                            <div class="bg-white rounded-4 h-100 py-5">
                                <div class="text-center"><span>Compartimento "E"</span></div>
                                <div class="text-center"><span>Nome do Medicamento</span></div>
                            </div>
                        </div>
                        <div class="px-2 pb-2 col-4">
                            <div class="bg-white rounded-4 h-100 py-5">
                                <div class="text-center"><span>Compartimento "F"</span></div>
                                <div class="text-center"><span>Nome do Medicamento</span></div>
                            </div>
                        </div>
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

        <?php
        require_once fullPath('views/components/footer.php');
        ?>
        <script src="../../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../../assets/js/jquery.js"></script>
        <script src="../../assets/js/jquery-ui.min.js"></script>
        <script src="../../assets/js/medicinesAutocomplete.js"></script>
    </main>
</body>

</html>