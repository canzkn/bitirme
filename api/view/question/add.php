<?php
/**
 * Question Add API.
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
$title   = htmlspecialchars(strip_tags($data->title));
$content = $data->content;
$tags    = $data->tags;

// Call the related class
include_once 'model/core/Question.php';
include_once 'model/Question.php';

// Set Question Operation
$question = new QuestionOperations($db);
$response_code = $question
    ->setUserID($auth->getUserID())
    ->setTitle($title)
    ->setContent($content)
    ->setCreateDate(date("Y-m-d H:i:s"))
    ->setTags($tags)
    ->addQuestion();

if($response_code == 1)
{
    
    echo json_encode(array(
        'message' => 'ADD_QUESTION_SUCCESS'
    ));
}
else
{
    echo json_encode(array(
        'message' => 'ADD_QUESTION_FAILED'
    ));
}
