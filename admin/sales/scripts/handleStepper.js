const downloadInvoiceBtnRef = document.getElementById('downloadInvoiceBtn');
const goBackBtn = document.getElementById('goBackBtn');
const addProductBtns = document.getElementById('addProductBtn');
const finalizeBtn = document.getElementById('finalizeBtn');
const retryBtn = document.getElementById('retryBtn');

const buttonIcons = {
    "downloadInvoiceBtn": './icons/pdf-icon.svg',
    "goBackBtn": './icons/back-icon.svg',
    "addProductBtn": './icons/plus-icon.svg',
    "finalizeBtn": './icons/right-swipe-icon.svg'
}

const buttonArrangements = {
    "1": {
        "downloadInvoiceBtn": {
            "display": false,
            "text": "Download PDF"
        },
        "goBackBtn": {
            "display": false,
            "text": "Go Back"
        },
        "addProductBtn": {
            "display": true,
            "text": "Add Products",
        },
        "finalizeBtn": {
            "display": true,
            "text": "Finalize Cart",
            "step": 2
        }
    },
    "2": {
        "downloadInvoiceBtn": {
            "display": false,
            "text": "Download PDF"
        },
        "goBackBtn": {
            "display": true,
            "text": "Go Back"
        },
        "addProductBtn": {
            "display": false,
            "text": "Add Products"
        },
        "finalizeBtn": {
            "display": true,
            "text": "Confirm Details"
        }

    },
    "3": {
        "downloadInvoiceBtn": {
            "display": false,
            "text": "Download PDF"
        },
        "goBackBtn": {
            "display": true,
            "text": "Go Back"
        },
        "addProductBtn": {
            "display": false,
            "text": "Add Products"
        },
        "finalizeBtn": {
            "display": true,
            "text": "Confirm Payment"
        }
    },
    "4": {
        "downloadInvoiceBtn": {
            "display": true,
            "text": "Download/Print PDF"
        },
        "goBackBtn": {
            "display": true,
            "text": "Go Back To Home"
        },
        "addProductBtn": {
            "display": false,
            "text": "Add Products"
        },
        "finalizeBtn": {
            "display": false,
            "text": "Create New Order"
        }
    }
}

var currentStep = 1;

const showStep = (step) => {
    const stepElement = document.querySelector(`[data-step="${step}"]`);
    stepElement.classList.remove('hidden');
    handleButtonVisibility(step);
}

const hideStep = (step) => {
    const stepElement = document.querySelector(`[data-step="${step}"]`);
    stepElement.classList.add('hidden');
}

const hideHiddenSteps = () => {
    const steps = document.querySelectorAll('.step');
    steps.forEach(step => {
        step.classList.add('hidden');
    });
}

const handleButtonVisibility = (step) => {
    const buttonArrangement = buttonArrangements[step];
    for (const button in buttonArrangement) {
        const buttonElement = document.getElementById(button);
        buttonElement.style.display = buttonArrangement[button].display ? 'flex' : 'none';
        buttonElement.innerHTML = `<img src="${buttonIcons[button]}" alt="${buttonArrangement[button].text}" /> ${buttonArrangement[button].text}`;
    }
}

const downloadInvoice = async (saleId) => {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', './services/getSaleInvoice.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(`saleId=${saleId}`);
    xhr.onload = () => {
        if (xhr.status == 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.status == 'success') {
                let pdfUrl = `../../assets/temp/invoices/${response.fileName}`;
                var link = document.createElement('a');
                link.href = "#";
                link.addEventListener("click", e => {
                    e.preventDefault();
                    window.open(pdfUrl, '_blank', 'fullscreen=yes');
                    return false;
                });
                link.dispatchEvent(new MouseEvent('click'));
                showAlert(response.message, response.status);
            } else {
                showAlert('Error: ' + response.message, 'error');
            }
        } else {
            console.log('Error: ' + xhr.status);
        }
    }
}

hideHiddenSteps();
showStep(currentStep);

retryBtn.addEventListener('click', () => {
    createNewSale();
});

finalizeBtn.addEventListener('click', async () => {
    const response = isValidToProceed(currentStep);
    calculateOtherDetails(currentStep);

    if (response.status == "success") {
        currentStep++;
        hideStep(currentStep - 1);
        if (currentStep == 4) {
            await createNewSale();
        }
        showStep(currentStep);
    } else {
        showAlert(response.message, "error");
    }
});

goBackBtn.addEventListener('click', () => {
    if (currentStep == 4) {
        window.location.href = currentFile == "singleSales.php" ? "salesView.php" : currentFile;
    } else {
        currentStep--;
        hideStep(currentStep + 1);
        showStep(currentStep);
    }
});

downloadInvoiceBtnRef.addEventListener('click', () => {
    downloadInvoice(currentSaleId);
});

const isValidToProceed = (step) => {
    if (step == 1) {
        const validation = saleTable.rows.length > 0;
        return {
            "status": validation ? "success" : "error",
            "message": validation ? "Products added to the cart" : "Please add products to the cart"
        };
    } else if (step == 2) {
        const customerDetailsValidation = isCustomerDetailsValid();
        return {
            "status": customerDetailsValidation.status,
            "message": customerDetailsValidation.message
        }
    } else if (step == 3) {
        const paymentDetailsValidation = isPaymentDetailsValid();
        return {
            "status": paymentDetailsValidation.status,
            "message": paymentDetailsValidation.message
        }
    }
}

const isCustomerDetailsValid = () => {
    const salesDate = document.getElementById('sales-date');
    const customerName = document.getElementById('customer-name');
    const customerPhone = document.getElementById('customer-phone');
    const customerEmail = document.getElementById('customer-email');
    const salesAttendedBy = document.getElementById('sales-attended-by');
    const discount = document.getElementById('discount');
    const discountType = document.getElementById('discount-type');

    if (discountType.value == 'percentage' && discount.value > 100) {
        return {
            "status": "error",
            "message": "Discount cannot be greater than 100%"
        }
    }

    if (salesDate.value == '' || customerName.value == '' || customerPhone.value == '' || salesAttendedBy.value == '') {
        return {
            "status": "error",
            "message": "Please fill all the fields"
        }
    }

    saleDetails.saleDate = salesDate.value;
    saleDetails.customerName = customerName.value;
    saleDetails.customerPhone = customerPhone.value;
    saleDetails.customerEmail = customerEmail.value;
    saleDetails.salesAttendedBy = salesAttendedBy.value;
    saleDetails.discount = discount.value;
    saleDetails.discountType = discountType.value;
    return {
        "status": "success",
        "message": "Customer details are valid"
    };
}

const isPaymentDetailsValid = () => {
    const cashAmount = parseFloat(document.getElementById('amount-cash').value) || 0;
    const upiAmount = parseFloat(document.getElementById('amount-upi').value) || 0;
    const cardAmount = parseFloat(document.getElementById('amount-card').value) || 0;
    const cardCharges = parseFloat(document.getElementById('card-charges').value) || 0;

    if (isNaN(cashAmount) || isNaN(upiAmount) || isNaN(cardAmount)) {
        return {
            "status": "error",
            "message": "Please enter valid amounts"
        }
    }

    const totalPaid = cashAmount + upiAmount + cardAmount;
    const totalWithCharges = totalPaid + cardCharges;
    const roundedTotalPaid = Math.round(totalWithCharges);
    
    if (Math.abs(roundedTotalPaid - totalDetails.netTotal) > 1) {
        return {
            "status": "error",
            "message": "Total payment amount must match the order total"
        }
    }

    paymentDetails.cashAmount = cashAmount.toFixed(2);
    paymentDetails.upiAmount = upiAmount.toFixed(2);
    paymentDetails.cardAmount = cardAmount.toFixed(2);
    paymentDetails.cardCharges = cardCharges.toFixed(2);
    
    return {
        "status": "success",
        "message": "Payment details are valid"
    }
}

const calculateOtherDetails = (currentStep) => {
    if (currentStep == 2) {
        calculatePaymentDetails();
    }
}

const calculatePaymentDetails = () => {
    const totals = saleTable.rows.reduce((acc, saleItem) => {
        const price = parseFloat(saleItem.price);
        const quantity = saleItem.quantity;
        const gstPercentage = parseFloat(saleItem.gstPercentage);
        const isIGST = saleItem.isIGST;
        const rowTotal = price * quantity;

        const gstAmount = (price * gstPercentage / 100) * quantity;
        const sGstAmount = isIGST ? 0 : gstAmount / 2;
        const cGstAmount = isIGST ? 0 : gstAmount / 2;
        const iGstAmount = isIGST ? gstAmount : 0;

        const grossTotal = rowTotal - gstAmount;
        const discount = saleItem.discountType === 'percentage'
            ? rowTotal * (saleItem.discount / 100)
            : saleItem.discount;
        const netTotal = rowTotal - discount;

        return {
            grossTotal: Number((acc.grossTotal + grossTotal).toFixed(2)),
            sGstTotal: Number((acc.sGstTotal + sGstAmount).toFixed(2)),
            cGstTotal: Number((acc.cGstTotal + cGstAmount).toFixed(2)),
            iGstTotal: Number((acc.iGstTotal + iGstAmount).toFixed(2)),
            discountTotal: Number((acc.discountTotal + discount).toFixed(2)),
            netTotal: Number((acc.netTotal + netTotal).toFixed(2))
        };
    }, {
        grossTotal: 0,
        sGstTotal: 0,
        cGstTotal: 0,
        iGstTotal: 0,
        discountTotal: 0,
        netTotal: 0
    });

    const subTotal = totals.grossTotal + totals.sGstTotal + totals.cGstTotal + totals.iGstTotal - totals.discountTotal;
    const finalDiscount = calculateFinalDiscount(subTotal, saleDetails.discount, saleDetails.discountType);
    const cardCharges = parseFloat(document.getElementById('card-charges').value) || 0;
    const netAmount = totals.netTotal - finalDiscount + cardCharges;
    const roundedNetAmount = Math.round(netAmount);
    const roundOff = roundedNetAmount - netAmount;

    document.getElementById('orderTotalBeforeTax').textContent = `Rs. ${totals.grossTotal}`;
    document.getElementById('sGstTotal').textContent = `Rs. ${totals.sGstTotal}`;
    document.getElementById('cGstTotal').textContent = `Rs. ${totals.cGstTotal}`;
    document.getElementById('iGstTotal').textContent = `Rs. ${totals.iGstTotal}`;
    document.getElementById('totalBeforeDiscount').textContent = `Rs. ${subTotal.toFixed(2)}`;
    document.getElementById('finalDiscount').textContent = `Rs. ${finalDiscount.toFixed(2)}`;
    document.getElementById('discountTotal').textContent = `Rs. ${totals.discountTotal}`;
    document.getElementById('cardCharges').textContent = `Rs. ${cardCharges.toFixed(2)}`;
    document.getElementById('roundOff').textContent = `Rs. ${roundOff.toFixed(2)}`;
    document.getElementById('totalAfterDiscount').textContent = `Rs. ${roundedNetAmount}`;

    totalDetails.grossTotal = totals.grossTotal;
    totalDetails.sGstTotal = totals.sGstTotal;
    totalDetails.cGstTotal = totals.cGstTotal;
    totalDetails.iGstTotal = totals.iGstTotal;
    totalDetails.totalBeforeDiscount = subTotal.toFixed(2);
    totalDetails.discountTotal = totals.discountTotal;
    totalDetails.finalDiscount = finalDiscount.toFixed(2);
    totalDetails.netTotal = roundedNetAmount;
    totalDetails.roundOff = roundOff.toFixed(2);
}

const createNewSale = async () => {
    const skeletonCont = document.getElementById('skeletonCont');
    const retryMainCont = document.getElementById('retryMainCont');
    const orderSummaryBtns = document.getElementById('orderSummaryBtns');

    skeletonCont.classList.remove('hidden');
    retryMainCont.classList.add('hidden');
    orderSummaryBtns.classList.add('hidden');

    const xhr = new XMLHttpRequest();
    xhr.open('POST', currentFile == 'salesUpload.php' ? './services/salesUpload.php' : './services/updateSales.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    const data = currentFile == 'salesUpload.php' ? 
        `saleDetails=${JSON.stringify(saleDetails)}&paymentDetails=${JSON.stringify(paymentDetails)}&saleRows=${JSON.stringify(saleTable.rows)}&totalDetails=${JSON.stringify(totalDetails)}` :
        `saleId=${currentSaleId}&saleDetails=${JSON.stringify(saleDetails)}&paymentDetails=${JSON.stringify(paymentDetails)}&saleRows=${JSON.stringify(saleTable.rows)}&totalDetails=${JSON.stringify(totalDetails)}`;

    xhr.send(data);

    xhr.onload = () => {
        if (xhr.status == 200) {
            const response = JSON.parse(xhr.responseText);
            currentSaleId = response.details.saleId;

            document.getElementById('orderId').textContent = response.details.saleId;
            document.getElementById('orderDate').textContent = response.details.saleDate;
            document.getElementById('customerName').textContent = response.details.customerName;
            document.getElementById('customerPhone').textContent = response.details.customerPhone;

            const cashAmount = parseFloat(response.details.cashAmount);
            const upiAmount = parseFloat(response.details.upiAmount);
            const cardAmount = parseFloat(response.details.cardAmount);

            document.getElementById('paymentMode').textContent = 
                cashAmount > 0 && upiAmount > 0 ? 'Cash,UPI' : 
                cashAmount > 0 ? 'Cash' : 
                upiAmount > 0 ? 'UPI' : 
                cardAmount > 0 ? 'Card' : '';

            document.getElementById('orderTotal').textContent = response.details.total;

            if (response.status == 'success') {
                showAlert('Sale created successfully', 'success');
                downloadInvoiceBtnRef.style.display = 'flex';
                goBackBtn.style.display = 'flex';
            } else {
                showAlert('Error: ' + response.message, 'error');
                retryMainCont.classList.remove('hidden');
                downloadInvoiceBtnRef.style.display = 'none';
                goBackBtn.style.display = 'none';
            }
        } else {
            console.log('Error: ' + xhr.status);
        }
    }

    skeletonCont.classList.add('hidden');
    orderSummaryBtns.classList.remove('hidden');
}

const calculateFinalDiscount = (total, discount, discountType) => {
    return discountType == 'percentage' ? total * (discount / 100) : parseFloat(discount);
}

document.getElementById('amount-card').addEventListener('input', function() {
    const cardPayment = parseFloat(this.value) || 0;
    const cardCharges = (cardPayment * 0.02).toFixed(2);
    
    document.getElementById('card-charges').value = cardCharges;
    calculatePaymentDetails(); // Recalculate totals when card amount changes
    isPaymentDetailsValid();
});

// document.getElementById('card-charges').addEventListener('input', function() {
//     calculatePaymentDetails(); // Recalculate totals when card charges change manually
//     isPaymentDetailsValid();
// });
