<?php
include "../utils/functions.php";
$table = "kitchens";
$response = [
    "status" => 0
];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $kitchen_owner_id = isset($_POST["kitchen_owner_id"]) ? $_POST["kitchen_owner_id"] : null;
    $kitchen_name = isset($_POST["kitchen_name"]) ? $_POST["kitchen_name"] : null;
    $kitchen_working_hours = isset($_POST["kitchen_working_hours"]) ? $_POST["kitchen_working_hours"] : null;
    $kitchen_uses_cash = isset($_POST["kitchen_uses_cash"]) ? toBoolean($_POST["kitchen_uses_cash"]) : null;
    $kitchen_uses_card = isset($_POST["kitchen_uses_card"]) ? toBoolean($_POST["kitchen_uses_card"]) : null;

    foreach (array('kitchen_owner_id', 'kitchen_name', 'kitchen_working_hours', 'kitchen_uses_cash', 'kitchen_uses_card') as $variable) {
        if (empty($$variable)) {
            $response["missing"][] = $variable;
        }
    }

    if (!isset($response["missing"])) {
        if (!user_exists($kitchen_owner_id)) {
            $response = ["error" => "user does not exist"];
        } else {
            $sql = "INSERT INTO $table (kitchen_owner, kitchen_name, kitchen_working_hours, kitchen_uses_cash, kitchen_uses_card) 
                VALUES ('$kitchen_owner_id', '$kitchen_name', '$kitchen_working_hours', '$kitchen_uses_cash', '$kitchen_uses_card')";

            if ($conn->query($sql) === TRUE) {
                $response = ["status" => 1];
            } else {
                $response = ["error" => $conn->error];
            }
        }
    }

    $conn->close();
} else {
    $response = ["error" => "request_method was not POST"];
}

echo json_encode($response);
?>