<?php

include "../../services/config.php";
include "../../services/helperFunctions.php";

$data = $_POST["data"];
$data = json_decode($data, true);

// response object
$finalObject = new \stdClass();


//Step 1 : getting all the variables
$name = $data["name"];
$brand = $data["brand"];
$category = $data["category"];
$barcode = $data["barcode"];
$quantity = $data["quantity"];
$code = $data["code"];
$costPrice = $data["costPrice"];
$cGSTPercentage = $data["cGSTPercentage"];
$sGSTPercentage = $data["sGSTPercentage"];
$iGSTPercentage = $data["iGSTPercentage"];
$postGSTPrice = $data["postGSTPrice"];
$sellingPrice = $data["sellingPrice"];
$parlourPrice = $data["parlourPrice"];
$description = $data["description"];
$images = $data["images"];

// get Current User ID
session_start();
$user_id = $_SESSION["user_id"];


try {
    $maximumProductId = getCurrentId("product_id", "products", $con);
    if (!isExist($name, "products", "product_name", $con)) {
        $sql = "insert into products values(" . $maximumProductId . "," . $category . "," . $brand . ",'" . $name . "','" . $barcode . "','" . $quantity . "','" . $code . "','" . $costPrice . "','" . $cGSTPercentage . "','" . $sGSTPercentage . "','" . $iGSTPercentage . "','" . $postGSTPrice . "','" . $sellingPrice . "','" . $parlourPrice . "','" . $description . "','" . getCurrentTimestamp() . "'," . $user_id . ",'" . getCurrentTimestamp() . "'," . $user_id . ")";
        if (mysqli_query($con, $sql)) {
            if (uploadImages($images, $maximumProductId)) {
                $finalObject->status = "success";
                $finalObject->message = "Product upload successfully!!!";
            } else {
                deleteProduct($maximumProductId, $con);
                deleteProductFolder($maximumProductId);
                $finalObject->status = "error";
                $finalObject->message = "Error #10002";
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

function deleteProduct($product_id, $con)
{
    $sql = "delete from products where product_id = " . $product_id;
    mysqli_query($con, $sql);
}
function deleteProductFolder($product_id)
{
    $mainDir = "../../../assets/images/products/" . $product_id . "/";
    if (is_dir($mainDir)) {
        rrmdir($mainDir);
    }
}

function uploadImages($images, $product_id)
{
    $uploaded = true;
    if (count($images) > 0) {
        $mainDir = "../../../assets/images/products/" . $product_id . "/";
        mkdir($mainDir);
        for ($i = 0; $i < count($images); $i++) {
            $image_data = getImageFromBase64($images[$i]);
            if (!file_put_contents($mainDir . $i . '.' . $image_data->type, $image_data->image)) {
                $uploaded = false;
            }
        }
    }
    return $uploaded;
}

function rrmdir($dir)
{
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (is_dir($dir . DIRECTORY_SEPARATOR . $object) && !is_link($dir . "/" . $object))
                    rrmdir($dir . DIRECTORY_SEPARATOR . $object);
                else
                    unlink($dir . DIRECTORY_SEPARATOR . $object);
            }
        }
        rmdir($dir);
    }
}

?>