var html5QrcodeScanner;
const addProductBtn = document.getElementById('addProductBtn');
const barcodeScanner = document.getElementById('barcodeScanner');
const barcodeBackground = document.getElementById('barcodeBackground');
const closeBarcodeScannerBtn = barcodeScanner.querySelector('.close');
const barcodePopup = document.getElementById('barcodeScanner');
const addPopupProductBtn = document.getElementById('addProduct');
const manualEntryBtn = document.getElementById('manualEntryBtn');
const manualEntryCont = document.getElementById('manualEntryCont');
const barcodeReader = document.getElementById('barcodeReader');
const manualEntryAddBtn = document.getElementById('manualEntryAddBtn');
var currentProduct;
var currentPrices;

// General Functions
const clickButton = (selector) => {
    const button = document.querySelector(selector);
    if (button) {
        button.click();
    }
}

const closeBarcodeScanner = () => {
    barcodeScanner.classList.add('hidden');
    barcodeBackground.classList.add('hidden');
    manualEntryCont.classList.add('hidden');
    document.getElementById('manualBarcode').value = '';
    if (html5QrcodeScanner) {
        html5QrcodeScanner.clear();
    }
}

const openBarcodePopup = () => {
    barcodePopup.classList.remove('hidden');
    document.getElementById('popupBackground').classList.remove('hidden');
}

const waitForStartButton = () => {
    const startButtonObserver = new MutationObserver((mutations, observer) => {
        mutations.forEach((mutation) => {
            if (mutation.addedNodes.length) {
                const startButton = document.querySelector('#barcodeReader__dashboard_section_csr > span:nth-child(2) > button:nth-child(2)');
                if (startButton) {
                    startButton.click();
                    observer.disconnect();
                }
            }
        });
    });
    const config = { childList: true, subtree: true };
    startButtonObserver.observe(document.body, config);
}

const addError = (errorCont, message) => {
    const errorMessage = document.createElement('div');
    errorMessage.classList.add('error');
    errorMessage.textContent = message;
    errorMessage.style.opacity = '0';
    errorCont.appendChild(errorMessage);

    // Trigger reflow to ensure the initial opacity is applied
    errorMessage.offsetHeight;

    // Fade in the error message
    errorMessage.style.transition = 'opacity 0.3s ease-in';
    errorMessage.style.opacity = '1';

    setTimeout(() => {
        // Fade out the error message
        errorMessage.style.opacity = '0';
        errorMessage.addEventListener('transitionend', () => {
            errorCont.removeChild(errorMessage);
        }, { once: true });
    }, 2000);

}

// Main Functions

// Step 1: Open Barcode Scanner
const openBarcodeScanner = () => {
    barcodeScanner.classList.remove('hidden');
    barcodeBackground.classList.remove('hidden');
    initBarcodeScanner();
}

// Step 2: Initialize Barcode Scanner
const initBarcodeScanner = () => {
    if (html5QrcodeScanner) {
        html5QrcodeScanner.render(onScanSuccess);
    } else {
        html5QrcodeScanner = new Html5QrcodeScanner(
            "barcodeReader",
            { fps: 60, qrbox: { width: 300, height: 200 } }
        );


        const permissionsButtonObserver = new MutationObserver((mutations, observer) => {
            mutations.forEach((mutation) => {
                if (mutation.addedNodes.length) {
                    const permissionsButton = document.querySelector('#barcodeReader__dashboard_section_csr > div > button');
                    if (permissionsButton) {
                        permissionsButton.click();
                        observer.disconnect();
                        waitForCameraSelection();
                    }
                }
            });
        });

        const config = { childList: true, subtree: true };
        permissionsButtonObserver.observe(document.querySelector('#barcodeReader'), config);


        html5QrcodeScanner.render(onScanSuccess);
    }
}

// Step 3: Wait for Camera Selection
const waitForCameraSelection = () => {
    const cameraSelectionObserver = new MutationObserver((mutations, observer) => {
        mutations.forEach((mutation) => {
            if (mutation.addedNodes.length) {
                const cameraSelect = document.querySelector('#barcodeReader__camera_selection');
                if (cameraSelect) {
                    for (let i = 0; i < cameraSelect.options.length; i++) {
                        if (cameraSelect.options[i].text.toLowerCase().includes("back")) {
                            cameraSelect.selectedIndex = i;
                            break;
                        }
                    }
                    const startButton = document.querySelector('#barcodeReader__dashboard_section_csr > span:nth-child(2) > button:nth-child(1)');

                    if (startButton) {
                        startButton.click();
                        setTimeout(() => {
                            manualEntryBtn.classList.remove('hidden');
                        }, 1500);
                    }
                    observer.disconnect();
                }
            }
        });
    });
    const config = { childList: true, subtree: true };
    cameraSelectionObserver.observe(document.body, config);
}

// Step 4: On Scan Success
const onScanSuccess = (decodedText) => {
    fetchProductDetails(decodedText);
}

// Step 5: Fetch Product Details
const fetchProductDetails = (barcode) => {
    // fetch(`./services/getProductDetails.php?barcode=74249367`)
    fetch(`./services/getProductDetails.php?barcode=${barcode}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                closeBarcodeScanner();
                openProductPopup();
                populatePopup(data.data);
            } else {
                showAlert(data.error, 'error');
                handleManualEntry();
            }
        })
        .catch(error => {
            alert('An error occurred while fetching product details');
        });
}

// Step 6: Populate Popup
const populatePopup = (product) => {
    const { details, prices } = product;
    currentProduct = details;
    currentPrices = prices;
    const {
        product_name,
        product_description,
        category_name,
    } = details;


    document.getElementById('productName').textContent = product_name;
    document.getElementById('categoryName').textContent = category_name;
    document.getElementById('productDescription').textContent = product_description;

    const productPriceSelect = document.getElementById('productPrice');

    // Empty the product price select
    productPriceSelect.innerHTML = '';

    // create the product price default option
    const defaultOption = document.createElement('option');
    defaultOption.value = '';
    defaultOption.textContent = 'Select Price';
    productPriceSelect.appendChild(defaultOption);

    // create the product price options
    prices.forEach(price => {
        const option = document.createElement('option');
        option.value = price["product_prices_id"];
        option.textContent = price["product_prices_selling_price"];
        productPriceSelect.appendChild(option);
    });

    const quantitySelect = document.getElementById('quantity');
    // Empty the quantity select
    quantitySelect.innerHTML = '';

    productPriceSelect.addEventListener("change", (e) => {

        // empty the quantity select
        quantitySelect.innerHTML = '';

        const priceId = parseInt(e.target.value);
        const price = prices.find(p => p["product_prices_id"] === priceId);
        const quantity = parseInt(price["product_prices_stock"]) >= 10 ? 10 : parseInt(price["product_prices_stock"]);
        for (let i = 1; i <= quantity; i++) {
            const option = document.createElement('option');
            option.value = i;
            option.textContent = i;
            quantitySelect.appendChild(option);
        }
    });

    // document.getElementById('productPrice').value = product.price;
    // Populate other fields as needed
}

// Step 7: Add Product to Sale
addPopupProductBtn.addEventListener("click", (e) => {
    e.preventDefault();
    // Get Product Price Text
    const productPrice = document.getElementById('productPrice').selectedOptions[0].text;
    const productPriceId = parseInt(document.getElementById('productPrice').value);
    const quantity = parseInt(document.getElementById('quantity').value);
    const discount = parseFloat(document.getElementById('popupDiscount').value);
    const discountType = document.getElementById('discountType').value;

    // check if all the fields are filled
    // check if the discount is empty or not a number
    if (productPrice == '' || quantity == '' || isNaN(discount) || discountType == '') {
        // show error message in popup
        addError(document.querySelector('#barcodeContent .error-cont'), "Please fill all the fields");
        return;
    } else if (discountType == 'percentage' && discount > 100) {
        addError(document.querySelector('#barcodeContent .error-cont'), "Discount Percentage cannot be greater than 100");
        return;
    }

    // Check if the product with same product id/name and price already exists
    const existingProduct = saleTable.rows.find(row => 
        row.productId === currentProduct.product_id && 
        row.price === productPrice
    );

    if (existingProduct) {
        addError(document.querySelector('#productContent .error-cont'), "This product with the same price already exists in the cart");
        return;
    }

    const gstPercentage = currentPrices.find(p => p["product_prices_id"] == productPriceId)["product_prices_gst_percentage"];
    const isIGST = document.getElementById('isIGST').checked;


    saleTable.addRow({
        productId: currentProduct.product_id,
        product: currentProduct.product_name,
        category: currentProduct.category_name,
        description: currentProduct.product_description,
        price: productPrice,
        priceId: productPriceId,
        quantity: quantity,
        gstPercentage: gstPercentage,
        isIGST: isIGST,
        discount: discount,
        discountType: discountType,
        total: productPrice * quantity - discount,
        prices: currentPrices
    });

    closeProductPopup();

    // clear the popup fields
    document.getElementById('productPrice').selectedIndex = 0;
    document.getElementById('quantity').selectedIndex = 0;
    document.getElementById('popupDiscount').value = 0;
    document.getElementById('discountType').selectedIndex = 1;

});

// Manual Entry
const handleManualEntryAdd = () => {
    const barcode = document.getElementById('manualBarcode').value;
    // closeBarcodeScanner();
    fetchProductDetails(barcode);
}

const handleManualEntry = () => {
    // Destroy the QR scanner
    if (html5QrcodeScanner) {
        html5QrcodeScanner.clear();
        html5QrcodeScanner = null;
    }
    // Show manual entry text and button
    manualEntryCont.classList.remove('hidden');
    manualEntryBtn.classList.add('hidden');
    manualEntryAddBtn.addEventListener('click', handleManualEntryAdd);
}




// Event Listeners
closeBarcodeScannerBtn.addEventListener('click', closeBarcodeScanner);

barcodeBackground.addEventListener('click', closeBarcodeScanner);

addProductBtn.addEventListener("click", () => {
    openBarcodeScanner();
    // fetchProductDetails('1111111111111');
});

manualEntryBtn.addEventListener('click', handleManualEntry);