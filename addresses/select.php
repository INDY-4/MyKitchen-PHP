<?php
include "../utils/functions.php";
$table = "addresses";
$response = [
    "status" => 0
];

// Stop if not GET
if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    outputJSON($response + ["error" => "request_method was not GET"]);
    return;
}

// If variable not present, set to null
$owner_id = isset($_GET["owner_id"]) ? $_GET["owner_id"] : null;
$address_type = isset($_GET["address_type"]) ? $_GET["address_type"] : null;

// Loop over variables to see which are null return the missing ones
foreach (array('owner_id', 'address_type') as $variable) {
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
foreach (["owner_id", "address_type"] as $variable) {
    $$variable = $conn->real_escape_string($$variable);
}

// Start doing database stuff
$sql = "SELECT * FROM $table WHERE address_owner = '$owner_id' AND address_type = '$address_type'";
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