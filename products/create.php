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

$data = getJSONPostData();

// If variable not present, set to null
$product_kitchen_id = isset($data["product_kitchen_id"]) ? $data["product_kitchen_id"] : null;
$product_title = isset($data["product_title"]) ? $data["product_title"] : null;
$product_desc = isset($data["product_desc"]) ? $data["product_desc"] : null;
$product_price = isset($data["product_price"]) ? $data["product_price"] : null;
$product_category = isset($data["product_category"]) ? $data["product_category"] : null;
$product_tags = isset($data["product_tags"]) ? $data["product_tags"] : null;
$product_image = isset($_FILES["product_image"]) ? $_FILES["product_image"] : null;

// Loop over variables to see which are null return the missing ones
foreach (array('product_kitchen_id', 'product_title', 'product_price') as $variable) {
    if (empty($$variable)) {
        $response["missing"][] = $variable;
    }
}

// Stop if not all variables entered
if (isset($response["missing"])) {
    outputJSON($response);
    return;
}

// Stop if kitchen does not exist
if (!kitchen_exists($product_kitchen_id)) {
    outputJSON($response + ["error" => "kitchen does not exist"]);
    return;
}

// Stop if the order is worth less than $0
if ($product_price < 0) {
    outputJSON($response + ["error" => "product_price is less than 0"]);
    return;
}

$product_tags = preg_replace("/[^a-zA-Z0-9,]/", "", $product_tags);
$finalProductImage = uploadImage($product_image, $imageRelativeDirectory);

// Escape all variables to prevent SQL injection
foreach (["product_kitchen_id", "product_title", "product_desc", "product_price" ,"product_category", "product_tags", "finalProductImage"] as $variable) {
    if ($$variable !== null) {
        $$variable = $conn->real_escape_string($$variable);
    }
}

// Can start doing things
$sql = "INSERT INTO $table (product_kitchen_id, product_title, product_desc, product_price, product_category, product_tags, product_image_url) 
        VALUES ('$product_kitchen_id', '$product_title', '$product_desc', '$product_price', '$product_category', '$product_tags', '$finalProductImage')";

if ($conn->query($sql) === TRUE) {
    $response = ["status" => 1]; // success
} else {
    $response = ["error" => $conn->error];
}

$conn->close();
outputJSON($response);
?>