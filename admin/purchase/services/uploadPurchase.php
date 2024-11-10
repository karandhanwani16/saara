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

$date = $data["date"];
$supplier = $data["supplier"];
$purchaseNo = $data["purchaseNo"];
$amount = $data["amount"];
$rows = $data["rows"];

//upload all the images

try {
    if (!checkIfPurchaseNoExist($purchaseNo, $date, $supplier, $con)) {
        $maxPurchaseId = getCurrentId("purchase_id", "purchase", $con);
        $query = "insert into purchase values(" . $maxPurchaseId . "," . $supplier . ",'" . $purchaseNo . "','" . $date . "','" . $amount . "'," . $user_id . ",'" . getCurrentTimestamp() . "'," . $user_id . ",'" . getCurrentTimestamp() . "')";
        if (mysqli_query($con, $query)) {
            if (uploadPurchaseItems($rows, $maxPurchaseId, $con)) {
                addLog("purchase", "created", "Purchase ID: " . $maxPurchaseId . " Supplier: " . getColumnValueFromTable("supplier_name", "supplier", "supplier_id", $supplier, $con) . " Date: " . formatDateForView($date) . " Amount: " . $amount . " <br/>created by: " . getUserNameFromUserId($user_id, $con), $con);
                $finalObject->status = "success";
                $finalObject->message = "Purchase upload successfully!!!";
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
        $finalObject->message = "Purchase with same number already exist!";
    }
} catch (Exception $e) {
    $finalObject->status = "error";
    $finalObject->message = "Error #1000".$e;
}

mysqli_close($con);
$response = json_encode($finalObject);
echo $response;


function checkIfPurchaseNoExist($purchaseNo, $date, $supplier, $con)
{
    $isExist = false;

    $currentFinancialYearDates = getFinancialYearDates($date);

    $query = "select * from purchase where purchase_no = '" . $purchaseNo . "' and purchase_date between '" . $currentFinancialYearDates["start"] . "' and '" . $currentFinancialYearDates["end"] . "' and supplier_id = " . $supplier;

    $result = $con->query($query);

    if ($result->num_rows > 0) {
        $isExist = true;
    }

    return $isExist;
}

function uploadPurchaseItems($rows, $purchaseId, $con)
{
    $uploaded = true;
    foreach ($rows as $row) {

        // update the stock
        $productId = intval($row["product_id"]);
        if ($productId !== 0) {

            $query = "update product_prices set product_prices_stock = product_prices_stock + " . $row["quantity"] . " where product_id = " . $productId . " and product_prices_cost_price = " . $row["price"];

            if (!mysqli_query($con, $query)) {
                $uploaded = false;
            }
        }

        $maxPurchaseItemId = getCurrentId("purchase_item_id", "purchase_items", $con);
        $query = "insert into purchase_items values(" . $maxPurchaseItemId . "," . $purchaseId . "," . $productId . ",'" . $row["product_name"] . "'," . $row["quantity"] . "," . $row["priceId"] . "," . $row["price"] . ",'" . $row["gstPercentage"] . "','" . (($row["isIGST"] == 1) ? "true" : "false") . "'," . $row["total"] . ")";

        if (!mysqli_query($con, $query)) {
            $uploaded = false;
        }
    }
    return $uploaded;
}

function getFinancialYearDates($inputDate)
{
    $startDate = date('Y-m-d', strtotime('April 1', strtotime($inputDate)));
    $endDate = date('Y-m-d', strtotime('March 31', strtotime('+1 year', strtotime($startDate))));

    return array('start' => $startDate, 'end' => $endDate);
}