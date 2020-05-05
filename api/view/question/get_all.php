<?php
/**
 * Get Last Question API.
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
$type = htmlspecialchars(strip_tags($data->type));
$filter = $data->filter;
$pageId = htmlspecialchars(strip_tags($data->pageId));

// Call the related class
include_once 'model/Functions.php';
include_once 'model/core/Question.php';
include_once 'model/Question.php';

// Set Question Operation
$question = new QuestionOperations($db);
$question->setUserID($auth->getUserID());

$questions = $question->getAllQuestions($type, $filter, $pageId);

echo json_encode($questions);