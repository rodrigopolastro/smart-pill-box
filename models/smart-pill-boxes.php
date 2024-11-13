<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/smart-pill-box/helpers/full-path.php';
require_once fullPath('database/mongodb/connection.php');

define('STRUCTURE_PATH', fullPath('database/mongodb/smart-pill-box-structure.json'));

function insertSmartPillBox($params)
{
    $structureFile = file_get_contents(STRUCTURE_PATH);
    $smartPillBox = json_decode($structureFile, true);

    $smartPillBox['personInCareId'] = $params['person_in_care_id'];
    $smartPillBox['status'] = $params['status'];

    global $smartPillBoxes;
    $smartPillBoxes->insertOne($smartPillBox);
}
