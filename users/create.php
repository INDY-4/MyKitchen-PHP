<?php
include "../utils/functions.php";
$table = "users";
$response = [
    "status" => 0
];

// Stop if not POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    outputJSON($response + ["error" => "request_method was not POST"]);
    return;
}

// If variable not present, set to null
$user_name = isset($_POST["user_name"]) ? $_POST["user_name"] : null;
$user_pass = isset($_POST["user_pass"]) ? $_POST["user_pass"] : null;
$user_email = isset($_POST["user_email"]) ? $_POST["user_email"] : null;

// Loop over variables to see which are null return the missing ones
foreach (array('user_name', 'user_pass', 'user_email') as $variable) {
    if (empty($$variable)) {
        $response["missing"][] = $variable;
    }
}

// Stop if not all variables entered
if (isset($response["missing"])) {
    outputJSON($response);
    return;
}

// Can start doing things
$sql = "INSERT INTO $table (user_name, user_pass, user_email) 
        VALUES ('$user_name', '$user_pass', '$user_email')";

if ($conn->query($sql) === TRUE) {
    $response = ["status" => 1]; // success
} else {
    $response = ["error" => $conn->error];
}

$conn->close();
outputJSON($response);
?>