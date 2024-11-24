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
    <title>Overview</title>
</head>

<body>
    <?php
    require_once fullPath('views/components/header.php');
    ?>
    <main class="min-vh-100">
        <div class="container min-vh-100 d-flex align-items-center">
            <div class="d-flex justify-content-between align-items-center w-100">
                <div class=" bg-white rounded-4 py-5 d-flex align-items-center justify-content-center" style="width: 31%; height: 300px">
                    <a href="./people-in-care.php" class="text-center">
                        <h3 class="fs-2">Pessoas sob Cuidado</h3>
                    </a>
                </div>
                <div class=" bg-white rounded-4 py-5 d-flex align-items-center justify-content-center" style="width: 31%; height: 300px">
                    <a href="./medicines.php" class="text-center">
                        <h3 class="fs-2">Medicamentos</h3>
                    </a>
                </div>
                <div class=" bg-white rounded-4 py-5 d-flex align-items-center justify-content-center" style="width: 31%; height: 300px">
                    <a href="./doses.php" class="text-center">
                        <h3 class="fs-2">Doses</h3>
                    </a>
                </div>
            </div>
        </div>
    </main>
    <?php
    require_once fullPath('views/components/footer.php');
    ?>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>