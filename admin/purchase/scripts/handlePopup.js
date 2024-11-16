const purchaseInvoiceTable = new PurchaseTable();

// add new Product button
const addNewProduct=document.getElementById('addNewProduct');

// purchase popup
const popup = document.getElementById('purchasePopup');
const popupBackground = document.getElementById('purchasePopupBackground');
const addPurchase = document.getElementById('addpurchase');

// add price popup
const dropdown = document.getElementById('productPrice');
const ddlAddNewPrice = document.getElementById('ddlotherprice');
const addNewPrice = document.getElementById('addnewpurchaseprice');
const newPricePopup = document.getElementById('priceEditPopup');
const pricePopupBackground = document.getElementById('priceEditPopupBackground');

const addedPrice = document.getElementById('editProductPrice');

// generate new barcode for new price
const generateBarcodeBtn = document.getElementById('editGenerateBarcode');

// closing add purchase
const cancelAddPurchase = document.getElementById('cancelPurchase');
const closeAddPurchase = document.getElementById('closeaddpurchase');

const addPurchaseToTable = document.getElementById('addPurchaseToTable');

// closing add new price
const cancelAddPrice = document.getElementById('cancelAddPrice');
const closeAddPrice = document.getElementById('closeaddprice');
let deletePurchaseBtn;

//GLobal Variables
let currentProduct;
let gstValue;
let currentPopupDetails;
let currentObj = {}; 

// Redirect to Product Page
function redirectToProduct(){
    window.location.href = "../products/productsUpload.php?redirected=true";
}

// Add Barcode Flow
function openAddPurchasePopup() {
    const productBarcode = document.getElementById('txtbarcode');
    fetchProductDetails(productBarcode.value);
}

function fetchProductDetails(barcode) {
    fetch(`./services/getProductDetails.php?barcode=${barcode}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                popup.classList.remove('hidden');
                popupBackground.classList.remove('hidden');
                populatePopup(data.data);
            } else {
                showAlert(data.error, 'error');
            }
        })
        .catch(error => {

        });
}

function populatePopup(product) {
    const { details, prices } = product;
    currentProduct = details;

    currentObj.details = details; // Store details in currentObj
    currentObj.prices = prices;   // Store prices in currentObj

    const {
        product_id,
        product_name,
        product_description,
        category_name,
    } = details;


    currentPopupDetails = details;

    document.getElementById('productName').textContent = product_name;
    document.getElementById('categoryName').textContent = category_name;
    document.getElementById('productDescription').textContent = product_description;

    const productPriceSelect = document.getElementById('productPrice');

    productPriceSelect.innerHTML = '';

    const defaultOption = document.createElement('option');
    defaultOption.value = '';
    defaultOption.textContent = 'Select Price';
    productPriceSelect.appendChild(defaultOption);

    prices.forEach(price => {
        const option = document.createElement('option');
        option.value = price["product_prices_id"];
        option.textContent = price["product_prices_cost_price"];
        productPriceSelect.appendChild(option);
    });

    const otherPriceOption = document.createElement('option');
    otherPriceOption.value = 'otherPrice';
    otherPriceOption.id = 'ddlotherprice';
    otherPriceOption.textContent = 'Other Price';
    productPriceSelect.appendChild(otherPriceOption);
}

// Add Purchase Flow
function addPurchaseTable() {
    const productBarcode = document.getElementById("txtbarcode").value;
    const quantity = document.getElementById("purchasequantity").value;
    const priceId = parseInt(document.getElementById("productPrice").value); // Price ID selected by user
    const isIGST = document.getElementById("isIGST").checked;

    
    if (!priceId) {
        showAlert("Please select price", "error"); 
        return;
    }
    if (!quantity) {
        showAlert("Please select quantity", "error");
        return;
    }
    const productId = currentProduct.product_id;
    let selectedPrice = 0;
    let selectedGst = 0;

    // Find matching price and GST based on price ID
    currentObj.prices.forEach(price => {
        if (price["product_prices_id"] === parseInt(priceId)) {
            selectedPrice = parseFloat(price["product_prices_cost_price"]);
            selectedGst = parseFloat(price["product_prices_gst_percentage"]);
        }
    });

    purchaseInvoiceTable.insertRow(productId, currentProduct.product_name, priceId, selectedPrice, selectedGst, isIGST, quantity);
    popupBackground.classList.add('hidden');
    popup.classList.add('hidden');
    document.getElementById("barcodeBackground").classList.add('hidden');
    document.getElementById("barcodeScanner").classList.add('hidden');
    document.getElementById("manualBarcode").value="";
    document.getElementById("purchasequantity").value = "";
    document.getElementById("txtbarcode").value = "";
    refreshView();
}

function refreshView() {
    document.querySelector('.invoice-products-body').innerHTML = purchaseInvoiceTable.displayRows();
    addDeleteFunctionality();
    loadSubSummary();
    loadSummary();
}

function addDeleteFunctionality() {
    let deleteButtons = document.querySelectorAll(".delete-btn");
    deleteButtons.forEach(deleteButton => {
        deleteButton.addEventListener("click", e => {
            let id = deleteButton.attributes['data-id'].value;
            purchaseInvoiceTable.removeRow(id);
            refreshView();
        });
    });
}

function loadSubSummary() {
    document.querySelector('.invoice-products-footer').innerHTML = purchaseInvoiceTable.displaySubTotal();
}

function loadSummary() {
    document.querySelector('.net-total').innerHTML = `₹ ${purchaseInvoiceTable.calculateNetTotal()}`;
    document.querySelector('.round-off').innerHTML = `₹ ${purchaseInvoiceTable.calculateRoundOff()}`;
}

// Add New Price Flow
function visibleAddPriceBtn() {
    if (this.value === 'otherPrice') {
        addNewPrice.classList.remove('hidden');
    } else {
        addNewPrice.classList.add('hidden');
    }
}

function openAddPricePopup() {
    document.getElementById("editProductName").innerHTML = currentPopupDetails.product_name;
    document.getElementById("editCategoryName").innerHTML = currentPopupDetails.category_name;
    document.getElementById("editProductDescription").innerHTML = currentPopupDetails.product_description;
    pricePopupBackground.classList.remove('hidden');
    newPricePopup.classList.remove('hidden');
}

function AddPriceInDB() {
    const costPrice = document.getElementById('purchasecostprice');
    const gstPercentage = document.getElementById('purchasegstpercentage');
    const sellingPrice = document.getElementById('purchasesellingprice');
    const parlourPrice = document.getElementById('purchaseparlourprice');
    const batchNumber = document.getElementById('purchasebatchnumber');
    const productBarcode = document.getElementById('txtbarcode');
    if (!costPrice.value || !gstPercentage.value || !sellingPrice.value || !parlourPrice.value || !batchNumber.value) {
        showAlert('Please fill in all price fields', 'error');
        return;
    }
    else {
        pricePopupBackground.classList.add('hidden');
        newPricePopup.classList.add('hidden');
        let productObject = {
            "costprice": 0,
            "gstpercentage": 0,
            "sellingprice": 0,
            "parlourprice": 0,
            "batchnumber":0,
            "barcode": productBarcode.value
        };
        productObject.costprice = costPrice.value;
        productObject.gstpercentage = gstPercentage.value;
        productObject.sellingprice = sellingPrice.value;
        productObject.parlourprice = parlourPrice.value;
        productObject.batchnumber = parlourPrice.value;
        productObject.barcode = productBarcode.value;
        //productObject.rows = purchaseInvoiceTable.invoiceRows;

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                var result = JSON.parse(this.responseText);
                showAlert(result.message, result.status);
                if (result.status === "success") {
                    costPrice.value = '';
                    gstPercentage.value = '';
                    sellingPrice.value = '';
                    parlourPrice.value = '';
                    batchNumber.value='';
                    openAddPurchasePopup();
                }
                else {
                    // alert("Didnt updated price");
                }
            }
        };
        xmlhttp.open("POST", `services/updateProductPrice.php`, true);
        xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xmlhttp.send("data=" + JSON.stringify(productObject));
    }
}

// Generate barcode for new price
function newPriceBarcode(){
    const sellingPrice = document.getElementById("purchasesellingprice").value;
    const parlourPrice = document.getElementById("purchaseparlourprice").value;
    const batchNumber = document.getElementById("purchasebatchnumber").value;
    debugger;
    generateBarcodeMain(sellingPrice, parlourPrice,batchNumber); 
}

// General Functions
function closeAddPricePopup() {
    pricePopupBackground.classList.add('hidden');
    newPricePopup.classList.add('hidden');
}

function closeAddPurchasePopup() {
    popupBackground.classList.add('hidden');
    popup.classList.add('hidden');
}

// Event Listeners

// Redirect to product page
addNewProduct.addEventListener('click',redirectToProduct);

// Add Barcode
addPurchase.addEventListener('click', openAddPurchasePopup);

// Add Purchase
addPurchaseToTable.addEventListener('click', addPurchaseTable);

//generate new price barcode 


// Add New Price
dropdown.addEventListener('change', visibleAddPriceBtn);
addNewPrice.addEventListener('click', openAddPricePopup);
addedPrice.addEventListener('click', AddPriceInDB);

// generate barcode for new price
generateBarcodeBtn.addEventListener('click',newPriceBarcode);

// Close Add Price
cancelAddPrice.addEventListener('click', closeAddPricePopup);
closeAddPrice.addEventListener('click', closeAddPricePopup);

// Close Add Purchase
cancelAddPurchase.addEventListener('click', closeAddPurchasePopup);
closeAddPurchase.addEventListener('click', closeAddPurchasePopup);


