<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/smart-pill-box/helpers/full-path.php';
require_once fullPath('database/mongodb/connection.php');

define('STRUCTURE_PATH', fullPath('database/mongodb/smart-pill-box-structure.json'));

function insertSmartPillBox($params)
{
    $structureFile = file_get_contents(STRUCTURE_PATH);
    $smartPillBox = json_decode($structureFile, true);

    $smartPillBox['nursingHomeId'] = $params['nursing_home_id'];
    $smartPillBox['personInCareId'] = $params['person_in_care_id'];
    $smartPillBox['status'] = $params['status'];

    global $smartPillBoxes;
    $smartPillBoxes->insertOne($smartPillBox);
}

function selectSmartPillBox($personInCareId)
{
    $filter = ['personInCareId' => $personInCareId];

    global $smartPillBoxes;
    $smartPillBox = $smartPillBoxes->findOne($filter);

    return $smartPillBox;
}

function updateSlotTreatment($params)
{
    $filter = ['personInCareId' => $params['person_in_care_id']];
    $slot = 'slots.' . $params['slot_name'];

    $update = [
        '$set' => [
            $slot  => [
                'treatmentId' => $params['treatment_id'],
                'medicineId' => $params['medicine_id'],
                'quantity' => $params['quantity'],
                'needsRefil' => null
            ]
        ]
    ];

    global $smartPillBoxes;
    $smartPillBoxes->updateOne($filter, $update);
}

function countMedicinesPillsInBoxes($params)
{
    $pipeline = [
        [
            '$match' => ['nursingHomeId' => $params['nursing_home_id']]
        ],
        [
            '$project' => ['slots' => ['$objectToArray' => '$slots']]
        ],
        [
            // Unwind the array to treat each slot as a document
            '$unwind' => '$slots'
        ],
        [
            '$match' => [
                'slots.v.medicineId' => [
                    '$exists' => true,
                    '$ne' => null
                ],
            ]
        ],
        [
            '$group' => [
                '_id' => '$slots.v.medicineId',
                'pillsInBoxes' => ['$sum' => '$slots.v.quantity']
            ]
        ]
    ];

    global $smartPillBoxes;
    $results = $smartPillBoxes->aggregate($pipeline)->toArray();

    //medicineId becomes the key and the quantity of pills in boxes becomes the value
    $medicinesPillsInBoxes = array_column($results, 'pillsInBoxes', '_id');
    return $medicinesPillsInBoxes;
}
