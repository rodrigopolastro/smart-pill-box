<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/smart-pill-box/helpers/full-path.php';
require_once fullPath('scripts/session-authentication.php');
require_once fullPath('controllers/medicines.php');

$medicines = medicinesController('get_all_medicines');
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
    require_once fullPath('views/components/medicine-users.php');
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
                        <th>Quantidade de Comprimidos</th>
                        <th>Utilizado por</th>
                        <th>Preço</th>
                    </thead>
                    <tbody>
                        <?php foreach ($medicines as $medicine) : ?>
                            <tr>
                                <td><?= $medicine['MED_name'] ?></td>
                                <td><?= $medicine['MED_description'] ?></td>
                                <td><?= $medicine['MED_quantity_pills'] ?></td>
                                <?php if ($medicine['quantity_users'] > 0) : ?>
                                    <td>
                                        <button
                                            type="button"
                                            class="btn btn-link"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalMedicineUsers">
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
</body>

</html>