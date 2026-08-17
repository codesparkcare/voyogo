<?php
$credentials = array(
    "MerchantID" => "300",
    "ApiKey" => "kXAY9yHARK",
    "ClientID" => "bitest",
    "Password" => "staging@1",
    "AgentCode" => "",
    "BrowserKey" => "caecd3cd30225512c1811070dce615c1",
    "Key" => "ef20-925c-4489-bfeb-236c8b406f7e"
);

$ch = curl_init('https://b2bapiutils.benzyinfotech.com/Utils/Signature');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($credentials));
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
// Set short timeout just to see if it connects
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);
if(curl_errno($ch)) {
    echo "cURL Error: " . curl_error($ch) . "\n";
} else {
    echo "Response: " . $response . "\n";
}
curl_close($ch);
