<?php
require_once fullPath('database/mysql/connection.php');

function selectPersonInCare($personInCareId)
{
    global $connection;
    $statement = $connection->prepare(
        "SELECT 
            PIC_id,
            PIC_nursing_home_id,  
            PIC_first_name,
            PIC_last_name, 
            PIC_birth_date,
            PIC_height, 
            PIC_weight, 
            PIC_allergies,
            PIC_notes 
        FROM PEOPLE_IN_CARE
        WHERE PIC_id = :person_in_care_id;"
    );

    $statement->bindValue(':person_in_care_id', $personInCareId);
    $statement->execute();

    $personInCare = $statement->fetch(PDO::FETCH_ASSOC);
    return $personInCare;
}

function selectAllPeopleInCare($nursingHomeId)
{
    global $connection;
    $statement = $connection->prepare(
        "SELECT 
            PIC_id,
            PIC_nursing_home_id,  
            PIC_first_name,
            PIC_last_name, 
            PIC_birth_date,
            PIC_height, 
            PIC_weight, 
            PIC_allergies,
            PIC_notes 
        FROM PEOPLE_IN_CARE
        WHERE PIC_nursing_home_id = :nursing_home_id;"
    );

    $statement->bindValue('nursing_home_id', $nursingHomeId);
    $statement->execute();

    $peopleInCare = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $peopleInCare;
}

function selectMedicineUsers($medicineId)
{
    global $connection;
    $statement = $connection->prepare(
        "SELECT 
            PIC_id,
            TTM_id,
            TTM_reason,
            PIC_first_name,
            PIC_last_name
        FROM PEOPLE_IN_CARE
        INNER JOIN TREATMENTS ON TTM_person_in_care_id = PIC_id
                             AND TTM_medicine_id = :medicine_id"
    );

    $statement->bindValue('medicine_id', $medicineId);
    $statement->execute();

    $medicineUsers = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $medicineUsers;
}

function insertPersonInCare($personInCare)
{
    global $connection;
    $statement = $connection->prepare(
        "INSERT INTO PEOPLE_IN_CARE (
            PIC_nursing_home_id,  
            PIC_first_name,
            PIC_last_name, 
            PIC_birth_date,
            PIC_height, 
            PIC_weight, 
            PIC_allergies,
            PIC_notes 
        ) VALUES (
            :nursing_home_id,  
            :first_name,
            :last_name, 
            :birth_date,
            :height, 
            :weight, 
            :allergies,
            :notes
        )"
    );

    $statement->bindValue('nursing_home_id', $personInCare['nursing_home_id']);
    $statement->bindValue('first_name', $personInCare['first_name']);
    $statement->bindValue('last_name', $personInCare['last_name']);
    $statement->bindValue('birth_date', $personInCare['birth_date']);
    $statement->bindValue('height', $personInCare['height']);
    $statement->bindValue('weight', $personInCare['weight']);
    $statement->bindValue('allergies', $personInCare['allergies']);
    $statement->bindValue('notes', $personInCare['notes']);
    $statement->execute();

    return $connection->lastInsertId();
}
