<?php
include "../utils/functions.php";
$table = "users";
$response = [
    "status" => 0
];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_name = isset($_POST["user_name"]) ? $_POST["user_name"] : null;
    $user_pass = isset($_POST["user_pass"]) ? $_POST["user_pass"] : null;
    $user_email = isset($_POST["user_email"]) ? $_POST["user_email"] : null;

    foreach (array('user_name', 'user_pass', 'user_email') as $variable) {
        if (empty($$variable)) {
            $response["missing"][] = $variable;
        }
    }

    if (!isset($response["missing"])) {
        $sql = "INSERT INTO $table (user_name, user_pass, user_email) 
            VALUES ('$user_name', '$user_pass', '$user_email')";

        if ($conn->query($sql) === TRUE) {
            $response = ["status" => 1];
        } else {
            $response = ["error" => $conn->error];
        }
    }
} else {
    $response = ["error" => "request_method was not POST"];
}

$conn->close();
echo json_encode($response);
?>