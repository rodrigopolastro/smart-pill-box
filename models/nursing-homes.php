<?php
require_once fullPath('database/mysql/connection.php');

function selectNursingHomeByEmail($nursingHomeEmail)
{
    global $connection;
    $statement = $connection->prepare(
        "SELECT 
            NSH_id,
            NSH_email,
            NSH_password,
            NSH_company_name
       FROM NURSING_HOMES
       WHERE NSH_email = :email"
    );

    $statement->bindValue(':email', $nursingHomeEmail);
    $statement->execute();

    $nursingHome = $statement->fetch(PDO::FETCH_ASSOC);
    return $nursingHome;
}

function insertNursingHome($nursingHome)
{
    global $connection;
    $statement = $connection->prepare(
        "INSERT INTO NURSING_HOMES (
            NSH_email, 
            NSH_password,
            NSH_company_name
        ) VALUES (
            :email, 
            :password,
            :company_name
        )"
    );

    $statement->bindValue(':email', $nursingHome['email']);
    $statement->bindValue(':password', $nursingHome['password']);
    $statement->bindValue(':company_name', $nursingHome['company_name']);
    $statement->execute();

    return $connection->lastInsertId();
}
