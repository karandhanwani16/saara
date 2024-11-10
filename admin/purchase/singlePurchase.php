<?php

include("../services/urlValidation.php");
include("../services/config.php");
include("../services/helperFunctions.php");
session_start();
$user_id = $_SESSION["user_id"];

$purchaseId = $_GET["id"];
// echo $purchaseId;
$purchaseDetails = getPurchaseData($purchaseId, $con);
$purchaseRows = getPurchaseRows($purchaseId, $con);
$productBarcode = getProductBarcode($purchaseRows, $con);

function getProductBarcode($purchaseRows, $con)
{
    if ($purchaseRows == null) {
        return null;
    }
    $productId = $purchaseRows[0]['purchase_item_product_id'];
    //echo $productId;
    $query = "select product_barcode from products where product_id = $productId";
    $result = $con->query($query);
    $rowCount = $result->num_rows;
    if ($rowCount == 0) {
        return null;
    } else {
        $row = $result->fetch_assoc();
        return $row;
    }
}
function getPurchaseData($purchaseId, $con)
{
    $query = "select purchase_id,purchase_date,purchase_no,supplier_id from purchase where purchase_id = $purchaseId";
    $result = $con->query($query);
    $rowCount = $result->num_rows;
    if ($rowCount == 0) {
        return null;
    } else {
        $row = $result->fetch_assoc();
        return $row;
    }
}
function getPurchaseRows($purchaseId, $con)
{
    $query = "select purchase_item_product_quantity,purchase_item_price_id,purchase_item_product_price,purchase_item_product_name,purchase_item_product_id,purchase_item_product_gst,purchase_item_product_is_igst from purchase_items where purchase_id = " . $purchaseId;

    $result = $con->query($query);
    if (!$result) {
        echo "SQL Error: " . $con->error;
        return null;
    }
    $rowCount = $result->num_rows;
    if ($rowCount == 0) {
        //echo "returned null";
        return null;
    } else {
        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $temp = $row;
            $rows[] = $temp;
        }
        return $rows;
    }
}

function loadSuppliers($alreadySelected, $con)
{
    $options = "";
    $query = "select * from supplier";
    $result = $con->query($query);
    while ($row = $result->fetch_assoc()) {
        $options .= "<option " . ($alreadySelected == $row['supplier_id'] ? "selected" : "") . " value='" . $row['supplier_id'] . "' >" . $row['supplier_name'] . "</option>";
    }
    return $options;
}

date_default_timezone_set('Asia/Kolkata');
$currentDate = date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Update</title>
    <link rel="stylesheet" href="../style/assets.css">
    <link rel="stylesheet" href="../style/forms.css">
    <link rel="stylesheet" href="./styles/salesStyle.css">
    <link rel="stylesheet" href="./styles/popup.css">
    <link rel="stylesheet" href="./styles/skeleton.css">
    <link rel="stylesheet" href="./styles/retry.css">
    <link rel="stylesheet" href="./styles/purchase.css">

</head>

<body>
    <div onclick="window.history.back()" class="back-btn"><img src='../assets/icons/back.svg' alt=''></div>

    <div class="alert--cont">
    </div>

    <!-- alert cont end -->

    <div class="title">Purchase</div>
    <div class="spacer"></div>

    <form action="#">
        <div class="inp-row row-3">

            <div class="inp-group">
                <div class="inp-label">Purchase Date</div>
                <input type="date"
                    value="<?php echo $purchaseDetails["purchase_date"] ? $purchaseDetails["purchase_date"] : $currentDate; ?>"
                    class="inp inp-date required" id="txtpurchasedate" placeholder="Purchase Date"
                    data-id="txtpurchasedate" />
                <div class="error-text" data-id="txtpurchasedate">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->

            <div class="inp-group">
                <div class="inp-label">Supplier</div>
                <select class="ddl required" id="ddlsupplier" data-id="ddlsupplier">
                    <option value="">Select Supplier Name</option>
                    <?php
                    echo loadSuppliers($purchaseDetails["supplier_id"], $con);
                    ?>
                </select>
                <div class="error-text" data-id="ddlsupplier">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->

            <div class="inp-group">
                <div class="inp-label">Purchase Number</div>
                <input type="text" value="<?php echo $purchaseDetails["purchase_no"] ?>"
                    onkeypress="return isNumber(event)" onkeypress="return isNumber(event)" class="inp required"
                    id="txtpurchasenumber" placeholder="Purchase Number" data-id="txtpurchasenumber" />
                <div class="error-text" data-id="txtpurchasenumber">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->
        </div>

        <div class="inp-row" id="barcodeRow" style="justify-content: flex-start;gap: 24px;">
            <div class="inp-group">
                <div class="inp-label hide-sm">Product Barcode</div>
                <div class="barcode-inp-cont">
                    <input type="text" class="inp hide-sm" onkeypress="return isNumber(event)"
                        value="<?php echo $productBarcode["product_barcode"] ?>" class="inp required"
                        onkeypress="return isNumber(event)" id="txtbarcode" placeholder="Product Barcode"
                        data-id="txtbarcode" />
                    <div class="btn action-btn add-purchase-btn hide-sm" id="addpurchase" data-type="add">Add</div>
                    <div class="btn action-btn scan-btn" id="scanBarcodeBtn" data-type="scan">Scan</div>
                </div>
                <div class="error-text" data-id="txtaddproduct">Cannot leave this field blank</div>
                <!-- inp group end -->
            </div>
        </div>

        <div class="inp-row full-width-row">
            <div class="full-width-table">
                <table id="purchaseMainTable">
                    <div>
                        <tr class="t-head">
                            <th style="width: 5%;">Sr No.</th>
                            <th style="width: 40%;">Product Name</th>
                            <th style="width: 10%;">Cost Price</th>
                            <th style="width: 8%;">GST</th>
                            <th style="width: 8%;">IGST</th>
                            <th style="width: 8%;">Quantity</th>
                            <th style="width: 10%;">Total</th>
                            <th style="width: 10%;">Action</th>
                        </tr>
                    </div>

                    <tbody class="invoice-products-body">
                    </tbody>

                    <tfoot class="invoice-products-footer">
                    </tfoot>
                </table>
            </div>
        </div>
        <!-- row end -->


        <div class="inp-row full-width-row">
            <table class="summary-table">
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="label">Round Off</td>
                    <td class="data round-off">₹ 0</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="label">Net Total</td>
                    <td class="data net-total">₹ 0</td>
                </tr>
            </table>
        </div>
        <!-- row end -->

        <div class="btn-row">
            <div class="primary-btn btn f-center submit--btn">Submit</div>
        </div>
    </form>

    <div id="barcodeBackground" class="popup-background hidden"></div>
    <div id="barcodeScanner" class="popup hidden">
        <div class="popup-content">
            <span class="close">&times;</span>
            <div id="barcodeReader"></div>
            <div class="manual-entry-cont hidden" id="manualEntryCont">
                <p>Enter Barcode</p>
                <div class="row">
                    <input type="text" placeholder="Enter Barcode" id="manualBarcode">
                    <button class="manual-btn" id="manualEntryAddBtn">Add Product</button>
                </div>
            </div>
            <div class="manual-entry-btn hidden" id="manualEntryBtn">Enter Manually</div>
        </div>
    </div>

    <div id="purchasePopupBackground" class="popup-background hidden"></div>
    <div id="purchasePopup" class="popup hidden">
        <div class="popup-content" id="productContent">
            <span id="closeaddpurchase" class="close">&times;</span>
            <h2 id="productName">-</h2>
            <p id="categoryName">-</p>
            <p id="productDescription">-</p>
            <div class="form-row">
                <select id="productPrice">
                    <option value="">Select Product Price</option>
                    <option value="otherPrice" id="ddlotherprice">Other Price</option>
                </select>
                <input type="text" placeholder="Enter Quantity" id="purchasequantity">
            </div>

            <div class="form-row">
                <label class="switch">
                    <p>Is IGST</p>
                    <input type="checkbox" value="1" id="isIGST">
                    <span class="slider round"></span>
                </label>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <button id="addnewpurchaseprice" class="btn-new hidden">Add New Price</button>
                <button id="addPurchaseToTable" class="btn-new">Add Product</button>
            </div>
            <button id="cancelPurchase" class="btn-secondary">Cancel</button>
            <div class="error-cont"></div>

        </div>
    </div>

    <div id="priceEditPopupBackground" class="popup-background hidden"></div>
    <div id="priceEditPopup" class="popup hidden">
        <div class="popup-content" id="editContent">
            <span id="closeaddprice" class="close">&times;</span>
            <h2 id="editProductName">-</h2>
            <p id="editCategoryName">-</p>
            <p id="editProductDescription">-</p>
            <div class="form-row">
                <input type="text" placeholder="Enter Cost Price" id="purchasecostprice">
                <input type="text" placeholder="Enter GST Percentage" id="purchasegstpercentage">
            </div>
            <div class="form-row">
                <input type="text" value="0" id="purchasesellingprice" placeholder="Enter Selling Price">
                <input type="text" placeholder="Enter Parlour Price" id="purchaseparlourprice">
            </div>
            <button id="editProductPrice" class="btn-primary">Edit Price</button>
            <button id="cancelAddPrice" class="btn-secondary">Cancel</button>
            <div class="error-cont"></div>

        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode@2.0.9/dist/html5-qrcode.min.js"></script>

    <script>
        let inputs = document.querySelectorAll("form input.required");
        let dropdowns = document.querySelectorAll("form select.required");
        let errorTexts = document.querySelectorAll("form .error-text");

        let isProductNew = false;
    </script>

    <script src="../scripts/helperFunctions.js"></script>
    <script src="../scripts/validate.js"></script>
    <script src="./scripts/PurchaseTable.js"></script>
    <script src="./scripts/handlePopup.js"></script>
    <script src="./scripts/handleBarcodeScanner.js"></script>


    <script>

        <?php

        if (count($purchaseRows) > 0) {

            foreach ($purchaseRows as $k => $v) {


                echo "purchaseInvoiceTable.insertRow(" . $v["purchase_item_product_id"] . ", '" . $v["purchase_item_product_name"] . "'," . $v["purchase_item_price_id"] . "," . $v["purchase_item_product_price"] . "," . $v["purchase_item_product_gst"] . "," . $v["purchase_item_product_is_igst"] . ", " . $v["purchase_item_product_quantity"] . ");";
            }
        }
        echo "refreshView();";
        ?>


    </script>

    <!-- submitting data -->
    <script>
        const purchaseId = <?php echo $purchaseDetails["purchase_id"]; ?>;

        let purchaseObject = {
            "date": document.getElementById("txtpurchasedate").value,
            "supplier": document.getElementById("ddlsupplier").value,
            "purchaseNo": document.getElementById("txtpurchasenumber").value,
            "amount": 0,
            "id": 0,
            "rows": []
        };



        let submitBtn = document.querySelector(".submit--btn");
        submitBtn.addEventListener("click", e => {

            //if form is valid
            if (isValid()) {
                if (purchaseInvoiceTable.calculateNetTotal() > 0) {

                    purchaseObject.date = document.getElementById("txtpurchasedate").value;
                    purchaseObject.supplier = document.getElementById("ddlsupplier").value;
                    purchaseObject.amount = purchaseInvoiceTable.calculateNetTotal();
                    purchaseObject.purchaseNo = document.getElementById("txtpurchasenumber").value;
                    purchaseObject.id = purchaseId;
                    purchaseObject.rows = purchaseInvoiceTable.invoiceRows;

                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function () {
                        if (this.readyState == 4 && this.status == 200) {
                            var result = JSON.parse(this.responseText);
                            removeLoadingState(submitBtn);
                            showAlert(result.message, result.status);
                            if (result.status === "success") {
                                refreshPage();
                            }
                        }
                    };

                    console.log(purchaseObject);
                    addLoadingState(submitBtn);
                    xmlhttp.open("POST", `services/updatePurchase.php`, true);
                    xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    xmlhttp.send("data=" + JSON.stringify(purchaseObject));
                } else {
                    showAlert("Purchase not updated", "error");
                }
            }
            else {
                showAlert("Enter All Required Fields", "error");
            }

        });
    </script>

</body>

</html>