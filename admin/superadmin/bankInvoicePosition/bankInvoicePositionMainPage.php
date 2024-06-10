<?php

include("../../services/urlValidation.php");
include("../../services/config.php");
include("../../services/helperFunctions.php");
// session_start();
// checkUrlValidation("admin", "../../login.php");
// $user_id = $_SESSION["user_id"];


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Invoice Position</title>
    <link rel="stylesheet" href="../../style/assets.css">
    <link rel="stylesheet" href="../../style/forms.css">
    <link rel="stylesheet" href="../../style/sales.css">
    <link rel="stylesheet" href="../../style/dragdrop.css">
    <style>
        .drag-drop-main-cont {
            flex-direction: column;
        }

        .row {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .row h2 {
            width: calc(50% - 32px);
            display: flex;
            justify-content: flex-start;
            text-transform: capitalize;
        }
    </style>
</head>

<body>
    <script>
        let currentIndex = 5;
    </script>
    <div class="alert--cont">
    </div>

    <!-- alert cont end -->

    <form action="#">
        <div class="inp-row adj-row row-4">
            <!-- <div class="inp-group">
                <div class="inp-label">Firm</div>
                <select id="ddllocation" tabindex="3" class="ddl required" data-id="ddllocation">
                    <option value="">Select Firm</option>
                </select>
                <div class="error-text" data-id="ddllocation">Cannot leave this field blank</div>
            </div> -->
            <!-- inp group end -->
        </div>
        <!-- input row end -->

        <!-- drag drop start -->
        <div class="drag-drop-main-cont">
            <div class="row">
                <h2>Invoice No. First</h2>
                <h2>Financial Year First</h2>
            </div>
            <div class="row">
                <div class="include-list" data-type="included" ondrop="drop(event)" ondragover="allowDrop(event)">
                    <!-- <div class="brand" value="badshah" draggable="true" data-id="1">Badshah</div>
                    <div class="brand" value="nivea" draggable="true" data-id="2">Nivea</div> -->
                </div>
                <div class="not-include-list" data-type="not-included" ondrop="drop(event)" ondragover="allowDrop(event)">
                    <!-- <div class="brand" value="" draggable="true" data-id="3">New brand</div> -->
                </div>
            </div>
        </div>
        <!-- drag drop end -->

        <div class="btn-row">
            <div class="primary-btn btn f-center submit--btn">Submit</div>
        </div>
    </form>


    <script>
        let includedListCont = document.querySelector(".include-list");
        let notIncludedListCont = document.querySelector(".not-include-list");

        function displayRows() {
            includedListCont.innerHTML = "";
            let includedString = "";
            includedList.forEach(item => {
                includedString += `<div class="brand" value="${item.name}" draggable="true" data-id="${item.id}">${item.name}</div>`;
            });
            includedListCont.innerHTML = includedString;

            //not included list show
            notIncludedListCont.innerHTML = "";
            let notIncludedString = "";
            notIncludedList.forEach(item => {
                notIncludedString += `<div class="brand" value="${item.name}" draggable="true" data-id="${item.id}">${item.name}</div>`;
            });
            notIncludedListCont.innerHTML = notIncludedString;

        }
        let includedList = [];
        let notIncludedList = [];
        displayRows();
    </script>
    <script src="scripts/handleDrop.js"></script>
    <script src="../../scripts/helperFunctions.js"></script>
    <script>
        loadData();

        function loadData() {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var result = JSON.parse(this.responseText);
                    includedList = [];
                    notIncludedList = [];

                    result.included.forEach(item => {
                        let tempIncluded = {
                            id: item[0],
                            name: item[1]
                        };
                        includedList.push(tempIncluded);
                    });
                    result.notincluded.forEach(item => {
                        let tempNotIncluded = {
                            id: item[0],
                            name: item[1]
                        };
                        notIncludedList.push(tempNotIncluded);
                    });
                    displayRows();
                    addListener();
                }
            };
            xmlhttp.open("POST", `services/getBankInvoicePosition.php`, true);
            xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xmlhttp.send();
        }
    </script>
    <!-- submitting data -->
    <script>
        let bankInvoicePositionObject = {
            "invoiceFirst": includedList
        };
        let submitBtn = document.querySelector(".submit--btn");
        submitBtn.addEventListener("click", e => {
            includedList.forEach(il => {
                il.name = escape(il.name);
            });
            bankInvoicePositionObject.invoiceFirst = includedList;
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var result = JSON.parse(this.responseText);
                    removeLoadingState(submitBtn);
                    showAlert(result.message, result.status);
                }
            };
            addLoadingState(submitBtn);
            xmlhttp.open("POST", `services/updateInvoicePosition.php`, true);
            xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xmlhttp.send("data=" + JSON.stringify(bankInvoicePositionObject));
        });
    </script>

</body>

</html>