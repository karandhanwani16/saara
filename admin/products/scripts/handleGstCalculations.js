const gstInputs = document.querySelectorAll(".gst-cal-inp");


gstInputs.forEach(inp => {
    inp.addEventListener("keyup", e => {
        calulateGst();
    });
})

function calulateGst() {

    // get all inputs
    const costPrice = document.querySelector("#txtcostprice").value;
    const cGstPercentage = document.querySelector("#txtcgstpercentage").value;
    const sGstPercentage = document.querySelector("#txtsgstpercentage").value;
    const iGstPercentage = document.querySelector("#txtigstpercentage").value;

    const pricePostGst = document.querySelector("#txtpricepostgst");


    pricePostGst.value = (Number(costPrice) + (Number(costPrice) * Number(cGstPercentage) / 100) + (Number(costPrice) * Number(sGstPercentage) / 100) + (Number(costPrice) * Number(iGstPercentage) / 100)).toFixed(2);



}