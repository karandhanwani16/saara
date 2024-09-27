// function onScanSuccess(decodedText, decodedResult) {
//     html5QrcodeScanner.clear();
//     document.querySelector('#txtbarcode').value = decodedText;
// }

// var html5QrcodeScanner = new Html5QrcodeScanner("qr-reader", { fps: 60, qrbox: { width: 250, height: 125 } });

// function clickButton(selector) {
//     const button = document.querySelector(selector);
//     if (button) {
//         button.click();
//         console.log(`Clicked ${selector}`);
//     }
// }

// function waitForCameraSelection() {
//     const cameraSelectionObserver = new MutationObserver((mutations, observer) => {
//         mutations.forEach((mutation) => {
//             if (mutation.addedNodes.length) {
//                 const cameraSelect = document.querySelector('#qr-reader__camera_selection');
//                 if (cameraSelect) {
//                     for (let i = 0; i < cameraSelect.options.length; i++) {
//                         if (cameraSelect.options[i].text.toLowerCase().includes("back")) {
//                             cameraSelect.selectedIndex = i;
//                             console.log(`Selected back camera: ${cameraSelect.options[i].text}`);
//                             break;
//                         }
//                     }
//                     const startButton = document.querySelector('#qr-reader__dashboard_section_csr > span:nth-child(2) > button:nth-child(1)');

//                     if (startButton) {
//                         startButton.click();
//                     }
//                     observer.disconnect();
//                 }
//             }
//         });
//     });
//     const config = { childList: true, subtree: true };
//     cameraSelectionObserver.observe(document.body, config);
// }

// function waitForStartButton() {
//     const startButtonObserver = new MutationObserver((mutations, observer) => {
//         mutations.forEach((mutation) => {
//             if (mutation.addedNodes.length) {
//                 const startButton = document.querySelector('#qr-reader__dashboard_section_csr > span:nth-child(2) > button:nth-child(2)');
//                 if (startButton) {
//                     startButton.click();
//                     console.log(`Clicked #qr-reader__dashboard_section_csr > span:nth-child(2) > button:nth-child(2)`);
//                     observer.disconnect();
//                 }
//             }
//         });
//     });
//     const config = { childList: true, subtree: true };
//     startButtonObserver.observe(document.body, config);
// }

// const permissionsButtonObserver = new MutationObserver((mutations, observer) => {
//     mutations.forEach((mutation) => {
//         if (mutation.addedNodes.length) {
//             const permissionsButton = document.querySelector('#qr-reader__dashboard_section_csr > div > button');
//             if (permissionsButton) {
//                 permissionsButton.click();
//                 console.log(`Clicked #qr-reader__dashboard_section_csr > div > button`);
//                 observer.disconnect();
//                 waitForCameraSelection();
//             }
//         }
//     });
// });

// const config = { childList: true, subtree: true };
// permissionsButtonObserver.observe(document.querySelector('#qr-reader'), config);

// html5QrcodeScanner.render(onScanSuccess);

var html5QrcodeScanner;

function onScanSuccess(decodedText, decodedResult) {
    html5QrcodeScanner.clear();
    document.querySelector('#txtbarcode').value = decodedText;
    closePopup("scan");
}

function clickButton(selector) {
    const button = document.querySelector(selector);
    if (button) {
        button.click();
    }
}

function waitForCameraSelection() {
    const cameraSelectionObserver = new MutationObserver((mutations, observer) => {
        mutations.forEach((mutation) => {
            if (mutation.addedNodes.length) {
                const cameraSelect = document.querySelector('#qr-reader__camera_selection');
                if (cameraSelect) {
                    for (let i = 0; i < cameraSelect.options.length; i++) {
                        if (cameraSelect.options[i].text.toLowerCase().includes("back")) {
                            cameraSelect.selectedIndex = i;
                            break;
                        }
                    }
                    const startButton = document.querySelector('#qr-reader__dashboard_section_csr > span:nth-child(2) > button:nth-child(1)');

                    if (startButton) {
                        startButton.click();
                    }
                    observer.disconnect();
                }
            }
        });
    });
    const config = { childList: true, subtree: true };
    cameraSelectionObserver.observe(document.body, config);
}

function waitForStartButton() {
    const startButtonObserver = new MutationObserver((mutations, observer) => {
        mutations.forEach((mutation) => {
            if (mutation.addedNodes.length) {
                const startButton = document.querySelector('#qr-reader__dashboard_section_csr > span:nth-child(2) > button:nth-child(2)');
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

function initializeScanner() {
    html5QrcodeScanner = new Html5QrcodeScanner("qr-reader", { fps: 60, qrbox: { width: 300, height: 200 } });

    const permissionsButtonObserver = new MutationObserver((mutations, observer) => {
        mutations.forEach((mutation) => {
            if (mutation.addedNodes.length) {
                const permissionsButton = document.querySelector('#qr-reader__dashboard_section_csr > div > button');
                if (permissionsButton) {
                    permissionsButton.click();
                    observer.disconnect();
                    waitForCameraSelection();
                }
            }
        });
    });

    const config = { childList: true, subtree: true };
    permissionsButtonObserver.observe(document.querySelector('#qr-reader'), config);

    html5QrcodeScanner.render(onScanSuccess);
}


const scanBtn = document.querySelector('.scan-btn');

scanBtn.addEventListener('click', () => {
    initializeScanner();
    openPopup("scan");
});