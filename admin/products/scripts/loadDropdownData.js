
const brandDdl = document.querySelector("#ddlbrand");
const categoryDdl = document.querySelector("#ddlcategory");

// load brand Data
loadDropdownData("brand_id", "brand_name", "brand", brandDdl, "Select Product Brand", defaultBrand, conditionalColumnName = "", conditionalColumnValue = "", "../services/loadDropdownData.php");

// load category Data
loadDropdownData("category_id", "category_name", "category", categoryDdl, "Select Product Category", defaultCategory, conditionalColumnName = "", conditionalColumnValue = "", "../services/loadDropdownData.php");