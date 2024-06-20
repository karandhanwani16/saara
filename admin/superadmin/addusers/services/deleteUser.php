<?php

require '../../../services/config.php';
require '../../../services/helperFunctions.php';

$userId = $_POST["id"];

session_start();
$user_id = $_SESSION["user_id"];


$query = "delete from users where user_id = " . $userId;

$finalObject = new \stdClass();

try {
    if (mysqli_query($con, $query)) {
        addLog("User", "deleted", "deleted User Id: " . $userId, $con);
        $finalObject->status = "success";
        $finalObject->message = "User Deleted successfully!!";
    } else {
        $finalObject->status = "error";
        $finalObject->message = "Error #1002";
    }
} catch (Exception $e) {
    $finalObject->status = "error";
    $finalObject->message = "Error #1001";
}


$response = json_encode($finalObject);
echo $response;



?>