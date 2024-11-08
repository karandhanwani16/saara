<?php

include ("dateFunctions.php");


function generateOtp($n)
{
    $generator = "1357902468";
    $result = "";
    for ($i = 1; $i <= $n; $i++) {
        $result .= substr($generator, (rand() % (strlen($generator))), 1);
    }
    return $result;
}

function getCurrentId($field_name, $table_name, $con)
{
    try {
        $sql = "select max(" . $field_name . ") as 'maxid' from " . $table_name . "";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_row($result);
        if ($row[0] == null) {
            return 1;
        } else {
            return $row[0] + 1;
        }
        mysqli_close($con);
    } catch (Exception $e) {
        echo "<script>alert('" . $e->getMessage() . "')</script>";
    }
}

function sendEmailHtml($email, $subject, $finalPasswordMessage)
{

    $to = $email;
    $from = 'no-reply@yashhenterprises.co.in';
    $fromName = 'Yash Enterprises';
    $subject = $subject;
    $message = $finalPasswordMessage;
    $headers = "From: $from";
    // Set content-type header for sending HTML email 
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    // Additional headers 
    $headers .= 'From: ' . $fromName . '<' . $from . '>' . "\r\n";
    $ok = @mail($to, $subject, $finalPasswordMessage, $headers);
    return $ok;
}

function sendEmail($email_var, $email_subject, $message_var)
{
    $to = $email_var;
    $from = "no-reply@yashhenterprises.co.in";
    $subject = $email_subject;
    $message = $message_var;

    $headers = "From: $from";
    $ok = @mail($to, $subject, $message, $headers, "-f " . $from);
    return $ok;
}
function calculateMinutes($timeDiffObject)
{
    return ($timeDiffObject->y * 12 * 30 * 24 * 60) + ($timeDiffObject->m * 30 * 24 * 60) + ($timeDiffObject->d * 24 * 60) + ($timeDiffObject->h * 60) + ($timeDiffObject->i);
}


function updateVerificationTable($otp, $email_param, $con, $table_name)
{
    //get current time for update

    $currentTimestamp = getCurrentTimestamp();
    $sql = "update " . $table_name . " set verification_code='" . $otp . "',last_requested_at='" . $currentTimestamp . "' where user_id = (select user_id from users where user_email='" . $email_param . "')";
    if (mysqli_query($con, $sql)) {
        return true;
    } else {
        return false;
    }
    mysqli_close($con);
}


function emailExist($email_param, $con, $table_name)
{
    $query = "select last_requested_at from " . $table_name . " where user_id = (select user_id from users where user_email='" . $email_param . "')";
    $result = $con->query($query);
    $count = $result->num_rows;
    if ($count != 0) {
        return 1;
    } else {
        return 0;
    }
    mysqli_close($con);
}
function isEmailAlreadySent($email, $con, $otpValidTime, $table_name)
{
    $sql = "select last_requested_at from " . $table_name . " where user_id = (SELECT user_id FROM users where user_email='" . $email . "')";
    $result = $con->query($sql);
    $count = $result->num_rows;
    if ($count != 0) {
        $row = $result->fetch_assoc();
        $requestedTime = $row['last_requested_at'];
        $timeDiffObject = json_decode(getTimeDifference($requestedTime));
        $totalMinutes = calculateMinutes($timeDiffObject);
        if ($totalMinutes < $otpValidTime) {
            return true;
        } else {
            return false;
        }
    }
    mysqli_close($con);
}


function insertForgotVerificationTable($otp, $email, $con)
{
    $currentTimestamp = getCurrentTimestamp();
    $userId = getUserId($email, $con);
    $sql = "insert into forgot_verification values(" . $userId . ",'" . $currentTimestamp . "','" . $otp . "')";
    if (mysqli_query($con, $sql)) {
        return true;
    } else {
        return false;
    }
    mysqli_close($con);
}

function getUserId($user_email, $con)
{
    $userId = "";
    $query = "select user_id from users where user_email='" . $user_email . "'";
    $result = $con->query($query);
    $row = $result->fetch_assoc();
    $count = $result->num_rows;
    if ($count != 0) {
        $userId = $row['user_id'];
    }
    return $userId;
}

function getFilesFromDirectory($dir)
{
    $fileArray = [];
    if ($handle = opendir($dir)) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
                array_push($fileArray, $entry);
            }
        }
        closedir($handle);
    }
    return $fileArray;
}


