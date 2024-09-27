<?php
include "../../services/config.php";
include "../../services/helperFunctions.php";

session_start();
$user_id = $_SESSION["user_id"];


$column = array("p.product_id","c.category_name","b.brand_name","p.product_name","p.product_barcode","p.product_code","p.product_description","p.product_created_by","p.product_updated_by");

$query = "select p.product_id,c.category_name,b.brand_name,p.product_name,p.product_barcode,p.product_code,p.product_description,p.product_created_by,p.product_updated_by from products p inner join category c on p.category_id=c.category_id inner join brand b on p.brand_id=b.brand_id";

if (isset($_POST["search"]["value"])) {
    // $query .= ' where category_id like "%' . $_POST["search"]["value"] . '%" or category_name like "%' . $_POST["search"]["value"] . '%"';
    $query .= ' where p.product_id like "%' . $_POST["search"]["value"] . '%" or p.product_name like "%' . $_POST["search"]["value"] . '%" or c.category_name like "%' . $_POST["search"]["value"] . '%" or b.brand_name like "%' . $_POST["search"]["value"] . '%" or p.product_barcode like "%' . $_POST["search"]["value"] . '%" or p.product_code like "%' . $_POST["search"]["value"] . '%" or p.product_description like "%' . $_POST["search"]["value"] . '%" or p.product_created_by like "%' . $_POST["search"]["value"] . '%" or p.product_updated_by like "%' . $_POST["search"]["value"] . '%"';
}

if (isset($_POST["order"])) {
    $query .= " ORDER BY " . $column[$_POST['order']['0']['column']] . " " . $_POST['order']['0']['dir'];
} else {
    $query .= ' ORDER BY p.product_id desc';
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
    $sub_array[] = $row['product_id'];
    $sub_array[] = "<a href='singleProduct.php?id=" . $row['product_id'] . "' class='select-btn'>Select</a>";
    $sub_array[] = "<div class='btn delete-btn' data-id='" . $row["product_id"] . "'>Delete</div>";
    // $sub_array[] = "<div class='btn print-barcode-btn' data-id='" . $row["product_id"] . "'>Print</div>";
    $sub_array[] = $row['category_name'];
    $sub_array[] = $row['brand_name'];
    $sub_array[] = $row['product_name'];
    $sub_array[] = $row['product_barcode'];
    $sub_array[] = $row['product_code'];
    $sub_array[] = $row['product_description'];
    $sub_array[] = getUserNameFromUserId($row['product_created_by'],$con);
    $sub_array[] = getUserNameFromUserId($row['product_updated_by'],$con);
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
