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
        <div class="container">
            <div>
                <a href="./people-in-care.php">Pessoas sob Cuidado</a>
            </div>
            <div>
                <a href="./medicines.php">Medicamentos</a>
            </div>
        </div>
    </main>
    <?php
    require_once fullPath('views/components/footer.php');
    ?>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>