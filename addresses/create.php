<?php
include "../utils/functions.php";
$table = "addresses";
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
$address_owner = isset($data["address_owner"]) ? $data["address_owner"] : null;
$address_type = isset($data["address_type"]) ? $data["address_type"] : null;
$address_line1 = isset($data["address_line1"]) ? $data["address_line1"] : null;
$address_line2 = isset($data["address_line2"]) ? $data["address_line2"] : null;
$address_city = isset($data["address_city"]) ? $data["address_city"] : null;
$address_state = isset($data["address_state"]) ? $data["address_state"] : null;
$address_zip = isset($data["address_zip"]) ? $data["address_zip"] : null;
$address_phone = isset($data["address_phone"]) ? $data["address_phone"] : null;

// Loop over variables to see which are null return the missing ones
foreach (array('address_owner', 'address_type', 'address_line1', 'address_city', 'address_state', 'address_zip') as $variable) {
    if (empty($$variable)) {
        $response["missing"][] = $variable;
    }
}

// Stop if not all variables entered
if (isset($response["missing"])) {
    outputJSON($response);
    return;
}

// Stop if address_type is not one of the preset options
$address_type_options = ["user", "kitchen"];
if (!in_array($address_type, $address_type_options)) {
    outputJSON($response + ["error" => "address_type not in [user, kitchen]"]);
    return;
}

if ($address_type == "user") {
    // Stop if the user doesn't exist
    if (!user_exists($address_owner)) {
        outputJSON($response + ["error" => "user does not exist"]);
        return;
    }
    if (!preg_match("/^\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}$/", $address_phone)) {
        outputJSON($response + ["error" => "user's phone number is invalid"]);
        return;
    }
} else {
    // Stop if the kitchen doesn't exist
    if (!kitchen_exists($address_owner)) {
        outputJSON($response + ["error" => "kitchen does not exist"]);
        return;
    } 
}

// Escape all variables to prevent SQL injection
foreach (["address_owner", "address_type", "address_line1", "address_line2", "address_city", "address_state", "address_zip", "address_phone"] as $variable) {
    if ($$variable !== null) {
        $$variable = $conn->real_escape_string($$variable);
    }
}

// Can start doing things
$sql = "INSERT INTO $table (address_owner, address_type, address_line1, address_line2, address_city, address_state, address_zip, address_phone) 
        VALUES ('$address_owner', '$address_type', '$address_line1', '$address_line2', '$address_city', '$address_state', '$address_zip', '$address_phone')";

if ($conn->query($sql) === TRUE) {
    $response = ["status" => 1]; // success
} else {
    $response = ["error" => $conn->error];
}

$conn->close();
outputJSON($response);
?>