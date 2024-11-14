<?php

require_once fullPath('database/mysql/connection.php');

function selectAllMedicines($nursingHomeId)
{
    global $connection;
    $statement = $connection->prepare(
        "SELECT
            NSH_id, 
            PIC_id,
            TTM_id,
            MED_id,
            MED_name,
            MED_description,
            MED_quantity_pills,
            MED_price
        FROM MEDICINES 
        INNER JOIN TREATMENTS ON TTM_medicine_id  = MED_id
        INNER JOIN PEOPLE_IN_CARE ON PIC_id = TTM_person_in_care_id
        INNER JOIN NURSING_HOMES ON NSH_id = PIC_nursing_home_id
        WHERE NSH_id = :nursing_home_id"
    );

    $statement->bindValue(':nursing_home_id', $nursingHomeId);
    $statement->execute();

    $medicines = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $medicines;
}

function createMedicine($medicine)
{
    global $connection;
    $statement = $connection->prepare(
        "INSERT INTO medicines (
            user_id,
            medicine_type_id,
            frequency_type_id,
            measurement_unit_id,
            medicine_name,
            medicine_description,
            doses_per_day,
            quantity_per_dose,
            treatment_start_date,
            total_usage_days
        ) VALUES (
            :user_id,
            :medicine_type_id,
            :frequency_type_id,
            :measurement_unit_id,
            :medicine_name,
            :medicine_description,
            :doses_per_day,
            :quantity_per_dose,
            :treatment_start_date,
            :total_usage_days
        )"
    );

    $statement->bindValue(':user_id', $medicine['user_id']);
    $statement->bindValue(':medicine_type_id', $medicine['medicine_type_id']);
    $statement->bindValue(':frequency_type_id', $medicine['frequency_type_id']);
    $statement->bindValue(':measurement_unit_id', $medicine['measurement_unit_id']);
    $statement->bindValue(':medicine_name', $medicine['medicine_name']);
    $statement->bindValue(':medicine_description', $medicine['medicine_description']);
    $statement->bindValue(':doses_per_day', $medicine['doses_per_day']);
    $statement->bindValue(':quantity_per_dose', $medicine['quantity_per_dose']);
    $statement->bindValue(':treatment_start_date', $medicine['treatment_start_date']);
    $statement->bindValue(':total_usage_days', $medicine['total_usage_days']);

    $statement->execute();

    return $connection->lastInsertId();
}
