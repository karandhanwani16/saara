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

$date = $data["date"];
$supplier = $data["supplier"];
$purchaseNo = $data["purchaseNo"];
$amount = $data["amount"];
$id = $data["id"];
$rows = $data["rows"];

try {


    $oldDetails = getOldDetails($id, $con);

    $query = "update purchase set purchase_date = '" . $date . "', supplier_id = " . $supplier . ", purchase_amount = '" . $amount . "',purchase_updated_by = " . $user_id . ", purchase_updated_at = '" . getCurrentTimestamp() . "' where purchase_id = " . $id;


    if (mysqli_query($con, $query)) {
        if (updatePurchaseItems($rows, $id, $con)) {

            addLog("purchase", "updated", "Purchase ID: " . $id . "  <br/> Supplier changed : " . getColumnValueFromTable("supplier_name", "supplier", "supplier_id", $oldDetails["supplier"], $con) . " -> " . getColumnValueFromTable("supplier_name", "supplier", "supplier_id", $supplier, $con) . " <br/>Date changed: " . formatDateForView($oldDetails["date"]) . " -> " . formatDateForView($date) . " <br/>Amount changed : " . $oldDetails["amount"] . " -> " . $amount . " <br/>updated by: " . getUserNameFromUserId($user_id, $con), $con);

            $finalObject->status = "success";
            $finalObject->message = "Purchase updated successfully!!!";
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
    $finalObject->message = "Error #1000" . $e;
}

mysqli_close($con);
$response = json_encode($finalObject);
echo $response;
function checkIfPurchaseNoExist($purchaseId, $purchaseNo, $date, $supplier, $con)
{
    $isExist = false;

    $currentFinancialYearDates = getFinancialYearDates($date);

    $query = "select * from purchase where purchase_id != " . $purchaseId . " and purchase_no = '" . $purchaseNo . "' and purchase_date between '" . $currentFinancialYearDates["start"] . "' and '" . $currentFinancialYearDates["end"] . "' and supplier_id = " . $supplier;


    $result = $con->query($query);

    if ($result->num_rows > 0) {
        $isExist = true;
    }


    return $isExist;
}


function updatePurchaseItems($rows, $purchaseId, $con)
{
    $updated = true;


    // Get the current stock count from purchase items by product_prices_id
    $productPriceWiseStock = getProductPriceWiseStock($purchaseId, $con);

    // Revert stock based on the current stock levels
    foreach ($productPriceWiseStock as $productPrice) {
        $priceId = intval($productPrice["priceId"]);
        $stock = $productPrice["quantity"];

        if ($priceId !== 0) {
            // Update stock by reducing the stock for each product price ID
            $query = "update product_prices set product_prices_stock = product_prices_stock - $stock where product_prices_id = $priceId";

            if (!mysqli_query($con, $query)) {
                $updated = false;
            }
        }
    }

    // Delete old purchase items
    $query = "delete from purchase_items where purchase_id = " . $purchaseId;
    if (!mysqli_query($con, $query)) {
        $updated = false;
    }

    if (!uploadPurchaseItems($rows, $purchaseId, $con)) {
        $updated = false;
    }
    return $updated;
}


function getProductPriceWiseStock($purchaseId, $con)
{
    $productPriceWiseStock = array();

    // Group stock by product_prices_id to accurately track quantities
    $query = "select purchase_item_price_id,purchase_item_product_quantity from purchase_items where purchase_id = " . $purchaseId;

    $result = $con->query($query);

    while ($row = $result->fetch_assoc()) {
        $productPriceWiseStock[] = array(
            "priceId" => $row["purchase_item_price_id"],
            "quantity" => $row["purchase_item_product_quantity"]
        );
    }

    return $productPriceWiseStock;
}

function uploadPurchaseItems($rows, $purchaseId, $con)
{
    $uploaded = true;

    foreach ($rows as $row) {

        // update the stock
        $productId = intval($row["product_id"]);
        if ($productId !== 0) {

            $query = "update product_prices set product_prices_stock = product_prices_stock + " . $row["quantity"] . " where product_prices_id = " . $row["priceId"];

            $queryResult = mysqli_query($con, $query);

            if (!$queryResult) {
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


function getOldDetails($id, $con)
{
    $oldDate = "";
    $oldSupplier = "";
    $oldAmount = "";
    $purchaseNo = "";

    $query = "select * from purchase where purchase_id = " . $id;

    $result = $con->query($query);

    $rowCount = $result->num_rows;

    if ($rowCount > 0) {
        $row = $result->fetch_assoc();
        $oldDate = $row["purchase_date"];
        $oldSupplier = $row["supplier_id"];
        $oldAmount = $row["purchase_amount"];
        $purchaseNo = $row["purchase_no"];
    }

    return array(
        "date" => $oldDate,
        "supplier" => $oldSupplier,
        "amount" => $oldAmount,
        "purchaseNo" => $purchaseNo
    );

}

