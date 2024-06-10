<?php

include "../../../services/config.php";
include "../../../services/helperFunctions.php";

$data = $_POST["data"];
$data =  json_decode($data, true);
session_start();

$user_id = $_SESSION["user_id"];

$includedArray = $data["invoiceFirst"];
$finalObject =  new \stdClass();



try {
    // delete query
    $sql = "delete from invoice_position_first";
    if (mysqli_query($con, $sql)) {
        if (insertInvoicePosition($includedArray, $con)) {
            $finalObject->status = "success";
            $finalObject->message = "Invoice Position Updated successfully!!!";
        }
        // new insert start 
        else {
            $finalObject->status = "error";
            $finalObject->message = "Error #1002";
        }
    }
    // main delete if end
    else {
        $finalObject->status = "error";
        $finalObject->message = "Error #1001";
    }
} catch (Exception $e) {
    $finalObject->status = "error";
    $finalObject->message = "Error #1000";
}
$response = json_encode($finalObject);
echo $response;

function insertInvoicePosition($includedArray, $con)
{
    $uploaded = true;
    for ($i = 0; $i < count($includedArray); $i++) {
        $firmId = $includedArray[$i]["id"];
        $query = "insert into invoice_position_first values(" . $firmId . ")";
        if (!mysqli_query($con, $query)) {
            $uploaded = false;
        }
    }
    return $uploaded;
}
