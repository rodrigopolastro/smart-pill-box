<?php
require_once fullPath('database/mysql-connection.php');

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
}
