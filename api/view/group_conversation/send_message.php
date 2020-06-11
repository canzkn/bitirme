<?php
/**
 * Send Group Message API.
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
$GroupID   = htmlspecialchars(strip_tags($data->GroupID));
$Message   = htmlspecialchars(strip_tags($data->Message));
// Call the related class
include_once 'model/Functions.php';
include_once 'model/core/GroupConversation.php';
include_once 'model/GroupConversation.php';


$group = new GroupConversationOperations($db);
$group
    ->setMessageDate(date("Y-m-d H:i:s"))
    ->setMessage($Message)
    ->setSenderID($auth->getUserID())
    ->setGroupID($GroupID);

$send = $group->sendMessage();

if($send == false)
{
    echo json_encode(array(
        'message' => '404_NOT_FOUND'
    ));
}
else
{
    echo json_encode(array(
        'message' => 'SEND_MESSAGE_SUCCESS',
        'data' => $send,
    ));
}
