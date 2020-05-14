<?php
/**
 * Update Profile Data API.
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
$Username   = htmlspecialchars(strip_tags($data->Username));
$Email   = htmlspecialchars(strip_tags($data->Email));
$Fullname   = htmlspecialchars(strip_tags($data->Fullname));
$Address   = htmlspecialchars(strip_tags($data->Address));
$AvatarImage   = htmlspecialchars(strip_tags($data->AvatarImage));
$CoverImage   = htmlspecialchars(strip_tags($data->CoverImage));
$Password   = htmlspecialchars(strip_tags($data->Password));


// Call the related class
include_once 'model/Functions.php';
include_once 'model/core/User.php';
include_once 'model/Profile.php';

// Set User Profile
$profile = new Profile($db);
$profile
    ->setUserID($auth->getUserID())
    ->setEmail($Email)
    ->setFullname($Fullname)
    ->setAddress($Address);

// avatar image operation
$search = "data:image";
if(preg_match("/{$search}/i", $AvatarImage))
{
    $now = date("Y-m-d");
    $id = rand();
    $upload_folder = '_upload/';

    $path = $upload_folder . $id .'.jpeg';

    if(preg_match("/jpeg;base64,/i", $AvatarImage))
    {
        $img = str_replace('data:image/jpeg;base64,', '', $AvatarImage);
    }
    
    if(preg_match("/png;base64,/i", $AvatarImage))
    {
        $img = str_replace('data:image/png;base64,', '', $AvatarImage);
    }

    $img = str_replace(' ', '+', $img);
    $img = base64_decode($img);

    if(file_put_contents($path, $img))
    {
        $profile->setAvatarImage($path);
    }
}
else
{
    $getProfileData = $profile->getProfile();
    $profile->setAvatarImage(str_replace($api_url, '', $getProfileData["AvatarImage"]));
}

// cover image operation
if(preg_match("/{$search}/i", $CoverImage))
{
    $now = date("Y-m-d");
    $id = rand();
    $upload_folder = '_upload/';

    $path = $upload_folder . $id .'.jpeg';

    if(preg_match("/jpeg;base64,/i", $CoverImage))
    {
        $img = str_replace('data:image/jpeg;base64,', '', $CoverImage);
    }
    
    if(preg_match("/png;base64,/i", $CoverImage))
    {
        $img = str_replace('data:image/png;base64,', '', $CoverImage);
    }
    
    $img = str_replace(' ', '+', $img);
    $img = base64_decode($img);
    if(file_put_contents($path, $img))
    {
        $profile->setCoverImage($path);
    }
}
else
{
    $getProfileData = $profile->getProfile();
    $profile->setCoverImage(str_replace($api_url, '', $getProfileData["CoverImage"]));
}

// if password exist?
if($Password)
{
    $profile->setPassword($Password);
}

$data = $profile->updateProfile();

if($data == false)
{
    echo json_encode(array(
        'message' => '404_NOT_FOUND'
    ));
}
else
{
    echo json_encode(array(
        'message' => 'USER_PROFILE_UPDATE_SUCCESS'
    ));
}
