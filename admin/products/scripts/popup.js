const popupCont = document.querySelector(".popup-cont");
const popupBackdrop = document.querySelector(".popup-backdrop");
const popupCloseBtn = document.querySelector(".close-btn");


function openPopup(type) {
    changePopupHeader(type);

    if (type === "generate") {

        // show generate body
        const generateBody = document.querySelector(".generate--cont");
        if (generateBody) {
            generateBody.style.display = "flex";
        } else {
            getGenerateContainerBody();
        }


        // extra details
        const extraDetailsHeader1 = document.querySelector("#extraDetailsHeader1");
        const extraDetailsHeader2 = document.querySelector("#extraDetailsHeader2");
        const productCode = document.querySelector("#productCode");
        const footerSellingPrice = document.querySelector("#footerSellingPrice");
        const footerParlourPrice = document.querySelector("#footerParlourPrice");

        const productName = document.querySelector("#txtname");
        const productCodeInput = document.querySelector("#txtcode");

        const sellingPrice = document.querySelector("#txtsellingprice");
        const parlourPrice = document.querySelector("#txtparlourprice");

        extraDetailsHeader1.innerHTML = `<p class='sara-barcode-logo' >Saara Beauty Centre & Gift</p>`;
        extraDetailsHeader2.innerHTML = `${productName.value} `;
        productCode.innerHTML = `${productCodeInput.value} `;
        footerSellingPrice.innerHTML = sellingPrice.value;
        footerParlourPrice.innerHTML = parlourPrice.value;


        // popup body height adjustment
        const popupBody = document.querySelector(".popup-body");
        popupBody.style.height = "auto";
    } else {
        // popup body height adjustment
        const popupBody = document.querySelector(".popup-body");
        popupBody.style.height = "328px";
    }

    popupCont.style.display = "block";
    popupBackdrop.style.display = "block";
}

function closePopup(type) {

    if (type === "scan") {
        if (html5QrcodeScanner) {
            html5QrcodeScanner.clear();
        }
    } else {
        const generateBody = document.querySelector(".generate--cont");
        generateBody.style.display = "none";
    }
    popupCont.style.display = "none";
    popupBackdrop.style.display = "none";

}

function changePopupHeader(type) {
    document.querySelector(".popup-header p").innerHTML = type === "scan" ? "Scan Barcode" : "Generate Barcode";
}


popupCloseBtn.addEventListener("click", function () {
    closePopup("scan");
});


function getGenerateContainerBody() {

    const popupBody = document.querySelector(".popup-body");

    const generateCont = document.createElement("div");
    generateCont.style.display = "flex";
    generateCont.classList.add("generate--cont");

    const barcode = document.createElement("div");
    barcode.classList.add("barcode");

    // Barcode Content Start
    const center = document.createElement("center");


    const extraDetailsHeader1 = document.createElement("div");
    extraDetailsHeader1.id = "extraDetailsHeader1";


    const extraDetailsHeader2 = document.createElement("div");
    extraDetailsHeader2.id = "extraDetailsHeader2";

    // Middle Cont start
    const middleCont = document.createElement("div");
    middleCont.classList.add("middleCont");

    const productCode = document.createElement("div");
    productCode.id = "productCode";

    const barcodeImage = document.createElement("img");
    barcodeImage.id = "generatedBarcode";

    middleCont.appendChild(productCode);
    middleCont.appendChild(barcodeImage);
    // Middle Cont end

    const extraDetailsFooter = document.createElement("div");
    extraDetailsFooter.id = "extraDetailsFooter";

    center.appendChild(extraDetailsHeader1);
    center.appendChild(extraDetailsHeader2);
    center.appendChild(middleCont);
    center.appendChild(extraDetailsFooter);

    // Barcode Content End
    barcode.appendChild(center);

    generateCont.appendChild(barcode);
    const btnCont = document.createElement("div");
    btnCont.classList.add("btn-cont");

    const printBtn = document.createElement("div");
    printBtn.classList.add("btn", "print-btn");
    printBtn.id = "printBtn";
    printBtn.innerHTML = "Print";

    const useCodeBtn = document.createElement("div");
    useCodeBtn.classList.add("btn", "print-btn");
    useCodeBtn.id = "useCodeBtn";
    useCodeBtn.innerHTML = "Use Code";

    btnCont.appendChild(printBtn);
    btnCont.appendChild(useCodeBtn);
    generateCont.appendChild(btnCont);
    popupBody.appendChild(generateCont);

    // generateBarcodeMain();

    useCodeBtn.addEventListener("click", useCodeFunc)
    printBtn.addEventListener("click", printBarcode);




}