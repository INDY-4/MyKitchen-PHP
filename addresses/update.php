<?php
include "../utils/functions.php";
$table = "addresses";
$response = [
    "status" => 0
];

// Stop if not POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    outputJSON($response + ["error" => "request_method was not POST"]);
    return;
}

$data = getJSONPostData();

// If variable not present, set to null
$address_id = isset($data["id"]) ? $data["id"] : null;
$address_line1 = isset($data["address_line1"]) ? $data["address_line1"] : null;
$address_line2 = isset($data["address_line2"]) ? $data["address_line2"] : null;
$address_city = isset($data["address_city"]) ? $data["address_city"] : null;
$address_state = isset($data["address_state"]) ? $data["address_state"] : null;
$address_zip = isset($data["address_zip"]) ? $data["address_zip"] : null;
$address_phone = isset($data["address_phone"]) ? $data["address_phone"] : null;

// Loop over variables to see which are null, return the missing ones
foreach (array('address_id') as $variable) {
    if (empty($$variable)) {
        $response["missing"][] = $variable;
    }
}

// Stop if not all variables entered
if (isset($response["missing"])) {
    outputJSON($response);
    return;
}

// Escape all variables to prevent SQL injection
foreach (["address_id", "address_line1", "address_line2", "address_city" ,"address_state", "address_zip", "address_phone"] as $variable) {
    if ($$variable !== null) {
        $$variable = $conn->real_escape_string($$variable);
    }
}

// Build SET string
$set = "";
if (!empty($address_line1)) {
    $set .= (!empty($set) ? " , " : "") . "address_line1 = '$address_line1'";
}
if (!empty($address_line2)) {
    $set .= (!empty($set) ? " , " : "") . "address_line2 = '$address_line2'";
}
if (!empty($address_city)) {
    $set .= (!empty($set) ? " , " : "") . "address_city = '$address_city'";
}
if (!empty($address_state)) {
    $set .= (!empty($set) ? " , " : "") . "address_state = '$address_state'";
}
if (!empty($address_zip)) {
    $set .= (!empty($set) ? " , " : "") . "address_zip = '$address_zip'";
}
if (!empty($address_phone)) {
    $set .= (!empty($set) ? " , " : "") . "address_phone = '$address_phone'";
}

// If this is still empty, nothing was updated
if ($set == "") {
    outputJSON($response + ["error" => "no updates provided"]);
    return;
}

$sql = "UPDATE $table 
        SET 
            $set
        WHERE 
            address_id = '$address_id'";

if ($conn->query($sql) === TRUE) {
    $response = ["status" => 1]; // success
} else {
    $response = ["error" => $conn->error];
}

$conn->close();
outputJSON($response);
?>