<?php

require_once fullPath('database/mongo-connection.php');

function recordSensorsData($user_id, $timestamp, $sensors){
    $document = [
        'user_id' => $user_id,
        'timestamp' => $timestamp,
        'sensors_data' => [
            'heart_rate' => $sensors['body_temperature'],
            'body_temperature' => $sensors['body_temperature'],
            'blood_oxygen' => $sensors['blood_oxygen'],
            'blood_pressure' => $sensors['blood_pressure']
        ]
    ];

    global $sensors_data_collection;
    $sensors_data_collection->insertOne($document);
}