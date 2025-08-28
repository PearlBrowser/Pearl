<?php
// Get user input
$name = $_GET['name'] ?? '';
$message = $_GET['message'] ?? '';
$channel = $_GET['channel'] ?? 'general';
$action = $_GET['action'] ?? 'send';

// Get user's IP
$user_ip = $_SERVER['REMOTE_ADDR'];

// Get country code from ipapi
$country = @file_get_contents("https://ipapi.co/$user_ip/country/") ?: 'un';

// Prepare POST data for Lumber
$postData = http_build_query([
    "name" => $name,
    "message" => $message,
    "channel" => $channel,
    "action" => $action,
    "country" => $country
]);

// Send to Lumber
$ch = curl_init("https://lumber.xo.je/");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/x-www-form-urlencoded",
    "Accept: */*"
]);

$response = curl_exec($ch);
curl_close($ch);

// Return Lumber response
header("Content-Type: application/json");
echo $response;
