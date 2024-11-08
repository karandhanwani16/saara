<?php

include "../../services/config.php";
include "../../services/helperFunctions.php";
include "commonFunctions.php";

$data = $_POST["data"];
$data = json_decode($data, true);

// response object
$finalObject = new \stdClass();


//Step 1 : getting all the variables
$name = $data["name"];
$brand = $data["brand"];
$category = $data["category"];
$barcode = $data["barcode"];
$code = $data["code"];
$description = $data["description"];
$prices = $data["prices"];
$images = $data["images"];

// get Current User ID
session_start();
$user_id = $_SESSION["user_id"];


try {
    $maximumProductId = getCurrentId("product_id", "products", $con);
    if (!isExist($name, "products", "product_name", $con)) {

        $sql = "insert into products values(" . $maximumProductId . "," . $category . "," . $brand . ",'" . $name . "','" . $barcode . "','" . $code . "','" . $description . "','" . getCurrentTimestamp() . "'," . $user_id . ",'" . getCurrentTimestamp() . "'," . $user_id . ")";

        if (mysqli_query($con, $sql)) {
            if (uploadProductPrices($maximumProductId, $prices,$con)) {
                if (uploadImages($images, $maximumProductId)) {
                    $finalObject->status = "success";
                    $finalObject->message = "Product upload successfully!!!";
                } else {
                    $finalObject->status = "error";
                    $finalObject->message = "Error #1003";
                }
            } else {
                $finalObject->status = "error";
                $finalObject->message = "Error #1002";
            }
        } else {
            $finalObject->status = "error";
            $finalObject->message = "Error #1001";
        }
    } else {
        $finalObject->status = "warning";
        $finalObject->message = "Product Name Already Exists!!!";
    }
} catch (Exception $e) {
    $finalObject->status = "error";
    $finalObject->message = "Error #1000";
}
 
$response = json_encode($finalObject);
echo $response;

