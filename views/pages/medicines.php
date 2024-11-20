<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/smart-pill-box/helpers/full-path.php';
require_once fullPath('scripts/session-authentication.php');
require_once fullPath('controllers/medicines.php');
require_once fullPath('controllers/smart-pill-boxes.php');

$medicines = medicinesController('get_all_medicines');
$medicinesPillsInBoxes = smartPillBoxesController('count_medicines_pills_in_boxes');
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/general.css" rel="stylesheet">
    <title>Medicamentos</title>
</head>

<body>
    <?php
    require_once fullPath('views/components/header.php');
    require_once fullPath('views/components/medicine-users-modal.php');
    ?>
    <main class="min-vh-100">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1>Medicamentos</h1>
                </div>
                <div>
                    <a
                        class="btn btn-primary"
                        href=" <?= fullPath('views/pages/new-medicine.php', true) ?>"
                        role=" button">
                        Adicionar
                    </a>
                </div>
            </div>
            <div>
                <table class="table table-striped">
                    <thead>
                        <th>Nome do Medicamento</th>
                        <th>Descrição</th>
                        <th>Em Estoque</th>
                        <th>Comprimidos Necessários</th>
                        <th>Comprimidos nas Caixas</th>
                        <th>Utilizado por</th>
                        <th>Preço</th>
                    </thead>
                    <tbody>
                        <?php foreach ($medicines as $medicine) : ?>
                            <tr>
                                <td><?= $medicine['MED_name'] ?></td>
                                <td><?= $medicine['MED_description'] ?></td>
                                <td>
                                    <?php $balance = $medicine['MED_quantity_pills'] - $medicine['total_required_pills'] ?>
                                    <?php if ($balance >= 0) : ?>
                                        <?= $medicine['MED_quantity_pills'] ?>
                                        <span class="text-success fw-bold">
                                            <?= '(+' . $balance . ')' ?>
                                        </span>
                                    <?php else : ?>
                                        <?= $medicine['MED_quantity_pills'] ?>
                                        <span class="text-danger fw-bold">
                                            <?= '(' . $balance . ')' ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php $balance = $medicine['total_required_pills'] - $medicinesPillsInBoxes[$medicine['MED_id']] ?>
                                    <?php if ($balance >= 0) : ?>
                                        <?= $medicine['total_required_pills'] ?>
                                        <span class="text-success fw-bold">
                                            <?= '(+' . $balance . ')' ?>
                                        </span>
                                    <?php else : ?>
                                        <?= $medicine['total_required_pills'] ?>
                                        <span class="text-danger fw-bold">
                                            <?= '(' . $balance . ')' ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= $medicinesPillsInBoxes[$medicine['MED_id']] ?>
                                </td>
                                <?php if ($medicine['quantity_users'] > 0) : ?>
                                    <td>
                                        <button
                                            type="button"
                                            class="btn btn-link"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalMedicineUsers"
                                            data-medicine-id="<?= $medicine['MED_id'] ?>"
                                            data-medicine-name="<?= $medicine['MED_name'] ?>"
                                            data-medicine-description="<?= $medicine['MED_description'] ?>">
                                            <span>
                                                <?= $medicine['quantity_users'] ?>
                                                <?= $medicine['quantity_users'] == 1 ? ' Pessoa' : ' Pessoas' ?>
                                            </span>
                                        </button>
                                    </td>
                                <?php else : ?>
                                    <td>Nenhum Usuário</td>
                                <?php endif; ?>
                                <td>R$ <?= number_format($medicine['MED_price'], 2, ',', ' ') ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <?php
    require_once fullPath('views/components/footer.php');
    ?>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../js/displayMedicineUsers.js"></script>
</body>

</html>