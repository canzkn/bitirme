<?php
/**
 * Get Group Conversation Data API.
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
include_once 'model/core/GroupConversation.php';
include_once 'model/GroupConversation.php';
// Set Group Conversation
$group = new GroupConversationOperations($db);
$return = $group->setUserID($auth->getUserID())->getGroups();

echo json_encode($return);