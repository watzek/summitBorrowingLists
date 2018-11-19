<?php

require_once 'vendor/autoload.php';
$client = new Google_Client();
$client->setAuthConfig('clientcreds.json');



$client->authenticate($_GET['code']);

/*
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    var_dump($token);
}
*/
//$_SESSION['access_token'] = $client->getAccessToken();

//$_SESSION['access_token'] = $client->getAccessToken();
//$token_data = $client->verifyIdToken()->getAttributes();

var_dump($token_data);

//var_dump($client);

//$authUrl = $client->createAuthUrl();
//echo $authUrl;




 ?>
