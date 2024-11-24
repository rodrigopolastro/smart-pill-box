<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/smart-pill-box/helpers/full-path.php';
require_once fullPath('models/doses.php');

if (!isset($_SESSION)) {
    session_start();
}

$jsonRequest = json_decode(file_get_contents('php://input'), true);

if (isset($jsonRequest['doses_action'])) {
    $params = $jsonRequest['params'] ?? [];
    echo json_encode(dosesController($jsonRequest['doses_action'], $params));
} else if (isset($_POST['doses_action'])) {
    $params = $_POST ?? [];
    echo json_encode(dosesController($_POST['doses_action'], $params));
}

function dosesController($dosesAction, $params = [])
{
    switch ($dosesAction) {
        case 'get_filtered_doses':
            $doses = selectFilteredDoses([
                'nursing_home_id' => $_SESSION['logged_nursing_home_id'],
                'filters' => $params['filters'] ?? NULL,
                'order_by' => $params['order_by'] ?? NULL
            ]);
            return $doses;
            break;

        case 'create_dose':
            insertDose([
                'treatment_id' => $params['treatment_id'],
                'due_datetime' => $params['due_datetime']
            ]);
            break;

        case 'take_dose':
            takeDose($params['dose_id']);
            break;

        case 'get_person_next_dose':
            $personNextDose = getNextPersonDose($params['person_in_care_id']);
            return $personNextDose;
            break;

        default:
            return [
                'sucess' => false,
                'errorMsg' => "Ação para Doses inválida informada: '" . $dosesAction . "'"
            ];
    }
}
