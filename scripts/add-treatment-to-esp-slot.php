<?php
function addTreatmentToEspSlot($params)
{
    $serverUrl = "http://192.168.1.110/addTreatmentToSlot";

    $jsonData = json_encode([
        "treatmentId" => $params['treatment_id'],
        "slotName" => $params['slot_name']
    ]);

    $ch = curl_init($serverUrl);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // Return response as a string
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        "Content-Length: " . strlen($jsonData)
    ));

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo "Error: " . curl_error($ch);
    } else {
        echo "Response: " . $response;
    }

    curl_close($ch);
}
