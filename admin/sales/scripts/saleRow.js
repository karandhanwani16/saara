class SaleRow {
    constructor(id, product, category,description, price, quantity,gstPercentage,isIGST,discount,discountType, total,prices, productId, priceId,priceBeforeTax,sGstAmount,cGstAmount,igstAmount) {
        this.id = id;
        this.product = product;
        this.category = category;
        this.description = description;
        this.price = price;
        this.quantity = quantity;
        this.discount = discount;
        this.total = total;
        this.gstPercentage = gstPercentage;
        this.isIGST = isIGST;
        this.discountType = discountType;
        this.prices = prices;
        this.productId = productId;
        this.priceId = priceId;
        this.priceBeforeTax = priceBeforeTax;
        this.sGstAmount = sGstAmount;
        this.cGstAmount = cGstAmount;
        this.igstAmount = igstAmount;
    }    
}