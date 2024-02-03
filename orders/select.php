<?php
include "../utils/functions.php";
$table = "orders";
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
$order_id = isset($_GET["id"]) ? $_GET["id"] : null;
$kitchen_id = isset($_GET["kitchen_id"]) ? $_GET["kitchen_id"] : null;
$user_id = isset($_GET["user"]) ? $_GET["user"] : null;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Escape all variables to prevent SQL injection
foreach (["order_id", "kitchen_id", "user_id", "page"] as $variable) {
    $$variable = $conn->real_escape_string($$variable);
}

// Build SQL based on variables supplied
$conditions = "";
if (!empty($order_id)) {
    $conditions .= "order_id = '$order_id'";
}

if (!empty($kitchen_id)) {
    $conditions .= (!empty($conditions) ? " AND " : "") . "order_kitchen_id = '$kitchen_id'";
}

if (!empty($user_id)) {
    $conditions .= (!empty($conditions) ? " AND " : "") . "order_user_id = '$user_id'";
}

// Stop if no variables supplied
if (empty($conditions)) {
    outputJSON($response + ["error" => "no conditions provided"]);
    return;
}

// Show 25 orders per page and offset by the page number if sent
$offset = ($page - 1) * 25;

// Start doing database stuff
$sql = "SELECT * FROM $table WHERE $conditions LIMIT 25 OFFSET $offset";
$result = $conn->query($sql);

// Stop if there was a sql error
if (!$result) {
    outputJSON($response + ["error" => $conn->error]);
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