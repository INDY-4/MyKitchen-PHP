<?php
include "../utils/functions.php";
$table = "kitchens";
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
$kitchen_id = isset($_GET["id"]) ? $_GET["id"] : null;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Escape all variables to prevent SQL injection
foreach (["kitchen_id", "page"] as $variable) {
    $$variable = $conn->real_escape_string($$variable);
}

// Build SQL based on variables supplied
$conditions = "";
if (!empty($kitchen_id)) {
    $conditions .= "WHERE kitchen_id = '$kitchen_id'";
}

// Show 25 kitchens per page and offset by the page number if sent
$offset = ($page - 1) * 25;

// Start doing database stuff
$sql = "SELECT * FROM $table $conditions LIMIT 25 OFFSET $offset";
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