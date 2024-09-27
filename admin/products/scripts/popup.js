const popupCont = document.querySelector(".popup-cont");
const popupBackdrop = document.querySelector(".popup-backdrop");
const popupCloseBtn = document.querySelector(".close-btn");

//Hello World

function openPopup(type, sellingPrice = 0, parlourPrice = 0) {
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
        const productName = document.querySelector("#txtname");
        const productCodeInput = document.querySelector("#txtcode");

        const footerSellingPrice = document.querySelector("#footerSellingPrice");
        // const footerParlourPrice = document.querySelector("#footerParlourPrice");
        // const sellingPrice = document.querySelector("#txtsellingprice");
        // const parlourPrice = document.querySelector("#txtparlourprice");

        extraDetailsHeader1.innerHTML = `<p class='sara-barcode-logo' >Saara Beauty Centre & Gift</p>`;
        extraDetailsHeader2.innerHTML = `${productName.value} `;
        productCode.innerHTML = `${productCodeInput.value} `;
        footerSellingPrice.innerHTML = sellingPrice;
        footerParlourPrice.innerHTML = parlourPrice;

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

    //  table start
    const table = document.createElement("table");

    const tr1 = document.createElement("tr");
    const th1 = document.createElement("th");
    th1.id = "extraDetailsHeader1";
    th1.style.fontSize = "14px";
    th1.style.fontWeight = "800";
    th1.colSpan = "2";

    tr1.appendChild(th1);

    const tr2 = document.createElement("tr");
    const th2 = document.createElement("th");
    th2.id = "extraDetailsHeader2";
    th2.style.fontSize = "16px";
    th2.style.fontWeight = "600";
    th2.colSpan = "2";


    tr2.appendChild(th2);


    const tr3 = document.createElement("tr");
    const td3 = document.createElement("td");
    const productCodeDiv = document.createElement("div");
    productCodeDiv.id = "productCode";
    productCodeDiv.style.transform = "rotate(-90deg)";
    productCodeDiv.style.height = "10px";

    td3.appendChild(productCodeDiv);

    const td4 = document.createElement("td");
    const img = document.createElement("img");
    img.id = "generatedBarcode";

    td4.appendChild(img);

    tr3.appendChild(td3);
    tr3.appendChild(td4);


    const tr4 = document.createElement("tr");
    const td5 = document.createElement("td");
    const footerSellingPrice = document.createElement("div");
    footerSellingPrice.classList.add("details");
    footerSellingPrice.id = "footerSellingPrice";

    const td6 = document.createElement("td");
    const footerParlourPrice = document.createElement("div");
    footerParlourPrice.classList.add("details");
    footerParlourPrice.style.textAlign = "right";
    footerParlourPrice.id = "footerParlourPrice";

    td6.appendChild(footerParlourPrice);

    tr4.appendChild(td5);
    tr4.appendChild(td6);

    td5.appendChild(footerSellingPrice);

    table.appendChild(tr1);
    table.appendChild(tr2);
    table.appendChild(tr3);
    table.appendChild(tr4);

    barcode.appendChild(table);




    //  table end
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