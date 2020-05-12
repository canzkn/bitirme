<?php
/**
 * Get Tags API.
 * @author      hasan can özkan <hasan@canozkan.net>
 * @author      oğuz kaan yıldız <yldzoguzkaan@gmail.com>
 * @copyright   2020
 * @license     The MIT License (MIT)
 */

// Compare QuestionCount
function comparatorQCount($object1, $object2) { 
    return $object1['QuestionCount'] < $object2['QuestionCount']; 
} 
// Compare Name
function comparatorName($object1, $object2) { 
    return $object1['TagName'] > $object2['TagName']; 
} 
// Headers for JSON Output.
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
// Get raw posted data
$data = json_decode(file_get_contents("php://input"));
// Clean Data and Set
$sort = htmlspecialchars(strip_tags($data->sort));
// Call the related class
include_once 'model/core/Tag.php';
include_once 'model/Tags.php';

$tags = new Tags($db);
$data = $tags->manipulation($tags->getTags());

if($sort == "popular")
{
    usort($data, 'comparatorQCount');
}
else
{
    usort($data, 'comparatorName');
}

echo json_encode($data);


