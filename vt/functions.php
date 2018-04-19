<?php



function getResponseFromURL($url = "", $post = array(), $headers = array(), $debug = false) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_URL => $url,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_CONNECTTIMEOUT => 0,
        CURLOPT_TIMEOUT => 400,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false
    ));

    if (count($post) > 0) {
        curl_setopt_array($curl, array(
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($post)
        ));
//          var_dump($post);echo '<hr/>';
    }
    if (!empty($headers)) {

        curl_setopt_array($curl, array(
            CURLOPT_HTTPHEADER => $headers
        ));
    }

    $resp = curl_exec($curl);

    if ($resp === false) {
        die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl) . "<br />" . $url);
    }

    if ($debug) {
        echo "<pre>" . print_r(curl_getinfo($curl), true) . "</pre>";
        echo "<pre>" . print_r($resp, true) . "</pre>";
    }

    curl_close($curl);
    return $resp;
}

