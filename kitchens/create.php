<?php
include "../utils/functions.php";
$table = "kitchens";
$response = [
    "status" => 0
];

// Stop if not POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    outputJSON($response + ["error" => "request_method was not POST"]);
    return;
}

$data = $_POST;

// If variable not present, set to null
$kitchen_owner_id = isset($data["kitchen_owner_id"]) ? $data["kitchen_owner_id"] : null;
$kitchen_name = isset($data["kitchen_name"]) ? $data["kitchen_name"] : null;
$kitchen_working_hours = isset($data["kitchen_working_hours"]) ? $data["kitchen_working_hours"] : null;
$kitchen_uses_cash = isset($data["kitchen_uses_cash"]) ? $data["kitchen_uses_cash"] : null;
$kitchen_uses_card = isset($data["kitchen_uses_card"]) ? $data["kitchen_uses_card"] : null;

// Loop over variables to see which are null return the missing ones
foreach (array('kitchen_owner_id', 'kitchen_name', 'kitchen_working_hours', 'kitchen_uses_cash', 'kitchen_uses_card') as $variable) {
    if (empty($$variable)) {
        $response["missing"][] = $variable;
    }
}

// Stop if not all variables entered
if (isset($response["missing"])) {
    outputJSON($response);
    return;
}

// Stop if user does not exist
if (!user_exists($kitchen_owner_id)) {
    outputJSON($response + ["error" => "user does not exist"]);
    return;
} 

// Stop if user already owns a kitchen
if (doesUserAlreadyOwnKitchen($kitchen_owner_id)) {
    outputJSON($response + ["error" => "user already owns a kitchen"]);
    return;
}

// Can start doing things
$kitchen_uses_cash = toBoolean($kitchen_uses_cash);
$kitchen_uses_card = toBoolean($kitchen_uses_card);

// Escape all variables to prevent SQL injection
foreach (["kitchen_owner_id", "kitchen_name", "kitchen_working_hours", "kitchen_uses_cash" ,"kitchen_uses_card"] as $variable) {
    if ($$variable !== null) {
        $$variable = $conn->real_escape_string($$variable);
    }
}

$sql = "INSERT INTO $table (kitchen_owner, kitchen_name, kitchen_working_hours, kitchen_uses_cash, kitchen_uses_card) 
        VALUES ('$kitchen_owner_id', '$kitchen_name', '$kitchen_working_hours', '$kitchen_uses_cash', '$kitchen_uses_card')";

if ($conn->query($sql) === TRUE) {
    $response = ["status" => 1]; // success
} else {
    $response = ["error" => $conn->error];
}

$conn->close();
outputJSON($response);
?>