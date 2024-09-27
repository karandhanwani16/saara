const popupCont = document.querySelector(".popup-cont");
const popupBackdrop = document.querySelector(".popup-backdrop");
const popupCloseBtn = document.querySelector(".close-btn");


function openPopup(productData) {


    const generateBody = document.querySelector(".generate--cont");
    if (generateBody) {
        generateBody.style.display = "flex";
    }

    const { name: productName, code: productCodeInput, sellingPrice, parlourPrice } = productData;

    // extra details
    const extraDetailsHeader1 = document.querySelector("#extraDetailsHeader1");
    const extraDetailsHeader2 = document.querySelector("#extraDetailsHeader2");
    const productCode = document.querySelector("#productCode");
    const footerSellingPrice = document.querySelector("#footerSellingPrice");
    const footerParlourPrice = document.querySelector("#footerParlourPrice");

    extraDetailsHeader1.innerHTML = `<p class='sara-barcode-logo' >Saara Beauty Centre & Gift</p>`;
    extraDetailsHeader2.innerHTML = productName;
    productCode.innerHTML = productCodeInput;
    footerSellingPrice.innerHTML = sellingPrice;
    footerParlourPrice.innerHTML = parlourPrice;


    // popup body height adjustment
    const popupBody = document.querySelector(".popup-body");
    popupBody.style.height = "auto";

    popupCont.style.display = "block";
    popupBackdrop.style.display = "block";
}

function closePopup() {

    popupCont.style.display = "none";
    popupBackdrop.style.display = "none";

}

popupCloseBtn.addEventListener("click", function () {
    closePopup();
});

