<?php
require_once fullPath('vendor/autoload.php');

use MongoDB\Client;

$connectionStr = 'mongodb://localhost:27017';
$client = new MongoDB\Client($connectionStr);

try {
    $db = $client->selectDatabase('smart_pill_box');
    $smartPillBoxes = $db->selectCollection('smartPillBoxes');
} catch (Exception $e) {
    printf($e->getMessage());
}
