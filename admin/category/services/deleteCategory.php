<?php

require '../../services/config.php';
require '../../services/helperFunctions.php';

$categoryId = $_POST["id"];

session_start();
$user_id = $_SESSION["user_id"];


$query = "delete from category where category_id = " . $categoryId;

$finalObject = new \stdClass();

try {

    if (mysqli_query($con, $query)) {
        addLog("Category", "deleted", "deleted Category Id: " . $categoryId, $con);
        $finalObject->status = "success";
        $finalObject->message = "Category Deleted successfully!!";
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