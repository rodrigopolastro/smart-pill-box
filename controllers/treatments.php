<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/smart-pill-box/helpers/full-path.php';
require_once fullPath('models/treatments.php');
require_once fullPath('controllers/doses.php');
require_once fullPath('controllers/smart-pill-boxes.php');

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
    switch ($treatmentsAction) {
        case 'create_treatment':
            $newTreatmentId = insertTreatment([
                'person_in_care_id' => $params['person_in_care_id'],
                'medicine_id' => $params['medicine_id'],
                'status' => 'ongoing',
                'reason' =>  $params['reason'],
                'usage_frequency' => $params['usage_frequency'],
                'start_date' => $params['start_date'],
                'doses_per_day' => $params['doses_per_day'],
                'pills_per_dose' => $params['pills_per_dose'],
                'total_usage_days' => $params['total_usage_days']
            ]);

            smartPillBoxesController('modify_slot_treatment', [
                'person_in_care_id' => $params['person_in_care_id'],
                'treatment_id' => $newTreatmentId,
                'slot_name' => $params['slot_name'],
                'medicine_id' => $params['medicine_id'],
                'quantity' => $quantity ?? 0
            ]);

            $treatmentStartDate = DateTimeImmutable::createFromFormat(
                'Y-m-d',
                $params['start_date'],
                new DateTimeZone('America/Sao_Paulo')
            );

            // what we call "today's midnight" actually belongs to "tomorrow"
            $daysIncrement = $params['doses_times'][0] == '00:00' ? 1 : 0;

            for ($daysIncrement; $daysIncrement < $params['total_usage_days']; $daysIncrement++) {
                $doseDueDate = $treatmentStartDate->add(new DateInterval('P' . $daysIncrement . 'D'));
                for ($doseTimeIndex = 0; $doseTimeIndex < $params['doses_per_day']; $doseTimeIndex++) {
                    $doseDueTime = $params['doses_times'][$doseTimeIndex];
                    $doseHours = explode(':', $doseDueTime)[0];
                    $firstDoseHours = explode(':', $params['doses_times'][0])[0];

                    //if time has overflowed the 24 hours, move to the next day
                    if (intval($doseHours) < intval($firstDoseHours)) {
                        $doseDueDate = $doseDueDate->add(new DateInterval('P1D'));
                    }

                    dosesController('create_dose', [
                        'treatment_id' => $newTreatmentId,
                        'due_datetime' => $doseDueDate->format('Y-m-d') . ' ' . $doseDueTime
                    ]);
                }
            }

            $queryStr =
                '?id=' . $params['person_in_care_id'] .
                '&status=treatment_created';
            if (isset($params['redirect_url'])) {
                header(
                    'Location: ' . fullPath('views/pages/person-in-care-profile.php' . $queryStr, true)
                );
                exit();
            }
            break;

        default:
            return [
                'sucess' => false,
                'errorMsg' => "Ação para Tratamentos inválida informada: '" . $treatmentsAction . "'"
            ];
    }
}
