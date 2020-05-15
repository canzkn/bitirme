<?php
/**
 * Send Message API.
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
$ReceiverID   = htmlspecialchars(strip_tags($data->ReceiverID));
$Message   = htmlspecialchars(strip_tags($data->Message));
// Call the related class
include_once 'model/Functions.php';
include_once 'model/core/Conversation.php';
include_once 'model/Conversation.php';

// Set User Profile
$conversation = new ConversationOperations($db);
$conversation
    ->setMessageDate(date("Y-m-d H:i:s"))
    ->setMessage($Message)
    ->setSenderID($auth->getUserID())
    ->setReceiverID($ReceiverID);

$data = $conversation->sendMessage();

if($data == false)
{
    echo json_encode(array(
        'message' => '404_NOT_FOUND'
    ));
}
else
{
    echo json_encode(array(
        'message' => 'SEND_MESSAGE_SUCCESS',
        'data' => $data,
    ));
}
