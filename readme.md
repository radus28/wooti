# VTiger ~ WooCommerce Sync

Get Script from https://github.com/radus28/wooti
This script is done for vtiger : Woocommerce Inegration of

* Products
* Orders
* Customers

Both ways.  Woo Customers can be synced bi-directionally into vtiger contacts


### Configuration steps
_______________________
1. Install Wordpress and WooCommerce
2. Use vtiger 7.1
3. Get  API keys as per [How to Get Woocommerc API Key](https://docs.woocommerce.com/document/woocommerce-rest-api/)
4. A folder should be added in CRM root named 'woo28' and extract the given zip into that folder.
5. Files are described below 
    1. in/
        > Add Webhooks from WooCommerce to the files here.<br>
        > In WP Admin panel,<br>
        > * WooCommerce->Settings->API->Webhooks->Add Webhook 
            
        1. order.php 
            > To sync orders from WooCommerce into vtiger SalesOrder
            >
            > Create a webhook that should trigger to this file when Orders are placed in WooCommerce. 
            > * Give **Delivery URL** to _'http://yourVTigerCRM.com/woo28/in/order.php'_
            > * Set **Topic** as _Order Updated_
        2. customer.php
            > To sync customers from WooCommerce into vtiger contacts
            >
            > Create **Two** webhooks that should trigger to this file when Customer are Created and Updated in WooCommerce. 
            > * Give **Delivery URL** to _'http://yourVTigerCRM.com/woo28/in/customer.php'_
                > 1. Set first webhook **Topic** as _Customer Created_
                > 2. Set second webhook **Topic** as _Customer Updated_
    2. out/
        1. products.php - This is the custom function file run through vtiger workflow to sync **Products** to wooCommerce
        2. customer.php - This is the custom function file run through vtiger workflow to sync **Customers** to wooCommerce
    3. woo/
        1. client.php 
            * Set your woocommerce URL and api keys here

                    //Consumer Key of your WooCommerce API
                    $cKey = 'ck_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';

                    //Consumer Secret of your WooCommerce API
                    $cSecret = 'cs_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';

                    //URL of your WooCommerce Site
                    $wooUrl = 'http://www.yourWooCommerceSite.com';
    4. vt/
        1. ws_auth.php
            * Set your VTiger CRM URL, username and accessKey

                    // You may use any user
                    $username = 'xxxxxx'; 

                    // Obtain from CRM > My Preference > Access Key (Use admin user to get all privileges
                    $accessKey = 'xxxxxxxxxxxxxxxx';

                    //Your VTiger CRM URL
                    $vtigerUrl = 'http://www.yourVTigerCRMSite.com'; 
    4. addfield.php  
        * Run this file from browser to add woo_id to SO and Products
    5. registercf.php
        1. Run this from browser to register custom function under Products in CRM. 
        2. A custom function will be listed under Product workflow
        3. Add a work-flow using above custom function to send products (condition : active = yes)

