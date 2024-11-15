<?php

require_once fullPath('database/mysql/connection.php');

function selectAllMedicines($nursingHomeId)
{
    global $connection;
    $statement = $connection->prepare(
        "SELECT
            MED_id,
            MED_name,
            MED_description,
            MED_quantity_pills,
            MED_price
        FROM MEDICINES 
        WHERE MED_nursing_home_id = :nursing_home_id"
    );

    $statement->bindValue(':nursing_home_id', $nursingHomeId);
    $statement->execute();

    $medicines = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $medicines;
}

function selectMedicine($medicineId)
{
    global $connection;
    $statement = $connection->prepare(
        "SELECT
            MED_id,
            MED_name,
            MED_description,
            MED_quantity_pills,
            MED_price
        FROM MEDICINES 
        WHERE MED_id = :medicine_id"
    );

    $statement->bindValue(':medicine_id', $medicineId);
    $statement->execute();

    $medicine = $statement->fetch(PDO::FETCH_ASSOC);
    return $medicine;
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
