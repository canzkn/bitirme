<?php
/**
 * User Profile Operations API.
 * @author      hasan can özkan <hasan@canozkan.net>
 * @author      oğuz kaan yıldız <yldzoguzkaan@gmail.com>
 * @copyright   2020
 * @license     The MIT License (MIT)
 */

// authentication control
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
// Get Authorization Header
$headers = apache_request_headers();
if(isset($headers['Authorization']))
{
    // Get User Informations.
    $matches = array();
    preg_match('/UserID=(.*?); token=(.*?);/', $headers['Authorization'], $matches);
    $UserID     = $matches[1];
    $Token      = $matches[2];
} 
else
{
    die(json_encode(array(
        'message' => 'AUTHORIZATION_FAILED'
    )));
}

// Call the related class
include_once 'model/core/User.php';
include_once 'model/Authentication.php';

// Set object
$auth = new Authentication($db);
// Set user id and token
$auth->setUserID($UserID)
    ->setAccessToken($Token);

// Control Authentication
if(!$auth->checkLogin())
{
    die(json_encode(array(
        'message' => 'AUTHORIZATION_FAILED'
    )));
}

// research operations control
if(!$param[1])
{
    include "view/research/search.php";
}
else
{
    echo json_encode(array(
        'message' => '404_NOT_FOUND'
    ));
}