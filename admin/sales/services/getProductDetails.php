<?php

include_once '../../services/config.php';

// Initialize final response object
$finalObject = new \stdClass();
$finalObject->status = "error"; // default status
$finalObject->data = [
    "details" => [],
    "prices" => []
];

$barcode = $_GET['barcode'] ?? null;

if (!$barcode) {
    $finalObject->status = "error";
    $finalObject->error = "Barcode not provided.";
    echo json_encode($finalObject);
    exit;
}

try {
    // Prepare and execute product query
    $productSql = "select p.product_id,p.category_id, p.brand_id, p.product_name, p.product_barcode, p.product_code, p.product_description, c.category_name from products p inner join category c on p.category_id = c.category_id where p.product_barcode = ?";
    $stmt = mysqli_prepare($con, $productSql);

    // Check if the statement was prepared successfully
    if (!$stmt) {
        throw new Exception("Failed to prepare product query: " . mysqli_error($con));
    }

    mysqli_stmt_bind_param($stmt, 's', $barcode);
    mysqli_stmt_execute($stmt);
    $productResult = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($productResult) > 0) {
        $productDetails = mysqli_fetch_assoc($productResult);
        $finalObject->data['details'] = $productDetails;

        // Retrieve product_id to fetch price details
        $productId = $productDetails['product_id'];

        // Prepare and execute price query
        $priceSql = "select * from product_prices where product_id = ?";
        $priceStmt = mysqli_prepare($con, $priceSql);

        // Check if the statement was prepared successfully
        if (!$priceStmt) {
            throw new Exception("Failed to prepare price query: " . mysqli_error($con));
        }

        mysqli_stmt_bind_param($priceStmt, 'i', $productId);
        mysqli_stmt_execute($priceStmt);
        
        $priceResult = mysqli_stmt_get_result($priceStmt);

        $prices = [];

        if (mysqli_num_rows($priceResult) > 0) {
            while ($price = mysqli_fetch_assoc($priceResult)) {
                $prices[] = $price;
            }
        }

        $finalObject->data['prices'] = $prices;

        $finalObject->status = "success";
    } else {
        $finalObject->status = "error";
        $finalObject->error = "Product not found.";
    }

} catch (\Exception $e) {
    // Handle any exceptions
    $finalObject->status = "error";
    $finalObject->error = "Error: " . $e->getMessage();
}

// Return final JSON response
echo json_encode($finalObject);

?>
