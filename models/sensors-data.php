<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/pi3-smart-pill-box/helpers/full-path.php';
require_once fullPath('database/mongo-connection.php');

function getRecordsFromSensor($user_id, $sensor_name, $limit)
{
    $filter = ['user_id' => $user_id];
    $options = [
        'projection' => [
            'timestamp' => 1,
            'sensors_data.' . $sensor_name => 1
        ],
        'limit' => $limit,
        'sort' => ['timestamp' => -1],
    ];
    global $sensors_data_collection;
    $cursor = $sensors_data_collection->find($filter, $options);
    $documents = $cursor->toArray();

    return $documents;
}

function getSensorsRecords($user_id, $limit)
{
    $filter = ['user_id' => $user_id];
    $options = [
        'limit' => $limit,
        'sort' => ['timestamp' => -1],
    ];

    global $sensors_data_collection;
    $cursor = $sensors_data_collection->find($filter, $options);
    $documents = $cursor->toArray();

    return $documents;
}

function recordSensorsData($user_id, $timestamp, $sensors)
{
    $document = [
        'user_id' => $user_id,
        'timestamp' => $timestamp,
        'sensors_data' => [
            'heart_rate' => $sensors['heart_rate'],
            'body_temperature' => $sensors['body_temperature'],
            'blood_oxygen' => $sensors['blood_oxygen'],
            'blood_pressure' => $sensors['blood_pressure']
        ]
    ];

    global $sensors_data_collection;
    $sensors_data_collection->insertOne($document);

    return $document;
}
