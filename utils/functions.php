<?php
header('Access-Control-Allow-Origin: *'); // Allow requests from any origin
header('Content-Type: application/json');
header('Cache-Control: no-cache');

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

function email_exists($email) {
    // Check the database if a user exists
    global $conn;
    $email_addr = $conn->real_escape_string($email);

    $sql = "SELECT COUNT(*) AS email_exists FROM users WHERE user_email = '$email_addr'";
    $result = $conn->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();
        $email_exists = $row['email_exists'];
        return $email_exists;
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

function generateRandomCode($length = 6) {
    $characters = '0123456789';
    $code = '';
    $max = strlen($characters) - 1;

    for ($i = 0; $i < $length; $i++) {
        $code .= $characters[rand(0, $max)];
    }

    return $code;
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
        $allowedTypes = ['jpg', 'jpeg', 'png'];
        if (!in_array($fileType, $allowedTypes)) {
            outputJSON([
                "status" => 0,
                "error" => "image filetype not in [jpg, jpeg, png]"
            ]);
            die;
        }
        // Move the uploaded file to the specified directory
        if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
            outputJSON([
                "status" => 0,
                "error" => "couldnt save image to disk"
            ]);
            die;
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

function sendPasswordResetEmail($to, $code) {
    $ch = curl_init();

    $email_data = [
        'personalizations' => [
            [
                'to' => [
                    [
                        'email' => $to
                    ]
                ],
                'subject' => 'MyKitchen: Password Reset Code'
            ]
        ],
        'from' => [
            'email' => 'noreply@indy-mail.zoty.us'
        ],
        'content' => [
            [
                'type' => 'text/plain',
                'value' => 'The code to reset your password is: ' . $code
            ]
        ]
    ];

    // Encode the email data as JSON
    $email_json = json_encode($email_data);

    // Set cURL options
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.sendgrid.com/v3/mail/send');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $email_json);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer API_KEY_HERE',
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute cURL request
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        outputJSON(["status" => 0, "error" => curl_error($ch)]);
        die();
    }
    curl_close($ch);

    return 1;
}

function recentPasswordRequest($email) {
    global $conn;

    $check_sql = "SELECT IF(pwr_created_date >= DATE_SUB(NOW(), INTERVAL 5 MINUTE), 'Within last 5 minutes', 'Not within last 5 minutes') 
                  AS expiration_status FROM password_reset WHERE pwr_user_email = '$email' 
                  ORDER BY pwr_created_date DESC LIMIT 1";
    $result = $conn->query($check_sql);
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row["expiration_status"] == "Within last 5 minutes") {
            return true;
        }
    }

    return false;
}

function passwordResetIsCorrect($email, $code) {
    global $conn;

    $check_sql = "SELECT IF(pwr_created_date >= DATE_SUB(NOW(), INTERVAL 5 MINUTE), 'Within last 5 minutes', 'Not within last 5 minutes') 
                  AS expiration_status FROM password_reset WHERE pwr_user_email = '$email' AND pwr_code = '$code'
                  ORDER BY pwr_created_date DESC LIMIT 1";
    $result = $conn->query($check_sql);
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row["expiration_status"] == "Within last 5 minutes") {
            return true;
        }
    }

    return false;
}

function deletePasswordResetByEmail($email) {
    global $conn;

    $delete_sql = "DELETE FROM password_reset WHERE pwr_user_email = '$email'";
    
    if ($conn->query($delete_sql) != TRUE) {
        outputJSON(["status" => 0, "error" => $conn->error]);
        die();
    } 
    
    return true;
}

function validateHMAC($requestData) {
    if (validateRequest($requestData)) {
        return true;
    }
    http_response_code(403);
    outputJSON(["status" => 0, "error" => "HMAC is incorrect"]);
    die();
}

function generateHMAC($data, $key) {
    return hash_hmac('sha256', $data, $key);
}

function validateRequest($requestData) {
    $secret = 'mykitchen_api';
    $timestamp = $requestData['timestamp'];
    $receivedHMAC = $requestData['hmac'];
    unset($requestData['timestamp'], $requestData['hmac']); // Remove timestamp and hmac from data
    $data = http_build_query($requestData, '', '&');

    $currentTime = time();
    $requestTime = intval($timestamp);
    $timeDifference = $currentTime - $requestTime;

    // Allow for a small negative time difference (up to 15 seconds) to account for potential delays
    if ($timeDifference > 0 || $timeDifference < -15) {
        return false; // Request received more than 15 seconds after it was sent or in the future
    }

    $calculatedHMAC = generateHMAC($timestamp . $data, $secret);

    return hash_equals($calculatedHMAC, $receivedHMAC);
}