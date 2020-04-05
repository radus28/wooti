<?php

require 'woo28/woo/vendor/autoload.php';
use Automattic\WooCommerce\Client;

//ConsumerKey+C_Secret
$cKey = 'ck_aa644e3ddc1edeb41wert38124058a984582831d'; // 
$cSecret = 'cs_68c47a3d7d56b71b5fba29ssss501fb5019d0f3d'; // 
$wooUrl = 'http://localhost/ecom/woo/';

$woocommerce = new Client(
        $wooUrl, $cKey, $cSecret, [
            'wp_api' => true,
            'version' => 'wc/v2',
            'verify_ssl' => false,
        ]
);
