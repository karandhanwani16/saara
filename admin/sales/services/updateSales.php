<?php

require("../../services/config.php");
require("../../services/helperFunctions.php");
require("commonFunctions.php");


$currentSaleId = $_POST['saleId'];

// Get data from POST request
$saleDetails = json_decode($_POST['saleDetails'], true);
$paymentDetails = json_decode($_POST['paymentDetails'], true);
$saleRows = json_decode($_POST['saleRows'], true);
$totalDetails = json_decode($_POST['totalDetails'], true);
// Initialize finalObject to track stats and message
$finalObject = new \stdClass();
$finalObject->status = "";
$finalObject->message = "";
$finalObject->details = [
    "saleId" => '',
    "saleDate" => '',
    "customerName" => '',
    "customerPhone" => '',
    "cashAmount" => '',
    "upiAmount" => '',
    "total" => 0
];

// Validate and sanitize the received data
if (!$saleDetails || !$paymentDetails || !$saleRows) {
    $finalObject->status = 'error';
    $finalObject->message = 'Invalid data received';
    die(json_encode($finalObject));
}

// get Current User ID
session_start();
$user_id = $_SESSION["user_id"];


// Process sale details
$saleDate = $saleDetails['saleDate'];
$customerName = $saleDetails['customerName'];
$customerPhone = $saleDetails['customerPhone'];
$customerEmail = $saleDetails['customerEmail'];
$discount = $saleDetails['discount'];
$discountType = $saleDetails['discountType'];

// Process payment details
$cashAmount = $paymentDetails['cashAmount'];
$upiAmount = $paymentDetails['upiAmount'];

// Process total details
$totalBeforeTax = $totalDetails['grossTotal'];
$totalSgst = $totalDetails['sGstTotal'];
$totalCgst = $totalDetails['cGstTotal'];
$totalIgst = $totalDetails['iGstTotal'];
$totalBeforeDiscount = $totalDetails['totalBeforeDiscount'];
$totalDiscount = $totalDetails['discountTotal'];
$finalDiscount = $totalDetails['finalDiscount'];
$roundOff = $totalDetails['roundOff'];
$netTotal = $totalDetails['netTotal'];



try {

    // Update the stock back
    if (updateUpdatedStock($currentSaleId, $con)) {
        // Delete the sale items
        $deleteQuery = "delete from sale_items where sale_id = $currentSaleId";
        if (mysqli_query($con, $deleteQuery)) {
            // Update the sale table
            $updateQuery = "update sales set sale_date = '$saleDate', customer_name = '$customerName', customer_phone = '$customerPhone', customer_email = '$customerEmail', discount = '$discount', discount_type = '$discountType', cash_amount = '$cashAmount', upi_amount = '$upiAmount',sale_total_before_tax = '$totalBeforeTax',sale_sgst_amount = '$totalSgst',sale_cgst_amount = '$totalCgst',sale_igst_amount = '$totalIgst',sale_total_after_tax = '$totalBeforeDiscount',sale_discount_amount = '$totalDiscount',sale_final_discount_amount = '$finalDiscount',sale_roundoff = '$roundOff',sale_net_amount = '$netTotal',sale_updated_at = '" . getCurrentTimestamp() . "',sale_updated_by = $user_id where sale_id = $currentSaleId";

            if (mysqli_query($con, $updateQuery)) {
                // Insert new sale items
                if (uploadSaleRows($saleRows, $con, $currentSaleId)) {
                    // Update stock for new items
                    if (updateStock($saleRows, $con)) {
                        $finalObject->status = 'success';
                        $finalObject->message = 'Sale updated successfully';
                        $finalObject->details["saleId"] = $currentSaleId;
                        $finalObject->details["saleDate"] = $saleDate;
                        $finalObject->details["customerName"] = $customerName;
                        $finalObject->details["customerPhone"] = $customerPhone;
                        $finalObject->details["cashAmount"] = $cashAmount;
                        $finalObject->details["upiAmount"] = $upiAmount;
                        $finalObject->details["total"] = $netTotal;
                    } else {
                        $finalObject->status = 'error';
                        $finalObject->message = 'Error #1006';
                    }
                } else {
                    $finalObject->status = 'error';
                    $finalObject->message = 'Error #1005';
                }
            } else {
                $finalObject->status = 'error';
                $finalObject->message = 'Error #1004';
            }
        } else {
            $finalObject->status = 'error';
            $finalObject->message = 'Error #1003';
        }
    } else {
        $finalObject->status = 'error';
        $finalObject->message = 'Error #1002';
    }
} catch (Exception $e) {
    $finalObject->status = 'error';
    $finalObject->message = 'Error #1001';
}


echo json_encode($finalObject);


function updateUpdatedStock($currentSaleId, $con)
{
    $isUpdated = true;
    $oldSaleRows = getSaleItemsDetails($currentSaleId, $con);
    foreach ($oldSaleRows as $row) {
        $productId = $row['product_id'];
        $productPrice = $row['product_price'];
        $quantity = $row['quantity'];
        $query = "update product_prices set product_prices_stock = product_prices_stock + $quantity where product_id = $productId and product_prices_selling_price = '$productPrice'";
        if (!mysqli_query($con, $query)) {
            $isUpdated = false;
        }
    }
    return $isUpdated;
}
