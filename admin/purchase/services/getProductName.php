<?php

include "../../services/config.php";
include "../../services/helperFunctions.php";

$data = $_POST["data"];
$data = json_decode($data, true);

// get current user
session_start();
$user_id = $_SESSION["user_id"];

$finalObject = new \stdClass();

//Step 1 : getting all the variables

$porductId = $data["id"];

try {
    $query = "select product_name from products where product_id = ".$productId;


    if (mysqli_query($con, $query)) {
        $finalObject->status = "success";
        $finalObject->message = "Product Name fetched";
    } else {
        $finalObject->status = "error";
        $finalObject->message = "Error #1002";
    }
} catch (Exception $e) {
    $finalObject->status = "error";
    $finalObject->message = "Error #1000" . $e;
}

mysqli_close($con);
$response = json_encode($finalObject);
echo $response;
?>