let priceStockArray = [];

function addPriceStockRow(costPrice, stock, gst, sellingPrice, parlourPrice, isInitialLoad = false) {
    const newRow = document.createElement("tr");
    newRow.innerHTML = `
        <td class="costprice-cell">${costPrice}</td>
        <td class="stock-cell">${stock}</td>
        <td class="gst-cell">${gst}</td>
        <td class="sellingprice-cell">${sellingPrice}</td>
        <td class="parlourprice-cell">${parlourPrice}</td>
        <td class="action-btns">
            <button type="button" class="btn generate-btn">Generate</button>
            <button type="button" class="btn edit-btn">Edit</button>
            <button type="button" class="btn delete-btn">Delete</button>
        </td>
    `;

    document.querySelector("#priceStockTableBody").appendChild(newRow);

    // Store the data in the array
    priceStockArray.push({ costPrice, stock, gst, sellingPrice, parlourPrice });

    if (!isInitialLoad) {
        // Clear input fields
        document.getElementById("txtCostPrice").value = "";
        document.getElementById("txtStock").value = "";
        document.getElementById("txtGST").value = "";
        document.getElementById("txtSellingPrice").value = "";
        document.getElementById("txtParlourPrice").value = "";
    }

    // Add delete functionality to the row
    newRow.querySelector(".delete-btn").addEventListener("click", function () {
        const rowIndex = this.closest("tr").rowIndex - 1; // Subtract 1 for the form row
        priceStockArray.splice(rowIndex, 1); // Remove from array
        this.closest("tr").remove(); // Remove from table
    });

    // Add edit functionality to the row
    const editButton = newRow.querySelector(".edit-btn");
    editButton.addEventListener("click", function () {
        const row = this.closest("tr");
        const costPriceCell = row.querySelector(".costprice-cell");
        const stockCell = row.querySelector(".stock-cell");
        const gstCell = row.querySelector(".gst-cell");
        const sellingPriceCell = row.querySelector(".sellingprice-cell");
        const parlourPriceCell = row.querySelector(".parlourprice-cell");

        if (this.textContent === "Edit") {
            // Replace text with input fields for editing
            costPriceCell.innerHTML = `<input type="text" class="inp edit-cost-price" value="${costPriceCell.textContent}" />`;
            stockCell.innerHTML = `<input type="text" class="inp edit-stock" value="${stockCell.textContent}" />`;
            gstCell.innerHTML = `<input type="text" class="inp edit-gst" value="${gstCell.textContent}" />`;
            sellingPriceCell.innerHTML = `<input type="text" class="inp edit-selling-price" value="${sellingPriceCell.textContent}" />`;
            parlourPriceCell.innerHTML = `<input type="text" class="inp edit-parlour-price" value="${parlourPriceCell.textContent}" />`;

            // Change "Edit" button to "Save"
            this.textContent = "Save";
        } else if (this.textContent === "Save") {
            // Get edited values
            const editedCostPrice = row.querySelector(".edit-cost-price").value;
            const editedStock = row.querySelector(".edit-stock").value;
            const editedGST = row.querySelector(".edit-gst").value;
            const editedSellingPrice = row.querySelector(".edit-selling-price").value;
            const editedParlourPrice = row.querySelector(".edit-parlour-price").value;

            // Update table cells with new values
            costPriceCell.textContent = editedCostPrice;
            stockCell.textContent = editedStock;
            gstCell.textContent = editedGST;
            sellingPriceCell.textContent = editedSellingPrice;
            parlourPriceCell.textContent = editedParlourPrice;

            // Update array with new values
            const rowIndex = row.rowIndex - 1;
            priceStockArray[rowIndex] = { costPrice: editedCostPrice, stock: editedStock, gst: editedGST, sellingPrice: editedSellingPrice, parlourPrice: editedParlourPrice };

            // Change "Save" button back to "Edit"
            this.textContent = "Edit";
        }
    });

    // add generate functionality to the row
    const generateButton = newRow.querySelector(".generate-btn");
    generateButton.addEventListener("click", function () {
        const sellingPrice = this.closest("tr").querySelector(".sellingprice-cell").textContent;
        const parlourPrice = this.closest("tr").querySelector(".parlourprice-cell").textContent;

        generateBarcodeMain(sellingPrice, parlourPrice);
    });
}

document.getElementById("addPriceStock").addEventListener("click", function () {
    const costPrice = document.getElementById("txtCostPrice").value;
    const stock = document.getElementById("txtStock").value;
    const gst = document.getElementById("txtGST").value;
    const sellingPrice = document.getElementById("txtSellingPrice").value;
    const parlourPrice = document.getElementById("txtParlourPrice").value;

    if (costPrice && stock && gst && sellingPrice && parlourPrice) {
        addPriceStockRow(costPrice, stock, gst, sellingPrice, parlourPrice);
    }
});

// Function to load initial data from DB
function loadInitialData(initialData) {
    initialData.forEach(item => {
        addPriceStockRow(item.costPrice, item.stock, item.gst, item.sellingPrice, item.parlourPrice, true);
    });
}

// Example usage:
// const initialData = [
//     {costPrice: "100", stock: "10", gst: "18", sellingPrice: "118", parlourPrice: "109"},
//     {costPrice: "200", stock: "20", gst: "12", sellingPrice: "224", parlourPrice: "220"}
// ];
// loadInitialData(initialData);