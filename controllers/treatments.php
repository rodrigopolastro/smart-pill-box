<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/smart-pill-box/helpers/full-path.php';
require_once fullPath('models/treatments.php');

if (!isset($_SESSION)) {
    session_start();
}

$jsonRequest = json_decode(file_get_contents('php://input'), true);

if (isset($jsonRequest['treatments_action'])) {
    $params = $jsonRequest['params'] ?? [];
    echo json_encode(treatmentsController($jsonRequest['treatments_action'], $params));
} else if (isset($_POST['treatments_action'])) {
    $params = $_POST ?? [];
    echo json_encode(treatmentsController($_POST['treatments_action'], $params));
}

function treatmentsController($treatmentsAction, $params = [])
{
    define('HOURS_IN_A_DAY', 24);

    switch ($treatmentsAction) {
        case 'create_treatment':
            $doses_per_day = HOURS_IN_A_DAY / $_POST['doses_hours_interval'];

            print_r($params);


            $newTreatmentId = insertTreatment([
                'person_in_care_id' => $params['person_in_care_id'],
                'medicine_id' => $params['medicine_id'],
                'reason' =>  $params['reason'],
                'usage_frequency' => $params['usage_frequency'],
                'start_date' => $params['start_date'],
                'doses_per_day' => $doses_per_day,
                'pills_per_dose' => $params['pills_per_dose'],
                'total_usage_days' => $params['total_usage_days']
            ]);

            // $created_medicine_id = createMedicine($medicine);
            // if ($created_medicine_id) {
            //     $query_string =
            //         '?action=insert_medicine_doses' .
            //         '&medicine_id=' . $created_medicine_id .
            //         '&medicine_name=' . $medicine['medicine_name'] .
            //         '&treatment_start_date=' . $_POST['treatment_start_date'] .
            //         '&total_usage_days=' . $_POST['total_usage_days'] .
            //         '&doses_per_day=' . $doses_per_day;

            //     for ($i = 1; $i <= $doses_per_day; $i++) {
            //         $var_name = 'dose_time_' . $i;
            //         $query_string .= '&dose_time_' . $i . '=' . $_POST[$var_name];
            //     }
            //     header('Location: /smart-pill-box/controllers/doses.php' . $query_string);
            // } else {
            //     header('Location: /smart-pill-box/views/pages/list-medicines.php');
            //     exit();
            // }

            // exit();
            break;

        default:
            return [
                'sucess' => false,
                'errorMsg' => "Ação para Pessoas Sob Cuidado inválida informada: '" . $treatmentsAction . "'"
            ];
    }
}
