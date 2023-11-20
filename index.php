<?php
include_once "./autoload.php";
$streamInput = file_get_contents("php://input");
if (strpos($streamInput, ":")) {
    $jsonData = true;
} else {
    $jsonData = false;
}
$user = new PDO1();
//$user = new USER();
$action = "unknown";
$request = $jsonData ? json_decode($streamInput, 1) : $_REQUEST;
if (isset($request['action'])) {
    $action = trim($request['action']);
    unset($request['action']);
}
$result = [];
switch ($action) {
    case 'getUser':
        $result = $user->getAll($request);
        break;
    case 'deleteUser':
        $result = $user->deleteQuery($request);
        break;
    case 'update':
        $result = $user->updateQuery($request);
        break;
    case 'addUser':
        $result = $user->insertFunc($request);
        break;
    case 'searchUser':
        $result = $user->searchQuery($request);
        break;
    case 'upload':
        $result = $user->upload($_FILES['image']);
        break;
    case 'getupload':
        $result = $user->getupload();
        break;
    case 'downloadImg':
        $result = $user->downloadImg($_POST['name']);
        break;
    default:
        invalidQuery();
}
if (is_array($result)) {
    header("Content-Type: application/json");
    echo json_encode($result);
    exit();
} else {
    echo $result;
    exit();
}
function invalidQuery($message = null)
{
    http_response_code(201);
    $response = new stdclass();
    $response->code = 203;
    $response->responseDescription = $message ? $message : "invalid request";

}
//$users= $user->insertFunc(['name'=>'Arnav','contact'=>'1234512345','email'=>'Arnav@gmail.com']);
//print_r($users);
// $users = $user->getAll(['name', 'email'], []);
//$users = $user->updateQuery(['name'=>'Aryan'],['name'=>'Aaryan']);
//$users = $user->deleteQuery(['id'=>'4']);
// print_r($users);

