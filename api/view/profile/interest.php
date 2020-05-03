<?php
/**
 * User Interest API.
 * @author      hasan can özkan <hasan@canozkan.net>
 * @author      oğuz kaan yıldız <yldzoguzkaan@gmail.com>
 * @copyright   2020
 * @license     The MIT License (MIT)
 */

// user profile operations control
if(!$param[2])
{
    include "view/profile/interest/get.php";
}
elseif ($param[2] && (file_exists("view/profile/interest/". $param[2] . ".php")))
{
    include "view/profile/interest/". $param[2] . ".php";
}
else
{
    echo json_encode(array(
        'message' => '404_NOT_FOUND'
    ));
}