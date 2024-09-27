const openPopupButton = document.getElementById('openPopup');
const popup = document.getElementById('popup');
const closeButton = document.querySelector('#productContent .close');
const addProductToList = document.getElementById('addProduct');
const cancelButton = document.getElementById('cancelProduct');
const popupBackground = document.getElementById('popupBackground');

function openProductPopup() {
    popup.classList.remove('hidden');
    popupBackground.classList.remove('hidden');
}

function closeProductPopup() {
    popup.classList.add('hidden');
    popupBackground.classList.add('hidden');
}

popupBackground.addEventListener('click', closeProductPopup);
closeButton.addEventListener('click', closeProductPopup);
cancelButton.addEventListener('click', closeProductPopup);

// addProductToList.addEventListener('click', function () {
//     const productPrice = document.getElementById('productPrice').value;
//     const quantity = document.getElementById('quantity').value;
//     const discount = document.getElementById('discount').value;
//     const discountType = document.getElementById('discountType').value;

//     // Here you can add logic to handle the product addition
//     console.log('Product added:', {
//         price: productPrice,
//         quantity: quantity,
//         discount: discount,
//         discountType: discountType
//     });

//     closePopup();
// });



addProductToList.addEventListener("submit", (e) => {
    e.preventDefault();
    const newProduct = {
        name: document.getElementById("productName").value,
        category: document.getElementById("category").value,
        quantity: parseInt(document.getElementById("quantity").value),
        price: parseFloat(document.getElementById("price").value),
        discount: parseFloat(document.getElementById("discount").value),
        discountType: document.getElementById("discountType").value,
        sgst: 90, // Hardcoded for this example
        cgst: 90, // Hardcoded for this example
        igst: 0, // Hardcoded for this example
    };
    addProduct(newProduct);
    addProductForm.reset();
});

