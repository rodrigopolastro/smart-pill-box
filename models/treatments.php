<?php

require_once fullPath('database/mysql/connection.php');

function insertTreatment($treatment)
{
    global $connection;
    $statement = $connection->prepare(
        "INSERT INTO TREATMENTS (
            TTM_person_in_care_id,
            TTM_medicine_id,
            TTM_status,
            TTM_reason,
            TTM_usage_frequency,
            TTM_start_date,
            TTM_doses_per_day,
            TTM_pills_per_dose,
            TTM_total_usage_days
        ) VALUES (
            :person_in_care_id,
            :medicine_id,
            :status,
            :reason,
            :usage_frequency,
            :start_date,
            :doses_per_day,
            :pills_per_dose,
            :total_usage_days
        )"
    );

    $statement->bindValue('person_in_care_id', $treatment['person_in_care_id']);
    $statement->bindValue('medicine_id', $treatment['medicine_id']);
    $statement->bindValue('status', $treatment['status']);
    $statement->bindValue('reason', $treatment['reason']);
    $statement->bindValue('usage_frequency', $treatment['usage_frequency']);
    $statement->bindValue('start_date', $treatment['start_date']);
    $statement->bindValue('doses_per_day', $treatment['doses_per_day']);
    $statement->bindValue('pills_per_dose', $treatment['pills_per_dose']);
    $statement->bindValue('total_usage_days', $treatment['total_usage_days']);

    $statement->execute();

    return $connection->lastInsertId();
}
