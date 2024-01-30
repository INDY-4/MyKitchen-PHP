<?php
include "../utils/functions.php";
$table = "users";
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
$user_id = isset($_POST["id"]) ? $_POST["id"] : null;
$user_pass = isset($_POST["user_pass"]) ? $_POST["user_pass"] : null;
$user_email = isset($_POST["user_email"]) ? $_POST["user_email"] : null;
$user_banner_url = isset($_POST["user_banner_url"]) ? $_POST["user_banner_url"] : null;

// Loop over variables to see which are null, return the missing ones
foreach (array('user_id') as $variable) {
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
foreach (["user_id", "user_pass", "user_email", "user_banner_url"] as $variable) {
    $$variable = $conn->real_escape_string($$variable);
}

// Build SET string
$set = "";
if (!empty($user_pass)) {
    $set .= (!empty($set) ? " , " : "") . "user_pass = '$user_pass'";
}
if (!empty($user_email)) {
    $set .= (!empty($set) ? " , " : "") . "user_email = '$user_email'";
}
if (!empty($user_banner_url)) {
    $set .= (!empty($set) ? " , " : "") . "user_banner_url = '$user_banner_url'";
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
            user_id = '$user_id'";

if ($conn->query($sql) === TRUE) {
    $response = ["status" => 1]; // success
} else {
    $response = ["error" => $conn->error];
}

$conn->close();
outputJSON($response);
?>