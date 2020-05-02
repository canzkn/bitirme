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
$email      = htmlspecialchars(strip_tags($data->email));
$date       = date("Y-m-d H:i:s");


// Call the related class
include_once 'model/Functions.php';
include_once 'model/core/User.php';
include_once 'model/Authentication.php';

// Set object
$auth = new Authentication($db);

// Create user
$auth->setUsername($username)
    ->setPassword($password)
    ->setEmail($email)
    ->setRegisterDate($date);

$create_code = $auth->create();

// FAILED_CODE
if($create_code == 0)
{
    echo json_encode(array(
        'message' => 'USER_REGISTRATION_FAILED'
    ));
}

// SUCCESS_CODE
if($create_code == 1)
{
    echo json_encode(array(
        'message' => 'USER_REGISTRATION_SUCCESS'
    ));
}

// BLANK_CODE
if($create_code == 2)
{
    echo json_encode(array(
        'message' => 'DO_NOT_LEAVE_EMPTY_SPACE'
    ));
}
// AVAILABLE_CODE
if($create_code == 3)
{
    echo json_encode(array(
        'message' => 'USER_AVAILABLE_IN_DB'
    ));
}

// INVALID_EMAIL_FORMAT
if($create_code == 6)
{
    echo json_encode(array(
        'message' => 'INVALID_EMAIL_FORMAT'
    ));
}

// Raw Object Pattern
// {
// 	"username":"Mrxdot",
//  "email":"hasan@canozkan.net",
//  "password":"q1w2e3123!!"
// }