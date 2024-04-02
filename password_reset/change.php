<?php
include "../utils/functions.php";
$table = "users";
$response = [
    "status" => 0
];

// Stop if not POST request
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    outputJSON($response + ["error" => "request_method was not POST"]);
    return;
}

$data = $_POST;
$user_email = isset($data["user_email"]) ? $data["user_email"] : null;
$reset_code = isset($data["reset_code"]) ? $data["reset_code"] : null;
$password = isset($data["password"]) ? $data["password"] : null;

foreach (array('user_email', 'reset_code', 'password') as $variable) {
    if (empty($$variable)) {
        $response["missing"][] = $variable;
    }
}

// Check if both email and reset code appear in the same row within the last 60 minutes
if (!recentPasswordRequest($user_email)) {
    outputJSON(["status" => 0, "error" => "No recent password reset request for this email"]);
    die(); 
}

if (!passwordResetIsCorrect($user_email, $reset_code)) {
    outputJSON(["status" => 0, "error" => "Password reset request invalid for this email and code"]);
    die(); 
}

// Update the user identified by the email in the users table
$update_sql = "UPDATE $table SET user_pass = '$password' WHERE user_email = '$user_email'";

if ($conn->query($update_sql) !== TRUE) {
    outputJSON($response + ["error" => $conn->error]);
    die();
}

deletePasswordResetByEmail($user_email);

$response['status'] = 1;

$conn->close();
outputJSON($response);