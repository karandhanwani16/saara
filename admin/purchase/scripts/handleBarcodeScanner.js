var html5QrcodeScanner;

const scanBarcodeBtn = document.getElementById('scanBarcodeBtn');

const barcodeScanner = document.getElementById('barcodeScanner');
const barcodeBackground = document.getElementById('barcodeBackground');
const closeBarcodeScannerBtn = barcodeScanner.querySelector('.close');
const barcodePopup = document.getElementById('barcodeScanner');
//const addPopupProductBtn = document.getElementById('addProduct');

const manualEntryBtn = document.getElementById('manualEntryBtn');
const manualEntryCont = document.getElementById('manualEntryCont');
const barcodeReader = document.getElementById('barcodeReader');
const manualEntryAddBtn = document.getElementById('manualEntryAddBtn');

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

    errorMessage.offsetHeight;

    errorMessage.style.transition = 'opacity 0.3s ease-in';
    errorMessage.style.opacity = '1';

    setTimeout(() => {
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
    document.getElementById("txtbarcode").value = decodedText;
    fetchProductDetails(decodedText);
}

// Manual Entry
const handleManualEntryAdd = () => {
    const barcode = document.getElementById('manualBarcode').value;
    document.getElementById("txtbarcode").value = barcode;
    fetchProductDetails(barcode);
}

const handleManualEntry = () => {
    if (html5QrcodeScanner) {
        html5QrcodeScanner.clear();
        html5QrcodeScanner = null;
    }
    manualEntryCont.classList.remove('hidden');
    manualEntryBtn.classList.add('hidden');
    manualEntryAddBtn.addEventListener('click', handleManualEntryAdd);
}
// Event Listeners
closeBarcodeScannerBtn.addEventListener('click', closeBarcodeScanner);

barcodeBackground.addEventListener('click', closeBarcodeScanner);

scanBarcodeBtn.addEventListener("click", () => {
    openBarcodeScanner();
});

manualEntryBtn.addEventListener('click', handleManualEntry);