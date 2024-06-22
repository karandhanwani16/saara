<?php

include ("../services/urlValidation.php");
include ("../services/config.php");
include ("../services/helperFunctions.php");
session_start();
$user_id = $_SESSION["user_id"];

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Upload</title>
    <link rel="stylesheet" href="../style/assets.css">
    <link rel="stylesheet" href="../style/forms.css">
</head>

<body>

    <div class="alert--cont">
    </div>

    <!-- alert cont end -->

    <div class="title">Suppliers</div>
    <div class="spacer"></div>

    <form action="#">
        <div class="inp-row row-full row-4">
            <div class="inp-group">
                <div class="inp-label">Supplier Name</div>
                <input type="text" value="" class="inp required" id="txtsuppliername" placeholder="Supplier Name"
                    data-id="txtsuppliername" />
                <div class="error-text" data-id="txtsuppliername">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->
            <div class="inp-group">
                <div class="inp-label">Supplier Location</div>
                <input type="text" value="" class="inp" id="txtsupplierlocation" placeholder="Supplier Location"
                    data-id="txtsupplierlocation" />
                <div class="error-text" data-id="txtsupplierlocation">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->
            <div class="inp-group">
                <div class="inp-label">Supplier Phone</div>
                <input type="text" value="" class="inp" onkeypress="return isNumber(event)" id="txtsupplierphone"
                    placeholder="Supplier Phone" data-id="txtsupplierphone" />
                <div class="error-text" data-id="txtsupplierphone">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->
            <div class="inp-group">
                <div class="inp-label">Supplier Description</div>
                <input type="text" value="" class="inp" id="txtsupplierdescription" placeholder="Supplier Description"
                    data-id="txtsupplierdescription" />
                <div class="error-text" data-id="txtsupplierdescription">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->
        </div>
        <!-- input row end -->
        <div class="btn-row">
            <div class="primary-btn btn f-center submit--btn">Submit</div>
        </div>
    </form>



    <script src="../scripts/helperFunctions.js"></script>

    <script>
        let inputs = document.querySelectorAll("form input.required");
        let dropdowns = document.querySelectorAll("form select.required");
        let errorTexts = document.querySelectorAll("form .error-text");
    </script>
    <script src="../scripts/validation.js"></script>
    <!-- submitting data -->
    <script>
        let supplierObject = {
            "name": "",
            "location": "",
            "mobile": "",
            "description": ""
        };

        let submitBtn = document.querySelector(".submit--btn");
        submitBtn.addEventListener("click", e => {

            //if form is valid
            if (isValid()) {

                supplierObject.name = document.getElementById("txtsuppliername").value;
                supplierObject.location = document.getElementById("txtsupplierlocation").value;
                supplierObject.mobile = document.getElementById("txtsupplierphone").value;
                supplierObject.description = document.getElementById("txtsupplierdescription").value;

                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        var result = JSON.parse(this.responseText);
                        removeLoadingState(submitBtn);
                        showAlert(result.message, result.status);
                        if (result.status == "success") {
                            refreshPage();
                        }
                    }
                };

                addLoadingState(submitBtn);
                xmlhttp.open("POST", `services/uploadSupplier.php`, true);
                xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xmlhttp.send("data=" + encodeURIComponent(JSON.stringify(supplierObject)));
            }

        });
    </script>
</body>

</html>