<?php

include "../../services/config.php";
include "../../services/helperFunctions.php";


$id = $_POST['id'];

$finalObject = new \stdClass();



$finalObject->name = "";
$finalObject->code = "";
$finalObject->sellingPrice = "";
$finalObject->parlourPrice = "";
$finalObject->barcode = "";

try {

    $query = "select product_name,product_code,product_selling_price,product_parlour_price,product_barcode from products where product_id = $id";

    $result = $con->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $finalObject->name = $row['product_name'];
        $finalObject->code = $row['product_code'];
        $finalObject->sellingPrice = $row['product_selling_price'];
        $finalObject->parlourPrice = $row['product_parlour_price'];
        $finalObject->barcode = $row['product_barcode'];


        $finalObject->status = "success";
        $finalObject->message = "success";

    } else {
        $finalObject->status = "error";
        $finalObject->message = "No Barcode found";
    }
} catch (Exception $e) {
    $finalObject->status = "error";
    $finalObject->message = "Error #1001";
}

echo json_encode($finalObject);

?>