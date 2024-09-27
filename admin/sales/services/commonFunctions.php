<?php
function getSaleItemsDetails($saleId, $con)
{
    $data = [];
    $query = "select * from sale_items where sale_id = $saleId";
    $result = $con->query($query);
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}


function uploadSaleRows($saleRows, $con, $maximumSaleId)
{
    $uploadStatus = true;
    foreach ($saleRows as $row) {

        $maximumSaleRowId = getCurrentId('sale_item_id', 'sale_items', $con);
        $productId = $row['productId'];
        $productName = $row['product'];
        $productPriceId = $row['priceId'];
        $productPrice = $row['price'];
        $quantity = $row['quantity'];
        $discount = $row['discount'];
        $discountType = $row['discountType'];
        $gstPercentage = $row['gstPercentage'];
        $isIgst = $row['isIGST'];
        $total = $row['total'];


        $query = "insert into sale_items values($maximumSaleRowId,$maximumSaleId, $productId,'$productName', $productPriceId, '$productPrice', $quantity, '$discount', '$discountType','$gstPercentage', '$isIgst', '$total');";
        if (!mysqli_query($con, $query)) {
            $uploadStatus = false;
        }
    }

    return $uploadStatus;
}


function updateStock($saleRows, $con)
{
    $stockUpdated = true;
    foreach ($saleRows as $row) {
        $productId = $row['productId'];
        $productPrice = $row['price'];
        $quantity = $row['quantity'];

        $query = "update product_prices set product_prices_stock = product_prices_stock - $quantity where product_id = $productId and product_prices_selling_price = '$productPrice';";
        if (!mysqli_query($con, $query)) {
            $stockUpdated = false;
        }
    }
    return $stockUpdated;
}

function checkStock($saleRows, $con)
{
    $stockDetails = new \stdClass();

    $stockDetails->isStockAvailable = true;
    $stockDetails->message = "";

    $temp = "Stock not available for ";
    $unavailableProducts = [];

    foreach ($saleRows as $row) {
        $productId = $row['productId'];
        $productPrice = $row['price'];
        $quantity = $row['quantity'];
        $productName = $row['product'];

        $query = "select product_prices_stock from product_prices where product_id = $productId and product_prices_selling_price = '$productPrice';";
        $result = mysqli_query($con, $query);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $stock = $row['product_prices_stock'];
            if ($stock < $quantity) {
                $stockDetails->isStockAvailable = false;
                $unavailableProducts[] = $productName;
            }
        }
    }

    if (!empty($unavailableProducts)) {
        $lastProduct = array_pop($unavailableProducts);
        $stockDetails->message = $temp . implode(', ', $unavailableProducts);
        if (!empty($unavailableProducts)) {
            $stockDetails->message .= ', ';
        }
        $stockDetails->message .= $lastProduct;
    }

    return $stockDetails;
}


?>