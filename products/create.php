<?php
include "../utils/functions.php";
$table = "products";
$response = [
    "status" => 0
];

// Stop if not POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $response = ["error" => "request_method was not POST"]; 
    outputJSON($response);
    return;
}

// If variable not present, set to null
$product_kitchen_id = isset($_POST["product_kitchen_id"]) ? $_POST["product_kitchen_id"] : null;
$product_title = isset($_POST["product_title"]) ? $_POST["product_title"] : null;
$product_desc = isset($_POST["product_desc"]) ? $_POST["product_desc"] : null;
$product_price = isset($_POST["product_price"]) ? $_POST["product_price"] : null;
$product_category = isset($_POST["product_category"]) ? $_POST["product_category"] : null;
$product_tags = isset($_POST["product_tags"]) ? $_POST["product_tags"] : null;
$product_image_url = isset($_POST["product_image_url"]) ? $_POST["product_image_url"] : null;

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
// Can start doing things
$sql = "INSERT INTO $table (product_kitchen_id, product_title, product_desc, product_price, product_category, product_tags, product_image_url) 
        VALUES ('$product_kitchen_id', '$product_title', '$product_desc', '$product_price', '$product_category', '$product_tags', '$product_image_url')";

if ($conn->query($sql) === TRUE) {
    $response = ["status" => 1]; // success
} else {
    $response = ["error" => $conn->error];
}

$conn->close();
outputJSON($response);
?>