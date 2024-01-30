<?php
include "../utils/functions.php";
$table = "kitchens";
$response = [
    "status" => 0
];

// Stop if not POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $response = ["error" => "request_method was not POST"]; 
    outputJSON($response);
    return;
}

// If variable not present, set to null
$kitchen_id = isset($_POST["kitchen_id"]) ? $_POST["kitchen_id"] : null;
$kitchen_name = isset($_POST["kitchen_name"]) ? $_POST["kitchen_name"] : null;
$kitchen_working_hours = isset($_POST["kitchen_working_hours"]) ? $_POST["kitchen_working_hours"] : null;
$kitchen_is_active = isset($_POST["kitchen_is_active"]) ? $_POST["kitchen_is_active"] : null;
$kitchen_uses_cash = isset($_POST["kitchen_uses_cash"]) ? $_POST["kitchen_uses_cash"] : null;
$kitchen_uses_card = isset($_POST["kitchen_uses_card"]) ? $_POST["kitchen_uses_card"] : null;

// Loop over variables to see which are null, return the missing ones
foreach (array('kitchen_id', 'kitchen_name', 'kitchen_working_hours', 'kitchen_is_active', 'kitchen_uses_cash', 'kitchen_uses_card') as $variable) {
    if (empty($$variable)) {
        $response["missing"][] = $variable;
    }
}

// Stop if not all variables entered
if (isset($response["missing"])) {
    outputJSON($response);
    return;
}

// Stop if kitchen does not exist
if (!kitchen_exists($kitchen_id)) {
    outputJSON($response + ["error" => "kitchen does not exist"]);
    return;
} 

// Can start doing things
$kitchen_is_active = toBoolean($kitchen_is_active);
$kitchen_uses_cash = toBoolean($kitchen_uses_cash);
$kitchen_uses_card = toBoolean($kitchen_uses_card);

// Escape all variables to prevent SQL injection
foreach (["kitchen_id", "kitchen_name", "kitchen_working_hours", "kitchen_is_active" ,"kitchen_uses_cash", "kitchen_uses_card"] as $variable) {
    $$variable = $conn->real_escape_string($$variable);
}

$sql = "UPDATE $table 
        SET 
            kitchen_name = '$kitchen_name', 
            kitchen_working_hours = '$kitchen_working_hours', 
            kitchen_is_active = '$kitchen_is_active', 
            kitchen_uses_cash = '$kitchen_uses_cash',
            kitchen_uses_card = '$kitchen_uses_card'  
        WHERE 
            kitchen_id = '$kitchen_id'";

if ($conn->query($sql) === TRUE) {
    $response = ["status" => 1]; // success
} else {
    $response = ["error" => $conn->error];
}

$conn->close();
outputJSON($response);
?>