<?php
include "../utils/functions.php";
$table = "orders";
$response = [
    "status" => 0
];

// Stop if not POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    outputJSON($response + ["error" => "request_method was not POST"]);
    return;
}

// If variable not present, set to null
$order_id = isset($_POST["id"]) ? $_POST["id"] : null;
$order_products = isset($_POST["order_products"]) ? $_POST["order_products"] : null;
$order_total = isset($_POST["order_total"]) ? $_POST["order_total"] : null;
$order_status = isset($_POST["order_status"]) ? $_POST["order_status"] : null;

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
foreach (["order_id", "order_products", "order_total", "order_status"] as $variable) {
    if ($$variable !== null) {
        $$variable = $conn->real_escape_string($$variable);
    }
}

// Build SET string
$set = "";
if (!empty($order_products)) {
    $set .= (!empty($set) ? " , " : "") . "order_products = '$order_products'";
}
if (!empty($order_total)) {
    // Stop if the order is worth less than $0
    if ($order_total < 0) {
        outputJSON($response + ["error" => "order_total is less than 0"]);
        return;
    }
    $set .= (!empty($set) ? " , " : "") . "order_total = '$order_total'";
}
if (!empty($order_status)) {
    // Stop if order_status is not one of the preset statuses
    $order_status_options = ["sent", "payment_waiting", "in_progress", "cooked", "done"];
    if (!in_array($order_status, $order_status_options)) {
        outputJSON($response + ["error" => "order_status not in array of [sent, payment_waiting, in_progress, cooked, done]"]);
        return;
    }
    $set .= (!empty($set) ? " , " : "") . "order_status = '$order_status'";
}

// If this is still empty, nothing was updated
if ($set == "") {
    outputJSON($response + ["error" => "no updates provided"]);
    return;
}

$sql = "UPDATE $table 
        SET 
            $set
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