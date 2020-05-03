<?php
/**
 * User Add Interest API.
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
$datas = (array) json_decode(file_get_contents("php://input"));

if(count($datas) > 0)
{
    // Call the related class
    include_once 'model/core/User.php';
    include_once 'model/Profile.php';
    // Set User Profile
    $profile = new Profile($db);
    $profile->setUserID($auth->getUserID());

    $status = 0;
    
    foreach($datas as $data)
    {
        if($profile->addInterest($data) == 1)
        {
            $status++;
        }
    }

    if($status > 0)
    {
        $profile->setInterestStatus();
        echo json_encode(array(
            'message' => 'ADD_INTEREST_SUCCESS'
        ));
    }
    else
    {
        echo json_encode(array(
            'message' => 'ADD_INTEREST_FAILED'
        ));
    }
}