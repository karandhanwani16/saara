<?php

include "../../../services/config.php";
include "../../../services/helperFunctions.php";
include "../../../../services/env.php";

$data = $_POST["data"];
$data = json_decode($data, true);

$finalObject = new \stdClass();

//Step 1 : getting all the variables

$email = $data["email"];
$fname = $data["fname"];
$lname = $data["lname"];
$type = $data["type"];
$password = $data["password"];
//upload all the images

$currentTimeStamp = getCurrentTimestamp();
//upload all the images

$password = password_hash($password, PASSWORD_BCRYPT);

try {
    $userCheck=isUserExist($email, $con);
    if (!$userCheck) {
        $maxUserId = getCurrentId("user_id", "users", $con);
        $sql = "insert into users values(" . $maxUserId . ",'" . $email . "','" . $password . "','" . $fname . "','" . $lname . "','" . $type . "','" . $currentTimeStamp . "')";
        if (mysqli_query($con, $sql)) {
            $finalObject->status = "success";
            $finalObject->message = "User created successfully!!!".$userCheck;
        } else {
            $finalObject->status = "error";
            $finalObject->message = "Error #1001";
        }
    } else {
        $finalObject->status = "error";
        $finalObject->message = "User Already Exists!!!";
    }
} catch (Exception $e) {
    $finalObject->status = "error";
    $finalObject->message = "Error #1000";
}

function isUserExist($email, $con)
{
    $isExist = true;
    $sql = "select * from users where user_email='" . $email . "'";
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) == 0) {
        $isExist = false;
    }
    return $isExist;
}
$response = json_encode($finalObject);
echo $response;






