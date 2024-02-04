<?php
include "../utils/functions.php";
$table = "transactions";
$response = [
    "status" => 0
];

// Stop if not GET
if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    outputJSON($response + ["error" => "request_method was not GET"]);
    return;
}

// If variable not present, set to null
$tr_id = isset($_GET["id"]) ? $_GET["id"] : null;
$tr_kitchen_id = isset($_GET["kitchen_id"]) ? intval($_GET["kitchen_id"]) : null;
$tr_user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : null;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Escape all variables to prevent SQL injection
foreach (["tr_id", "tr_kitchen_id", "tr_user_id", "page"] as $variable) {
    if ($$variable !== null) {
        $$variable = $conn->real_escape_string($$variable);
    }
}

// Build SQL based on variables supplied
$conditions = "";
if (!empty($tr_id)) {
    $conditions .= "tr_id = '$tr_id'";
}

if (!empty($tr_kitchen_id)) {
    $conditions .= (!empty($conditions) ? " AND " : "") . "tr_kitchen_id = '$tr_kitchen_id'";
}

if (!empty($tr_user_id)) {
    $conditions .= (!empty($conditions) ? " AND " : "") . "tr_user_id = '$tr_user_id'";
}

// Stop if no variables supplied
if (empty($conditions)) {
    outputJSON($response + ["error" => "no conditions provided"]);
    return;
}

// Show 25 transactions per page and offset by the page number if sent
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