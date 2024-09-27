// const productList = document.getElementById("productList");
// const productCount = document.getElementById("productCount");

// function renderProducts(products) {
//     productList.innerHTML = "";
//     products.forEach((product, index) => {
//         const productCard = document.createElement("div");
//         productCard.className = "product-card";

        
//         const sGstAmount = (product.price * (product.sgst / 100)).toFixed(2);
//         const cGstAmount = (product.price * (product.cgst / 100)).toFixed(2);
//         const igstAmount = (product.price * (product.igst / 100)).toFixed(2);
//         const grossPrice = (product.price - sGstAmount - cGstAmount - igstAmount).toFixed(2);

//         productCard.innerHTML = `
//             <div class="action-button delete-button">Delete</div>
//             <div class="product-card-content">
//                 <div class="product-name">${product.name}</div>
//                 <div class="category">${product.category}</div>
//                 <div class="details">Gross Price: <b>${grossPrice}</b> Price: <b>${product.price}</b>  Quantity: <b>${product.quantity}</b> </div>
//                 <div class="details">SGST(9%): <b>${sGstAmount}</b> CGST(9%): <b>${cGstAmount}</b> IGST(0%): <b>${igstAmount}</b></div>
//                 <div class="details">Discount: <b>${product.discount}</b> Discount Type: <b>${product.discountType}</b></div>
//                 <div class="product-total">Product Total: Rs. <b>${calculateTotal(grossPrice,sGstAmount,cGstAmount,igstAmount,product.quantity,product.discount, product.discountType)}</b></div>
//             </div>
//             <div class="action-button edit-button">Edit</div>
//         `;
//         productList.appendChild(productCard);

//         let startX, currentX;
//         const content = productCard.querySelector(".product-card-content");
//         const deleteButton = productCard.querySelector(".delete-button");
//         const editButton = productCard.querySelector(".edit-button");
//         const cardWidth = productCard.offsetWidth;
//         const threshold = cardWidth * 0.2; // 20% of card width

//         function handleStart(e) {
//             startX = e.type.includes("mouse")
//                 ? e.clientX
//                 : e.touches[0].clientX;
//         }

//         function handleMove(e) {
//             if (!startX) return;
//             currentX = e.type.includes("mouse")
//                 ? e.clientX
//                 : e.touches[0].clientX;
//             const diff = currentX - startX;
//             // if the diff is more than threshold then don't move the card
//             if (Math.abs(diff) > threshold) {
//                 return;
//             }
//             content.style.transform = `translateX(${diff}px)`;
//         }

//         function handleEnd() {

//             if (!startX || !currentX) return;
            
//             const diff = currentX - startX;

//             if (diff > threshold) {
//                 // Swiped left more than 20%, call delete function
//                 // deleteProduct(index);
//                 setTimeout(() => {
//                     deleteButton.click();
//                 }, 750);
//             } else if (diff < -threshold) {
//                 // Swiped right more than 20%, call edit function

//                 setTimeout(() => {
//                     editButton.click();
//                     content.style.transform = '';

//                 }, 750);
//             } else {
//                 // Swiped less than 20%, return to normal position
//                 content.style.transition = 'transform 0.3s ease-out';
//                 content.style.transform = '';
//                 setTimeout(() => {
//                     content.style.transition = '';
//                 }, 300);
//             }

//             startX = null;
//             currentX = null;
//         }

//         productCard.addEventListener("mousedown", handleStart);
//         productCard.addEventListener("touchstart", handleStart);
//         productCard.addEventListener("mousemove", handleMove);
//         productCard.addEventListener("touchmove", handleMove);
//         productCard.addEventListener("mouseup", handleEnd);
//         productCard.addEventListener("touchend", handleEnd);
//         productCard.addEventListener("mouseleave", handleEnd);


//         deleteButton.addEventListener("click", () => {
//             saleTable.removeRow(product.id);
//             // console.log("delete button clicked");
//             // const message = `Deleting product:\n${JSON.stringify(product, null, 2)}`;
//             // alert(message);
//             // deleteProduct(index);
//         });

//         editButton.addEventListener("click", () => {
//             console.log("edit button clicked");
//             const message = `Editing product:\n${JSON.stringify(product, null, 2)}`;
//             alert(message);
//             // editProduct(index);
//         });
//     });
//     productCount.textContent = `No. of products: ${products.length}`;
// }

// function calculateTotal(grossPrice,sgst,cgst,igst,quantity,discount,discountType) {
    
//     const total = Number(grossPrice) + Number(sgst) + Number(cgst) + Number(igst);
//     const subtotal = total * Number(quantity);
    
//     let discountAmount;
//     if (discountType === "Percentage") {
//         discountAmount = subtotal * (Number(discount) / 100);
//     } else {
//         discountAmount = Number(discount);
//     }
    
//     return (subtotal - discountAmount).toFixed(2);
// }

// function addProduct(product,products) {
//     products.push(product);
//     renderProducts(products);
// }

// function deleteProduct(index,products) {
//     products.splice(index, 1);
//     renderProducts(products);
// }

// function editProduct(index,products) {
//     console.log(`Editing product at index ${index}`);
//     // Implement edit functionality here
// }



// Initialize with some sample products
// addProduct({
//     name: "Product Name 1",
//     category: "Category Name",
//     quantity: 10,
//     price: 1000,
//     sgst: 90,
//     cgst: 90,
//     igst: 0,
//     discount: 10,
//     discountType: "Percentage",
// });
// addProduct({
//     name: "Product Name 2",
//     category: "Category Name",
//     quantity: 5,
//     price: 1500,
//     sgst: 90,
//     cgst: 90,
//     igst: 0,
//     discount: 50,
//     discountType: "Fixed",
// });

// renderProducts();
