<?php
/**
 * @author Muznious
 */
$webhookContent = "";
$webhook = fopen('php://input', 'rb');
while (!feof($webhook)) {
    $webhookContent .= fread($webhook, 4096);
}
fclose($webhook);

require_once '../vt/functions.php';
require_once '../vt/ws_auth.php';

$wooData = json_decode($webhookContent, true);

/**
 * Getting Contacts by woo_id
 */
$query = "SELECT+%2A+FROM+Contacts+WHERE+woo_id='" . $wooData['id'] . "';";
$params = "sessionName=" . $session . "&operation=query&query=" . $query;
$response = getResponseFromURL($endpointurl . "?" . $params);
$jsonResponse = json_decode($response, true);

$data = array();
if (isset($jsonResponse['result'][0])) {
    $data = $jsonResponse['result'][0];
    $operation = 'update';
} else {
    $operation = 'create';
}
$dataWoo = array(
    'firstname' => $wooData['first_name'],
    'lastname' => $wooData['last_name'],
    'woo_id' => $wooData['id'],
    'email' => $wooData['email'],
    'assigned_user_id' => '19x1',
    'phone' => $wooData['billing']['phone'],
    'mailingstreet' => $wooData['billing']['address_1'],
    'otherstreet' => $wooData['billing']['address_2'],
    'mailingcity' => $wooData['billing']['city'],
    'mailingstate' => $wooData['billing']['state'],
    'mailingzip' => $wooData['billing']['postcode'],
    'mailingcountry' => $wooData['billing']['country'],
    'createdtime' => $wooData['date_created'],
    'modifiedtime' => $wooData['date_modified'],
);
$data = array_merge($data, $dataWoo);
$params = array(
    "sessionName" => $session,
    "operation" => $operation,
    "element" => json_encode($data),
    "elementType" => 'Contacts'
);

$response = getResponseFromURL($endpointurl, $params);
$created = json_decode($response);
//var_dump($response);
//exit;
file_put_contents('customerLog.txt', PHP_EOL . PHP_EOL . date('Y-m-d H:i:s', time()) . PHP_EOL . $webhookContent, FILE_APPEND);
