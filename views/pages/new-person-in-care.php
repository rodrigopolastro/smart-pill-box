<?php
$pageTitle = 'Cadastrar Pessoa Sob Cuidado';

require_once $_SERVER['DOCUMENT_ROOT'] . '/smart-pill-box/helpers/full-path.php';
require_once fullPath('scripts/session-authentication.php');
require_once fullPath('views/components/header.php');
?>

<div class="container">
    <div class="my-5 d-flex justify-content-center align-items-center">
        <div class="w-50 bg-white p-5 rounded-3">
            <h1 class="mb-3">Cadastrar Pessoa Sob Cuidado</h1>
            <form action="../../controllers/people-in-care.php" method="POST">
                <input type="hidden" name="people_in_care_action" value="create_person_in_care">
                <div class="mb-3">
                    <label for="txtFirstName" class="form-label">Primeiro Nome*</label>
                    <input type="text" id="txtFirstName" name="first_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="txtLastName" class="form-label">Sobrenome*</label>
                    <input type="text" id="txtFirstName" name="last_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="dtBirthDate" class="form-label">Data de Nascimento</label>
                    <input type="date" id="dtBirthDate" name="birth_date" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="numHeight" class="form-label">Altura (cm)</label>
                    <input type="number" id="numHeight" name="height" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="numWeight" class="form-label">Peso (kg)</label>
                    <input type="number" id="numWeight" name="weight" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="txtAllergies" class="form-label">Alergias</label>
                    <input type="text" id="txtAllergies" name="allergies" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="taNotes" class="form-label">Notas</label>
                    <textarea id="taNotes" name="notes" class="form-control"></textarea>
                </div>
                <input type="submit" value="Cadastrar" class="btn btn-primary">
            </form>
            <?php if (isset($_GET['sign_up_status']) && $_GET['sign_up_status'] == 'already_registered') : ?>
                <p id="errorMessage" class="mt-3 fw-bold">Casa de Repouso já cadastrada!</p>
            <?php endif; ?>
            <div class="d-flex justify-content-end">
                <p>Já possui uma conta? <a href="./login.php" class="text-primary">Entre</a></p>
            </div>
        </div>
    </div>
</div>