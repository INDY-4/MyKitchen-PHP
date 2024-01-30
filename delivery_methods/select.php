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
$kdm_id = isset($_GET["id"]) ? $_GET["id"] : null;
$kitchen_id = isset($_GET["kitchen_id"]) ? $_GET["kitchen_id"] : null;

// Escape all variables to prevent SQL injection
foreach (["kdm_id", "kitchen_id"] as $variable) {
    $$variable = $conn->real_escape_string($$variable);
}

// Build SQL based on variables supplied
$conditions = "";
if (!empty($kdm_id)) {
    $conditions .= "kdm_id = '$kdm_id'";
}

if (!empty($kitchen_id)) {
    $conditions .= (!empty($conditions) ? " AND " : "") . "kdm_owner = '$kitchen_id'";
}

// Stop if no variables supplied
if (empty($conditions)) {
    outputJSON($response + ["error" => "no conditions provided"]);
    return;
}

// Start doing database stuff
$sql = "SELECT * FROM $table WHERE $conditions";
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