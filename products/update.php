<?php
include "../utils/functions.php";
$table = "products";
$response = [
    "status" => 0
];
$imageRelativeDirectory = '../images/products/';

// Stop if not POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    outputJSON($response + ["error" => "request_method was not POST"]);
    return;
}

$data = $_POST;

// If variable not present, set to null
$product_id = isset($data["id"]) ? $data["id"] : null;
$product_title = isset($data["product_title"]) ? $data["product_title"] : null;
$product_desc = isset($data["product_desc"]) ? $data["product_desc"] : null;
$product_price = isset($data["product_price"]) ? $data["product_price"] : null;
$product_category = isset($data["product_category"]) ? $data["product_category"] : null;
$product_tags = isset($data["product_tags"]) ? $data["product_tags"] : null;
$product_image = isset($_FILES["product_image"]) ? $_FILES["product_image"] : null;

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

$finalProductImage = uploadImage($product_image, $imageRelativeDirectory);
$oldImagePath = getProductImage($product_id);

// Escape all variables to prevent SQL injection
foreach (["product_id", "product_title", "product_desc", "product_price" ,"product_category", "product_tags", "finalProductImage"] as $variable) {
    if ($$variable !== null) {
        $$variable = $conn->real_escape_string($$variable);
    }
}

// Build SET string
$set = "";
if (!empty($product_title)) {
    $set .= (!empty($set) ? " , " : "") . "product_title = '$product_title'";
}
if (!empty($product_desc)) {
    $set .= (!empty($set) ? " , " : "") . "product_desc = '$product_desc'";
}
if (!empty($product_price)) {
    // Stop if the order is worth less than $0
    if ($product_price < 0) {
        outputJSON($response + ["error" => "product_price is less than 0"]);
        return;
    }
    $set .= (!empty($set) ? " , " : "") . "product_price = '$product_price'";
}
if (!empty($product_category)) {
    $set .= (!empty($set) ? " , " : "") . "product_category = '$product_category'";
}
if (!empty($product_tags)) {
    $product_tags = preg_replace("/[^a-zA-Z0-9,]/", "", $product_tags);
    $set .= (!empty($set) ? " , " : "") . "product_tags = '$product_tags'";
}
if (!empty($finalProductImage)) {
    $set .= (!empty($set) ? " , " : "") . "product_image_url = '$finalProductImage'";
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
            product_id = '$product_id'";

if ($conn->query($sql) === TRUE) {
    $response = ["status" => 1]; // success
    deleteImage($oldImagePath);
} else {
    $response = ["error" => $conn->error];
}

$conn->close();
outputJSON($response);
?>