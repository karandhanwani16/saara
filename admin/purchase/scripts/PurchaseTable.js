class PurchaseRow {
    constructor(
        id,
        productId,
        productName,
        priceId,
        price,
        gst,
        isIGST,
        quantity
    ) {
        this.id = id;
        this.product_id = productId;
        this.product_name = productName;
        this.priceId = priceId;
        this.price = price;
        this.gstPercentage = gst;
        this.isIGST = isIGST;
        this.quantity = quantity;

        const beforeGST = parseFloat(quantity) * parseFloat(price);
        this.subTotal = parseFloat(beforeGST.toFixed(2));

        // Calculate CGST and SGST if not IGST, else calculate IGST
        this.cgst = isIGST ? 0 : parseFloat(((gst / 2 / 100) * beforeGST).toFixed(2));
        this.sgst = isIGST ? 0 : parseFloat(((gst / 2 / 100) * beforeGST).toFixed(2));
        this.igst = isIGST ? parseFloat(((gst / 100) * beforeGST).toFixed(2)) : 0;

        // Calculate total
        this.total = parseFloat((this.subTotal + this.cgst + this.sgst + this.igst).toFixed(2));
    }
}


class PurchaseTable {
    constructor() {
        this.currentId = 1;
        this.invoiceRows = [];
        this.netTotal = 0;
    }

    incrementId() {
        this.currentId++;
    }
    decrementId() {
        this.currentId--;
    }

    insertRow( productId, productName, priceId, price, gst, isIGST, quantity) {
        let tempObject = new PurchaseRow(this.currentId, productId, productName, priceId, price, gst, isIGST, quantity);
        this.invoiceRows.push(tempObject);
        this.incrementId();
    }
    removeRow(id) {
        this.invoiceRows = this.invoiceRows.filter(function (item) {
            return item.id != id;
        });
    }
    displayRows() {
        
        let finalResult = "";
        let count = 1;
        
        this.invoiceRows.forEach((row) => {
            finalResult += "<tr style='height:50px;'>";
            finalResult += "<td class='edit--link' data-id='" + row.id + "'>" + count + "</td>";
            finalResult += "<td>" + row.product_name + "</td>";
            finalResult += "<td>" + row.price + "</td>";
            finalResult += "<td>" + row.gstPercentage + "</td>";
            finalResult += "<td>" + row.isIGST + "</td>";
            finalResult += "<td>" + row.quantity + "</td>";
            finalResult += "<td>" + row.total + "</td>";
            finalResult += "<td><div id='delete-purchase-btn' data-id='" + row.id + "' class='delete-btn'>Delete</div></td>";
            finalResult += "</tr>";
            count++;
        });
        return finalResult;
    }

    calculateRoundOff() {
        let grossTotal = 0;
        this.invoiceRows.forEach(row => {
            grossTotal += parseFloat(row.total);
        });


        const roundOff = Math.ceil(grossTotal) - grossTotal;

        return roundToTwo(roundOff);

    }
    calculateGrossTotal() {
        let grossTotal = 0;
        this.invoiceRows.forEach(row => {
            grossTotal += parseFloat(row.total);
        });
        return roundToTwo(grossTotal);
    }
    calculateNetTotal() {

        let netTotal = parseFloat(this.calculateGrossTotal());

        netTotal += this.calculateRoundOff();

        this.netTotal = netTotal;

        return roundToTwo(this.calculateGrossTotal() + this.calculateRoundOff());
    }

    calculateQuantity() {
        let quantity = 0;
        this.invoiceRows.forEach(row => {
            quantity += parseInt(row.quantity);
        });
        return quantity;
    }

    displaySubTotal() {
        console.log(this.calculateQuantity());
        return `<tr>
            <td style="width: 5%;">${this.invoiceRows.length}</td>
            <td style="width: 10%;"></td>
            <td style="width: 10%;"></td>
            <td style="width: 10%;"></td>
            <td style="width: 10%;"></td>
            <td style="width: 5%;">${this.calculateQuantity()}</td>
            <td style="width: 10%;">${this.calculateGrossTotal()}</td>
            <td style="width: 7.5%;"></td>
        </tr>`;
    }

    productExist(id) {
        return this.invoiceRows.some(row => row.productId === id);
    }
}

function roundToTwo(num) {
    return +(Math.round(num + "e+2") + "e-2");
}