<?php

require '../../services/config.php';
require '../../services/helperFunctions.php';

$brandId = $_POST["id"];

session_start();
$user_id = $_SESSION["user_id"];


$query = "delete from brand where brand_id = " . $brandId;

$finalObject = new \stdClass();

try {

    if (mysqli_query($con, $query)) {
        addLog("Brand", "deleted", "deleted Brand Id: " . $brandId, $con);
        $finalObject->status = "success";
        $finalObject->message = "Brand Deleted successfully!!";
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