<?php
include "../utils/functions.php";
$table = "kitchen_delivery_methods";
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
$kdm_id = isset($_POST["id"]) ? $_POST["id"] : null;

// Loop over variables to see which are null, return the missing ones
foreach (array('kdm_id') as $variable) {
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
foreach (["kdm_id"] as $variable) {
    $$variable = $conn->real_escape_string($$variable);
}

$sql = "DELETE FROM $table WHERE kdm_id = '$kdm_id'";

if ($conn->query($sql) === TRUE) {
    $response = ["status" => 1]; // success
} else {
    $response = ["error" => $conn->error];
}

$conn->close();
outputJSON($response);
?>