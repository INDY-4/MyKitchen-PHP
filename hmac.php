<?php
include './utils/functions.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    outputJSON($response + ["error" => "request_method was not POST"]);
    return;
}

validateHMAC($_POST);
outputJSON(["status" => 1]);