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
$order_kitchen_id = isset($_POST["order_kitchen_id"]) ? $_POST["order_kitchen_id"] : null;
$order_user_id = isset($_POST["order_user_id"]) ? $_POST["order_user_id"] : null;
$order_products = isset($_POST["order_products"]) ? $_POST["order_products"] : null;
$order_total = isset($_POST["order_total"]) ? $_POST["order_total"] : null;
$order_status = isset($_POST["order_status"]) ? $_POST["order_status"] : null;

// Loop over variables to see which are null return the missing ones
foreach (array('order_kitchen_id', 'order_user_id', 'order_products', 'order_total', 'order_status') as $variable) {
    if (empty($$variable)) {
        $response["missing"][] = $variable;
    }
}

// Stop if not all variables entered
if (isset($response["missing"])) {
    outputJSON($response);
    return;
}

// Stop if order_status is not one of the preset statuses
$order_status_options = ["sent", "payment_waiting", "in_progress", "cooked", "done"];
if (!in_array($order_status, $order_status_options)) {
    outputJSON($response + ["error" => "order_status not in array of [sent, payment_waiting, in_progress, cooked, done]"]);
    return;
}

// Stop if the kitchen doesn't exist
if (!kitchen_exists($order_kitchen_id)) {
    outputJSON($response + ["error" => "kitchen does not exist"]);
    return;
} 

// Stop if the user doesn't exist
if (!user_exists($order_user_id)) {
    outputJSON($response + ["error" => "user does not exist"]);
    return;
} 

// Stop if the order is worth less than $0
if ($order_total < 0) {
    outputJSON($response + ["error" => "order_total is less than 0"]);
    return;
}

// Escape all variables to prevent SQL injection
foreach (["order_kitchen_id", "order_user_id", "order_products", "order_total" ,"order_status"] as $variable) {
    $$variable = $conn->real_escape_string($$variable);
}

// Can start doing things
$sql = "INSERT INTO $table (order_kitchen_id, order_user_id, order_products, order_total, order_status) 
        VALUES ('$order_kitchen_id', '$order_user_id', '$order_products', '$order_total', '$order_status')";

if ($conn->query($sql) === TRUE) {
    $response = ["status" => 1]; // success
} else {
    $response = ["error" => $conn->error];
}

$conn->close();
outputJSON($response);
?>