let currentElement = "";
let currentElementId = "";

function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev) {
    currentElement = ev.target.attributes["value"].value;
    currentElementId = ev.target.attributes["data-id"].value;
    // console.log(currentElementId + "--" + currentElement);
    // console.log(ev.target.attributes["value"].value);
}

function drop(ev) {
    ev.preventDefault();
    exchangeBrand(ev.target.attributes["data-type"].value, currentElementId, currentElement);
}

let brands = document.querySelectorAll(".brand");
addListener();

function addListener() {
    brands = document.querySelectorAll(".brand");
    brands.forEach(brand => {
        brand.addEventListener("dragstart", e => {
            drag(e);
        });
    });
}


function exchangeBrand(targetType, brandId, brandValue) {
    if (targetType == "not-included") {
        let returnedIncludedList = includedList.filter(item => item.id != brandId);
        includedList = returnedIncludedList;
        addItem("not-included", brandId, brandValue);
    } else {
        let returnedNotIncludedList = notIncludedList.filter(item => item.id != brandId);
        notIncludedList = returnedNotIncludedList;
        addItem("included", brandId, brandValue);
    }
    displayRows();
    addListener();
}


function addItem(type, brandId, brandValue) {
    let temp = { id: brandId, name: brandValue };
    if (type == "not-included") {
        notIncludedList.push(temp);
    } else {
        includedList.push(temp);
    }

}