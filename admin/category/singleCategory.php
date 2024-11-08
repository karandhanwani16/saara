<?php

include ("../services/config.php");
include ("../services/helperFunctions.php");
session_start();
$user_id = $_SESSION["user_id"];

$categoryId = $_GET["id"];


$categoryData = getCategoryData($categoryId, $con);

function getCategoryData($categoryId, $con)
{
    $categoryDetails = new \stdClass();

    $query = "select * from category where category_id = $categoryId";

    $result = $con->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $categoryDetails->category_id = $row["category_id"];
        $categoryDetails->category_name = $row["category_name"];
    }

    return $categoryDetails;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Update</title>
    <link rel="stylesheet" href="../style/assets.css">
    <link rel="stylesheet" href="../style/forms.css">
</head>

<body>

    <div onclick="window.history.back()" class="back-btn"><img src='../assets/icons/back.svg' alt=''></div>
    <div class="alert--cont">
    </div>

    <!-- alert cont end -->

    <div class="title">Category</div>
    <div class="spacer"></div>

    <form action="#">
        <div class="inp-row row-full row-4">
            <div class="inp-group">
                <div class="inp-label">Category Name</div>
                <input value="<?php echo $categoryData->category_name ?>" type="text" class="inp required"
                    id="txtcategoryname" placeholder="category name" data-id="txtcategoryname" />
                <div class="error-text" data-id="txtcategoryname">Cannot leave this field blank</div>
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
        const currentCategoryId = <?php echo $categoryData->category_id ? $categoryData->category_id : 0; ?>;
        let inputs = document.querySelectorAll("input.required");
        let dropdowns = document.querySelectorAll("select");
        let errorTexts = document.querySelectorAll(".error-text");
    </script>

    <script src="../scripts/validation.js"></script>

    <script>
        let categoryObject = {
            "name": "",
            "id": currentCategoryId
        };

        let submitBtn = document.querySelector(".submit--btn");
        submitBtn.addEventListener("click", e => {

            //if form is valid
            if (isValid()) {

                // assigning the values
                categoryObject.name = document.getElementById("txtcategoryname").value;
                categoryObject.id = currentCategoryId;

                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        var result = JSON.parse(this.responseText);
                        removeLoadingState(submitBtn);
                        showAlert(result.message, result.status);
                    }
                };

                addLoadingState(submitBtn);

                xmlhttp.open("POST", `services/updateCategory.php`, true);
                xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xmlhttp.send("data=" + encodeURIComponent(JSON.stringify(categoryObject)));
            }

        });

    </script>
</body>

</html>