<?php

include "../../../services/config.php";
include "../../../services/helperFunctions.php";

$data = $_POST["data"];
$data = json_decode($data, true);

session_start();
$user_id = $_SESSION["user_id"];

$finalObject = new \stdClass();

//Step 1 : getting all the variables
$id = $data["id"];
$email = $data["email"];
$fname = $data["fname"];
$lname = $data["lname"];
$type = $data["type"];
$password = $data["password"];

$currentTimeStamp = getCurrentTimestamp();


$hashed_password = password_hash($password, PASSWORD_BCRYPT);

try {
    if(!isUserExistUpdate($id,$email,$con)){
        $sql = "update users set user_email = '" . $email . "',user_password='" . $hashed_password . "',user_first_name='" . $fname . "',user_last_name='" . $lname . "',user_type='" . $type . "' where user_id = " . $id;

        if (mysqli_query($con, $sql)) {
            $finalObject->status = "success";
            $finalObject->message = "User updated successfully!!!";
        }
    }
    else{
        $finalObject->status = "error";
        $finalObject->message = "User Already Exists!!!";
    }

} catch (Exception $e) {
    $finalObject->status = "error";
    $finalObject->message = "Error #1000".$e;
}
function isUserExistUpdate($id,$email, $con)
{
    $isExist = true;
    $sql = "select * from users where user_email='" . $email . "' and user_id!=".$id;
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) == 0) {
        $isExist = false;
    }
    return $isExist;
}

$response = json_encode($finalObject);
echo $response;