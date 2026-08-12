<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://wrc.benzyinfotech.com/wp-login.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'log' => 'apitest',
    'pwd' => 'BenzyAPI2021*',
    'wp-submit' => 'Log In',
    'redirect_to' => 'https://wrc.benzyinfotech.com/home/b2b-revamp-flight/flight/',
    'testcookie' => '1'
]));
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$response = curl_exec($ch);
curl_close($ch);

file_put_contents('flight_docs.html', $response);
echo "Fetched successfully.";
