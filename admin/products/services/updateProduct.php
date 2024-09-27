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
$images = $data["images"];
$currentProductId = $data["id"];
$prices = $data["prices"];

// get Current User ID
session_start();
$user_id = $_SESSION["user_id"];


try {
    if (!isExistUpdate($name, "products", "product_name", "product_id", $currentProductId, $con)) {

        $sql = "update products set category_id = $category,brand_id = $brand,product_name='$name',product_barcode='$barcode',product_code='$code',product_description='$description',product_updated_at='" . getCurrentTimestamp() . "',product_updated_by=" . $user_id . " where product_id = $currentProductId";

        // echo $sql;

        if (mysqli_query($con, $sql)) {

            if (deleteProductPrices($currentProductId, $con)) {
                if (uploadProductPrices($currentProductId, $prices, $con)) {
                    if (deleteProductImages($currentProductId, $con)) {
                        if (uploadImages($images, $currentProductId)) {
                            $finalObject->status = "success";
                            $finalObject->message = "Product updated successfully!!!";
                        } else {
                            $finalObject->status = "error";
                            $finalObject->message = "Error #1004";
                        }
                    } else {
                        $finalObject->status = "error";
                        $finalObject->message = "Error #1004";
                    }
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
