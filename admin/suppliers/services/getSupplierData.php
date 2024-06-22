<?php
include "../../services/config.php";
include "../../services/helperFunctions.php";

$column = array("supplier_id", "supplier_name", "supplier_mobile", "supplier_location", "supplier_description");

$query = "select supplier_id,supplier_name,supplier_mobile,supplier_location,supplier_description,supplier_created_by,supplier_created_at,supplier_updated_at,supplier_updated_by from supplier where ";

if (isset($_POST["search"]["value"])) {
    $query .= 'supplier_id like "%' . $_POST["search"]["value"] . '%" or supplier_name like "%' . $_POST["search"]["value"] . '%" or supplier_mobile like "%' . $_POST["search"]["value"] . '%" or supplier_location like "%' . $_POST["search"]["value"] . '%" or supplier_description like "%' . $_POST["search"]["value"] . '%" ';

}
if (isset($_POST["order"])) {
    $query .= " ORDER BY " . $column[$_POST['order']['0']['column']] . " " . $_POST['order']['0']['dir'];
} else {
    $query .= ' ORDER BY supplier_id desc';
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
    $sub_array[] = $row['supplier_id'];
    $sub_array[] = "<a href='singleSupplier.php?id=" . $row['supplier_id'] . "' class='select-btn'>Select</a>";
    $sub_array[] = "<div class='btn delete-btn' data-id='" . $row["supplier_id"] . "'>Delete</div>";

    $sub_array[] = $row['supplier_name'];
    $sub_array[] = $row['supplier_mobile'];
    $sub_array[] = $row['supplier_location'];
    $sub_array[] = $row['supplier_description'];
    $sub_array[] = getUserNameFromUserId($row["supplier_created_by"], $con);
    $sub_array[] = getUserNameFromUserId($row["supplier_updated_by"], $con);

    $data[] = $sub_array;
}

function count_all_data($con)
{
    $query = "select * from supplier";
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
