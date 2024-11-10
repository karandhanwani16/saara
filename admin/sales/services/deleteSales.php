<?php

require '../../services/config.php';
require '../../services/helperFunctions.php';

$saleId = $_POST["id"];

session_start();
$user_id = $_SESSION["user_id"];



$finalObject = new \stdClass();

try {

    if (updateStock($saleId, $con)) {
        $query = "delete from sale_items where sale_id = " . $saleId;
        if (mysqli_query($con, $query)) {
            $query2 = "delete from sales where sale_id = " . $saleId;
            if (mysqli_query($con, $query2)) {
                $finalObject->status = "success";
                $finalObject->message = "Sale Deleted successfully!!";
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
} catch (Exception $e) {
    $finalObject->status = "error";
    $finalObject->message = "Error #1001";
}


$response = json_encode($finalObject);
echo $response;

function updateStock($saleId, $con)
{
    $stockUpdated = true;
    $query = "select * from sale_items where sale_id = $saleId";
    $result = mysqli_query($con, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $productId = $row['product_id'];
        $productPrice = $row['product_price'];
        $quantity = $row['quantity'];
        $query = "update product_prices set product_prices_stock = product_prices_stock + $quantity where product_id = $productId and product_prices_selling_price = '$productPrice'";
        if (!mysqli_query($con, $query)) {
            $stockUpdated = false;
        }
    }
    return $stockUpdated;
}
