<?php
require 'config.php';

// Use the API key
echo "Your API key is: " . $apiKey;

// Example of using the API key in a request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.example.com/data");
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: Bearer ' . $apiKey
));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

echo $response;
?>
