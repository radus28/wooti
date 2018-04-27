<?php

require 'woo28/woo/vendor/autoload.php';
use Automattic\WooCommerce\Client;

//ConsumerKey+C_Secret
$cKey = 'ck_aa644e3ddc1edeb411e9d38124058a984582831d'; // 'ck_75c3a4ada55b7c4f8c7b8a41e3e817bd7a4c716b'
$cSecret = 'cs_68c47a3d7d56b71b5fba29407c501fb5019d0f3d'; // 'cs_65653b7830c3ba2287f2d77bfc37a00b626f6dba' 

$woocommerce = new Client(
        'http://localhost/ecom/woo/', $cKey, $cSecret, [
            'wp_api' => true,
            'version' => 'wc/v2',
            'verify_ssl' => false,
        ]
);
