<?php

require_once fullPath('database/mysql-connection.php');

function getNursingHomeByEmail($nursing_home_email)
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

    $statement->bindValue(':email', $nursing_home_email);
    $statement->execute();

    $nursing_home = $statement->fetch(PDO::FETCH_ASSOC);
    return $nursing_home;
}

function createNursingHome($nursing_home)
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

    $statement->bindValue(':email', $nursing_home['email']);
    $statement->bindValue(':password', $nursing_home['password']);
    $statement->bindValue(':company_name', $nursing_home['company_name']);
    $statement->execute();

    return $connection->lastInsertId();
}
