<?php 
include "conn.php";
$return = [];

function user_exists($user_id) {
    // Check the database if a user exists
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

function username_exists($username) {
    // Check the database if a user exists
    global $conn;
    $username = $conn->real_escape_string($username);

    $sql = "SELECT COUNT(*) AS user_exists FROM users WHERE user_name = '$username'";
    $result = $conn->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();
        $user_exists = $row['user_exists'];
        return $user_exists;
    } else {
        return FALSE;
    }
}

function kitchen_exists($kitchen_id) {
    // Check the database if a user exists
    global $conn;
    $kitchen_id = $conn->real_escape_string($kitchen_id);

    $sql = "SELECT COUNT(*) AS kitchen_exists FROM kitchens WHERE kitchen_id = '$kitchen_id'";
    $result = $conn->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();
        $kitchen_exists = $row['kitchen_exists'];
        return $kitchen_exists;
    } else {
        return FALSE;
    }
}

function doesKitchenAlreadyOwnMethod($kitchen_id) {
    // Check the database if a user exists
    global $conn;
    $kitchen_id = $conn->real_escape_string($kitchen_id);

    $sql = "SELECT COUNT(*) AS kitchen_exists FROM kitchen_delivery_methods WHERE kdm_owner = '$kitchen_id'";
    $result = $conn->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();
        $kitchen_exists = $row['kitchen_exists'];
        return $kitchen_exists;
    } else {
        return FALSE;
    }
}

function doesUserAlreadyOwnKitchen($user_id) {
    // Check the database if a user exists
    global $conn;
    $user_id = $conn->real_escape_string($user_id);

    $sql = "SELECT COUNT(*) AS kitchen_exists FROM kitchens WHERE kitchen_owner = '$user_id'";
    $result = $conn->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();
        $kitchen_exists = $row['kitchen_exists'];
        return $kitchen_exists;
    } else {
        return FALSE;
    }
}

function toBoolean($input) {
    // Convert "true/false" to 1/0 bit
    $lowercaseInput = strtolower($input);

    if ($lowercaseInput === "true") {
        return 1;
    }
    return 0;
}

function outputJSON($input) {
    // Output $input as beautiful JSON
    echo(json_encode($input));
}