<?php

include("../services/config.php");
include("../../services/utils/generalFunctions.php");
session_start();
$user_id = $_SESSION["user_id"];

$productId = $_GET["id"];

$productData = getProductData($productId, $con);


function getProductData($productId, $con)
{
    $productDetails = new \stdClass();

    $query = "select * from products where product_id = $productId";

    $result = $con->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $productDetails = $row;
    }


    return $productDetails;
}

function getProductPrices($productId, $con){
    $query = "select product_prices_cost_price as costPrice,product_prices_stock as stock,product_prices_gst_percentage as gst,product_prices_selling_price as sellingPrice,product_prices_parlour_price as parlourPrice from product_prices where product_id = $productId";
    $result = $con->query($query);
    $prices = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $prices[] = $row;
        }
    }
    return $prices;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Upload</title>
    <link rel="stylesheet" href="../style/assets.css">
    <link rel="stylesheet" href="../style/forms.css">
    <link rel="stylesheet" href="../style/sales.css">
    <link rel="stylesheet" href="./style/product.css">
    <link rel="stylesheet" href="./style/multiImage.css">

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

<div onclick="window.history.back()" class="back-btn"><img src='../assets/icons/back.svg' alt=''></div>


    <div class="alert--cont">
    </div>

    <!-- alert cont end -->

    <div class="title">Product</div>
    <div class="spacer"></div>

    <form action="#">
        <div class="inp-row row-5">

            <div class="inp-group">
                <div class="inp-label">Product Name</div>
                <input type="text required" value="<?php echo $productData["product_name"]; ?>" class="inp" id="txtname"
                    placeholder="product name" data-id="txtname" />
                <div class="error-text" data-id="txtname">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->

            <div class="inp-group">
                <div class="inp-label">Product Brand</div>
                <select class="ddl required" id="ddlbrand" data-id="ddlbrand"></select>
                <div class="error-text" data-id="ddlbrand">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->

            <div class="inp-group">
                <div class="inp-label">Product Category</div>
                <select class="ddl required" id="ddlcategory" data-id="ddlcategory"></select>
                <div class="error-text" data-id="ddlcategory">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->

            <div class="inp-group barcode-inp">
                <div class="inp-label">Product Barcode</div>
                <input type="text" class="inp required" value="<?php echo $productData["product_barcode"]; ?>" id="txtbarcode" placeholder="product barcode"
                    data-id="txtbarcode" />

                <div class="barcode-options">
                    <div class="btn action-btn scan-btn" data-type="scan">Scan</div>
                    <!-- <div class="btn action-btn generate-btn" data-type="generate">Generate</div> -->
                </div>

                <div class="error-text" data-id="txtbarcode">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->


        </div>
        <!-- input row end -->

        <div class="inp-row row-5 adj-5" style="justify-content: flex-start;gap: 24px;">
            <div class="inp-group">
                <div class="inp-label">Product Code</div>
                <input type="text" class="inp required" value="<?php echo $productData["product_code"]; ?>" id="txtcode" placeholder="product Code"
                    data-id="txtcode" />
                <div class="error-text" data-id="txtcode">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->
            <div class="inp-group">
                <div class="inp-label">Product Description</div>
                <input type="text" class="inp" value="<?php echo $productData["product_description"]; ?>" id="txtdescription" placeholder="Product Description"
                    data-id="txtdescription" />
                <div class="error-text" data-id="txtdescription">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->

        </div>
        <!-- input row end -->

        <div class=" full-width-row input-row" style="margin: 20px 0;">
            <div class="full-width-table">

                <table id="priceStockTable" class="adjusted-table">
                    <tbody>
                        <!-- Form row for adding new product details -->

                        <tr class="controls-row">
                            <td><input type="text" id="txtCostPrice" class="inp" placeholder="Enter Cost Price" /></td>
                            <td><input type="text" id="txtStock" class="inp" placeholder="Enter Stock" /></td>
                            <td><input type="text" id="txtGST" class="inp" placeholder="Enter GST %" /></td>
                            <td><input type="text" id="txtSellingPrice" class="inp" placeholder="Enter Selling Price" />
                            </td>
                            <td><input type="text" id="txtParlourPrice" class="inp" placeholder="Enter Parlour Price" />
                            </td>
                            <td><button type="button" id="addPriceStock" class="btn action-btn">Add</button></td>
                        </tr>

                        <tr class="t-head">
                            <th>Cost Price</th>
                            <th>Stock</th>
                            <th>GST %</th>
                            <th>Selling Price</th>
                            <th>Parlour Price</th>
                            <th>Action</th>
                        </tr>

                    </tbody>
                    <tbody class="table-body" id="priceStockTableBody">
                </table>
            </div>

        </div>

        <!-- inp row end -->


        <div class="input-row">
            <div class="inp-group image-group">

                <div class="inp-label">Product Images</div>
                <div class="images--cont">
                    <div class="image--slider" id="ImageSlider" runat="server">

                    </div>
                    <div class="upload--btn">
                        <input type="file" accept="image/jpeg" id="fileproduct" class="upload--element" multiple
                            data-id="fuproduct" id="fuproduct">
                        <img src="../assets/icons/add-image.svg" alt="Alternate Text" />
                        <p>Upload an image</p>
                    </div>
                </div>
                <div class="error-text" data-id="fuproduct">Cannot leave this field blank</div>
            </div>

            <input type="hidden" id="HiddenField1"">
        </div>
        <!-- inp row end -->


        <div class=" btn-row" style="margin-top: 16px;">
            <div class="primary-btn btn f-center submit--btn">Submit</div>
        </div>
    </form>

    <div class="popup-backdrop"></div>
    <div class="popup-cont">
        <div class="popup-header">
            <p></p>
            <div class="close-btn">
                <img src="../assets/icons/close.svg" alt="close btn">
            </div>
        </div>

        <div class="popup-body" id="qr-reader">
            <div class="generate--cont">
                <div class="barcode">
                    <!-- <center>
                        <div id="extraDetailsHeader1"></div>
                        <div id="extraDetailsHeader2"></div>
                        <div class="middle-cont">
                            <div id="productCode"></div>
                            <img id="generatedBarcode" />
                        </div>
                        <div class="details" id="extraDetailsFooter"></div>
                    </center> -->
                    <table>
                        <tr>
                            <th id="extraDetailsHeader1" style="font-size: 14px;font-weight: 800;" colspan="2"></th>
                        </tr>
                        <tr>
                            <th id="extraDetailsHeader2" style="font-size: 16px;font-weight: 600;" colspan="2"></th>
                        </tr>
                        <tr>
                            <td>
                                <div id="productCode" style="transform: rotate(-90deg);height: 10px;"></div>
                            </td>
                            <td>
                                <img id="generatedBarcode" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="details" id="footerSellingPrice"></div>
                            </td>
                            <td>
                                <div class="details" style="text-align: right;" id="footerParlourPrice"></div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="btn-cont">
                    <div class="btn print-btn" id="printBtn">Print</div>
                    <div class="btn print-btn" id="useCodeBtn">Use Code</div>
                </div>

            </div>
        </div>
    </div>


    <script src="https://unpkg.com/html5-qrcode@2.0.9/dist/html5-qrcode.min.js"></script>
    <script src="../scripts/helperFunctions.js"></script>

    <script>
        const defaultBrand = "<?php echo $productData["brand_id"]; ?>";
        const defaultCategory = "<?php echo $productData["category_id"]; ?>";

        let inputs = document.querySelectorAll("input.required");
        let dropdowns = document.querySelectorAll("select");
        let errorTexts = document.querySelectorAll(".error-text");
        var generatedNumber;
        let productObject = {
            "id": "",
            "name": "",
            "brand": "",
            "category": "",
            "barcode": "",
            "code": "",
            "description": "",
            "prices": [],
            "images": []
        };
    </script>

    <script src="../scripts/validation.js"></script>

    <script src="./scripts/handlePrice.js"></script>

    <script src="./scripts/loadDropdownData.js"></script>

    <script src="./scripts/popup.js"></script>

    <script src="./scripts/barcodeScanner.js"></script>

    <script src="./scripts/barcodeGenerator.js"></script>

    <script src="./scripts/handleGstCalculations.js"></script>

    <script src="./scripts/multipleImageUpload.js"></script>

    <script>
        const currentProductId = <?php echo $productId; ?>;
    </script>

    <!--load product prices start-->
    <script>
        <?php
        
        $prices = getProductPrices($productId, $con);

        ?>

        let productPrices = <?php echo json_encode($prices); ?>;
        loadInitialData(productPrices);

    </script>
    <!--load product prices end-->

    <!-- load images -->
    <script>

        <?php
        $dir_name = "../../assets/images/products/" . $productId . "/";
        $images = glob($dir_name . "*.*");  

        $base64Images = [];
        foreach ($images as $imagePath) {
            $imageType = pathinfo($imagePath, PATHINFO_EXTENSION);
            $imageData = file_get_contents($imagePath);
            $base64Image = 'data:image/' . $imageType . ';base64,' . base64_encode($imageData);
            $base64Images[] = $base64Image;
        }
        // print_r(json_encode($base64Images));
        ?>

        let serverImages = <?php echo json_encode($base64Images); ?>;

        // Load server images
        if (serverImages && serverImages.length > 0) {
            serverImages.forEach((base64Image, index) => {
                let newImage = `<div class="upload--image"><div data-no="${currentIndex}" class="image--del"><img src="../assets/icons/close.svg" /></div><img src="${base64Image}" alt="Server Image" /></div>`;
                imageSrc[currentIndex] = newImage;
                productObject.images.push(base64Image); // Add server images to the productObject
                currentIndex++;
            });
            loadImages();
        }
    </script>
    <!-- load images -->

    <script>
        let submitBtn = document.querySelector(".submit--btn");
        submitBtn.addEventListener("click", e => {

            //if form is valid
            if (isValid()) {

                // assigning the values
                productObject.id = currentProductId;
                productObject.name = document.getElementById("txtname").value;
                productObject.brand = document.getElementById("ddlbrand").value;
                productObject.category = document.getElementById("ddlcategory").value;
                productObject.barcode = document.getElementById("txtbarcode").value;
                productObject.code = document.getElementById("txtcode").value;
                productObject.description = document.getElementById("txtdescription").value;
                productObject.prices = priceStockArray;

                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var result = JSON.parse(this.responseText);
                        removeLoadingState(submitBtn);
                        if (result.status == "success") {
                            refreshPage();
                        }
                        showAlert(result.message, result.status);
                    }
                };
                addLoadingState(submitBtn);

                xmlhttp.open("POST", `services/updateProduct.php`, true);
                xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xmlhttp.send("data=" + encodeURIComponent(JSON.stringify(productObject)));
            }

        });
    </script>
</body>

</html>