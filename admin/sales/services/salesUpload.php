<?php


require("../../services/config.php");
require("../../services/helperFunctions.php");
require("commonFunctions.php");

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

    $maximumSaleId = getCurrentId('sale_id', 'sales', $con);

    $stockDetails = checkStock($saleRows, $con);

    if ($stockDetails->isStockAvailable) {
        $query = "insert into sales values($maximumSaleId, '$saleDate', '$customerName', '$customerPhone', '$customerEmail', '$discount', '$discountType', '$cashAmount', '$upiAmount','$totalBeforeTax','$totalSgst','$totalCgst','$totalIgst','$totalBeforeDiscount','$totalDiscount','$finalDiscount','$roundOff','$netTotal','" . getCurrentTimestamp() . "'," . $user_id . ",'" . getCurrentTimestamp() . "'," . $user_id . ");";

        if (mysqli_query($con, $query)) {
            // upload sale rows
            if (uploadSaleRows($saleRows, $con, $maximumSaleId)) {

                // manage stock 

                if (updateStock($saleRows, $con)) {
                    $finalObject->status = 'success';
                    $finalObject->message = 'Sale uploaded successfully';
                    $finalObject->details["saleId"] = $maximumSaleId;
                    $finalObject->details["saleDate"] = $saleDate;
                    $finalObject->details["customerName"] = $customerName;
                    $finalObject->details["customerPhone"] = $customerPhone;
                    $finalObject->details["cashAmount"] = $cashAmount;
                    $finalObject->details["upiAmount"] = $upiAmount;
                    $finalObject->details["total"] = $netTotal;
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
        $finalObject->message = $stockDetails->message;
    }
} catch (Exception $e) {
    $finalObject->status = 'error';
    $finalObject->message = 'Error #1001';
}


echo json_encode($finalObject);

