<?php

include "../../services/config.php";
include "../../services/helperFunctions.php";


try {

    if ($_POST['action'] == 'edit') {
        $categoryName = htmlspecialchars($_POST["category_name"], ENT_QUOTES, 'UTF-8');

        if (!checkUpdateExist($categoryName, $_POST['category_id'], "category", $con)) {
            $query = "update category set category_name='" . $categoryName . "' where category_id =" . $_POST['category_id'];
            mysqli_query($con, $query);
        } else {
            $_POST["error"] = "Category already exist!";
        }
    }
    if ($_POST['action'] == 'delete') {
        $query = "delete from category where category_id = " . $_POST['category_id'];
        mysqli_query($con, $query);
    }
} catch (exception $e) {
    $_POST["error"] = "Error #1001";
}
echo json_encode($_POST);


