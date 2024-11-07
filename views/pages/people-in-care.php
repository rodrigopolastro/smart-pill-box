<?php
$pageTitle = 'Pessoa Sob Cuidado';

require_once $_SERVER['DOCUMENT_ROOT'] . '/smart-pill-box/helpers/full-path.php';
require_once fullPath('scripts/session-authentication.php');
require_once fullPath('views/components/header.php');
require_once fullPath('controllers/people-in-care.php');

$peopleInCare = controllerPeopleInCare('get_people_in_care');
?>

<div class="container">
    <div class="py-5 d-flex flex-wrap">
        <div class="d-flex justify-content-center align-items-center w-25">
            <a href="./new-person-in-care.php" class="btn btn-primary">Adicionar</a>
        </div>
        <?php foreach ($peopleInCare as $personInCare): ?>
            <?php
            $currentDate = new DateTimeImmutable('now', new DateTimeZone('America/Sao_Paulo'));
            $birthDate = new DateTimeImmutable($personInCare['PIC_birth_date']);
            $datesDifference = date_diff($currentDate, $birthDate);
            $personInCareAge = $datesDifference->format('%y');
            ?>
            <div class="w-25 d-flex justify-content-center align-items-center p-2">
                <div class="p-2 border border-primary">
                    <div class="d-flex justify-content-center align-items-center">
                        <img src="../../assets/images/pic-image-placeholder.webp" alt="" class="w-75">
                    </div>
                    <div class="d-flex justify-content-center align-items-center">
                        <span><?= $personInCare['PIC_first_name'] . ' ' . $personInCare['PIC_last_name'] ?></span>
                    </div>
                    <div class="d-flex justify-content-center align-items-center">
                        <span>Idade: <?= $personInCareAge ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>