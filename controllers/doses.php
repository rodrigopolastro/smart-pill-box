<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/pi3-smart-pill-box/helpers/full-path.php';
require_once fullPath('models/doses.php');
require_once fullPath('scripts/schedule-dose-reminder.php');


if (!isset($_SESSION)) {
    session_start();
}
if (isset($_GET['action'])) {
    controllerDoses($_GET['action']);
} else if (isset($_POST['action'])) {
    controllerDoses($_POST['action']);
}

function controllerDoses($action)
{
    switch ($action) {
        case 'select_all_doses':
            $doses = ['not_taken_doses' => getNextDoses($_SESSION['user_id'])];
            echo json_encode($doses);
            break;

        case 'select_medicine_doses':
            $medicine_id = $_POST['medicine_id'];
            $results = getDosesFromMedicineId($medicine_id);
            $doses = [
                'taken_doses' => [],
                'not_taken_doses' => [],
            ];
            foreach ($results as $dose) {
                if ($dose['was_taken']) {
                    array_push($doses['taken_doses'], $dose);
                } else {
                    array_push($doses['not_taken_doses'], $dose);
                }
            }
            echo json_encode($doses);
            break;

        case 'count_medicine_doses':
            $medicine_id = $_POST['medicine_id'];
            $number_doses = countMedicineDoses($medicine_id);
            echo json_encode($number_doses);
            break;

        case 'insert_medicine_doses':
            $initial_date_time = DateTimeImmutable::createFromFormat(
                'Y-m-d',
                $_GET['treatment_start_date'],
                new DateTimeZone('America/Sao_Paulo')
            );

            // what we call "today's midnight" actually belongs to "tomorrow"
            $days_increment = $_GET['dose_time_1'] == '00:00' ? 1 : 0;

            //Funciona bem, mas é mais inteligente calcular os valores de doses diária
            //de todas as doses a partir da primeira utilizando o objeto dateTime quando forem
            //doses diárias com intervalos padrões. 
            //só usar cada um dos valores para doses
            //cujos horários são personalizados
            for ($days_increment; $days_increment < $_GET['total_usage_days']; $days_increment++) {
                $increment_string = 'P' . $days_increment . 'D';
                $dose_due_date = $initial_date_time->add(new DateInterval($increment_string));
                for ($dose_time_index = 1; $dose_time_index <= $_GET['doses_per_day']; $dose_time_index++) {
                    $dose_due_time = $_GET['dose_time_' . $dose_time_index];
                    $dose_hours = explode(':', $dose_due_time)[0];
                    $first_dose_hours = explode(':', $_GET['dose_time_1'])[0];

                    if (intval($dose_hours) < intval($first_dose_hours)) {
                        $dose_due_date = $dose_due_date->add(new DateInterval('P1D'));
                    }

                    $dose = [
                        'medicine_id' => $_GET['medicine_id'],
                        'due_date' => $dose_due_date->format('Y-m-d'),
                        'due_time' => $dose_due_time,
                    ];
                    foreach ($dose as $key => $value) {
                        if (empty($value)) {
                            $dose[$key] = NULL;
                        }
                    }
                    $dose['taken_date'] = NULL;
                    $dose['taken_time'] = NULL;
                    $dose['was_taken'] = FALSE;

                    $dose_id = createDose($dose);
                    $dose['dose_id'] = $dose_id;
                    $dose['medicine_name'] = $_GET['medicine_name'];

                    // TODO: ONLY SCHEDULE DOSES OF THE DAY!
                    // The sendGrid API can only schedule emails up to 72 hours in advance
                    scheduleDoseReminder($dose);
                }
            }
            header('Location: /pi3-smart-pill-box/views/pages/list-medicines.php');
            exit();
            break;

        case 'take_dose':
            $date = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
            try {
                takeDose([
                    'dose_id' => $_POST['dose_id'],
                    'taken_date' => $date->format('Y-m-d'),
                    'taken_time' => $date->format('H:i:00'),
                ]);
                echo json_encode(['success' => true]);
            } catch (PDOException $exception) {
                echo json_encode(['success' => false, 'exception' => $exception->getMessage()]);
            }
            break;

        case 'set_dose_not_taken':
            try {
                setDoseNotTaken([
                    'dose_id' => $_POST['dose_id'],
                    'taken_date' => NULL,
                    'taken_time' => NULL,
                ]);
                echo json_encode(['success' => true]);
            } catch (PDOException $exception) {
                echo json_encode(['success' => false, 'exception' => $exception->getMessage()]);
            }
            break;

        default:
            $error_message = 'Invalid action informed: action = ' . $action;
            return $error_message;
    }
}
