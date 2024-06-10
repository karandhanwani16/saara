<?php
include "../../../services/config.php";
include "../../../services/helperFunctions.php";


$finalObject = new \stdClass();

$query = "select firm_id,firm_name from firm where firm_id not in (select firm_id from invoice_position_first)";
$result = $con->query($query);
$number_filter_row = $result->num_rows;

$finalObject->notincluded = array();

if ($number_filter_row > 0) {
    while ($row = $result->fetch_assoc()) {
        $sub_array = array();
        $sub_array[] = $row["firm_id"];
        $sub_array[] = $row["firm_name"];
        $finalObject->notincluded[] = $sub_array;
    }
}

$query = "select f.firm_id,f.firm_name from invoice_position_first ipf,firm f where ipf.firm_id = f.firm_id";
$result = $con->query($query);
$number_filter_row = $result->num_rows;

$finalObject->included = array();

if ($number_filter_row > 0) {
    while ($row = $result->fetch_assoc()) {
        $sub_array = array();
        $sub_array[] = $row["firm_id"];
        $sub_array[] = $row["firm_name"];
        $finalObject->included[] = $sub_array;
    }
}

echo json_encode($finalObject);
