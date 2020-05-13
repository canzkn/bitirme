<?php
/**
 * Get All Users API.
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
$sort = htmlspecialchars(strip_tags($data->sort));

// Call the related class
include_once 'model/Functions.php';
include_once 'model/core/User.php';
include_once 'model/Users.php';

// Set Question Operation
$users = new UserOperations($db);

$data = $users->getAllUsers($sort);

echo json_encode($data);