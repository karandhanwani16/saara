<?php

require '../../services/config.php';
require '../../services/helperFunctions.php';
require 'commonFunctions.php';

$productId = $_POST["id"];

session_start();
$user_id = $_SESSION["user_id"];



$finalObject = new \stdClass();

try {

    if (deleteProductImages($productId, $con)) {
        $query = "delete from products where product_id = " . $productId;
        if (mysqli_query($con, $query)) {
            $finalObject->status = "success";
            $finalObject->message = "Product Deleted successfully!!";
        } else {
            $finalObject->status = "error";
            $finalObject->message = "Error #1002";
        }
    } else {
        $finalObject->status = "error";
        $finalObject->message = "Error #1001";
    }
} catch (Exception $e) {
    $finalObject->status = "error";
    $finalObject->message = "Error #1001";
}


$response = json_encode($finalObject);
echo $response;




?>