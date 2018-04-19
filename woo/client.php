<?php
    require 'woo28/woo/vendor/autoload.php';
    use Automattic\WooCommerce\Client;
$woocommerce = new Client(
        'https://localhost/wpwoo/', 'ck_75c3a4ada55b7c4f8c7b8a41e3e817bd7a4c716b', 'cs_65653b7830c3ba2287f2d77bfc37a00b626f6dba', [
    'wp_api' => true,
    'version' => 'wc/v2',
    'verify_ssl' => false,
        ]
);
