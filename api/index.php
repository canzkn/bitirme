<?php
/**
 * Router file.
 * @author      hasan can özkan <hasan@canozkan.net>
 * @author      oğuz kaan yıldız <yldzoguzkaan@gmail.com>
 * @copyright   2020
 * @license     The MIT License (MIT)
 */

include 'config/configuration.php';



$page = @$_GET["page"];

$param = array_filter(explode("/", $page));

if( !@$param[0] )
{
    echo json_encode(array('message' => 'EMPTY_REQUEST_URI'));
}
elseif( $param[0] && (file_exists('view/' . $param[0] . '.php')))
{
    include 'view/' . $param[0] . '.php';
}
else
{
    echo json_encode(array('message' => '404_NOT_FOUND'));
}