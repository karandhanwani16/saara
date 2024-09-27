<?php

function deleteProduct($product_id, $con)
{
    $sql = "delete from products where product_id = " . $product_id;
    mysqli_query($con, $sql);
}
function deleteProductFolder($product_id)
{
    $mainDir = "../../../assets/images/products/" . $product_id . "/";
    if (is_dir($mainDir)) {
        rrmdir($mainDir);
    }
}

function uploadImages($images, $product_id)
{
    $uploaded = true;
    if (count($images) > 0) {
        $mainDir = "../../../assets/images/products/" . $product_id . "/";
        mkdir($mainDir);
        for ($i = 0; $i < count($images); $i++) {
            $image_data = getImageFromBase64($images[$i]);
            if (!file_put_contents($mainDir . $i . '.' . $image_data->type, $image_data->image)) {
                $uploaded = false;
            }
        }
    }
    return $uploaded;
}

function rrmdir($dir)
{
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (is_dir($dir . DIRECTORY_SEPARATOR . $object) && !is_link($dir . "/" . $object))
                    rrmdir($dir . DIRECTORY_SEPARATOR . $object);
                else
                    unlink($dir . DIRECTORY_SEPARATOR . $object);
            }
        }
        rmdir($dir);
    }
}

function uploadProductPrices($product_id, $prices,$con){
    $isUploaded = true;
    //iterating through the prices array
    for ($i = 0; $i < count($prices); $i++) {
        //get new id for product prices
        $maximumProductPriceId = getCurrentId("product_prices_id", "product_prices", $con);
        $price = $prices[$i];
        $sql = "insert into product_prices values(" . $maximumProductPriceId . "," . $product_id . ",'" . $price["costPrice"] . "','" . $price["stock"] . "','" . $price["gst"] . "','" . $price["sellingPrice"] . "','" . $price["parlourPrice"] . "')";
        if (!mysqli_query($con, $sql)) {
            $isUploaded = false;
        }
    }
    return $isUploaded;
}

function deleteProductPrices($product_id, $con)
{
    $sql = "delete from product_prices where product_id = " . $product_id;
    return mysqli_query($con, $sql);
}

function deleteProductImages($productId, $con)
{
    $deleted = true;
    $productDir = "../../../assets/images/products/" . $productId . "/";

    if (is_dir($productDir)) {
        $objects = scandir($productDir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                unlink($productDir . $object);
            }
        }
        // delete directory as well
        rmdir($productDir);
    }



    return $deleted;
}


?>