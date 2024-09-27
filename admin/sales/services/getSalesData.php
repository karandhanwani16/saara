<?php
include "../../services/config.php";
include "../../services/helperFunctions.php";

session_start();
$user_id = $_SESSION["user_id"];



$column = array("sale_id", "sale_date", "customer_name", "sale_net_amount", "cash_amount", "upi_amount","sale_discount_amount","sale_final_discount_amount", "sale_created_by", "sale_updated_by");

$query = "select sale_id,sale_date,customer_name,sale_net_amount,cash_amount,upi_amount,sale_discount_amount,sale_final_discount_amount,sale_created_by,sale_updated_by from sales";

if (isset($_POST["search"]["value"])) {
    // $query .= ' where category_id like "%' . $_POST["search"]["value"] . '%" or category_name like "%' . $_POST["search"]["value"] . '%"';
    $query .= ' where sale_id like "%' . $_POST["search"]["value"] . '%" or sale_date like "%' . $_POST["search"]["value"] . '%" or customer_name like "%' . $_POST["search"]["value"] . '%" or sale_net_amount like "%' . $_POST["search"]["value"] . '%" or cash_amount like "%' . $_POST["search"]["value"] . '%" or upi_amount like "%' . $_POST["search"]["value"] . '%" or sale_discount_amount like "%' . $_POST["search"]["value"] . '%" or sale_final_discount_amount like "%' . $_POST["search"]["value"] . '%" or sale_created_by like "%' . $_POST["search"]["value"] . '%" or sale_updated_by like "%' . $_POST["search"]["value"] . '%"';
}

if (isset($_POST["order"])) {
    $query .= " ORDER BY " . $column[$_POST['order']['0']['column']] . " " . $_POST['order']['0']['dir'];
} else {
    $query .= ' ORDER BY sale_id desc';
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
    $sub_array[] = $row['sale_id'];
    $sub_array[] = "<a href='singleSales.php?id=" . $row['sale_id'] . "' class='select-btn'>Select</a>";
    $sub_array[] = "<div class='btn delete-btn' data-id='" . $row["sale_id"] . "'>Delete</div>";
    $sub_array[] = "<div class='btn download-btn' data-id='" . $row["sale_id"] . "'>Download</div>";
    $sub_array[] = formatDateString($row['sale_date']);
    $sub_array[] = $row['customer_name'];
    $sub_array[] = moneyFormatIndia($row['sale_net_amount']);
    $sub_array[] = moneyFormatIndia($row['cash_amount']);
    $sub_array[] = moneyFormatIndia($row['upi_amount']);
    $sub_array[] = moneyFormatIndia($row['sale_discount_amount']);
    $sub_array[] = moneyFormatIndia($row['sale_final_discount_amount']);
    $sub_array[] = getUserNameFromUserId($row['sale_created_by'],$con);
    $sub_array[] = getUserNameFromUserId($row['sale_updated_by'],$con);
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
