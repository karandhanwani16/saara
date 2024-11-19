<?php
include "../../services/config.php";
include "../../services/helperFunctions.php";

session_start();
$user_id = $_SESSION["user_id"];
$product_id = $_GET["product_id"];

$column = array("s.sale_id", "s.sale_date", "s.sales_attended_by", "s.customer_name", "s.sale_net_amount", "si.discount", "si.discount_type", "si.product_id", "si.quantity", "pr.product_prices_cost_price", "pr.product_prices_batch_number");

$query = "SELECT s.sale_id, s.sale_date, s.sales_attended_by, s.customer_name, s.sale_net_amount, si.discount_type, si.discount, si.product_id, si.quantity, pr.product_prices_selling_price, pr.product_prices_batch_number FROM sales s JOIN sale_items si ON s.sale_id = si.sale_id JOIN product_prices pr ON si.product_price_id = pr.product_prices_id WHERE si.product_id=".$product_id;

// SELECT s.sale_id, s.sale_date, s.sales_attended_by, s.customer_name, s.sale_net_amount, si.discount_type, si.discount, si.product_id, si.quantity, pr.product_prices_selling_price, pr.product_prices_batch_number FROM sales s JOIN sale_items si ON s.sale_id = si.sale_id JOIN product_prices pr ON si.product_price_id = pr.product_prices_id WHERE si.product_id=1;


if (isset($_POST["search"]["value"])) {
    $search_value = $_POST["search"]["value"];
    $query .= " AND (
        s.sale_id = '$search_value' OR
        s.sale_date = '$search_value' OR
        s.sales_attended_by = '$search_value' OR
        s.customer_name = '$search_value' OR
        s.sale_net_amount = '$search_value' OR
        si.discount_type = '$search_value' OR
        si.discount = '$search_value' OR
        si.quantity = '$search_value' OR
        pr.product_prices_selling_price = '$search_value' OR
        pr.product_prices_batch_number = '$search_value'
    )";
}

if (isset($_POST["order"])) {
    $query .= " ORDER BY " . $column[$_POST['order']['0']['column']] . " " . $_POST['order']['0']['dir'];
} else {
    $query .= ' ORDER BY s.sale_id desc';
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
    $sub_array[] = $row['sale_id'];                  
    $sub_array[] = $row['sale_date'];
    $sub_array[] = $row['sales_attended_by'];                        
    $sub_array[] = $row['customer_name'];            
    $sub_array[] = $row['quantity'];                 
    $sub_array[] = $row['product_prices_selling_price'];  
    $sub_array[] = $row['product_prices_batch_number'];  
    $sub_array[] = $row['discount'];                 
    $sub_array[] = $row['discount_type'];            
    $sub_array[] = $row['sale_net_amount'];          
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
