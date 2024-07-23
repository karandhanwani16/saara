// Variables
const generateBtn = document.querySelector('.generate-btn');
const printBtn = document.querySelector('#printBtn');
const useCodeBtn = document.querySelector('#useCodeBtn');


generateBtn.addEventListener('click', generateBarcodeMain);
useCodeBtn.addEventListener("click", useCodeFunc)
printBtn.addEventListener("click", printBarcode);

//  Main generate Logic

function generateBarcodeMain() {
    openPopup("generate");
    loadScript("https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/barcodes/JsBarcode.code128.min.js", generateBarcode);
}

function generateBarcode() {
    generatedNumber = generateBarcodeNumber(8);

    JsBarcode("#generatedBarcode", generatedNumber, {
        format: "CODE128",
        barWidth: 2,
        height: 45,
        margin: 10,
        fontSize: 16,
        textMargin: 2
    });

}



// use code and print button

function useCodeFunc() {
    closePopup("generate");
    const barcodeInput = document.querySelector("#txtbarcode");
    barcodeInput.value = generatedNumber;
}

function printBarcode() {
    // Get the div element by its ID
    var divElement = document.querySelector(".barcode");
    // Get the content inside the div
    var divContent = divElement.innerHTML;

    // Create a new window
    var printWindow = window.open('', '', 'height=600,width=800');

    // Add HTML to the new window
    printWindow.document.write('<html><head><title>Print Div Content</title>');
    // Add CSS for page size and margins
    printWindow.document.write('<style>');
    printWindow.document.write('@page { size: 125mm 55mm; margin: 2.5mm; }');
    printWindow.document.write('body { font-family: Arial, sans-serif; }');
    printWindow.document.write('</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write(divContent);
    printWindow.document.write('</body></html>');

    // Close the document to complete the content writing
    printWindow.document.close();

    // Wait for the content to be fully loaded before printing
    printWindow.onload = function () {
        printWindow.print();
        printWindow.close();
    };
}


// function printBarcode() {
//     // Get the number of copies
//     // const numCopies = parseInt(document.querySelector("#numCopies").value, 10);
//     const numCopies = 5;

//     // Get the div element by its ID
//     var divElement = document.querySelector(".barcode");
//     // Get the content inside the div
//     var divContent = divElement.innerHTML;

//     // Function to create and print an iframe
//     function printIframe(content) {
//         // Create an iframe
//         var printIframe = document.createElement('iframe');
//         printIframe.style.position = 'absolute';
//         printIframe.style.width = '0';
//         printIframe.style.height = '0';
//         printIframe.style.border = 'none';

//         // Append the iframe to the body
//         document.body.appendChild(printIframe);

//         // Write the content to the iframe
//         var iframeDoc = printIframe.contentWindow.document;
//         iframeDoc.open();
//         iframeDoc.write('<html><head><title>Print Div Content</title>');
//         iframeDoc.write('<style>@page { size: 100mm 40mm; margin: 5mm; } body { font-family: Arial, sans-serif; }</style>');
//         iframeDoc.write('</head><body>');
//         iframeDoc.write(content);
//         iframeDoc.write('</body></html>');
//         iframeDoc.close();

//         // Print the content of the iframe
//         printIframe.contentWindow.onload = function () {
//             printIframe.contentWindow.print();
//             // Remove the iframe after printing
//             document.body.removeChild(printIframe);
//         };
//     }

//     // Print the specified number of copies
//     for (let i = 0; i < numCopies; i++) {
//         printIframe(divContent);
//     }
// }


// Generic Functions

function loadScript(src, callback) {
    // Check if the script already exists
    const existingScript = document.querySelector(`script[src="${src}"]`);
    if (existingScript) {
        if (callback) callback();
        return;
    }

    // Create and append the script if not present
    const script = document.createElement('script');
    script.src = src;
    script.async = true;
    script.onload = callback;
    script.onerror = function () {
        console.error(`Failed to load script: ${src}`);
    };
    document.head.appendChild(script);
}

function generateBarcodeNumber(n) {
    if (n <= 0) return '0';
    const min = Math.pow(10, n - 1);
    const max = Math.pow(10, n) - 1;
    return Math.floor(Math.random() * (max - min + 1)) + min;
}