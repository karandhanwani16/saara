<?php

include "../../services/config.php";
include "../../services/helperFunctions.php";

$data = $_POST["data"];
$data = json_decode($data, true);

session_start();
$user_id = $_SESSION["user_id"];

$finalObject = new \stdClass();

//Step 1 : getting all the variables
$name = addslashes($data["name"]);


try {
    if (!checkBrandExist($name, $con)) {
        $maximumBrandId = getCurrentId("brand_id", "brand", $con);
        $sql = "insert into brand values(" . $maximumBrandId . ",'" . $name . "',".$user_id.",'".getCurrentTimestamp()."',".$user_id.",'".getCurrentTimestamp()."')";
        if (mysqli_query($con, $sql)) {
            $finalObject->status = "success";
            $finalObject->message = "Brand uploaded successfully!";
        }
    } else {
        $finalObject->status = "error";
        $finalObject->message = "Brand already exist!";
    }
} catch (Exception $e) {
    $finalObject->status = "error";
    $finalObject->message = "Error #1000";
}

$response = json_encode($finalObject);
echo $response;


function checkBrandExist($name, $con)
{
    $brandExist = false;
    try {
        $sql = "select * from brand where brand_name = '" . $name . "'";
        $result = mysqli_query($con, $sql);
        if ($result->num_rows > 0) {
            $brandExist = true;
        }
    } catch (Exception $e) {
        $brandExist = false;
    }
    return $brandExist;
}
