<?php

function productsToWoo($entity) {
    global $adb;
    $en_data = get_object_vars($entity);
    $idParts = explode('x', $en_data['id']);
    $id = $idParts[1];
    $data = $en_data['data'];

    require_once 'woo28/woo/client.php';
    
    /**
     * @author sutharsan@nulosoft.com.au
     * Refer https://woocommerce.github.io/woocommerce-rest-api-docs/?php#update-a-product
     * Add images from CRM Storage by obttaining image URL from vtiger_seattachmentsrel  and vtiger_attachment tables
     */
    $params = [
    'name' => $data['productname'],
    'type' => 'simple',
    'regular_price' => $data['unit_price'],
    'description' => $data['description'],
    'short_description' => $data['description'],
    'categories' => [
        [
            'id' => 9
        ],
        [
            'id' => 14
        ]
    ],
    'images' => [
        [
            'src' => 'http://demo.woothemes.com/woocommerce/wp-content/uploads/sites/56/2013/06/T_2_front.jpg',
            'position' => 0
        ],
        [
            'src' => 'http://demo.woothemes.com/woocommerce/wp-content/uploads/sites/56/2013/06/T_2_back.jpg',
            'position' => 1
        ]
    ]
];

    try {
        if($data['woo_id']==''){
        $res = $woocommerce->post('products',$params);
        }
        else{
        $res = $woocommerce->post('products/'.$data['woo_id'],$params);
        }

        $adb->pquery('UPDATE vtiger_products SET woo_id=? WHERE productid=?',array($res->id,$id)); // Update woo commerce id in crm

    } catch (Exception $e) {
        echo $e->getMessage();
    }

}
