<?php

include("../services/config.php");
include("../../services/utils/generalFunctions.php");
session_start();
$user_id = $_SESSION["user_id"];

function loadBrand($con)
{
    $sql = "SELECT brand_name FROM brand ORDER BY brand_name";
    $result = $con->query($sql);
    while ($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['brand_name'] . "'>" . $row['brand_name'] . "</option>";
    }
}

function loadCategory($con)
{
    $sql = "SELECT category_name FROM category ORDER BY category_name";
    $result = $con->query($sql);
    while ($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['category_name'] . "'>" . $row['category_name'] . "</option>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Form</title>
    <link rel="stylesheet" href="../style/assets.css">
    <link rel="stylesheet" href="../style/forms.css">
    <link rel="stylesheet" href="../style/sales.css">
    <style>
        .error-inp+.barcode-options {
            bottom: 26px;
        }

        /* barcode classes start */
        .sara-barcode-logo {
            font-size: 14px;
            font-weight: 800;
        }

        .extra-details-footer {
            padding: 0 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .barcode {
            height: 180px;
        }


        .middle-cont {
            display: flex;
            justify-content: flex-start;
            align-items: center;
        }

        .image-group {
            width: 40%;
        }


        #priceStockTableBody .action-btns {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 18px;
            flex-shrink: 0 !important;
        }

        /* barcode classes end */
    </style>

</head>

<body>
    <div class="alert--cont">
    </div>

    <!-- alert cont end -->

    <div class="title">Order Form</div>
    <div class="spacer"></div>

    <form action="#">
        <div class="inp-row row-5 adj-5" style="justify-content: flex-start; gap: 10vw;">

            <div class="inp-group">
                <div class="inp-label">Product Brand</div>
                <select class="ddl required" id="ddlbrand" data-id="ddlbrand">
                    <option value="novalue">Select Supplier Name</option>
                    <?php
                    echo loadBrand($con);
                    ?>
                </select>
                <div class="error-text" data-id="ddlbrand">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->

            <div class="inp-group">
                <div class="inp-label">Product Category</div>
                <select class="ddl required" id="ddlcategory" data-id="ddlcategory">
                    <option value="novalue">Select Supplier Name</option>
                    <?php
                    echo loadCategory($con);
                    ?>
                </select>
                <div class="error-text" data-id="ddlcategory">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->
        </div>
        <!-- inp row end -->

        <div class=" full-width-row input-row" style="margin: 20px 0;">
            <div class="full-width-table">
                <table id="priceStockTable" class="adjusted-table">
                    <tbody>
                        <tr class="t-head">
                            <th width="5%">Sr No.</th>
                            <th width="40%">Product Name</th>
                            <th width="10%">Product Minimum Stock</th>
                            <th width="10%">Product Current Stock</th>
                            <th width="10%"></th>
                        </tr>
                    </tbody>
                    <tbody class="table-body" id="priceStockTableBody">
                    </tbody>
                </table>
            </div>
        </div>

        <div class=" btn-row" style="margin-top: 16px;">
            <div class="primary-btn btn f-center submit--btn">Download</div>
        </div>

    </form>

    <script src="https://unpkg.com/html5-qrcode@2.0.9/dist/html5-qrcode.min.js"></script>
    <script src="../scripts/helperFunctions.js"></script>

    <script src="../scripts/validation.js"></script>


    <script>
        let orderObject = {
            "brand": "",
            "category": ""
        };

        let downloadObject = {
            "products": [],
            "brand": "",
            "category": ""
        };

        function fetchAndPopulateProducts() {
            orderObject.brand = document.getElementById("ddlbrand").value;
            orderObject.category = document.getElementById("ddlcategory").value;

            let xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    let result = JSON.parse(this.responseText);

                    if (result.status === "success") {
                        let tableBody = document.getElementById('priceStockTableBody');
                        tableBody.innerHTML = '';

                        result.data.forEach((product) => {
                            downloadObject.products = result.data.map(product => ({  // Changed to use `map()` for efficiency
                                "product_name": product.product_name,
                                "product_minimum_stock": product.product_minimum_stock,
                                "product_current_stock": product.product_current_stock
                            }));
                            downloadObject.brand = orderObject.brand;
                            downloadObject.category = orderObject.category;
                            let row = document.createElement('tr');
                            row.innerHTML = `
                        <td>${product.srNo}</td>
                        <td>${product.product_name}</td>
                        <td>${product.product_minimum_stock}</td>
                        <td>${product.product_current_stock}</td>
                        <td><input type="text" class="inp"></td>
                    `;
                            tableBody.appendChild(row);
                        });
                    }
                }
            };
            xmlhttp.open("POST", "services/fetchProducts.php", true);
            xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xmlhttp.send("data=" + encodeURIComponent(JSON.stringify(orderObject)));
        }

        // Attach event listeners
        document.addEventListener("DOMContentLoaded", function () {
            fetchAndPopulateProducts();

            document.getElementById("ddlbrand").addEventListener("change", fetchAndPopulateProducts);
            document.getElementById("ddlcategory").addEventListener("change", fetchAndPopulateProducts);
        });


        let submitBtn = document.querySelector(".submit--btn");

        submitBtn.addEventListener("click", e => {
            addLoadingStateWithText(submitBtn, "Downloading...");
            const xhr = new XMLHttpRequest();
            xhr.open('POST', './services/getOrder.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            console.log(downloadObject);
            xhr.send("data=" + encodeURIComponent(JSON.stringify(downloadObject))); 
            xhr.onload = () => {
                if (xhr.status == 200) {
                    const response = JSON.parse(xhr.responseText);
                    removeLoadingStateWithText(submitBtn, "Download");
                    if (response.status == 'success') {
                        let pdfUrl = `../../assets/temp/order/${response.fileName}`;
                        var link = document.createElement('a');
                        link.href = "#";
                        link.addEventListener("click", e => {
                            e.preventDefault();
                            window.open(pdfUrl, '_blank', 'fullscreen=yes');
                            return false;
                        });
                        link.dispatchEvent(new MouseEvent('click'));
                        showAlert(response.message, response.status);
                    } else {
                        showAlert('Error: ' + response.message, 'error');
                    }
                } else {
                    console.log('Error: ' + xhr.status);
                }
            }

        });

    </script>


</body>

</html>