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
$id = $data["id"];
$name = htmlspecialchars($data["name"]);
$location = htmlspecialchars($data["location"]);
$mobile = htmlspecialchars($data["mobile"]);
$description = htmlspecialchars($data["description"]);

try {
    $oldName = getColumnValueFromTable("supplier_name", "supplier", "supplier_id", $id, $con);

    if (!isUpdateExist($name, "supplier", "supplier_name", $id, "supplier_id", $con)) {
        $query = "update supplier set supplier_name = '" . $name . "', supplier_mobile = '" . $mobile . "', supplier_location = '" . $location . "', supplier_description = '" . $description . "',supplier_updated_by = " . $user_id . ", supplier_updated_at = '" . getCurrentTimestamp() . "' where supplier_id = " . $id;
        if (mysqli_query($con, $query)) {
            addLog("supplier", "updated", "Supplier Name updated from " . $oldName . " to: " . $name . " <br/>updated by: " . getUserNameFromUserId($user_id, $con), $con);
            $finalObject->status = "success";
            $finalObject->message = "Supplier update successfully!";
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