<?php
// lumber-api.xo.je/send.php

$name = $_GET['name'] ?? '';
$message = $_GET['message'] ?? '';
$channel = $_GET['channel'] ?? 'general';
$action = $_GET['action'] ?? 'send';

if (!$name || !$message) {
    http_response_code(400);
    echo json_encode(["error"=>"Name and message required"]);
    exit;
}

// Build POST for Lumber
$postData = http_build_query([
    "name" => $name,
    "message" => $message,
    "channel" => $channel,
    "action" => $action
]);

$ch = curl_init("https://lumber.xo.je/");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/x-www-form-urlencoded",
    "Accept: */*"
]);
curl_setopt($ch, CURLOPT_COOKIEJAR, "cookies.txt");
curl_setopt($ch, CURLOPT_COOKIEFILE, "cookies.txt");

$response = curl_exec($ch);
curl_close($ch);

echo $response;
