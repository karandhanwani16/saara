<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Upload</title>
    <link rel="stylesheet" href="../style/assets.css">
    <link rel="stylesheet" href="../style/forms.css">
    <link rel="stylesheet" href="./styles/salesStyle.css">
    <link rel="stylesheet" href="./styles/popup.css">
    <link rel="stylesheet" href="./styles/skeleton.css">
    <link rel="stylesheet" href="./styles/retry.css">
</head>

<body>

    <div class="alert--cont">
    </div>

    <!-- alert cont end -->


    <div class="container">

        <div id="retryMainCont" class="hidden">
            <div class="retry-cont">
                <div class="icon" aria-hidden="true">&#9888;</div>
                <h1>Oops! Upload Failed</h1>
                <p>We encountered an issue while uploading your sale. This could be due to a network problem or a temporary server issue. Don't worry, your data is safe. Please try again.</p>
                <button class="retry-button" id="retryBtn">Retry Upload</button>
            </div>
        </div>

        <div class="skeleton-cont hidden" id="skeletonCont">

            <div class="skeleton-card">
                <div class="skeleton-row">
                    <div class="skeleton skeleton-label"></div>
                    <div class="skeleton skeleton-value"></div>
                </div>
                <div class="skeleton-row">
                    <div class="skeleton skeleton-label"></div>
                    <div class="skeleton skeleton-value"></div>
                </div>
                <div class="skeleton-row">
                    <div class="skeleton skeleton-label"></div>
                    <div class="skeleton skeleton-value"></div>
                </div>
                <div class="skeleton-row">
                    <div class="skeleton skeleton-label"></div>
                    <div class="skeleton skeleton-value"></div>
                </div>
                <div class="skeleton-row">
                    <div class="skeleton skeleton-label"></div>
                    <div class="skeleton skeleton-value"></div>
                </div>
                <div class="skeleton-row">
                    <div class="skeleton skeleton-label"></div>
                    <div class="skeleton skeleton-value"></div>
                </div>
                <div class="skeleton-row">
                    <div class="skeleton skeleton-label"></div>
                    <div class="skeleton skeleton-value"></div>
                </div>
            </div>

            <div class="skeleton-btn-cont">
                <div class="skeleton skeleton-btn"></div>
                <div class="skeleton skeleton-btn"></div>
                <div class="skeleton skeleton-btn"></div>
            </div>

        </div>

        <div id="productUpload" class="step" data-step="1">
            <h1 id="productCount">No. of products: 0</h1>
            <div id="productList" class="product-list"></div>
        </div>

        <div id="customerDetails" class="step" data-step="2">
            <h1>Customer Details</h1>
            <form>
                <label for="sales-date">Sales date</label>
                <input type="date" id="sales-date" name="sales-date" value="<?php echo date('Y-m-d'); ?>">

                <label for="customer-name">Customer Name</label>
                <input type="text" id="customer-name" name="customer-name" placeholder="Customer Name">

                <label for="customer-phone">Customer Phone</label>
                <input type="tel" id="customer-phone" name="customer-phone" placeholder="Customer Phone">

                <label for="customer-email">Customer Email</label>
                <input type="email" id="customer-email" name="customer-email" placeholder="Customer Email">

                <label for="discount">Discount</label>
                <input type="text" value="0" id="discount" name="discount" placeholder="Discount">

                <label for="discount-type">Discount Type</label>
                <select id="discount-type" name="discount-type">
                    <option value="" disabled selected>Discount Type</option>
                    <option value="percentage">Percentage</option>
                    <option value="amount">Amount</option>
                </select>
            </form>
        </div>

        <div id="paymentDetails" class="step" data-step="3">
            <h1>Payment Details</h1>
            <div class="payment-summary">
                <div class="payment-row">
                    <span>Order Total Before Tax</span>
                    <span id="orderTotalBeforeTax">Rs. -</span>
                </div>
                <div class="payment-row">
                    <span>GST (SGST)</span>
                    <span id="sGstTotal">Rs. -</span>
                </div>
                <div class="payment-row">
                    <span>GST (CGST)</span>
                    <span id="cGstTotal">Rs. -</span>
                </div>
                <div class="payment-row">
                    <span>GST (IGST)</span>
                    <span id="iGstTotal">Rs. -</span>
                </div>
                <div class="payment-row">
                    <span>Total Before Discount</span>
                    <span id="totalBeforeDiscount">Rs. -</span>
                </div>
                <div class="payment-row">
                    <span>Discount</span>
                    <span id="discountTotal">Rs. -</span>
                </div>
                <div class="payment-row">
                    <span>Final Discount</span>
                    <span id="finalDiscount">Rs. -</span>
                </div>
                <div class="payment-row">
                    <span>Round Off</span>
                    <span id="roundOff">Rs. -</span>
                </div>
                <div class="payment-row">
                    <span>Total</span>
                    <span id="totalAfterDiscount">Rs. -</span>
                </div>
            </div>
            <form>
                <label for="amount-cash">Amount In Cash</label>
                <input type="text" value="0" id="amount-cash" name="amount-cash" placeholder="Amount In Cash">

                <label for="amount-upi">Amount in UPI</label>
                <input type="text" value="0" id="amount-upi" name="amount-upi" placeholder="Amount in UPI">


            </form>
        </div>


        <div id="orderSummary" class="step" data-step="4">
            <div class="order-summary">
                <div class="order-row">
                    <span>Order Id</span>
                    <span id="orderId">-</span>
                </div>
                <div class="order-row">
                    <span>Order Date</span>
                    <span id="orderDate">-</span>
                </div>
                <div class="order-row">
                    <span>Customer Name</span>
                    <span id="customerName">-</span>
                </div>
                <div class="order-row">
                    <span>Customer Phone</span>
                    <span id="customerPhone">-</span>
                </div>
                <div class="order-row">
                    <span>Payment Mode</span>
                    <span id="paymentMode">-</span>
                </div>
                <div class="order-row">
                    <span>Order Total</span>
                    <span id="orderTotal">-</span>
                </div>

            </div>
        </div>


        <div class="order-summary-btns" id="orderSummaryBtns">
            <button class="btn btn-download" id="downloadInvoiceBtn">
                <img src="./icons/pdf-icon.svg" alt="Download PDF" />
                Download PDF
            </button>

            <button class="btn btn-back" id="goBackBtn">
                <img src="./icons/back-icon.svg" alt="Go Back" />
                Go Back
            </button>

            <button class="btn btn-add" id="addProductBtn">
                <img src="./icons/plus-icon.svg" alt="Add Product" />
                Add Products
            </button>
            <button class="btn btn-finalize" id="finalizeBtn">
                <img src="./icons/right-swipe-icon.svg" alt="Finalize Cart" />
                Finalize Cart
            </button>
        </div>
    </div>

    <!-- Barcode Scanner Popup -->
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


    <!-- Product Popup -->
    <div id="popupBackground" class="popup-background hidden"></div>
    <div id="popup" class="popup hidden">
        <div class="popup-content" id="productContent">
            <span class="close">&times;</span>
            <h2 id="productName">-</h2>
            <p id="categoryName">-</p>
            <p id="productDescription">-</p>
            <div class="form-row">
                <select id="productPrice">
                    <option value="">Select Product Price</option>
                </select>
                <select id="quantity">
                    <option value="">Select Quantity</option>
                </select>
            </div>
            <div class="form-row">
                <input type="text" value="0" id="popupDiscount" placeholder="Discount">
                <select id="discountType">
                    <option value="">Select Discount Type</option>
                    <option value="percentage" selected>Percentage</option>
                    <option value="fixed">Fixed Amount</option>
                </select>
            </div>

            <div class="form-row">
                <label class="switch">
                    <p>Is IGST</p>
                    <input type="checkbox" value="1" id="isIGST">
                    <span class="slider round"></span>
                </label>
            </div>

            <button id="addProduct" class="btn-primary">Add Product</button>
            <button id="cancelProduct" class="btn-secondary">Cancel</button>
            <div class="error-cont"></div>

        </div>
    </div>



    <div id="editPopupBackground" class="popup-background hidden"></div>
    <div id="editPopup" class="popup hidden">
        <div class="popup-content" id="editContent">
            <span class="close">&times;</span>
            <h2 id="editProductName">-</h2>
            <p id="editCategoryName">-</p>
            <p id="editProductDescription">-</p>
            <div class="form-row">
                <select id="editProductPrice">
                    <option value="">Select Product Price</option>
                </select>
                <select id="editQuantity">
                    <option value="">Select Quantity</option>
                </select>
            </div>
            <div class="form-row">
                <input type="text" value="0" id="editDiscount" placeholder="Discount">
                <select id="editDiscountType">
                    <option value="">Select Discount Type</option>
                    <option value="percentage" selected>Percentage</option>
                    <option value="fixed">Fixed Amount</option>
                </select>
            </div>
            <div class="form-row">
                <label class="switch">
                    <p>Is IGST</p>
                    <input type="checkbox" value="1" id="editIsIGST">
                    <span class="slider round"></span>
                </label>
            </div>
            <button id="editProduct" class="btn-primary">Edit Product</button>
            <button id="cancelEdit" class="btn-secondary">Cancel</button>
            <div class="error-cont"></div>

        </div>
    </div>


    <script src="https://unpkg.com/html5-qrcode@2.0.9/dist/html5-qrcode.min.js"></script>

    <script src="./scripts/saleTable.js"></script>

    <script>
        const currentFile = "salesUpload.php";
        const saleTable = new SaleTable();
        var currentSaleId = "";
        const saleDetails = {
            "saleDate": "",
            "customerName": "",
            "customerPhone": "",
            "customerEmail": "",
            "discount": "",
            "discountType": ""
        }

        const paymentDetails = {
            "cashAmount": "",
            "upiAmount": ""
        }

        const totalDetails = {
            "grossTotal": "",
            "sGstTotal": "",
            "cGstTotal": "",
            "iGstTotal": "",
            "totalBeforeDiscount": "",
            "discountTotal": "",
            "roundOff": "",
            "netTotal": ""
        }
    </script>

    <script src="../scripts/helperFunctions.js"></script>
    <script src="./scripts/saleRow.js"></script>
    <script src="./scripts/handlePopup.js"></script>
    <script src="./scripts/handleStepper.js"></script>
    <script src="./scripts/handleBarcodeScanner.js"></script>
    <script src="./scripts/handleProductSwipes.js"></script>

</body>

</html>