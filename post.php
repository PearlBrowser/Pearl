 <?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require '../../db.php';

$api_token = $_GET['token'] ?? '';
$message = $_GET['message'] ?? '';
$channel = $_GET['channel'] ?? 'general';

if(!$api_token || !$message) { echo json_encode(['status'=>'error','msg'=>'Missing parameters']); exit; }

// Retrieve stored Lumber cookies
session_start();
if(!isset($_SESSION['lumber_sessions'][$api_token])) {
    echo json_encode(['status'=>'error','msg'=>'Invalid API token']); exit;
}
$cookies = $_SESSION['lumber_sessions'][$api_token];

// Convert cookies array to header string
$cookie_header = '';
foreach($cookies as $k=>$v) { $cookie_header .= "$k=$v; "; }

// Send message to Lumber
$ch = curl_init("https://lumber.xo.je/");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'message' => $message,
    'channel' => $channel,
    'action'  => 'send'
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Cookie: $cookie_header"]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
echo $response;
?>