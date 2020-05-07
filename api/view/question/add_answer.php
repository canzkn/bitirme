<?php
/**
 * Add Answer to Question API.
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
$QuestionID = htmlspecialchars(strip_tags($data->QuestionID));
$UserID = htmlspecialchars(strip_tags($data->UserID));
$Content = $data->Content;

// Call the related class
include_once 'model/Functions.php';
include_once 'model/core/Answer.php';
include_once 'model/Answer.php';

// Set Answer Operation
$answer = new AnswerOperations($db);

$response_code = $answer
    ->setUserID($UserID)
    ->setContent($Content)
    ->setCreateDate(date("Y-m-d H:i:s"))
    ->setQuestionID($QuestionID)
    ->addAnswer();

if($response_code > 0)
{
    echo json_encode(array(
        'message'       => 'ADD_ANSWER_SUCCESS'
    ));
}
else
{
    echo json_encode(array(
        'message' => 'ADD_ANSWER_FAILED'
    ));
}