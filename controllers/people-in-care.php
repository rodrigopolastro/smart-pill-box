<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/smart-pill-box/helpers/full-path.php';
require_once fullPath('models/people-in-care.php');
require_once fullPath('controllers/smart-pill-boxes.php');

if (!isset($_SESSION)) {
    session_start();
}

$jsonRequest = json_decode(file_get_contents('php://input'), true);

if (isset($jsonRequest['people_in_care_action'])) {
    $params = $jsonRequest['params'] ?? [];
    echo json_encode(peopleInCareController($jsonRequest['people_in_care_action'], $params));
} else if (isset($_POST['people_in_care_action'])) {
    $params = $_POST ?? [];
    echo json_encode(peopleInCareController($_POST['people_in_care_action'], $params));
}

function peopleInCareController($peopleInCareAction, $params = [])
{
    switch ($peopleInCareAction) {
        case 'get_person_in_care':
            $personInCare = selectPersonInCare($params['person_in_care_id']);
            return $personInCare;
            break;

        case 'get_all_people_in_care':
            $peopleInCare = selectAllPeopleInCare($_SESSION['logged_nursing_home_id']);
            return $peopleInCare;
            break;

        case 'create_person_in_care':
            $personInCare = [
                'nursing_home_id' => $_SESSION['logged_nursing_home_id'],
                'first_name'      => $params['first_name'],
                'last_name'       => $params['last_name'],
                'birth_date'      => $params['birth_date'],
                'height'          => $params['height'],
                'weight'          => $params['weight'],
                'allergies'       => $params['allergies'],
                'notes'           => $params['notes']
            ];
            $personInCareId = insertPersonInCare($personInCare);
            smartPillBoxesController('create_smart_pill_box', [
                'person_in_care_id' => $personInCareId
            ]);

            header('Location: /smart-pill-box/views/pages/people-in-care.php');
            break;

        default:
            return [
                'sucesso' => false,
                'msgErro' => "Ação para Pessoas Sob Cuidado inválida informada: '" . $peopleInCareAction . "'"
            ];
    }
}
