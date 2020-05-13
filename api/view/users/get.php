<?php
/**
 * Get Single User API.
 * @author      hasan can özkan <hasan@canozkan.net>
 * @author      oğuz kaan yıldız <yldzoguzkaan@gmail.com>
 * @copyright   2020
 * @license     The MIT License (MIT)
 */

// Headers for JSON Output.
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
// Get raw posted data
$data = json_decode(file_get_contents("php://input"));
// Clean Data and Set
$UserID = htmlspecialchars(strip_tags($data->UserID));
$filter = $data->filter;

// Call the related class
include_once 'model/Functions.php';
include_once 'model/core/User.php';
include_once 'model/Users.php';

// Set Question Operation
$users = new UserOperations($db);
$users->setUserID($UserID);

$data = $users->getUser($filter);

if($data == false)
{
    echo json_encode(array(
        'message' => '404_NOT_FOUND'
    ));
}
else
{
    echo json_encode($data);
}