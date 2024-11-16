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
            MED_price,
            (   SELECT COUNT(1) 
                FROM TREATMENTS
                WHERE TTM_medicine_id = MED_id
            ) AS 'quantity_users'
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

function insertMedicine($medicine)
{
    global $connection;
    $statement = $connection->prepare(
        "INSERT INTO medicines (
            MED_nursing_home_id,
            MED_name,
            MED_description,
            MED_price,
            MED_quantity_pills
        ) VALUES (
            :nursing_home_id,
            :name,
            :description,
            :price,
            :quantity_pills
        )"
    );

    $statement->bindValue(':nursing_home_id', $medicine['nursing_home_id']);
    $statement->bindValue(':name', $medicine['name']);
    $statement->bindValue(':description', $medicine['description']);
    $statement->bindValue(':price', $medicine['price']);
    $statement->bindValue(':quantity_pills', $medicine['quantity_pills']);

    $statement->execute();
}

function selectPersonUnusedMedicines($personInCareId)
{
    global $connection;
    $statement = $connection->prepare(
        "SELECT 
            MED_id,
            MED_name,
            MED_description,
            MED_quantity_pills
            FROM MEDICINES
            LEFT JOIN TREATMENTS ON TTM_medicine_id = MED_id
                                AND TTM_person_in_care_id = :person_in_care_id
            WHERE TTM_medicine_id IS NULL;"
    );

    $statement->bindValue(':person_in_care_id', $personInCareId);
    $statement->execute();

    $personUnusedMedicines = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $personUnusedMedicines;
}
