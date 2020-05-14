<?php
/**
 * Get Profile Data API.
 * @author      hasan can özkan <hasan@canozkan.net>
 * @author      oğuz kaan yıldız <yldzoguzkaan@gmail.com>
 * @copyright   2020
 * @license     The MIT License (MIT)
 */

// Headers for JSON Output.
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
// Call the related class
include_once 'model/Functions.php';
include_once 'model/core/User.php';
include_once 'model/Profile.php';

// Set User Profile
$profile = new Profile($db);
$profile->setUserID($auth->getUserID());

$data = $profile->getProfile();

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
