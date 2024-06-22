<?php

include ("../services/config.php");
include ("../../services/utils/generalFunctions.php");
session_start();
$user_id = $_SESSION["user_id"];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brand Upload</title>
    <link rel="stylesheet" href="../style/assets.css">
    <link rel="stylesheet" href="../style/forms.css">
</head>

<body>

    <div class="alert--cont">
    </div>

    <!-- alert cont end -->

    <div class="title">Brand</div>
    <div class="spacer"></div>

    <form action="#">
        <div class="inp-row">
            <div class="inp-group">
                <div class="inp-label">Brand Name</div>
                <input type="text" class="inp" id="txtbrandname" placeholder="Brand Name"
                    data-id="txtbrandname" />
                <div class="error-text" data-id="txtbrandname">Cannot leave this field blank</div>
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
        let inputs = document.querySelectorAll("input");
        let dropdowns = document.querySelectorAll("select");
        let errorTexts = document.querySelectorAll(".error-text");
        let brandObject = {
            "name": document.getElementById("txtbrandname").value
        };
    </script>

    <script src="../scripts/validation.js"></script>

    <script>

        let submitBtn = document.querySelector(".submit--btn");
        submitBtn.addEventListener("click", e => {

            //if form is valid
            if (isValid()) {

                // assigning the values
                brandObject.name = document.getElementById("txtbrandname").value;

                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        var result = JSON.parse(this.responseText);
                        removeLoadingState(submitBtn);
                        showAlert(result.message, result.status);
                    }
                };
                addLoadingState(submitBtn);

                xmlhttp.open("POST", `services/uploadBrand.php`, true);
                xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xmlhttp.send("data=" + encodeURIComponent(JSON.stringify(brandObject)));
            }

        });



    </script>
</body>

</html>