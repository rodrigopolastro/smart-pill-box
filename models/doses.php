<?php

require_once fullPath('database/mysql/connection.php');

function selectFilteredDoses($params)
{
    $sql =
        "SELECT
            DOS_id,
            DOS_treatment_id,
            MED_id,
            MED_name,
            PIC_id,
            PIC_first_name,
            PIC_last_name,
            DOS_number,
            DOS_due_datetime,
            DOS_was_taken,
            DOS_taken_datetime,
            TTM_id,
            TTM_pills_per_dose,
            ( 
                SELECT COUNT(1) FROM DOSES 
                WHERE DOS_treatment_id = TTM_id
            ) AS 'treatment_total_doses'
        FROM DOSES
        INNER JOIN TREATMENTS ON TTM_id = DOS_treatment_id
        INNER JOIN MEDICINES ON MED_id = TTM_medicine_id
        INNER JOIN PEOPLE_IN_CARE ON PIC_id = TTM_person_in_care_id
        WHERE PIC_nursing_home_id = :nursing_home_id";

    if (!is_null($params['filters'])) {
        foreach ($params['filters'] as $filter) {
            $column = $filter[0];
            $value = $filter[1];
            if ($column == 'DOS_due_datetime') {
                //'yyyy-mm-dd' is the format used by html date components
                $sql .= " AND DATE_FORMAT(DOS_due_datetime, '%Y-%m-%d')" .  " = " . " '$value' ";
            } else {
                $sql .= ' AND ' . $column . ' = ' . $value;
            }
        }
    }
    if (!is_null($params['filters'])) {
        $column = $params['order_by'][0];
        $direction = $params['order_by'][1];
        $sql .= ' ORDER BY ' . $column . ' ' . $direction;
    }

    global $connection;
    $statement = $connection->prepare($sql);
    $statement->bindValue(':nursing_home_id', $params['nursing_home_id']);
    $statement->execute();

    $doses = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $doses;
}

function countMedicineDoses($medicine_id)
{
    global $connection;
    $statement = $connection->prepare(
        "SELECT 
            (SELECT COUNT(*) FROM doses WHERE medicine_id = :medicine_id ) 
                AS 'total_doses',
            (SELECT COUNT(*) FROM doses WHERE medicine_id = :medicine_id 
                AND was_taken = TRUE) 
                AS 'taken_doses'"
    );

    $statement->bindValue(':medicine_id', $medicine_id);
    $statement->execute();
    $results = $statement->fetch(PDO::FETCH_ASSOC);
    return $results;
}

function getDosesFromMedicineId($medicine_id)
{
    global $connection;
    $statement = $connection->prepare(
        "SELECT 
            md.medicine_id,
            md.medicine_name,
            md.quantity_per_dose,
            mu.portuguese_name measurement_unit,
            ds.dose_id,
            ds.due_date,
            ds.due_time,
            ds.taken_date,
            ds.taken_time,
            ds.was_taken
        FROM doses ds 
        INNER JOIN medicines md on md.medicine_id = ds.medicine_id
        INNER JOIN measurement_units mu on mu.measurement_unit_id = md.measurement_unit_id
        WHERE ds.medicine_id = :medicine_id"
    );

    $statement->bindValue(':medicine_id', $medicine_id);
    $statement->execute();
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $results;
}

function insertDose($dose)
{
    global $connection;
    $statement = $connection->prepare(
        "INSERT INTO DOSES (
            DOS_treatment_id,
            DOS_due_datetime,
            DOS_number
        ) VALUES (
            :treatment_id,
            :due_datetime,
            :dose_number
        )"
    );

    $statement->bindValue(':treatment_id', $dose['treatment_id']);
    $statement->bindValue(':due_datetime', $dose['due_datetime']);
    $statement->bindValue(':dose_number', $dose['dose_number']);
    $statement->execute();

    return $connection->lastInsertId();
}

function takeDose($doseId)
{
    global $connection;
    $statement = $connection->prepare(
        "UPDATE DOSES SET 
        DOS_was_taken = TRUE, 
        DOS_taken_datetime = CURRENT_TIMESTAMP()
      WHERE DOS_id = :dose_id"
    );

    $statement->bindValue(':dose_id', $doseId);
    $statement->execute();
}

function getTreatmentNextDose($treatmentId)
{
    global $connection;
    $statement = $connection->prepare(
        "SELECT 
            DOS_id, 
            DOS_due_datetime,
            UNIX_TIMESTAMP(DOS_due_datetime) AS due_date_timestamp
        FROM DOSES
        WHERE DOS_treatment_id = :treatment_id
          AND DOS_due_datetime > CURRENT_TIMESTAMP()
        ORDER BY DOS_due_datetime ASC LIMIT 1"
    );

    $statement->bindValue(':treatment_id', $treatmentId);
    $statement->execute();
    $treatmentNextDose = $statement->fetch(PDO::FETCH_ASSOC);

    if ($statement->rowCount() > 0) {
        return $treatmentNextDose;
    } else {
        return [
            'DOS_id' => 0,
            'DOS_due_datetime' => "",
            'due_date_timestamp' => 0
        ];
    }
}
