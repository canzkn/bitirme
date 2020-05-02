<?php
/**
 * User Register API.
 * @author      hasan can özkan <hasan@canozkan.net>
 * @author      oğuz kaan yıldız <yldzoguzkaan@gmail.com>
 * @copyright   2020
 * @license     The MIT License (MIT)
 */

// Headers for JSON Output.
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

// Clean Data and Set
$username   = htmlspecialchars(strip_tags($data->username));
$password   = htmlspecialchars(strip_tags($data->password));

// Call the related class
include_once 'model/Functions.php';
include_once 'model/core/User.php';
include_once 'model/Authentication.php';

// Set object
$auth = new Authentication($db);

$isLogin = $auth->setUsername($username)->setPassword($password)->login();

// FAILED_CODE
if($isLogin == 0)
{
    echo json_encode(array(
        'message' => 'LOGIN_FAILED'
    ));
}
    
// SUCCESS_CODE
if($isLogin == 1)
{
    echo json_encode(array(
        'message' => 'LOGIN_SUCCESS',
        'data'    => [
            'UserID'       => $auth->getUserID(),
            'Username'     => $username,
            'Token'        => $auth->getAccessToken()
        ]
    ));
}

// BLANK_CODE
if($isLogin == 2)
{
    echo json_encode(array(
        'message' => 'DO_NOT_LEAVE_IN_BLANK'
    ));
}

// ALREADY_LOGGED
if($isLogin == 4)
{
    echo json_encode(array(
        'message' => 'ALREADY_LOGGED'
    ));
}

// TOKEN_CREATE_FAILED
if($isLogin == 5)
{
    echo json_encode(array(
        'message' => 'TOKEN_CREATE_FAILED'
    ));
}

// Raw Object Pattern
// {
//     "username":"Mrxdot",
//     "password":"q1w2e3123!!"
// }