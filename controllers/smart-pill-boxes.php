<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/smart-pill-box/helpers/full-path.php';
require_once fullPath('models/smart-pill-boxes.php');
require_once fullPath('scripts/add-treatment-to-esp-slot.php');

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

function smartPillBoxesController($smartPillBoxesAction, $params = [])
{
    switch ($smartPillBoxesAction) {
        case 'get_smart_pill_box':
            $smartPillBox = selectSmartPillBox($params['person_in_care_id']);
            return $smartPillBox;
            break;

        case 'create_smart_pill_box':
            insertSmartPillBox([
                'nursing_home_id' => $_SESSION['logged_nursing_home_id'],
                'person_in_care_id' => $params['person_in_care_id'],
                'status' => 'active'
            ]);
            break;

        case 'modify_slot_treatment':
            updateSlotTreatment([
                'person_in_care_id' => $params['person_in_care_id'],
                'treatment_id' => $params['treatment_id'],
                'slot_name' => $params['slot_name'],
                'medicine_id' => $params['medicine_id'],
                'quantity' => $params['quantity']
            ]);

            addTreatmentToEspSlot([
                'treatment_id' => intval($params['treatment_id']),
                'slot_name' => $params['slot_name']
            ]);
            break;

        case 'count_medicines_pills_in_boxes':
            $medicinesPillsInBoxes = countMedicinesPillsInBoxes([
                'nursing_home_id' => strval($_SESSION['logged_nursing_home_id'])
            ]);
            return $medicinesPillsInBoxes;
            break;

        case 'add_pills_to_slot':
            addPillsToSlot([
                'person_in_care_id' => $params['person_in_care_id'],
                'slot_name' => $params['slot_name'],
                'pills_added_to_slot' => intval($params['pills_added_to_slot'])
            ]);
            break;

        default:
            return [
                'sucess' => false,
                'errorMsg' => "Ação para Caixas Inteligentes inválida informada: '" . $smartPillBoxesAction . "'"
            ];
    }
}
