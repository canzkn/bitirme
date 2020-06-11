<?php
/**
 * Create Group Conversation Data API.
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
$GroupName  = htmlspecialchars(strip_tags($data->GroupName));
$Users      = $data->Users;
// Call the related class
include_once 'model/Functions.php';
include_once 'model/core/GroupConversation.php';
include_once 'model/GroupConversation.php';
// Set Group Conversation
$group = new GroupConversationOperations($db);
$return = $group->setCreatorID($auth->getUserID())->setGroupName($GroupName)->setUsers($Users)->create();

if($return)
{
    echo json_encode(array(
        'message' => 'CREATE_GROUP_SUCCESS',
        'ConversationID' => $return,
    ));
}
else
{
    echo json_encode(array(
        'message' => 'CREATE_GROUP_FAILED'
    ));
}
