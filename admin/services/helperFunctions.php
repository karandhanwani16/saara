<?php

// type: required
function isExist($value, $table_name, $column_name, $con)
{
    $isExist = false;
    $query = "select * from " . $table_name . " where " . $column_name . "='" . $value . "'";

    $result = $con->query($query);
    $totalCount = $result->num_rows;

    if ($totalCount != 0) {
        $isExist = true;
    }
    return $isExist;
}

// type: required
function isExistId($id, $table_name, $column_name, $con)
{
    $isExist = false;
    $query = "select * from " . $table_name . " where " . $column_name . " = " . $id;

    $result = $con->query($query);
    $totalCount = $result->num_rows;

    if ($totalCount != 0) {
        $isExist = true;
    }
    return $isExist;
}

function isUpdateExist($value, $table_name, $column_name, $current_id, $id_column_name, $con)
{
    $isExist = false;
    $query = "select * from " . $table_name . " where " . $column_name . "='" . $value . "' and " . $id_column_name . " != " . $current_id;

    $result = $con->query($query);
    $totalCount = $result->num_rows;

    if ($totalCount != 0) {
        $isExist = true;
    }
    return $isExist;
}
function delete_directory($dirname)
{
    $dir_handle = "";
    if (is_dir($dirname))
        $dir_handle = opendir($dirname);
    if (!$dir_handle)
        return false;
    while ($file = readdir($dir_handle)) {
        if ($file != "." && $file != "..") {
            if (!is_dir($dirname . "/" . $file))
                unlink($dirname . "/" . $file);
            else
                delete_directory($dirname . '/' . $file);
        }
    }
    closedir($dir_handle);
    rmdir($dirname);
    return true;
}



function isExistUpdate($value, $table_name, $column_name, $id_field, $id, $con)
{
    $sql = "select * from " . $table_name . " where " . $column_name . "='" . $value . "' and " . $id_field . " != " . $id;
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) == 0) {
        return FALSE;
    } else {
        return TRUE;
    }
    mysqli_close($con);
}

function getCurrentId($field_name, $table_name, $con)
{
    $sql = "select max(" . $field_name . ") as 'maxid' from " . $table_name;
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_row($result);
    if ($row[0] == null) {
        return 1;
    } else {
        return $row[0] + 1;
    }
    mysqli_close($con);
}

function random_password($length = 6)
{
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
    $password = substr(str_shuffle($chars), 0, $length);
    return $password;
}

function getCurrentTimestamp()
{
    // Set the new timezone
    date_default_timezone_set('Asia/Kolkata');
    return date('y-m-d G:i:s');
}

function getCurrentDate()
{
    // Set the new timezone
    date_default_timezone_set('Asia/Kolkata');
    return date('Y-m-d');
}


function formatDateString($date_string)
{
    $date = date_create($date_string);
    return date_format($date, "jS F, Y");
}
function formatDate($date_string)
{
    $date = date_create($date_string);
    return date_format($date, "jS F, Y");
}
function formatDateForView($date_string)
{
    $date = date_create($date_string);
    return date_format($date, "d-m-Y");
}

function sendEmail($email, $subject, $finalPasswordMessage)
{

    $to = $email;
    $from = "no-reply@yashhenterprises.co.in";
    $subject = $subject;
    $message = $finalPasswordMessage;

    $headers = "From: $from";
    $ok = @mail($to, $subject, $message, $headers, "-f " . $from);
    return $ok;
}



function getMonthWord($month)
{
    $word = "";
    switch ($month) {
        case '1':
            $word = "Jan";
            break;
        case '2':
            $word = "Feb";
            break;
        case '3':
            $word = "Mar";
            break;
        case '4':
            $word = "Apr";
            break;
        case '5':
            $word = "May";
            break;
        case '6':
            $word = "June";
            break;
        case '7':
            $word = "July";
            break;
        case '8':
            $word = "Aug";
            break;
        case '9':
            $word = "Sept";
            break;
        case '10':
            $word = "Oct";
            break;
        case '11':
            $word = "Nov";
            break;
        case '12':
            $word = "Dec";
            break;

        default:
            break;
    }
    return $word;
}

function convertNumberToWords($number)
{
    $words = "";
    $no = floor($number);
    $point = round($number - $no, 2) * 100;
    $hundred = null;
    $digits_1 = strlen($no);
    $i = 0;
    $str = array();
    $words = array(
        '0' => '',
        '1' => 'one',
        '2' => 'two',
        '3' => 'three',
        '4' => 'four',
        '5' => 'five',
        '6' => 'six',
        '7' => 'seven',
        '8' => 'eight',
        '9' => 'nine',
        '10' => 'ten',
        '11' => 'eleven',
        '12' => 'twelve',
        '13' => 'thirteen',
        '14' => 'fourteen',
        '15' => 'fifteen',
        '16' => 'sixteen',
        '17' => 'seventeen',
        '18' => 'eighteen',
        '19' => 'nineteen',
        '20' => 'twenty',
        '30' => 'thirty',
        '40' => 'forty',
        '50' => 'fifty',
        '60' => 'sixty',
        '70' => 'seventy',
        '80' => 'eighty',
        '90' => 'ninety'
    );
    $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
    while ($i < $digits_1) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += ($divider == 10) ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str[] = ($number < 21) ? $words[$number] .
                " " . $digits[$counter] . $plural . " " . $hundred
                :
                $words[floor($number / 10) * 10]
                . " " . $words[$number % 10] . " "
                . $digits[$counter] . $plural . " " . $hundred;
        } else
            $str[] = null;
    }
    $str = array_reverse($str);
    $result = implode('', $str);

    // Adding paisa part
    $paisaWords = '';
    if ($point) {
        if ($point < 20) {
            $paisaWords = $words[$point];
        } else {
            $paisaWords = $words[floor($point / 10) * 10] . " " . $words[$point % 10];
        }
        $paisaWords .= " paisa";
    }

    $words = $result;
    if ($point) {
        $words .= " and " . $paisaWords;
    }
    $words = ucfirst($words);
    return $words;
}

function addLog($action_type, $action, $description, $con)
{
    $currentTimestamp = getCurrentTimestamp();
    $username = getUserName($con);
    $sql = "insert into logs values('" . $currentTimestamp . "','" . $action_type . "','" . $action . "','" . $username . "','" . $description . "')";
    mysqli_query($con, $sql);
}
function addApiLog($endPoint, $response, $status, $con)
{
    $apiLogId = getCurrentId("api_log_id", "api_logs", $con);
    $currentTimestamp = getCurrentTimestamp();
    $username = getUserName($con);
    $sql = "insert into api_logs values(" . $apiLogId . ",'" . $endPoint . "','" . $response . "','" . $status . "','" . $currentTimestamp . "','" . $username . "')";
    mysqli_query($con, $sql);
}

function getUserName($con)
{
    $userName = "";
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $user_id = $_SESSION["user_id"];
    $sql = "select user_first_name,user_last_name from users where user_id = " . $user_id;
    $result = $con->query($sql);
    $count = $result->num_rows;
    if ($count != 0) {
        $row = $result->fetch_assoc();
        $userName = $row['user_first_name'] . " " . $row['user_last_name'];
    }
    return $userName;
}

function getUserNameFromUserId($user_id, $con)
{
    $name = "";
    try {
        $query = "select user_first_name,user_last_name from users where user_id = " . $user_id;

        $result = $con->query($query);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $name = $row["user_first_name"] . " " . $row["user_last_name"];
        }

    } catch (Exception $e) {
        $name = "";
    }
    return $name;
}


function dateDiff($date1)
{
    date_default_timezone_set('Asia/Kolkata');
    $date1_ts = strtotime($date1);
    $date2_ts = strtotime(date('Y-m-d'));
    $diff = $date2_ts - $date1_ts;
    return round($diff / 86400);
}

function formatTo2Decimals($num)
{
    return number_format((float) $num, 2, '.', '');
}

function moneyFormatIndia($num)
{
    $num = formatTo2Decimals($num);
    return preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $num);
}


function getColumnValueFromTable($columne_name, $table_name, $id_column_name, $id_value, $con)
{
    $columnValue = "";
    $query = "select " . $columne_name . " from " . $table_name . " where " . $id_column_name . " = " . $id_value;
    $result = $con->query($query);
    $totalCount = $result->num_rows;
    if ($totalCount > 0) {
        $row = $result->fetch_assoc();
        $columnValue = $row[$columne_name];
    }
    return $columnValue;
}

function group_by($array, $key)
{
    $return = array();

    foreach ($array as $val) {
        $return[$val->$key][] = $val;
    }
    return $return;
}
function getCurrentEmail($user_id, $con)
{
    $email = "";
    $query = "select user_email from users where user_id = " . $user_id;
    // $finalString .= $query;
    $result = $con->query($query);
    $count = $result->num_rows;
    if ($count != 0) {
        $row = $result->fetch_assoc();
        $email = $row["user_email"];
    }
    return $email;
}


function getFinancialyear($date_plain)
{

    $date = date_create($date_plain);
    $month = date_format($date, 'm');

    if ($month >= 4) {
        $y = date_format($date, 'Y');
        $pt = date('Y', strtotime($date_plain . ' + 1 years'));
        $fy = $y . "-" . $pt;
    } else {
        $y = date('Y', strtotime($date_plain . ' - 1 years'));
        $pt = date_format($date, 'Y');

        // $y = date('Y', strtotime('-1 year'));
        // $pt = date_format($date, 'Y');
        $fy = $y . "-" . $pt;
    }
    return $fy;
}

function sendEmailHtml($email, $subject, $finalPasswordMessage, $billingMainEmail, $companyName)
{

    $to = $email;
    $from = $billingMainEmail;
    $fromName = $companyName;
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


function sendEmailWithAttachment($email, $subject, $message, $billingMainEmail, $companyName, $attachment)
{

    // Recipient 
    $to = $email;

    // Sender 
    $from = $billingMainEmail;
    $fromName = $companyName;

    // Email subject 
    $subject = $subject;

    // Attachment file 
    $file = $attachment;

    // Email body content 
    $htmlContent = $message;

    // Header for sender info 
    $headers = "From: $fromName" . " <" . $from . ">";

    // Boundary  
    $semi_rand = md5(time());
    $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

    // Headers for attachment  
    $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";

    // Multipart boundary  
    $message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" .
        "Content-Transfer-Encoding: 7bit\n\n" . $htmlContent . "\n\n";

    // Preparing attachment 
    if (!empty($file) > 0) {
        if (is_file($file)) {
            $message .= "--{$mime_boundary}\n";
            $fp = @fopen($file, "rb");
            $data = @fread($fp, filesize($file));

            @fclose($fp);
            $data = chunk_split(base64_encode($data));
            $message .= "Content-Type: application/octet-stream; name=\"" . basename($file) . "\"\n" .
                "Content-Description: " . basename($file) . "\n" .
                "Content-Disposition: attachment;\n" . " filename=\"" . basename($file) . "\"; size=" . filesize($file) . ";\n" .
                "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
        }
    }
    $message .= "--{$mime_boundary}--";
    $returnpath = "-f" . $from;

    // Send email 
    $mail = @mail($to, $subject, $message, $headers, $returnpath);

    return $mail;
}

function getNumberString($number, $length)
{
    $numberString = strval($number);
    $zeroesToAdd = $length - strlen($numberString);
    for ($i = 0; $i < $zeroesToAdd; $i++) {
        $numberString = '0' . $numberString;
    }
    return $numberString;
}



function getWholeTableRowDetails($table_name, $column_name, $id, $con)
{
    $row = array();
    $query = "select * from $table_name where $column_name = $id";
    $result = $con->query($query);
    $row = $result->num_rows > 0 ? $result->fetch_assoc() : [];
    return $row;
}

function getIdFromColumn($idColumn, $table_name, $search_columns, $search_values, $con)
{
    $id = 0;
    $query = "select $idColumn from $table_name where ";
    for ($i = 0; $i < count($search_columns); $i++) {
        // not put end if it is last loop
        if ($i != count($search_columns) - 1) {
            $query .= $search_columns[$i] . " = '" . $search_values[$i] . "' and ";
        } else {
            $query .= $search_columns[$i] . " = '" . $search_values[$i] . "'";
        }
    }
    $result = $con->query($query);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id = $row[$idColumn];
    }
    return $id;
}

function checkUpdateExist($name, $id, $table, $con)
{
    $isExist = false;
    try {
        $sql = "select * from " . $table . " where category_name = '" . $name . "' and category_id != " . $id;
        $result = mysqli_query($con, $sql);
        if ($result->num_rows > 0) {
            $isExist = true;
        }
    } catch (Exception $e) {
        $isExist = false;
    }
    return $isExist;
}



?>