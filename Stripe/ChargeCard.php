<?php
include './Stripe.php';
$table = 'transactions';
$response = [
    "status" => 0
];
$endpoint = 'https://api.stripe.com/v1/charges';

// Stop if not POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    outputJSON($response + ["error" => "request_method was not POST"]);
    return;
}

$kitchen_id = isset($_POST["kitchen_id"]) ? $_POST["kitchen_id"] : null;
$user_id = isset($_POST["user_id"]) ? $_POST["user_id"] : null;
$card_number = isset($_POST["card_number"]) ? $_POST["card_number"] : null;
$card_name = isset($_POST["card_name"]) ? $_POST["card_name"] : null;
$card_exp_m = isset($_POST["card_exp_m"]) ? intval($_POST["card_exp_m"]) : null;
$card_exp_y = isset($_POST["card_exp_y"]) ? intval($_POST["card_exp_y"]) : null;
$card_cvc = isset($_POST["card_cvc"]) ? intval($_POST["card_cvc"]) : null;
$amount = isset($_POST["amount"]) ? $_POST["amount"] : null;

// Loop over variables to see which are null, return the missing ones
foreach (array('kitchen_id', 'user_id', 'card_number', 'amount') as $variable) {
    if (empty($$variable)) {
        $response["missing"][] = $variable;
    }
}

// Stop if not all variables entered
if (isset($response["missing"])) {
    outputJSON($response);
    return;
}

$requestData = [
    'amount' => $amount * 100, // Amount in cents
    'currency' => 'usd',
    'source' => getCardType($card_number),
];

$charge = stripe_request($endpoint, $requestData);
if (!(isset($charge['captured']) && isset($charge['paid'])) || !($charge['captured'] && $charge['paid'])) {
    outputJSON($response + ["error" => "payment declined"]);
    $tr_status = "failed";
    return;
}
$tr_status = "paid";
$tr_stripe_id = $charge["id"];


// Escape all variables to prevent SQL injection
foreach (["kitchen_id", "user_id", "amount", "tr_status", "tr_stripe_id"] as $variable) {
    $$variable = $conn->real_escape_string($$variable);
}

// Can start doing things
$sql = "INSERT INTO $table (tr_kitchen_id, tr_user_id, tr_amount, tr_status, tr_stripe_id) 
        VALUES ('$kitchen_id', '$user_id', '$amount', '$tr_status', '$tr_stripe_id')";

if ($conn->query($sql) === TRUE) {
    $response = ["status" => 1]; // success
} else {
    $response = ["error" => $conn->error];
}

outputJSON($response);