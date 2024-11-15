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
            // $doses_per_day = HOURS_IN_A_DAY / $_POST['doses_hours_interval'];

            // $medicine = [
            //     'user_id' => $_SESSION['user_id'],
            //     'medicine_type_id' => $_POST['medicine_type_id'],
            //     'frequency_type_id' => $_POST['frequency_type_id'],
            //     'measurement_unit_id' => $_POST['measurement_unit_id'],
            //     'medicine_name' => $_POST['medicine_name'],
            //     'medicine_description' => $_POST['medicine_description'],
            //     'doses_per_day' => $doses_per_day,
            //     'quantity_per_dose' => $_POST['quantity_per_dose'],
            //     'treatment_start_date' => $_POST['treatment_start_date'],
            //     'total_usage_days' => $_POST['total_usage_days'],
            // ];

            // foreach ($medicine as $key => $value) {
            //     if (empty($value)) {
            //         $medicine[$key] = NULL;
            //     }
            // }

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
                'errorMsg' => "Ação para Medicamentos inválida informada: '" . $medicinesAction . "'"
            ];
    }
}
