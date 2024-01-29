<?php 
include "conn.php";
$return = [];

function user_exists($user_id) {
    global $conn;
    $user_id = $conn->real_escape_string($user_id);

    $sql = "SELECT COUNT(*) AS user_exists FROM users WHERE user_id = '$user_id'";
    $result = $conn->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();
        $user_exists = $row['user_exists'];
        return $user_exists;
    } else {
        return FALSE;
    }
}

function toBoolean($input) {
    $lowercaseInput = strtolower($input);

    if ($lowercaseInput === "true") {
        return 1;
    }
    return 0;
}

function outputJSON($input) {
    echo(json_encode($input));
}