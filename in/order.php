<?php

$webhookContent = "";
$webhook = fopen('php://input', 'rb');
while (!feof($webhook)) {
    $webhookContent .= fread($webhook, 4096);
}
fclose($webhook);
$wooOrder = json_decode($webhookContent,true);

require_once '../vt/functions.php';
require_once '../vt/ws_auth.php';

//$wooOrder = json_decode('{"id":69,"parent_id":0,"number":"66","order_key":"wc_order_5ace047ea29f6","created_via":"","version":"3.3.4","status":"cancelled","currency":"GBP","date_created":"2018-04-11T12:48:38","date_created_gmt":"2018-04-11T12:48:38","date_modified":"2018-04-11T14:26:56","date_modified_gmt":"2018-04-11T14:26:56","discount_total":"0.00","discount_tax":"0.00","shipping_total":"0.00","shipping_tax":"0.00","cart_tax":"0.00","total":"90.00","total_tax":"0.00","prices_include_tax":false,"customer_id":2,"customer_ip_address":"","customer_user_agent":"","customer_note":"","billing":{"first_name":"Sutharsan","last_name":"Jeganathan","company":"","address_1":"223","address_2":"sde","city":"ccc","state":"","postcode":"23232","country":"AU","email":"sutharsan@suje.lk","phone":"416555785"},"shipping":{"first_name":"Sutharsan","last_name":"Jeganathan","company":"","address_1":"223","address_2":"sde","city":"ccc","state":"","postcode":"23232","country":"AU"},"payment_method":"","payment_method_title":"","transaction_id":"","date_paid":null,"date_paid_gmt":null,"date_completed":null,"date_completed_gmt":null,"cart_hash":"","meta_data":[],"line_items":[{"id":2,"name":"CR67","product_id":64,"variation_id":0,"quantity":1,"tax_class":"","subtotal":"21.00","subtotal_tax":"0.00","total":"21.00","total_tax":"0.00","taxes":[],"meta_data":[],"sku":"","price":21},{"id":3,"name":"CO2","product_id":57,"variation_id":0,"quantity":1,"tax_class":"","subtotal":"51.00","subtotal_tax":"0.00","total":"51.00","total_tax":"0.00","taxes":[],"meta_data":[],"sku":"","price":51},{"id":4,"name":"Tshirt","product_id":36,"variation_id":0,"quantity":1,"tax_class":"","subtotal":"18.00","subtotal_tax":"0.00","total":"18.00","total_tax":"0.00","taxes":[],"meta_data":[],"sku":"","price":18}],"tax_lines":[],"shipping_lines":[],"fee_lines":[],"coupon_lines":[],"refunds":[]}', true);

$status_map = array(''=>'Created','pending'=>'Created','processing'=>'Created','completed'=>'Completed','cancelled'=>'Cancelled');

echo '<pre>';

/**
 * Getting Client by email
 */
$query = "SELECT+%2A+FROM+Accounts+WHERE+email1='" . $wooOrder['billing']['email'] . "'+or+email2='" . $wooOrder['billing']['email'] . "';";
$params = "sessionName=" . $session . "&operation=query&query=" . $query;
$response = getResponseFromURL($endpointurl . "?" . $params);
$jsonResponse = json_decode($response, true);
if (!isset($jsonResponse['result']) || !isset($jsonResponse['result'][0]['id'])) {
    file_put_contents('payload.txt', PHP_EOL . PHP_EOL . date('Y-m-d H:i:s', time()) . ' :: No Client found by email-'.$response, FILE_APPEND);
    exit;
}
$clientId = $jsonResponse['result'][0]['id'];

/**
 * Product details
 */
$lineItems = array();
foreach ($wooOrder['line_items'] as $key => $item) {
    $query = "SELECT+%2A+FROM+Products+WHERE+woo_id='" . $item['product_id'] . "';";
    $params = "sessionName=" . $session . "&operation=query&query=" . $query;
    $response = getResponseFromURL($endpointurl . "?" . $params);
    $jsonResponse = json_decode($response, true);
    if (isset($jsonResponse['result'][0]['id'])) {
        $lineItems[] = array('productid' => $jsonResponse['result'][0]['id'], 'listprice' => $item['price'],
            'quantity' => $item['quantity'], 'tax1' => '0', 'discount_percent' => '', 'discount_amount' => 0);
          }
    
}

if (count($lineItems) == 0) {
    file_put_contents('payload.txt', PHP_EOL . PHP_EOL . date('Y-m-d H:i:s', time()) . ' :: No Product found in crm', FILE_APPEND);
    exit;
}

/**
 * Getting SO by woo_id
 */
$query = "SELECT+%2A+FROM+SalesOrder+WHERE+woo_id='" . $wooOrder['id'] . "';";
$params = "sessionName=" . $session . "&operation=query&query=" . $query;
$response = getResponseFromURL($endpointurl . "?" . $params);
$jsonResponse = json_decode($response, true);
// if SO exists in crm already by woo_id
if (isset($jsonResponse['result'][0]['id'])) {
//    var_dump($jsonResponse);
    $so_retreieved = getResponseFromURL("$endpointurl?operation=retrieve&sessionName=" . $session . "&id=" . $jsonResponse['result'][0]['id']);
    $so_data = json_decode($so_retreieved, true);
//    var_dump($so_data);
    $data = $so_data['result'];
    $data['invoicestatus']='Auto Created';
    $data['productid'] = $lineItems[0]['productid'];
    $operation = 'update';
} else {

    $data = array(
        'subject' => $wooOrder['order_key'],
        'woo_id' => $wooOrder['id'],
        'account_id' => $clientId,
        'contact_id' =>'',
        'invoicestatus' => 'Auto Created',
        'assigned_user_id' => '19x1',
        'currency_id' => '21x1',
        'hdnTaxType' => 'group',
        'conversion_rate' => 1,
        'productid' => $lineItems[0]['productid'],
        'hdnDiscountAmount' => '0',
        'LineItems' => $lineItems,
        "ship_street" => '-',
        "bill_street" => '-',
        'balance' => 0,
        'hdnS_H_Amount' => 0,
        'hdnGrandTotal' => 0,
        'received' => 0
    );
    $operation = 'create';
}
$data['sostatus']= $status_map[$wooOrder['status']];
$params = array("sessionName" => $session, "operation" => $operation,
    "element" => json_encode($data), "elementType" => 'SalesOrder');
$response = getResponseFromURL($endpointurl, $params);
$created = json_decode($response);
//var_dump($response);
//exit;

file_put_contents('payload.txt', PHP_EOL . PHP_EOL . date('Y-m-d H:i:s', time()) . PHP_EOL . $webhookContent, FILE_APPEND);
