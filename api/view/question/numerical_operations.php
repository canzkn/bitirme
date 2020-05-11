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
$ID = htmlspecialchars(strip_tags($data->ID));
$type = htmlspecialchars(strip_tags($data->type));
$creaseType = htmlspecialchars(strip_tags($data->creaseType));
$column = htmlspecialchars(strip_tags($data->column));

if($type == "question")
{
    // Call the related class
    include_once 'model/Functions.php';
    include_once 'model/core/Question.php';
    include_once 'model/Question.php';

    // Set Question Operation
    $question = new QuestionOperations($db);
    $question->setUserID($auth->getUserID());

    $getQuestion = $question->setQuestionID($ID)->numericalOperations($creaseType, $column);

    if($getQuestion == 404)
    {
        echo json_encode(array(
            'message' => '404_NOT_FOUND'
        ));
    }
    elseif($getQuestion == 1)
    {
        echo json_encode(array(
            'message' => strtoupper('QUESTION_'.$column . '_'.$creaseType.'_SUCCESS')
        ));
    }
    elseif($getQuestion == 5)
    {
        echo json_encode(array(
            'message' => strtoupper('QUESTION_REPUTED_BEFORE')
        ));
    }
    else
    {
        echo json_encode(array(
            'message' => strtoupper('QUESTION_'.$column . '_'.$creaseType.'_FAILED')
        ));
    }
}
elseif($type == "answer")
{
    // Call the related class
    include_once 'model/Functions.php';
    include_once 'model/core/Answer.php';
    include_once 'model/Answer.php';

    // Set Answer Operation
    $answer = new AnswerOperations($db);
    $answer->setUserID($auth->getUserID());
    
    $getAnswer = $answer->setAnswerID($ID)->numericalOperations($creaseType, $column);

    if($getAnswer == 404)
    {
        echo json_encode(array(
            'message' => '404_NOT_FOUND'
        ));
    }
    elseif($getAnswer == 1)
    {
        echo json_encode(array(
            'message' => strtoupper('ANSWER_'.$column . '_'.$creaseType.'_SUCCESS')
        ));
    }
    elseif($getAnswer == 5)
    {
        echo json_encode(array(
            'message' => strtoupper('ANSWER_REPUTED_BEFORE')
        ));
    }
    else
    {
        echo json_encode(array(
            'message' => strtoupper('ANSWER_'.$column . '_'.$creaseType.'_FAILED')
        ));
    }
}


