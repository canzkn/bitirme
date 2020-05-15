<?php
/**
 * Get Conversation Data API.
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
include_once 'model/core/Conversation.php';
include_once 'model/Conversation.php';

// Set User Profile
$conversation = new ConversationOperations($db);
$conversation
    ->setSenderID($auth->getUserID());

$data = $conversation->getConversations();

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
