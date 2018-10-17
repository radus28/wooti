<?php

/**
 * @author Muznious
 */
function customersToWoo($entity) {
    global $adb;
    $en_data = get_object_vars($entity);
    $idParts = explode('x', $en_data['id']);
    $id = $idParts[1];
    $data = $en_data['data'];

    if (empty($data['email'])) {
        return false;
    }
    require_once 'woo28/woo/client.php';

    $params = [
        'email' => $data['email'],
        'first_name' => $data['firstname'],
        'last_name' => $data['lastname'],
        'username' => $data['email'],
        'password' => $data['email'],
        'billing' => [
            'first_name' => $data['firstname'],
            'last_name' => $data['lastname'],
            'company' => '',
            'address_1' => $data['mailingstreet'],
            'address_2' => $data['otherstreet'],
            'city' => $data['mailingcity'],
            'state' => $data['mailingstate'],
            'postcode' => $data['mailingzip'],
            'country' => $data['mailingcountry'],
            'email' => $data['email'],
            'phone' => $data['phone']
        ],
        'shipping' => [
            'first_name' => $data['firstname'],
            'last_name' => $data['lastname'],
            'company' => '',
            'address_1' => $data['mailingstreet'],
            'address_2' => $data['otherstreet'],
            'city' => $data['mailingcity'],
            'state' => $data['mailingstate'],
            'postcode' => $data['mailingzip'],
            'country' => $data['mailingcountry']
        ]
    ];

    try {
        if ($data['woo_id'] == '') {
            $res = $woocommerce->post('customers', $params);
        } else {
            $res = $woocommerce->post('customers/' . $data['woo_id'], $params);
        }
        $adb->pquery('UPDATE vtiger_contactdetails SET woo_id=? WHERE contactid=?', array($res->id, $id)); // Update woo commerce id in crm
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    echo 'Done';
//    die;
}
