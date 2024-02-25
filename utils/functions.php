<?php 
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');

include "conn.php";
$return = [];
$api_url = "https://indy-api.zoty.us/";

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

function order_exists($order_id) {
    // Check the database if a user exists
    global $conn;
    $order_id = $conn->real_escape_string($order_id);

    $sql = "SELECT COUNT(*) AS order_exists FROM orders WHERE order_id = '$order_id'";
    $result = $conn->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();
        $order_exists = $row['order_exists'];
        return $order_exists;
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

    if ($lowercaseInput === "yes") {
        return 1;
    }
    return 0;
}

function outputJSON($input) {
    // Output $input as beautiful JSON
    echo(json_encode($input));
}

function getJSONPostData() {
    $jsonData = file_get_contents('php://input');
    return json_decode($jsonData, true);
}

// Function to generate a unique filename
function generateUniqueFilename($uploadDir, $filename) {
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    do {
        // Generate random 64-character string
        $randomString = bin2hex(random_bytes(32));
        // Append extension to the random string
        $uniqueFilename = $randomString . '.' . $extension;
        // Check if the file with this name already exists
        $filePath = $uploadDir . $uniqueFilename;
    } while (file_exists($filePath));
    
    return $filePath;
}

function uploadImage($image, $relativeDirectory) {
    global $api_url;

    $finalImage = "";
    if ($image != null && $image["error"] == 0) {
        $fileName = basename($image['name']);
        $imagePath = generateUniqueFilename($relativeDirectory, $fileName);
        $fileType = pathinfo($imagePath, PATHINFO_EXTENSION);
    
        // Check if the file is an image
        $allowedTypes = array('jpg', 'jpeg', 'png');
        if (!in_array($fileType, $allowedTypes)) {
            outputJSON($response + ["error" => "image filetype not in [jpg, jpeg, png]"]);
            return;
        }
        // Move the uploaded file to the specified directory
        if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
            outputJSON($response + ["error" => "couldnt save image to disk"]);
            return;
        }
        // If no errors, change finalProductImage to be the new location of the image
        $finalImage = $imagePath;
    }
    
    return $api_url . str_replace('../', '', $finalImage);
}

function getProductImage($product_id) {
    global $conn;
    $sql = "SELECT product_image_url FROM products WHERE product_id = '$product_id'";
    $result = $conn->query($sql);
    if (!$result) {
        outputJSON([
            "status" => 0,
            "error" => $conn->error
        ]);
        return;
    }
    $row = $result->fetch_assoc();
    return $row['product_image_url'];
}

function deleteImage($path) {
    $urlParts = parse_url($path);
    $relativePath = '../' . ltrim($urlParts['path'], '/');
    if (file_exists($relativePath)) {
        unlink($relativePath);
    }
}