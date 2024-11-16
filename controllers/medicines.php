<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/smart-pill-box/helpers/full-path.php';
require_once fullPath('models/medicines.php');

define('HOURS_IN_A_DAY', 24);

if (!isset($_SESSION)) {
    session_start();
}

$jsonRequest = json_decode(file_get_contents('php://input'), true);

if (isset($jsonRequest['medicines_action'])) {
    $params = $jsonRequest['params'] ?? [];
    echo json_encode(medicinesController($jsonRequest['medicines_action'], $params));
} else if (isset($_POST['medicines_action'])) {
    $params = $_POST ?? [];
    echo json_encode(medicinesController($_POST['medicines_action'], $params));
}

function medicinesController($medicinesAction, $params = [])
{
    switch ($medicinesAction) {
        case 'get_all_medicines':
            $medicines = selectAllMedicines($_SESSION['logged_nursing_home_id']);
            return $medicines;
            break;

        case 'get_medicine':
            $medicine = selectMedicine($params['medicine_id']);
            return $medicine;
            break;

        case 'create_medicine':
            insertMedicine([
                'nursing_home_id' => $_SESSION['logged_nursing_home_id'],
                'name' => $params['name'],
                'description' => $params['description'],
                'price' => floatval($params['price']),
                'quantity_pills' => $params['quantity_pills']
            ]);
            header('Location: ' . fullPath('views/pages/medicines.php', true));
            exit();
            break;

        case 'get_person_unused_medicines':
            $personUnusedMedicines = selectPersonUnusedMedicines(
                $params['person_in_care_id']
            );
            return $personUnusedMedicines;
            break;

        default:
            return [
                'sucess' => false,
                'errorMsg' => "Ação para Medicamentos inválida informada: '" . $medicinesAction . "'"
            ];
    }
}
