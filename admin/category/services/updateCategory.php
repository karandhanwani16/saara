<?php

include "../../services/config.php";
include "../../services/helperFunctions.php";

$data = $_POST["data"];
$data = json_decode($data, true);

session_start();
$user_id = $_SESSION["user_id"];

$finalObject = new \stdClass();

//Step 1 : getting all the variables
$name = htmlspecialchars($data["name"]);
$id = $data["id"];


try {
    if (!checkCategoryExistUpdate($id,$name, $con)) {
        $sql = "update category set category_name = '" . $name . "',category_updated_by=" . $user_id . ",category_updated_at='" . getCurrentTimestamp() . "' where category_id = " . $id;
        
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
    $finalObject->message = "Error #1000";
}

$response = json_encode($finalObject);
echo $response;

function checkCategoryExistUpdate($id,$name, $con)
{
    $categoryExist = false;
    try {
        $sql = "select * from category where category_name = '" . $name . "' and category_id!=".$id;
        $result = mysqli_query($con, $sql);
        if ($result->num_rows > 0) {
            $categoryExist = true;
        }
    } catch (Exception $e) {
        $categoryExist = false;
    }
    return $categoryExist;
}