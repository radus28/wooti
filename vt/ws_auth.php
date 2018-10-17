<?php

/**
 * Change your CRM instance URL and accessKey
 */
$username = 'admin'; // You may use any user
$accessKey = 'tmx6yuW7ABAs1MFZ';//YuC6SLwRzU10TgJh'; // Obtain from CRM > My Preference > Access Key  (Use admin user to get all privileges
$vtigerUrl = 'http://localhost/crm/v71dev'; //CRM URL

$endpointurl = $vtigerUrl.'/webservice.php'; // You crm url prepended with webservice.php

/**
 * Getting challenge token - Challenge response authentication to avoid eavesdropping
 */
$ws_url = "{$endpointurl}?operation=getchallenge&username={$username}";
$response = getResponseFromURL($ws_url);
$resobj = json_decode($response);
$token = $resobj->result->token;

/**
 * Authentication (Login)
 */
$preparedkey = md5($token . $accessKey);
$postlist = array(
    'operation' => 'login',
    'username' => $username,
    'accessKey' => $preparedkey
);
$loginresponse = getResponseFromURL($endpointurl, $postlist);
$sesobj = json_decode($loginresponse);
$session = $sesobj->result->sessionName;
$userId = $sesobj->result->userId;