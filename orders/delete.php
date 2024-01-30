<?php
include "../utils/functions.php";
$table = "orders";
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
$order_id = isset($_POST["order_id"]) ? $_POST["order_id"] : null;

// Loop over variables to see which are null, return the missing ones
foreach (array('order_id') as $variable) {
    if (empty($$variable)) {
        $response["missing"][] = $variable;
    }
}

// Stop if not all variables entered
if (isset($response["missing"])) {
    outputJSON($response);
    return;
}

// Stop if order does not exist
if (!order_exists($order_id)) {
    outputJSON($response + ["error" => "order does not exist"]);
    return;
} 

// Escape all variables to prevent SQL injection
foreach (["order_id"] as $variable) {
    $$variable = $conn->real_escape_string($$variable);
}

$sql = "DELETE FROM $table WHERE order_id = '$order_id'";

if ($conn->query($sql) === TRUE) {
    $response = ["status" => 1]; // success
} else {
    $response = ["error" => $conn->error];
}

$conn->close();
outputJSON($response);
?>