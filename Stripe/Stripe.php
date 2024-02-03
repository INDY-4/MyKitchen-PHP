<?php
include "../utils/functions.php";

function stripe_request($endpoint, $data) {
    $return = "";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Basic ' . getStripeKey(),
        'Content-Type: application/x-www-form-urlencoded',
    ]);
    
    // Execute the cURL request
    $response = curl_exec($ch);

    // Check for cURL errors or handle the response as needed
    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
    } else {
        // Process the response (this is a basic example, adjust as needed)
        $responseData = json_decode($response, true);
        $return = $responseData;
    }

    // Close cURL session
    curl_close($ch);
    return $return;
}

function getCardType($cardNumber) {
    $firstDigit = substr($cardNumber, 0, 1);
    switch ($firstDigit) {
        case '0':
            return 'tok_visa';
        case '1':
            return 'tok_mastercard';
        case '2':
            return 'tok_mastercard_debit'; // MasterCard may start with 1 or 2
        case '3':
            return 'tok_amex';
        case '4':
            return 'tok_visa_debit';
        case '5':
            return 'tok_mastercard_prepaid';
        case '6':
            return 'tok_discover';
        case '7':
            return 'tok_unionpay';
        case '8':
            return 'tok_jcb';
        case '9':
            return 'tok_visa_chargeDeclined';
        default:
            return 'tok_amex';
    }
}

function getStripeKey() {
    // Read Stripe key file from above root directory
    $stripeFilePath = '../../stripe.txt';
    if (file_exists($stripeFilePath)) {
        $stripeCode = file_get_contents($stripeFilePath);
        $authToken = base64_encode($stripeCode . ':');
        return $authToken;
    } else {
        die('Stripe credentials not provided');
    }
}