<?php

require './service/ContactService.php';

$token = isset($_REQUEST['token']) ? $_REQUEST['token'] : null;

if ($token) {
    $q = new ContactService();

    $response = $q->insert($_REQUEST);
    //return $response;
    var_dump($response);  
}

