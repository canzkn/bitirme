<?php
/**
 * Views or Reputation Increase or Descrease API.
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
$type = htmlspecialchars(strip_tags($data->type));
$column = htmlspecialchars(strip_tags($data->column));

// Call the related class
include_once 'model/Functions.php';
include_once 'model/core/Question.php';
include_once 'model/Question.php';

// Set Question Operation
$question = new QuestionOperations($db);
$question->setUserID($auth->getUserID());

$getQuestion = $question->setQuestionID($QuestionID)->numericalOperations($type, $column);

if($getQuestion == 404)
{
    echo json_encode(array(
        'message' => '404_NOT_FOUND'
    ));
}
elseif($getQuestion == 1)
{
    echo json_encode(array(
        'message' => strtoupper($column . '_'.$type.'_SUCCESS')
    ));
}
else
{
    echo json_encode(array(
        'message' => strtoupper($column . '_'.$type.'_FAILED')
    ));
}
