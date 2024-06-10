<?php
include "../../../services/config.php";
include "../../../services/helperFunctions.php";

$column = array("api_log_id", "api_status", "api_endpoint", "api_call_timestamp", "api_response", "api_log_user");

$query = "select api_log_id,api_status,api_endpoint,api_call_timestamp,api_response,api_log_user from api_logs where ";

if (isset($_POST["search"]["value"])) {
    $query .= 'api_log_id like "%' . $_POST["search"]["value"] . '%" or api_status like "%' . $_POST["search"]["value"] . '%" or api_endpoint like "%' . $_POST["search"]["value"] . '%" or api_call_timestamp like "%' . $_POST["search"]["value"] . '%" or api_response like "%' . $_POST["search"]["value"] . '%" or api_log_user like "%' . $_POST["search"]["value"] . '%"';
}

if (isset($_POST["order"])) {
    $query .= " ORDER BY " . $column[$_POST['order']['0']['column']] . " " . $_POST['order']['0']['dir'];
} else {
    $query .= ' ORDER BY api_log_id desc';
}
$query1 = '';

if ($_POST["length"] != -1) {
    $query1 = ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}


$result = $con->query($query);
$number_filter_row = $result->num_rows;
$result = $con->query($query . $query1);


// echo $query . $query1;

$data = array();

while ($row = $result->fetch_assoc()) {

    $sub_array = array();
    // $sub_array[] = "<a href='singleproduct.php?id=" . $row['product_id'] . "' class='select-btn'>Select</a>";
    $sub_array[] = $row['api_log_id'];
    $sub_array[] = $row['api_status'] == "success" ? "<div class='label success'>Success</div>" : "<div class='label failed'>Failed</div>";
    $sub_array[] = $row['api_endpoint'];
    $sub_array[] = $row['api_call_timestamp'];
    $sub_array[] = $row['api_response'];
    $sub_array[] = $row['api_log_user'];
    $data[] = $sub_array;
}

function count_all_data($con)
{
    $query = "select * from api_logs";
    $result = $con->query($query);
    return $result->num_rows;
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => count_all_data($con),
    'recordsFiltered' => $number_filter_row,
    'data' => $data
);

echo json_encode($output);