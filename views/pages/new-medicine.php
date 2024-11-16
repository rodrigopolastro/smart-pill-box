<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/smart-pill-box/helpers/full-path.php';
require_once fullPath('scripts/session-authentication.php');
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/general.css" rel="stylesheet">
    <title>Nova Medicamento</title>
</head>

<body>
    <?php
    ?>
    <main>
        <div class="container">
            <div class="my-5 d-flex justify-content-center align-items-center">
                <div class="w-50 bg-white p-5 rounded-3">
                    <p class="mb-3 fs-3 fw-bold">Cadastrar Medicamento</p>
                    <form action="../../controllers/medicines.php" method="POST">
                        <input type="hidden" name="medicines_action" value="create_medicine">
                        <div class="mb-3">
                            <label for="txtName" class="form-label">Nome do Medicamento</label>
                            <input required type="text" id="txtName" name="name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="txtDescription" class="form-label">Descrição</label>
                            <input type="text" id="txtDescription" name="description" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="numPrice" class="form-label">Preço (R$)</label>
                            <input required type="number" step="0.01" min="1"
                                id="numPrice" name="price" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="numQuantityPills" class="form-label">Quantidade de Comprimidos Atual</label>
                            <input required type="number" id="numQuantityPills" name="quantity_pills" class="form-control">
                        </div>
                        <input type="submit" value="Cadastrar" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </div>7
    </main>
    <?php
    ?>
    <script src="../../assets/js/bootstrap.bundle.min.js">
    </script>
</body>

</html>