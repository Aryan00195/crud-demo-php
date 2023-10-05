<?php
include "./autoload.php";
$user = new user();
// $users = $user->getAll();
// $users = $user->updateQuery();
$users = $user->deleteQuery();
print_r($users);

