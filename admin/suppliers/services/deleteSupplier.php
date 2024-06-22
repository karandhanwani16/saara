<?php

require '../../services/config.php';
require '../../services/helperFunctions.php';

session_start();
$user_id = $_SESSION["user_id"];

$supplierId = $_POST["id"];



$finalObject = new \stdClass();

try {
    $supplierName = getColumnValueFromTable("supplier_name", "supplier", "supplier_id", $supplierId, $con);
    $query = "delete from supplier where supplier_id = " . $supplierId;
    if (mysqli_query($con, $query)) {
        addLog("Supplier", "deleted", "Supplier: " . $supplierName . " <br/> Deleted by: " . getUserNameFromUserId($user_id, $con), $con);
        $finalObject->status = "success";
        $finalObject->message = "Product Deleted successfully!!";
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



?>