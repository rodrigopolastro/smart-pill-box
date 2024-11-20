<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/smart-pill-box/helpers/full-path.php';
require_once fullPath('scripts/session-authentication.php');
require_once fullPath('helpers/calc-age.php');
require_once fullPath('controllers/people-in-care.php');
require_once fullPath('controllers/smart-pill-boxes.php');
require_once fullPath('controllers/medicines.php');
require_once fullPath('controllers/treatments.php');

$personInCare = peopleInCareController(
    'get_person_in_care',
    ['person_in_care_id' => $_GET['id']]
);
$smartPillBox = smartPillBoxesController(
    'get_smart_pill_box',
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
    require_once fullPath('views/components/add-pills-to-slot-modal.php');
    ?>
    <main class="">
        <div class="container py-4">
            <div class="d-flex justify-content-center">
                <div class="row p-3 bg-white rounded-4 w-50">
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
            <div class="w-100 d-flex justify-content-center">
                <div id="smartPillBoxSLots" class="py-3 w-75 d-flex flex-wrap">
                    <?php foreach ($smartPillBox['slots'] as $slotName => $slot): ?>
                        <div class="px-2 pb-2 w-50">
                            <div class="bg-white rounded-4 h-100 py-5">
                                <div class="text-center"><span>Compartimento <?= $slotName ?><span></span></div>
                                <?php if (is_null($slot['treatmentId'])) : ?>
                                    <div class="text-center"><span>Nenhum Medicamento</span></div>
                                    <div class="d-flex justify-content-center">
                                        <button type="button" class="btn btn-success"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalNewTreatment"
                                            data-slot-name="<?= $slotName ?>">
                                            Cadastrar tratamento
                                        </button>
                                    </div>
                                <?php else : ?>
                                    <?php
                                    $medicine = medicinesController(
                                        'get_medicine',
                                        ['medicine_id' => $slot['medicineId']]
                                    );
                                    $treatment = treatmentsController(
                                        'get_treatment',
                                        ['treatment_id' => $slot['treatmentId']]
                                    );
                                    ?>
                                    <div class="text-center">
                                        <span><?= $medicine['MED_name'] ?></span>
                                        <span> - <?= $slot['quantity'] ?> comprimidos</span>
                                    </div>
                                    <div class="text-center">
                                        <span>
                                            Período:
                                            <?= date_format(date_create($treatment['TTM_start_date']), "d/m/y") ?>
                                            a
                                            <?= date_format(date_create($treatment['last_dose_date']), "d/m/y") ?>
                                        </span>
                                    </div>
                                    <div class="text-center">
                                        <span>
                                            Doses:
                                            <?= $treatment['taken_doses'] . '/' . $treatment['total_doses'] ?>
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-center gap-2">
                                        <button type="button" class="btn btn-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalAddPillsToSlot"
                                            data-medicine-id="<?= $slot['medicineId'] ?>"
                                            data-slot-name="<?= $slotName ?>">
                                            Adicionar Comprimidos
                                        </button>
                                        <button type="button" class="btn btn-info"
                                            data-slot-name="<?= $slotName ?>">
                                            Editar Tratamento
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php
        require_once fullPath('views/components/footer.php');
        ?>
        <script src="../../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../../assets/js/jquery.js"></script>
        <script src="../../assets/js/jquery-ui.min.js"></script>
        <script src="../js/medicinesAutocomplete.js"></script>
        <script src="../js/displayDosesTimesInputs.js"></script>
        <script src="../js/addSlotNameToModal.js"></script>
        <script src="../js/displayAddPillsModalInfo.js"></script>
    </main>
</body>

</html>