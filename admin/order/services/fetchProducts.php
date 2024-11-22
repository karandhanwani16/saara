<?php

include "../../services/config.php";
include "../../services/helperFunctions.php";

$data = $_POST["data"];
$data = json_decode($data, true);


$brand = $data["brand"];
$category = $data["category"];

session_start();
$user_id = $_SESSION["user_id"];

$column = array(
    "p.product_name",
    "p.product_minimum_stock",
    "SUM(CAST(pr.product_prices_stock AS DECIMAL)) AS product_current_stock",
    "b.brand_name",
    "c.category_name"
);



$query1 = "select p.product_name, p.product_minimum_stock, SUM(CAST(pr.product_prices_stock AS DECIMAL)) AS product_current_stock from products p inner join product_prices pr on p.product_id = pr.product_id group by p.product_id, p.product_name, p.product_minimum_stock having p.product_minimum_stock > SUM(CAST(pr.product_prices_stock AS DECIMAL))";

$query2 = "select p.product_name, p.product_minimum_stock, SUM(CAST(pr.product_prices_stock AS DECIMAL)) AS product_current_stock, b.brand_name, c.category_name FROM products p INNER JOIN product_prices pr on p.product_id = pr.product_id LEFT JOIN brand b on p.brand_id = b.brand_id LEFT JOIN category c on p.category_id = c.category_id WHERE b.brand_name = '$brand' AND c.category_name = '$category' GROUP BY p.product_id, p.product_name, p.product_minimum_stock, b.brand_name, c.category_name having p.product_minimum_stock >= SUM(CAST(pr.product_prices_stock AS DECIMAL));";

$query3 = "select p.product_name, p.product_minimum_stock, SUM(CAST(pr.product_prices_stock AS DECIMAL)) AS product_current_stock, b.brand_name, c.category_name FROM products p INNER JOIN product_prices pr on p.product_id = pr.product_id LEFT JOIN brand b on p.brand_id = b.brand_id LEFT JOIN category c on p.category_id = c.category_id WHERE c.category_name = '$category' GROUP BY p.product_id, p.product_name, p.product_minimum_stock, b.brand_name, c.category_name having p.product_minimum_stock >= SUM(CAST(pr.product_prices_stock AS DECIMAL));";

$query4 = "select p.product_name, p.product_minimum_stock, SUM(CAST(pr.product_prices_stock AS DECIMAL)) AS product_current_stock, b.brand_name, c.category_name FROM products p INNER JOIN product_prices pr on p.product_id = pr.product_id LEFT JOIN brand b on p.brand_id = b.brand_id LEFT JOIN category c on p.category_id = c.category_id WHERE b.brand_name = '$brand' GROUP BY p.product_id, p.product_name, p.product_minimum_stock, b.brand_name, c.category_name having p.product_minimum_stock >= SUM(CAST(pr.product_prices_stock AS DECIMAL));";

if ($brand != "novalue" && $category != "novalue") {
    $query = $query2;
} else if ($brand != "novalue" && $category == "novalue") {
    $query = $query4; 
} else if ($brand == "novalue" && $category != "novalue") {
    $query = $query3;
} else {
    $query = $query1;
}

$result = $con->query($query);
$number_filter_row = $result->num_rows;

$data = array();
$srNo = 1;

while ($row = $result->fetch_assoc()) {
    // Create a row object
    $rowData = array(
        'srNo' => $srNo,
        'product_name' => $row['product_name'],
        'product_minimum_stock' => $row['product_minimum_stock'],
        'product_current_stock' => $row['product_current_stock']
    );
    $data[] = $rowData;
    $srNo++;
}

// Prepare final response object
$finalObject = new \stdClass();
$finalObject->status = "success";
$finalObject->data = $data;
echo json_encode($finalObject);

?>