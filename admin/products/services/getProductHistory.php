<?php
include "../../services/config.php";
include "../../services/helperFunctions.php";

session_start();
$user_id = $_SESSION["user_id"];
$product_id = $_GET["product_id"];
$column = array("i.purchase_item_product_id"," p.purchase_id", "p.purchase_date", "s.supplier_name", "p.purchase_type", "p.purchase_signed_by", "i.purchase_item_product_quantity", "pr.product_prices_cost_price", "pr.product_prices_batch_number", "p.purchase_amount");

$query = "SELECT i.purchase_item_product_id, p.purchase_id, p.purchase_date, s.supplier_name, p.purchase_type, p.purchase_signed_by, i.purchase_item_product_quantity, pr.product_prices_cost_price, pr.product_prices_batch_number, p.purchase_amount FROM purchase p JOIN purchase_items i ON p.purchase_id = i.purchase_id JOIN supplier s ON p.supplier_id = s.supplier_id JOIN product_prices pr ON i.purchase_item_price_id = pr.product_prices_id where i.purchase_item_product_id=".$product_id;

if (isset($_POST["search"]["value"])) {
    $search_value = $_POST["search"]["value"];
    $query .= " AND (
    i.purchase_item_product_id LIKE '%$search_value%' OR
    p.purchase_id LIKE '%$search_value%' OR
    p.purchase_date LIKE '%$search_value%' OR
    s.supplier_name LIKE '%$search_value%' OR
    p.purchase_type LIKE '%$search_value%' OR
    p.purchase_signed_by LIKE '%$search_value%' OR
    pr.product_prices_cost_price LIKE '%$search_value%' OR
    pr.product_prices_batch_number LIKE '%$search_value%' OR
    p.purchase_amount LIKE '%$search_value%'
)";
}

if (isset($_POST["order"])) {
    $query .= " ORDER BY " . $column[$_POST['order']['0']['column']] . " " . $_POST['order']['0']['dir'];
} else {
    $query .= ' ORDER BY p.purchase_id desc';
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
    $sub_array[] = $row['purchase_id'];              
    $sub_array[] = $row['purchase_date'];            
    $sub_array[] = $row['supplier_name'];            
    $sub_array[] = $row['purchase_type'];            
    $sub_array[] = $row['purchase_signed_by'];       
    $sub_array[] = $row['purchase_item_product_quantity']; 
    $sub_array[] = $row['product_prices_cost_price'];      
    $sub_array[] = $row['product_prices_batch_number'];    
    $sub_array[] = $row['purchase_amount']; 
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
