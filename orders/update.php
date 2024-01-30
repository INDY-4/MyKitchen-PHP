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
$order_products = isset($_POST["order_products"]) ? $_POST["order_products"] : null;
$order_total = isset($_POST["order_total"]) ? $_POST["order_total"] : null;
$order_status = isset($_POST["order_status"]) ? $_POST["order_status"] : null;

// Loop over variables to see which are null, return the missing ones
foreach (array('order_id', 'order_products', 'order_total', 'order_status') as $variable) {
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

// Stop if the order is worth less than $0
if ($order_total < 0) {
    outputJSON($response + ["error" => "order_total is less than 0"]);
    return;
}

// Stop if order_status is not one of the preset statuses
$order_status_options = ["sent", "payment_waiting", "in_progress", "cooked", "done"];
if (!in_array($order_status, $order_status_options)) {
    outputJSON($response + ["error" => "order_status not in array of [sent, payment_waiting, in_progress, cooked, done]"]);
    return;
}

// Escape all variables to prevent SQL injection
foreach (["order_id", "order_products", "order_total", "order_status"] as $variable) {
    $$variable = $conn->real_escape_string($$variable);
}

$sql = "UPDATE $table 
        SET 
            order_products = '$order_products', 
            order_total = '$order_total', 
            order_status = '$order_status' 
        WHERE 
            order_id = '$order_id'";

if ($conn->query($sql) === TRUE) {
    $response = ["status" => 1]; // success
} else {
    $response = ["error" => $conn->error];
}

$conn->close();
outputJSON($response);
?>