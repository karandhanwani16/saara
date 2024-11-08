class SaleTable {
    constructor() {

        this.rows = [];
        this.currentId = 0;
    }

    addRow(row) {
        const { product, category, description, price, quantity, gstPercentage, isIGST, discount, discountType, total, prices, productId, priceId } = row;
        this.currentId++;

        const sgst = isIGST ? 0 : parseInt(gstPercentage) / 2;
        const cgst = isIGST ? 0 : parseInt(gstPercentage) / 2;
        const igst = isIGST ? parseInt(gstPercentage) : 0;


        const sGstAmount = (price * (sgst / 100)).toFixed(2);
        const cGstAmount = (price * (cgst / 100)).toFixed(2);
        const igstAmount = (price * (igst / 100)).toFixed(2);
        const grossPrice = (price - sGstAmount - cGstAmount - igstAmount).toFixed(2);


        const calculatedTotal = this.calculateTotal(grossPrice, sGstAmount, cGstAmount, igstAmount, quantity, discount, discountType);

        const saleRow = new SaleRow(this.currentId, product, category, description, price, quantity, gstPercentage, isIGST, discount, discountType, calculatedTotal, prices, productId, priceId, grossPrice, sGstAmount, cGstAmount, igstAmount);

        this.rows.push(saleRow);
        this.showTable();
    }

    showTable() {
        const productCount = document.getElementById("productCount");

        productList.innerHTML = '';

        this.rows.forEach(row => {

            const productList = document.getElementById("productList");
            const productCard = document.createElement("div");
            productCard.className = "product-card";


            productCard.innerHTML = `
                        <div class="action-button delete-button">Delete</div>
                        <div class="product-card-content">
                            <div class="product-name">${row.product}</div>
                            <div class="category">${row.category}</div>
                            <div class="details">Gross Price: <b>${row.priceBeforeTax}</b> Price: <b>${row.price}</b>  Quantity: <b>${row.quantity}</b> </div>
                            <div class="details">SGST(9%): <b>${row.sGstAmount}</b> CGST(9%): <b>${row.cGstAmount}</b> IGST(0%): <b>${row.igstAmount}</b></div>
                            <div class="details">Discount: <b>${row.discount}</b> Discount Type: <b>${row.discountType}</b></div>
                            <div class="product-total">Product Total: Rs. <b>${row.total}</b></div>
                            </div>
                            <div class="action-button edit-button">Edit</div>
                            `;

            productList.appendChild(productCard);


            this.addCardListeners(productCard, row);

        });

        productCount.textContent = `No. of products: ${this.rows.length}`;
    }

    removeRow(id) {
        this.rows = this.rows.filter(r => {
            return r.id !== id;
        });
        this.showTable();
    }

    addCardListeners(productCard, row) {
        let startX, currentX;
        const content = productCard.querySelector(".product-card-content");
        const deleteButton = productCard.querySelector(".delete-button");
        const editButton = productCard.querySelector(".edit-button");
        const cardWidth = productCard.offsetWidth;
        const threshold = cardWidth * 0.2; // 20% of card width

        function handleStart(e) {
            startX = e.type.includes("mouse")
                ? e.clientX
                : e.touches[0].clientX;
        }

        function handleMove(e) {
            if (!startX) return;
            currentX = e.type.includes("mouse")
                ? e.clientX
                : e.touches[0].clientX;
            const diff = currentX - startX;
            // if the diff is more than threshold then don't move the card
            if (Math.abs(diff) > threshold) {
                return;
            }
            content.style.transform = `translateX(${diff}px)`;
        }

        function handleEnd() {

            if (!startX || !currentX) return;

            const diff = currentX - startX;

            if (diff > threshold) {
                // Swiped left more than 20%, call delete function
                // deleteProduct(index);
                setTimeout(() => {
                    deleteButton.click();
                }, 500);
            } else if (diff < -threshold) {
                // Swiped right more than 20%, call edit function

                setTimeout(() => {
                    editButton.click();
                    content.style.transform = '';

                }, 500);
            } else {
                // Swiped less than 20%, return to normal position
                content.style.transition = 'transform 0.3s ease-out';
                content.style.transform = '';
                setTimeout(() => {
                    content.style.transition = '';
                }, 300);
            }

            startX = null;
            currentX = null;
        }

        productCard.addEventListener("mousedown", handleStart);
        productCard.addEventListener("touchstart", handleStart);
        productCard.addEventListener("mousemove", handleMove);
        productCard.addEventListener("touchmove", handleMove);
        productCard.addEventListener("mouseup", handleEnd);
        productCard.addEventListener("touchend", handleEnd);
        productCard.addEventListener("mouseleave", handleEnd);


        deleteButton.addEventListener("click", () => {
            saleTable.removeRow(row.id);
        });

        editButton.addEventListener("click", () => {
            this.showEditPopup(row);
        });

    }

    showEditPopup(row) {
        const editProduct = document.getElementById("editProduct");
        const editPopup = document.getElementById("editPopup");
        const editPopupBackground = document.getElementById("editPopupBackground");
        const editClose = document.querySelector("#editContent .close");
        const editCancel = document.querySelector("#cancelEdit");
        editPopup.classList.remove("hidden");
        editPopupBackground.classList.remove("hidden");



        editCancel.addEventListener("click", (e) => {
            this.closeEditPopup();
        });
        editPopupBackground.addEventListener("click", (e) => {
            this.closeEditPopup();
        });
        editClose.addEventListener("click", (e) => {
            this.closeEditPopup();
        });

        const editProductName = document.getElementById("editProductName");
        const editCategoryName = document.getElementById("editCategoryName");
        const editProductDescription = document.getElementById("editProductDescription");
        const editProductPrice = document.getElementById("editProductPrice");
        const editQuantity = document.getElementById("editQuantity");
        const editDiscount = document.getElementById("editDiscount");
        const editDiscountType = document.getElementById("editDiscountType");

        editProductName.textContent = row.product;
        editCategoryName.textContent = row.category;
        editProductDescription.textContent = row.description;
        editDiscount.value = row.discount;
        editDiscountType.value = row.discountType;

        const selectedPrice = row.prices.find(p => p["product_prices_selling_price"] == row.price);



        // set the selected price

        const editProductPriceSelect = document.getElementById('editProductPrice');

        // Empty the product price select
        editProductPriceSelect.innerHTML = '';

        // create the product price default option
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = 'Select Price';
        editProductPriceSelect.appendChild(defaultOption);

        // create the product price options
        row.prices.forEach(price => {
            const option = document.createElement('option');
            option.value = price["product_prices_id"];
            option.textContent = price["product_prices_selling_price"];
            editProductPriceSelect.appendChild(option);
        });


        editProductPrice.value = selectedPrice["product_prices_id"];

        const editQuantitySelect = document.getElementById('editQuantity');
        // Empty the quantity select
        editQuantitySelect.innerHTML = '';

        this.addQuantity(selectedPrice["product_prices_stock"], editQuantitySelect);

        editQuantity.value = row.quantity;


        editProductPriceSelect.addEventListener("change", (e) => {

            // empty the quantity select
            editQuantitySelect.innerHTML = '';

            const priceId = parseInt(e.target.value);
            const editPrice = row.prices.find(p => p["product_prices_id"] === priceId);
            this.addQuantity(editPrice["product_prices_stock"], editQuantitySelect);
            // const editQuantity = parseInt(editPrice["product_prices_stock"]) >= 10 ? 10 : parseInt(editPrice["product_prices_stock"]);
            // for (let i = 1; i <= editQuantity; i++) {
            //     const option = document.createElement('option');
            //     option.value = i;
            //     option.textContent = i;
            //     editQuantitySelect.appendChild(option);
            // }
        });


        // add the isIGST checkbox
        const editIsIGST = document.getElementById('editIsIGST');
        editIsIGST.checked = row.isIGST;


        editProduct.addEventListener("click", () => {
            // add all the condtions
            if (editProductPrice.value == '' || editQuantity.value == '' || isNaN(editDiscount.value) || editDiscountType.value == '') {
                addError(document.querySelector('#editContent .error-cont'), "Please fill all the fields");
                return;
            } else if (editDiscountType.value == 'percentage' && editDiscount.value > 100) {
                addError(document.querySelector('#editContent .error-cont'), "Discount Percentage cannot be greater than 100");
                return;
            }
            this.editProduct(row.id, editProductPrice.value, editQuantity.value, editDiscount.value, editDiscountType.value, editIsIGST.checked);
        });



    }

    editProduct(id, price, quantity, discount, discountType, isIGST) {
        this.rows = this.rows.map(r => {
            if (r.id === id) {
                r.price = r.prices.find(p => p["product_prices_id"].toString() === price.toString())["product_prices_selling_price"];
                r.quantity = quantity;
                r.discount = discount;
                r.discountType = discountType;
                r.isIGST = isIGST;
            }
            return r;
        });
        this.showTable();
        this.closeEditPopup();
    }

    closeEditPopup() {
        const editPopup = document.getElementById("editPopup");
        const editPopupBackground = document.getElementById("editPopupBackground");
        editPopup.classList.add("hidden");
        editPopupBackground.classList.add("hidden");
    }

    calculateTotal(grossPrice, sgst, cgst, igst, quantity, discount, discountType) {

        const total = Number(grossPrice) + Number(sgst) + Number(cgst) + Number(igst);
        const subtotal = total * Number(quantity);

        let discountAmount;
        if (discountType === "percentage") {
            discountAmount = subtotal * (Number(discount) / 100);
        } else {
            discountAmount = Number(discount);
        }

        return (subtotal - discountAmount).toFixed(2);
    }


    addQuantity(stock, dropdownElement) {
        const qty = parseInt(stock) >= 10 ? 10 : parseInt(stock);
        for (let i = 1; i <= qty; i++) {
            const option = document.createElement('option');
            option.value = i;
            option.textContent = i;
            dropdownElement.appendChild(option);
        }
    }

}
