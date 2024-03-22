<?php
include "../utils/functions.php";
$table = "kitchen_delivery_methods";
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
$kdm_owner = isset($data["kdm_owner"]) ? $data["kdm_owner"] : null;
$kdm_type = isset($data["kdm_type"]) ? $data["kdm_type"] : null;
$kdm_range = isset($data["kdm_range"]) ? $data["kdm_range"] : null;

// Loop over variables to see which are null return the missing ones
foreach (array('kdm_owner', 'kdm_type', 'kdm_range') as $variable) {
    if (empty($$variable)) {
        $response["missing"][] = $variable;
    }
}

// Stop if not all variables entered
if (isset($response["missing"])) {
    outputJSON($response);
    return;
}

// Stop if kdm_type is not one of the preset options
$kdm_type_options = ["local_pickup", "delivery"];
if (!in_array($kdm_type, $kdm_type_options)) {
    outputJSON($response + ["error" => "kdm_type not in array of [local_pickup, delivery]"]);
    return;
}

// Stop if the kitchen doesn't exist
if (!kitchen_exists($kdm_owner)) {
    outputJSON($response + ["error" => "kitchen does not exist"]);
    return;
} 

// Stop if the range is less than 0
if ($kdm_range < 0 || $kdm_range > 255) {
    outputJSON($response + ["error" => "kdm_range outside of range [0-255]"]);
    return;
}

// Stop if kitchen already has a delivery method
if (doesKitchenAlreadyOwnMethod($kdm_owner)) {
    outputJSON($response + ["error" => "kitchen already has a delivery method"]);
    return;
}

// Escape all variables to prevent SQL injection
foreach (["kdm_owner", "kdm_type", "kdm_range"] as $variable) {
    if ($$variable !== null) {
        $$variable = $conn->real_escape_string($$variable);
    }
}

// Can start doing things
$sql = "INSERT INTO $table (kdm_owner, kdm_type, kdm_range) 
        VALUES ('$kdm_owner', '$kdm_type', '$kdm_range')";

if ($conn->query($sql) === TRUE) {
    $response = ["status" => 1]; // success
} else {
    $response = ["error" => $conn->error];
}

$conn->close();
outputJSON($response);
?>