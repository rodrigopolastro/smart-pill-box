<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/smart-pill-box/helpers/full-path.php';
require_once fullPath('scripts/session-authentication.php');
require_once fullPath('controllers/doses.php');
require_once fullPath('controllers/medicines.php');
require_once fullPath('controllers/people-in-care.php');

$filters = [];
$selectedFilter = null;
$filteredValue = null;
if (isset($_POST['selected_filter'])) {
    $selectedFilter = $_POST['selected_filter'];
    if ($_POST['selected_filter'] == 'person_in_care') {
        $filters[] = ['PIC_id', $_POST['person_in_care_id']];
        $filteredValue = intval($_POST['person_in_care_id']);
    } elseif ($_POST['selected_filter'] == 'medicine') {
        $filters[] = ['MED_id', $_POST['medicine_id']];
        $filteredValue = $_POST['medicine_id'];
    } elseif ($_POST['selected_filter'] == 'due_datetime') {
        $filters[] = ['DOS_due_datetime', $_POST['due_datetime']];
        $filteredValue = $_POST['due_datetime'];
    }
}

$not_taken_doses = dosesController('get_filtered_doses', [
    'filters' => array_merge($filters, [['DOS_was_taken', 0]]),
    'order_by' => ['DOS_due_datetime', 'ASC']
]);
$taken_doses = dosesController('get_filtered_doses', [
    'filters' => array_merge($filters, [['DOS_was_taken', 1]]),
    'order_by' => ['DOS_taken_datetime', 'DESC']
]);
$medicines = medicinesController('get_all_medicines');
$peopleInCare = peopleInCareController('get_all_people_in_care');
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/general.css" rel="stylesheet">
    <title>Controle das Doses</title>
</head>

<body>
    <?php
    require_once fullPath('views/components/header.php');
    ?>
    <main class="min-vh-100 pt-3 pb-5">
        <div class="container">
            <div class="mb-3 d-flex justify-content-between align-items-center py-2">
                <div>
                    <h1>Controle das Doses</h1>
                </div>
                <form action="./doses.php" method="POST">
                    <label for="selectFilterDoses">Filtrar por</label>
                    <div class="d-flex gap-1">
                        <select id="selectFilterDoses" name="selected_filter" class="form-control w-auto">
                            <option value="no_filter">
                                Sem Filtros
                            </option>
                            <option value="person_in_care" <?= $selectedFilter == 'person_in_care' ? 'selected' : '' ?>>
                                Pessoa sob Cuidado
                            </option>
                            <option value="medicine" <?= $selectedFilter == 'medicine' ? 'selected' : '' ?>>
                                Medicamento
                            </option>
                            <option value="due_datetime" <?= $selectedFilter == 'due_datetime' ? 'selected' : '' ?>>
                                Data
                            </option>
                        </select>
                        <select id="selectPeopleInCare" name="person_in_care_id" class="d-none form-control w-auto">
                            <?php foreach ($peopleInCare as $personInCare) : ?>
                                <option value="<?= $personInCare['PIC_id'] ?>"
                                    <?= ($selectedFilter == 'person_in_care' && $personInCare['PIC_id'] == $filteredValue) ? 'selected' : '' ?>>
                                    <?= $personInCare['PIC_first_name'] ?>
                                    <?= $personInCare['PIC_last_name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <select id="selectMedicines" name="medicine_id" class="d-none form-control w-auto">
                            <?php foreach ($medicines as $medicine) : ?>
                                <option value="<?= $medicine['MED_id'] ?>"
                                    <?= ($selectedFilter == 'medicine' && $medicine['MED_id'] == $filteredValue) ? 'selected' : '' ?>>
                                    <?= $medicine['MED_name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input
                            type="date" id="dtDateDose" name="due_datetime"
                            value="<?= $selectedFilter == 'due_datetime' ?: $filteredValue ?>"
                            class="d-none form-control w-auto">
                        <input type="submit" id="btnFiltrar" value="Filtrar" class="btn btn-primary">
                    </div>
                </form>
            </div>
            <div class="mb-4">
                <div>
                    <h3>Próximas Doses</h3>
                </div>
                <?php if ($not_taken_doses > 0) : ?>
                    <table id="tableNotTakenDoses" class="table table-striped">
                        <thead>
                            <th>Status</th>
                            <th>Pessoa Sob Cuidado</th>
                            <th>Medicamento</th>
                            <th>Data Marcada</th>
                            <th>Número da Dose</th>
                        </thead>
                        <tbody>
                            <?php foreach ($not_taken_doses as $not_taken_dose) : ?>
                                <?php
                                $current_time = date_create();
                                $dose_due_datetime = date_create($not_taken_dose['DOS_due_datetime']);
                                ?>
                                <tr>
                                    <td>
                                        <?php if ($dose_due_datetime < $current_time): ?>
                                            <span class="text-danger fw-bold">Atrasada</span>
                                        <?php elseif (date_format($dose_due_datetime, 'd/m/y') == date_format($current_time, 'd/m/y')) : ?>
                                            <span class="text-warning fw-bold">Dose para hoje</span>
                                        <?php else : ?>
                                            <span class="text-primary fw-bold">Dose Futura</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="./person-in-care-profile.php?id=<?= $not_taken_dose['PIC_id'] ?>">
                                            <span><?= $not_taken_dose['PIC_first_name'] ?> <?= $not_taken_dose['PIC_last_name'] ?></span>
                                        </a>
                                    </td>
                                    <td>
                                        <span><?= $not_taken_dose['MED_name'] ?></span>
                                    </td>
                                    <td>
                                        <?php if (date_format($dose_due_datetime, 'd/m/y') == date_format($current_time, 'd/m/y')) : ?>
                                            <span class="text-warning fw-bold">
                                                Hoje - <?= date_format(date_create($not_taken_dose['DOS_due_datetime']), "H:i") ?>
                                            </span>
                                        <?php else : ?>
                                            <span><?= date_format(date_create($not_taken_dose['DOS_due_datetime']), "d/m/y - H:i") ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= $not_taken_dose['treatment_taken_doses'] . '/' . $not_taken_dose['treatment_total_doses'] ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                <?php else : ?>
                    <div id="divNoDosesToTake" class="d-flex justify-content-center align-items-cente bg-white rounded-4 py-5">
                        <span class="fs-4">Nenhuma próxima dose encontrada</span>
                    </div>
                <?php endif; ?>
            </div>
            <div>
                <div>
                    <h3>Doses Tomadas</h3>
                </div>
                <?php if (count($taken_doses) > 0) : ?>
                    <table class="table table-striped">
                        <thead>
                            <th>Status</th>
                            <th>Pessoa Sob Cuidado</th>
                            <th>Medicamento</th>
                            <th>Horário Marcado</th>
                            <th>Horário da Utilização</th>
                            <th>Número da Dose</th>
                        </thead>
                        <tbody>
                            <?php foreach ($taken_doses as $taken_dose) : ?>
                                <?php
                                $current_time = date_create();
                                $dose_due_datetime = date_create($taken_dose['DOS_due_datetime']);
                                ?>
                                <tr>
                                    <td>
                                        <span class="text-success fw-bold">Dose tomada</span>
                                    </td>
                                    <td>
                                        <span><?= $taken_dose['PIC_first_name'] ?> <?= $taken_dose['PIC_last_name'] ?></span>
                                    </td>
                                    <td>
                                        <span><?= $taken_dose['MED_name'] ?></span>
                                    </td>
                                    <td>
                                        <?php if (date_format($dose_due_datetime, 'd/m/y') == date_format($current_time, 'd/m/y')) : ?>
                                            <span class="fw-bold">
                                                Hoje - <?= date_format(date_create($taken_dose['DOS_due_datetime']), "H:i") ?>
                                            </span>
                                        <?php else : ?>
                                            <span><?= date_format(date_create($taken_dose['DOS_due_datetime']), "d/m/y - H:i") ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (date_format($dose_due_datetime, 'd/m/y') == date_format($current_time, 'd/m/y')) : ?>
                                            <span class="fw-bold">
                                                Hoje - <?= date_format(date_create($taken_dose['DOS_due_datetime']), "H:i") ?>
                                            </span>
                                        <?php else : ?>
                                            <span><?= date_format(date_create($taken_dose['DOS_due_datetime']), "d/m/y - H:i") ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= $taken_dose['treatment_taken_doses'] . '/' . $taken_dose['treatment_total_doses'] ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                <?php else : ?>
                    <div id="divNoTakenDoses" class="d-flex justify-content-center align-items-cente bg-white rounded-4 py-5">
                        <span class="fs-4">Nenhuma dose tomada encontrada</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
    <?php
    require_once fullPath('views/components/footer.php');
    ?>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../js/displayDosesFilters.js"></script>
</body>

</html>