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

// Loop over variables to see which are null, return the missing ones
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

// Stop if kitchen does not exist
if (!kitchen_exists($kitchen_id)) {
    outputJSON($response + ["error" => "kitchen does not exist"]);
    return;
} 

// Escape all variables to prevent SQL injection
foreach (["kitchen_id"] as $variable) {
    $$variable = $conn->real_escape_string($$variable);
}

$sql = "DELETE FROM $table WHERE kitchen_id = '$kitchen_id'";

if ($conn->query($sql) === TRUE) {
    $response = ["status" => 1]; // success
} else {
    $response = ["error" => $conn->error];
}

$conn->close();
outputJSON($response);
?>