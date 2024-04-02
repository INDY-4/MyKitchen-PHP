<?php
include "../utils/functions.php";
$table = "users";
$response = [
    "status" => 0
];

// Stop if not GET
if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    outputJSON($response + ["error" => "request_method was not GET"]);
    return;
}

// If variable not present, set to null
$user_id = isset($_GET["id"]) ? $_GET["id"] : null;
$user_name = isset($_GET["username"]) ? $_GET["username"] : null;
$user_email = isset($_GET["email"]) ? $_GET["email"] : null;

// Escape all variables to prevent SQL injection
foreach (["user_id", "user_name", "user_email"] as $variable) {
    if ($$variable !== null) {
        $$variable = $conn->real_escape_string($$variable);
    }
}

// Build SQL based on variables supplied
$conditions = "";
if (!empty($user_id)) {
    $conditions .= "user_id = '$user_id'";
}
if (!empty($user_name)) {
    $conditions .= (!empty($conditions) ? " AND " : "") . "user_name = '$user_name'";
}
if (!empty($user_email)) {
    $conditions .= (!empty($conditions) ? " AND " : "") . "user_email = '$user_email'";
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