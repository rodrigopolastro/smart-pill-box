<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/pi3-smart-pill-box/helpers/full-path.php';
require_once fullPath('models/medicines.php');

define('HOURS_IN_A_DAY', 24);

if (!isset($_SESSION)) {
    session_start();
}
if (isset($_POST['action'])) {
    controllerMedicines($_POST['action']);
}

function controllerMedicines($action)
{
    switch ($action) {
        case 'select_medicines':
            $medicines = getAllMedicines($_SESSION['user_id']);
            return $medicines;
            break;

        case 'insert_medicine':
            $doses_per_day = HOURS_IN_A_DAY / $_POST['doses_hours_interval'];

            $medicine = [
                'user_id' => $_SESSION['user_id'],
                'medicine_type_id' => $_POST['medicine_type_id'],
                'frequency_type_id' => $_POST['frequency_type_id'],
                'measurement_unit_id' => $_POST['measurement_unit_id'],
                'medicine_name' => $_POST['medicine_name'],
                'medicine_description' => $_POST['medicine_description'],
                'doses_per_day' => $doses_per_day,
                'quantity_per_dose' => $_POST['quantity_per_dose'],
                'treatment_start_date' => $_POST['treatment_start_date'],
                'total_usage_days' => $_POST['total_usage_days'],
            ];

            foreach ($medicine as $key => $value) {
                if (empty($value)) {
                    $medicine[$key] = NULL;
                }
            }

            $created_medicine_id = createMedicine($medicine);
            if ($created_medicine_id) {
                $query_string =
                    '?action=insert_medicine_doses' .
                    '&medicine_id=' . $created_medicine_id .
                    '&medicine_name=' . $medicine['medicine_name'] .
                    '&treatment_start_date=' . $_POST['treatment_start_date'] .
                    '&total_usage_days=' . $_POST['total_usage_days'] .
                    '&doses_per_day=' . $doses_per_day;

                for ($i = 1; $i <= $doses_per_day; $i++) {
                    $var_name = 'dose_time_' . $i;
                    $query_string .= '&dose_time_' . $i . '=' . $_POST[$var_name];
                }
                header('Location: /pi3-smart-pill-box/controllers/doses.php' . $query_string);
            } else {
                header('Location: /pi3-smart-pill-box/views/pages/list-medicines.php');
                exit();
            }

            exit();
            break;

        default:
            $error_message = 'Invalid action informed: action = ' . $action;
            return $error_message;
    }
}
