<?php
include "../utils/functions.php";
$table = "products";
$response = [
    "status" => 0
];
$imagePath = "";

// Stop if not POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    outputJSON($response + ["error" => "request_method was not POST"]);
    return;
}

$data = $_POST;

// If variable not present, set to null
$product_id = isset($data["id"]) ? $data["id"] : null;

// Loop over variables to see which are null, return the missing ones
foreach (array('product_id') as $variable) {
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
foreach (["product_id"] as $variable) {
    if ($$variable !== null) {
        $$variable = $conn->real_escape_string($$variable);
    }
}

// Before deleting, we need to find the product to retrieve its image path
$imagePath = getProductImage($product_id);

// Now delete the product, and if it gets deleted, remove its image from disk
$sql = "DELETE FROM $table WHERE product_id = '$product_id'";
if ($conn->query($sql) === TRUE) {
    $response = ["status" => 1]; // success
    deleteImage($imagePath);
} else {
    $response = ["error" => $conn->error];
}

$conn->close();
outputJSON($response);
?>