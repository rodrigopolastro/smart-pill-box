<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/smart-pill-box/helpers/full-path.php';
require_once fullPath('models/smart-pill-boxes.php');

if (!isset($_SESSION)) {
    session_start();
}

$jsonRequest = json_decode(file_get_contents('php://input'), true);

if (isset($jsonRequest['smart_pill_boxes_action'])) {
    $params = $jsonRequest['params'] ?? [];
    echo json_encode(smartPillBoxesController($jsonRequest['smart_pill_boxes_action'], $params));
} else if (isset($_POST['smart_pill_boxes_action'])) {
    $params = $_POST ?? [];
    echo json_encode(smartPillBoxesController($_POST['smart_pill_boxes_action'], $params));
}

function smartPillBoxesController($peopleInCareAction, $params = [])
{
    switch ($peopleInCareAction) {
        case 'create_smart_pill_box':
            insertSmartPillBox([
                'person_in_care_id' => $params['person_in_care_id'],
                'status' => 'active'
            ]);
            break;

        default:
            return [
                'sucesso' => false,
                'msgErro' => "Ação para Pessoas Sob Cuidado inválida informada: '" . $peopleInCareAction . "'"
            ];
    }
}
