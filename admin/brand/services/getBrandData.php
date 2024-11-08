<?php
include "../../services/config.php";
include "../../services/helperFunctions.php";

session_start();
$user_id = $_SESSION["user_id"];


$column = array("brand_id", "brand_name");

$query = "select brand_id,brand_name from brand";

if (isset($_POST["search"]["value"])) {
    $query .= ' where brand_id like "%' . $_POST["search"]["value"] . '%" or brand_name like "%' . $_POST["search"]["value"] . '%"';
}

if (isset($_POST["order"])) {
    $query .= " ORDER BY " . $column[$_POST['order']['0']['column']] . " " . $_POST['order']['0']['dir'];
} else {
    $query .= ' ORDER BY brand_id desc';
}
$query1 = '';

if ($_POST["length"] != -1) {
    $query1 = ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$result = $con->query($query);
$number_filter_row = $result->num_rows;
$result = $con->query($query . $query1);



$data = array();

while ($row = $result->fetch_assoc()) {

    $sub_array = array();
    $sub_array[] = $row['brand_id'];
    $sub_array[] = "<a href='singleBrand.php?id=" . $row['brand_id'] . "' class='select-btn'>Select</a>";
    $sub_array[] = "<div class='btn delete-btn' data-id='" . $row["brand_id"] . "'>Delete</div>";
    $sub_array[] = $row['brand_name'];
    $data[] = $sub_array;
}

function count_all_data($con)
{
    $query = "select * from brand";
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
