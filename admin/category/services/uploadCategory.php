<?php

include "../../services/config.php";
include "../../services/helperFunctions.php";

$data = $_POST["data"];
$data = json_decode($data, true);

session_start();
$user_id = $_SESSION["user_id"];


// $images = $data["images"];
$finalObject = new \stdClass();

//Step 1 : getting all the variables
$name = htmlspecialchars($data["name"], ENT_QUOTES, 'UTF-8');

//upload all the images

try {
    if (!checkCategoryExist($name, $con)) {
        $maximumCategoryId = getCurrentId("category_id", "category", $con);
        $sql = "insert into category values(" . $maximumCategoryId . ",'" . $name . "',".$user_id.",'".getCurrentTimestamp()."',".$user_id.",'".getCurrentTimestamp()."')";
        if (mysqli_query($con, $sql)) {
            $finalObject->status = "success";
            $finalObject->message = "Category upload successfully!";
        }
    } else {
        $finalObject->status = "error";
        $finalObject->message = "Category already exist!";
    }
} catch (Exception $e) {
    $finalObject->status = "error";
    $finalObject->message = "Error #1000".$e;
}

// $image_data = getImageFromBase64($data->image);


$response = json_encode($finalObject);
echo $response;


function checkCategoryExist($name, $con)
{
    $categoryExist = false;
    try {
        $sql = "select * from category where category_name = '" . $name . "'";
        $result = mysqli_query($con, $sql);
        if ($result->num_rows > 0) {
            $categoryExist = true;
        }
    } catch (Exception $e) {
        $categoryExist = false;
    }
    return $categoryExist;
}
