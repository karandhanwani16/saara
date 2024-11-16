<?php

include "../../services/config.php";
include "../../services/helperFunctions.php";

$data = $_POST["data"];
$data = json_decode($data, true);

// get current user
session_start();
$user_id = $_SESSION["user_id"];

// $images = $data["images"];
$finalObject = new \stdClass();

//Step 1 : getting all the variables

$costPrice = $data["costprice"];
$gstPercentage = $data["gstpercentage"];
$sellingPrice = $data["sellingprice"];
$parlourPrice = $data["parlourprice"];
$batchNumber = $data["batchnumber"];
$barcode = $data["barcode"];

//upload all the images

try {
    $productId = getProductIdFromBarcode($barcode, $con);
    if (!checkIfPriceExist($productId, $con, $costPrice)) {
        $maxProductPriceId = getCurrentId("product_prices_id", "product_prices", $con);
        $query = "insert into product_prices values(" . $maxProductPriceId . "," . $productId . ",'" . $costPrice . "','" . 0 . "','" . $gstPercentage . "'," . $sellingPrice . ",'" . $parlourPrice . "','" . $batchNumber . "')";
        if (mysqli_query($con, $query)) {
                addLog("price", "created", "Product Price ID: " . $maxProductPriceId . " Price: " . $costPrice . " <br/>created by: " . getUserNameFromUserId($user_id, $con), $con);
                $finalObject->status = "success";
                $finalObject->message = "Price Added successfully!!!";
            
        } else {
            $finalObject->status = "error";
            $finalObject->message = "Error #1002";
        }
    } else {
        $finalObject->status = "error";
        $finalObject->message = "Price already exist!";
    }
} catch (Exception $e) {
    $finalObject->status = "error";
    $finalObject->message = "Error #1000".$e;
}

mysqli_close($con);
$response = json_encode($finalObject);
echo $response;


function checkIfPriceExist($productId, $con, $costPrice)
{
    $isExist = false;

    $query = "select product_prices_cost_price from product_prices where product_id = " . $productId;

    $result = $con->query($query);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            if($row['product_prices_cost_price'] == $costPrice) {
                $isExist = true;
                break;
            }
        }
    }

    return $isExist;
}

function getProductIdFromBarcode($barcode, $con){
    $productId = 0;
    $query = "select product_id from products where product_barcode = '" . $barcode . "'";
    $result = $con->query($query);
    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        $productId = $row["product_id"];
    }
    return $productId;
}

function getFinancialYearDates($inputDate)
{
    $startDate = date('Y-m-d', strtotime('April 1', strtotime($inputDate)));
    $endDate = date('Y-m-d', strtotime('March 31', strtotime('+1 year', strtotime($startDate))));

    return array('start' => $startDate, 'end' => $endDate);
}