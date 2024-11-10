<?php

require '../../services/config.php';
require '../../services/helperFunctions.php';

$purchaseId = $_POST["id"];

session_start();
$user_id = $_SESSION["user_id"];



$finalObject = new \stdClass();

try {

    if (updateStock($purchaseId, $con)) {
        $query = "delete from purchase_items where purchase_id = " . $purchaseId;
        if (mysqli_query($con, $query)) {
            $query2 = "delete from purchase where purchase_id = " . $purchaseId;
            if (mysqli_query($con, $query2)) {
                $finalObject->status = "success";
                $finalObject->message = "Purchase Deleted successfully!!";
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
    $finalObject->message = "Error #1001".$e;
}


$response = json_encode($finalObject);
echo $response;

function updateStock($purchaseId, $con)
{
    $stockUpdated = true;
    $query = "select * from purchase_items where purchase_id = " . $purchaseId;

    $result = mysqli_query($con, $query);
    
    while ($row = mysqli_fetch_assoc($result)) {
        $productId = $row['purchase_item_product_id'];
        $productPrice = $row['purchase_item_product_price'];
        $quantity = $row['purchase_item_product_quantity'];
        
        $updateQuery = "update product_prices set product_prices_stock = product_prices_stock - $quantity where product_id = $productId and product_prices_cost_price = '$productPrice'";

        
        if (!mysqli_query($con, $updateQuery)) {
            $stockUpdated = false; 
        }
    }
    return $stockUpdated;
}

