<?php
include "../utils/functions.php";
$table = "kitchen_delivery_methods";
$response = [
    "status" => 0
];

// Stop if not GET
if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    $response = ["error" => "request_method was not GET"]; 
    outputJSON($response);
    return;
}

// If variable not present, set to null
$kitchen_id = isset($_GET["kitchen_id"]) ? $_GET["kitchen_id"] : null;

// Loop over variables to see which are null return the missing ones
foreach (array('kitchen_id') as $variable) {
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
foreach (["kitchen_id"] as $variable) {
    $$variable = $conn->real_escape_string($$variable);
}

// Start doing database stuff
$sql = "SELECT * FROM $table WHERE kdm_owner = '$kitchen_id'";
$result = $conn->query($sql);

// Stop if there was a sql error
if (!$result) {
    $response = ["error" => $conn->error];
    return;
}

// Fill array with sql result, if empty thats ok
$rows = [];
while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
}
$response = [
    "status" => 1,
    "data" => $rows
];

$conn->close();
outputJSON($response);
?>