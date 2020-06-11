<?php
/**
 * Get Group Conversation Messages API.
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
// Call the related class
include_once 'model/Functions.php';
include_once 'model/core/GroupConversation.php';
include_once 'model/GroupConversation.php';


$group = new GroupConversationOperations($db);
$group
    ->setSenderID($auth->getUserID())
    ->setGroupID($GroupID);

$messages = $group->getMessages();

if($messages == false)
{
    echo json_encode(array(
        'message' => '404_NOT_FOUND'
    ));
}
else
{
    echo json_encode($messages);
}