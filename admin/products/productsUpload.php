<?php

include ("../services/config.php");
include ("../../services/utils/generalFunctions.php");
session_start();
$user_id = $_SESSION["user_id"];

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

        /* barcode classes end */
    </style>

</head>

<body>

    <div class="alert--cont">
    </div>

    <!-- alert cont end -->

    <div class="title">Product</div>
    <div class="spacer"></div>

    <form action="#">
        <div class="inp-row row-5">

            <div class="inp-group">
                <div class="inp-label">Product Name</div>
                <input type="text required" value="Product name Test" class="inp" id="txtname"
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
                <input type="text required" class="inp" id="txtbarcode" placeholder="product barcode"
                    data-id="txtbarcode" />

                <div class="barcode-options">
                    <div class="btn action-btn scan-btn" data-type="scan">Scan</div>
                    <div class="btn action-btn generate-btn" data-type="generate">Generate</div>
                </div>

                <div class="error-text" data-id="txtbarcode">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->


        </div>
        <!-- input row end -->

        <div class="inp-row row-5">
            <div class="inp-group">
                <div class="inp-label">Product Quantity</div>
                <input type="text" class="inp required" id="txtquantity" placeholder="Product Quantity"
                    data-id="txtquantity" />
                <div class="error-text" data-id="txtquantity">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->
            <div class="inp-group">
                <div class="inp-label">Product Code</div>
                <input type="text" class="inp required" value="1000110" id="txtcode" placeholder="product Code"
                    data-id="txtcode" />
                <div class="error-text" data-id="txtcode">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->
            <div class="inp-group">
                <div class="inp-label">Product Cost Price</div>
                <input type="text" class="inp  required gst-cal-inp" id="txtcostprice" placeholder="product cost price"
                    data-id="txtcostprice" />
                <div class="error-text" data-id="txtcostprice">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->
            <div class="inp-group">
                <div class="inp-label">CGST Percentage</div>
                <input type="text" value="0" class="inp  required gst-cal-inp" id="txtcgstpercentage"
                    placeholder="CGST Percentage" data-id="txtcgstpercentage" />
                <div class="error-text" data-id="txtcgstpercentage">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->
            <div class="inp-group">
                <div class="inp-label">SGST Percentage</div>
                <input type="text" value="0" class="inp  required gst-cal-inp" id="txtsgstpercentage"
                    placeholder="SGST Percentage" data-id="txtsgstpercentage" />
                <div class="error-text" data-id="txtsgstpercentage">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->

        </div>
        <!-- input row end -->


        <div class="inp-row row-5">
            <div class="inp-group">
                <div class="inp-label">IGST Percentage</div>
                <input type="text" value="0" class="inp  required gst-cal-inp" id="txtigstpercentage"
                    placeholder="IGST Percentage" data-id="txtigstpercentage" />
                <div class="error-text" data-id="txtigstpercentage">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->

            <div class="inp-group">
                <div class="inp-label">Price Post GST</div>
                <input type="text" class="inp required" id="txtpricepostgst" placeholder="Price Post GST"
                    data-id="txtpricepostgst" />
                <div class="error-text" data-id="txtpricepostgst">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->

            <div class="inp-group">
                <div class="inp-label">Product Selling Price</div>
                <input type="text" class="inp required" value="1003.25" id="txtsellingprice"
                    placeholder="product Selling price" data-id="txtsellingprice" />
                <div class="error-text" data-id="txtsellingprice">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->
            <div class="inp-group">
                <div class="inp-label">Product Parlour Price</div>
                <input type="text" class="inp required" value="999.99" id="txtparlourprice"
                    placeholder="product Parlour price" data-id="txtparlourprice" />
                <div class="error-text" data-id="txtparlourprice">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->


            <div class="inp-group">
                <div class="inp-label">Product Description</div>
                <input type="text" class="inp" id="txtdescription" placeholder="Product Description"
                    data-id="txtdescription" />
                <div class="error-text" data-id="txtdescription">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->

        </div>
        <!-- input row end -->

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

        const defaultBrand = "";
        const defaultCategory = "";


        let inputs = document.querySelectorAll("input.required");
        let dropdowns = document.querySelectorAll("select");
        let errorTexts = document.querySelectorAll(".error-text");
        var generatedNumber;
        let productObject = {
            "name": "",
            "brand": "",
            "category": "",
            "barcode": "",
            "quantity": "",
            "code": "",
            "costPrice": "",
            "cGSTPercentage": "",
            "sGSTPercentage": "",
            "iGSTPercentage": "",
            "postGSTPrice": "",
            "sellingPrice": "",
            "parlourPrice": "",
            "description": "",
            "images": []
        };
    </script>

    <script src="../scripts/validation.js"></script>

    <script src="./scripts/loadDropdownData.js"></script>

    <script src="./scripts/popup.js"></script>

    <script src="./scripts/barcodeScanner.js"></script>

    <script src="./scripts/barcodeGenerator.js"></script>

    <script src="./scripts/handleGstCalculations.js"></script>

    <script src="./scripts/multipleImageUpload.js"></script>


    <script>

        let submitBtn = document.querySelector(".submit--btn");
        submitBtn.addEventListener("click", e => {

            //if form is valid
            if (isValid()) {

                // assigning the values
                productObject.name = document.getElementById("txtname").value;
                productObject.brand = document.getElementById("ddlbrand").value;
                productObject.category = document.getElementById("ddlcategory").value;
                productObject.barcode = document.getElementById("txtbarcode").value;
                productObject.quantity = document.getElementById("txtquantity").value;
                productObject.code = document.getElementById("txtcode").value;
                productObject.costPrice = document.getElementById("txtcostprice").value;
                productObject.cGSTPercentage = document.getElementById("txtcgstpercentage").value;
                productObject.sGSTPercentage = document.getElementById("txtsgstpercentage").value;
                productObject.iGSTPercentage = document.getElementById("txtigstpercentage").value;
                productObject.postGSTPrice = document.getElementById("txtpricepostgst").value;
                productObject.sellingPrice = document.getElementById("txtsellingprice").value;
                productObject.parlourPrice = document.getElementById("txtparlourprice").value;
                productObject.description = document.getElementById("txtdescription").value;


                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
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

                xmlhttp.open("POST", `services/uploadProduct.php`, true);
                xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xmlhttp.send("data=" + encodeURIComponent(JSON.stringify(productObject)));
            }

        });



    </script>
</body>

</html>