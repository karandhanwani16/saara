<?php
include "../../services/config.php";
include "../../services/helperFunctions.php";

session_start();
$user_id = $_SESSION["user_id"];



$column = array("p.purchase_id", "p.supplier_id", "p.purchase_no", "p.purchase_date", "p.purchase_amount", "p.purchase_created_by", "p.purchase_updated_by","s.supplier_name");

$query = "select p.purchase_id,p.purchase_no,p.purchase_date,s.supplier_name,p.purchase_amount,p.purchase_created_by,p.purchase_updated_by from purchase p,supplier s where p.supplier_id = s.supplier_id ";


if (isset($_POST["search"]["value"])) {
    $query .= ' and (p.purchase_id like "%' . $_POST["search"]["value"] . '%" or p.purchase_date like "%' . $_POST["search"]["value"] . '%" or s.supplier_name like "%' . $_POST["search"]["value"] . '%" or p.purchase_amount like "%' . $_POST["search"]["value"] . '%" or p.purchase_no like "%' . $_POST["search"]["value"] . '%")';

}

if (isset($_POST["order"])) {
    $query .= " ORDER BY " . $column[$_POST['order']['0']['column']] . " " . $_POST['order']['0']['dir'];
} else {
    $query .= ' ORDER BY purchase_id desc';
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
    $sub_array[] = $row['purchase_id'];
    $sub_array[] = "<a href='singlePurchase.php?id=" . $row['purchase_id'] . "' class='select-btn'>Select</a>";
    $sub_array[] = "<div class='btn delete-btn' data-id='" . $row["purchase_id"] . "'>Delete</div>";
    $sub_array[] = $row['purchase_no'];
    $sub_array[] = formatDateString($row['purchase_date']);
    $sub_array[] = $row['supplier_name'];
    $sub_array[] = moneyFormatIndia($row['purchase_amount']);
    $sub_array[] = getUserNameFromUserId($row['purchase_created_by'],$con);
    $sub_array[] = getUserNameFromUserId($row['purchase_updated_by'],$con);
    $data[] = $sub_array;
}

function count_all_data($con)
{
    $query = "select * from products";
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
