<?php

// Initialize variables
$app_id = '1017282308444421';
$secret = '76af20048e9a99473885e9a70538d2ed';
$version = 'v1.1'; // 'v1.1' for example

// Method to send Get request to url
function doCurl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = json_decode(curl_exec($ch), true);
    curl_close($ch);
    return $data;
}

// Exchange authorization code for access token
$token_exchange_url = 'https://graph.accountkit.com/'.$version.'/access_token?'.
    'grant_type=authorization_code'.
    '&code='.$_POST['code'].
    "&access_token=AA|$app_id|$secret";
$data = doCurl($token_exchange_url);
var_dump($data);
$user_id = $data['id'];
$user_access_token = $data['access_token'];
$refresh_interval = $data['token_refresh_interval_sec'];

// Get Account Kit information
$me_endpoint_url = 'https://graph.accountkit.com/'.$version.'/me?'.
    'access_token='.$user_access_token;
$data = doCurl($me_endpoint_url);
var_dump($data);
$phone = isset($data['phone']) ? $data['phone']['number'] : '';
$email = isset($data['email']) ? $data['email']['address'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Account Kit PHP App</title>
</head>
<body>
<div>Code: <?php echo $_POST['code']; ?></div>
<div>User ID: <?php echo $user_id; ?></div>
<div>Phone Number: <?php echo $phone; ?></div>
<div>Email: <?php echo $email; ?></div>
<div>Access Token: <?php echo $user_access_token; ?></div>
<div>Refresh Interval: <?php echo $refresh_interval; ?></div>
</body>
</html>
