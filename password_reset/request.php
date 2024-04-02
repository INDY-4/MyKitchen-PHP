<?php
include "../utils/functions.php";
$table = "users";
$response = [
    "status" => 0
];

// Stop if not G
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    outputJSON($response + ["error" => "request_method was not POST"]);
    return;
}

$data = $_POST;
$user_email = isset($data["user_email"]) ? $data["user_email"] : null;
if (empty($user_email)) {
    outputJSON($response + ["error" => "user_email is missing"]);
    return;
}

// Check if email exists in the database
if (!email_exists($user_email)) {
    outputJSON($response + ["error" => "email not found"]);
    return;
}

// Generate reset code and insert into password_reset table
$reset_code = generateRandomCode(6); // Function to generate 6 digit random code

if (recentPasswordRequest($user_email)) {
    outputJSON(["status" => 0, "error" => "Please wait 5 minutes between requests"]);
    die(); 
}

deletePasswordResetByEmail($user_email);

$insert_sql = "INSERT INTO password_reset (pwr_user_email, pwr_code) 
                VALUES ('$user_email', '$reset_code')";

if ($conn->query($insert_sql) !== TRUE) {
    $response = ["error" => $conn->error];
}

$result = sendPasswordResetEmail($user_email, $reset_code);
if ($result == 1) {
    $response['status'] = 1;
}

$conn->close();
outputJSON($response);