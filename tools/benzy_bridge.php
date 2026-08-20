<?php
/**
 * Benzy Infotech API Bridge / Proxy Script for Whitelisted Server (195.201.164.20)
 * 
 * Upload this file to your Plesk / Live server at httpdocs/benzy_bridge.php.
 * It forwards incoming JSON API requests from your local development environment
 * to Benzy Infotech from your whitelisted Static IP (195.201.164.20) and returns
 * the live response.
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Receive incoming payload
$rawInput = file_get_contents('php://input');
$request = json_decode($rawInput, true);

if (empty($request) || empty($request['target_url'])) {
    // Health check & IP verification
    echo json_encode(array(
        'status' => 'online',
        'server_ip' => $_SERVER['SERVER_ADDR'] ?? '195.201.164.20',
        'message' => 'Benzy Infotech Static IP Bridge is active and ready on 195.201.164.20.'
    ));
    exit;
}

$targetUrl = $request['target_url'];
$method = strtoupper($request['method'] ?? 'POST');
$payload = $request['payload'] ?? null;
$token = $request['token'] ?? null;

// Ensure target is only Benzy Infotech API domains for security
$allowedHosts = array(
    'b2bapiutils.benzyinfotech.com',
    'b2bapiflights.benzyinfotech.com',
    'travelportalapi.benzyinfotech.com',
    'b2bapihotels.benzyinfotech.com'
);

$parsedHost = parse_url($targetUrl, PHP_URL_HOST);
if (!in_array($parsedHost, $allowedHosts)) {
    http_response_code(403);
    echo json_encode(array('error' => 'Target domain not allowed'));
    exit;
}

$headers = array('Content-Type: application/json');
if (!empty($token)) {
    $headers[] = 'Authorization: Bearer ' . $token;
}

$ch = curl_init($targetUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
if (!empty($payload)) {
    curl_setopt($ch, CURLOPT_POSTFIELDS, is_string($payload) ? $payload : json_encode($payload));
}
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

http_response_code($httpCode ?: 200);
if (!empty($response)) {
    echo $response;
} else {
    echo json_encode(array('error' => $error ?: 'Empty response from Benzy Infotech Gateway', 'http_code' => $httpCode));
}
