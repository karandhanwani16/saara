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

$name = htmlspecialchars($data["name"]);
$location = htmlspecialchars($data["location"]);
$mobile = htmlspecialchars($data["mobile"]);
$description = htmlspecialchars($data["description"]);

try {
    $maxSupplierId = getCurrentId("supplier_id", "supplier", $con);
    if (!isExist($name, "supplier", "supplier_name", $con)) {
        $query = "insert into supplier values(" . $maxSupplierId . ",'" . $name . "','" . $location . "','" . $mobile . "','" . $description . "'," . $user_id . ",'" . getCurrentTimestamp() . "'," . $user_id . ",'" . getCurrentTimestamp() . "')";
        if (mysqli_query($con, $query)) {
            addLog("supplier", "created", "Supplier: " . $name . " <br/>created by: " . getUserNameFromUserId($user_id, $con), $con);
            $finalObject->status = "success";
            $finalObject->message = "Supplier upload successfully!";
        } else {
            $finalObject->status = "error";
            $finalObject->message = "Error #1001";
        }
    } else {
        $finalObject->status = "error";
        $finalObject->message = "Supplier already exists!";
    }
} catch (Exception $e) {
    $finalObject->status = "error";
    $finalObject->message = "Error #1000";
}

mysqli_close($con);


$response = json_encode($finalObject);
echo $response;

?>