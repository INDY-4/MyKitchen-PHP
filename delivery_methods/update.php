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

$data = getJSONPostData();

// If variable not present, set to null
$kdm_id = isset($data["id"]) ? $data["id"] : null;
$kdm_type = isset($data["kdm_type"]) ? $data["kdm_type"] : null;
$kdm_range = isset($data["kdm_range"]) ? $data["kdm_range"] : null;

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
foreach (["kdm_id", "kdm_type", "kdm_range"] as $variable) {
    if ($$variable !== null) {
        $$variable = $conn->real_escape_string($$variable);
    }
}

$set = "";
if (!empty($kdm_type)) {
    $set .= (!empty($set) ? " , " : "") . "kdm_type = '$kdm_type'";
}
if (!empty($kdm_range)) {
    $set .= (!empty($set) ? " , " : "") . "kdm_range = '$kdm_range'";
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
            kdm_id = '$kdm_id'";

if ($conn->query($sql) === TRUE) {
    $response = ["status" => 1]; // success
} else {
    $response = ["error" => $conn->error];
}

$conn->close();
outputJSON($response);
?>