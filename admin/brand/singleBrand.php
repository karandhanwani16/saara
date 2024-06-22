<?php

include ("../services/config.php");
include ("../services/helperFunctions.php");
session_start();
$user_id = $_SESSION["user_id"];

$brandId = $_GET["id"];


$brandData = getBrandData($brandId, $con);

function getBrandData($brandId, $con)
{
    $brandDetails = new \stdClass();

    $query = "select * from brand where brand_id = $brandId";

    $result = $con->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $brandDetails->brand_id = $row["brand_id"];
        $brandDetails->brand_name = $row["brand_name"];
    }

    return $brandDetails;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brand Update</title>
    <link rel="stylesheet" href="../style/assets.css">
    <link rel="stylesheet" href="../style/forms.css">
</head>

<body>

    <div onclick="window.history.back()" class="back-btn"><img src='../assets/icons/back.svg' alt=''></div>
    <div class="alert--cont">
    </div>

    <!-- alert cont end -->

    <div class="title">Brand</div>
    <div class="spacer"></div>

    <form action="#">
        <div class="inp-row row-full row-4">
            <div class="inp-group">
                <div class="inp-label">Brand Name</div>
                <input value="<?php echo $brandData->brand_name ?>" type="text" class="inp required"
                    id="txtbrandname" placeholder="Brand Name" data-id="txtbrandname" />
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
        const currentBrandId = <?php echo $brandData->brand_id ? $brandData->brand_id : 0; ?>;
        let inputs = document.querySelectorAll("input.required");
        let dropdowns = document.querySelectorAll("select");
        let errorTexts = document.querySelectorAll(".error-text");
    </script>

    <script src="../scripts/validation.js"></script>

    <script>
        let brandObject = {
            "name": "",
            "id": currentBrandId
        };

        let submitBtn = document.querySelector(".submit--btn");
        submitBtn.addEventListener("click", e => {

            //if form is valid
            if (isValid()) {

                // assigning the values
                brandObject.name = document.getElementById("txtbrandname").value;
                brandObject.id = currentBrandId;

                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        var result = JSON.parse(this.responseText);
                        removeLoadingState(submitBtn);
                        showAlert(result.message, result.status);
                    }
                };

                addLoadingState(submitBtn);

                xmlhttp.open("POST", `services/updateBrand.php`, true);
                xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xmlhttp.send("data=" + encodeURIComponent(JSON.stringify(brandObject)));
            }

        });

    </script>
</body>

</html>