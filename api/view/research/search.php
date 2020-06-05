<?php
/**
 * Research API.
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
$query = urlencode(htmlspecialchars(strip_tags($data->query)));
$language = urlencode(htmlspecialchars(strip_tags($data->language)));
$page = htmlspecialchars(strip_tags($data->page));
// if page is not exist
if(!$page)
{
    $page = 1;
}
// prepare the search url
$url = "https://github.com/search?l={$language}&p={$page}&q={$query}&type=Repositories";
// connect to github
$github = file_get_contents($url);
// found count
$countPattern = '@<h3>(.*?)</h3>@si';
preg_match_all($countPattern, $github, $data);
$explode = explode(" ", trim($data[1][0]));
$foundCount = $explode[0];
// found repo
$repoPattern = '@<a class="v-align-middle" data-hydro-click="(.*?)" data-hydro-click-hmac="(.*?)" href="(.*?)">(.*?)</a>@si';
preg_match_all($repoPattern, $github, $data);
// found description
$repodescPattern = '@<p class="mb-1">(.*?)</p>@si';
preg_match_all($repodescPattern, $github, $desc);

$repositories = array();
for($i=0; $i<count($data[3]);$i++)
{
    $repositories[$i]["Name"] = $data[4][$i];
    $repositories[$i]["Description"] = trim(strip_tags($desc[1][$i]));
    $repositories[$i]["Url"] = $data[3][$i];
}

$result = array("Found" => $foundCount, "Repositories" => $repositories);

echo json_encode($result);
