<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style/assets.css">
    <link rel="stylesheet" href="../style/forms.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://markcell.github.io/jquery-tabledit/assets/js/tabledit.min.js"></script>
    <link rel="stylesheet" href="./style/product.css">

    <style>
        .print-barcode-btn {
            background-color: var(--success-color);
        }

        .print-barcode-btn:hover {
            cursor: pointer;
            color: #fff;
        }

        .barcode {
            height: 172px;
        }
    </style>

</head>

<body>
    <div class="alert--cont">
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">Products</div>
        <div class="panel-body">
            <div class="table-responsive">
                <table id="sample_data" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Select</th>
                            <th>Delete</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th>Name</th>
                            <th>Barcode</th>
                            <th>Code</th>
                            <th>Description</th>
                            <th>Created By</th>
                            <th>Updated By</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- <div class="popup-backdrop"></div>
    <div class="popup-cont">
        <div class="popup-header">
            <p></p>
            <div class="close-btn">
                <img src="../assets/icons/close.svg" alt="close btn">
            </div>
        </div>

        <div class="popup-body" id="qr-reader">
            <div class="generate--cont">
                <div class="barcode">
                    <center>
                        <table>
                            <tr>
                                <th id="extraDetailsHeader1"
                                    style="font-size: 14px;font-weight: 800;text-align: center;" colspan="2"></th>
                            </tr>
                            <tr>
                                <th id="extraDetailsHeader2" style="font-size: 16px;font-weight: 600;text-align:center;"
                                    colspan="2"></th>
                            </tr>
                            <tr>
                                <td>
                                    <div id="productCode" style="transform: rotate(-90deg);height: 10px;"></div>
                                </td>
                                <td>
                                    <img id="generatedBarcode" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="details" id="footerSellingPrice"></div>
                                </td>
                                <td>
                                    <div class="details" style="text-align: right;" id="footerParlourPrice"></div>
                                </td>
                            </tr>
                        </table>
                    </center>

                </div>
                <div class="btn-cont">
                    <div class="btn print-btn" id="printBtn">Print</div>
                    <div style="display: none;" class="btn print-btn" id="useCodeBtn">Use Code</div>
                </div>

            </div>
        </div>
    </div> -->


    <script src="../scripts/helperFunctions.js"></script>
    <!-- <script src="./scripts/viewBarcodeGenerator.js"></script> -->

    <!-- <script src="./scripts/viewProductDetails.js"></script>
    <script src="./scripts/viewPopup.js"></script> -->

    <script type="text/javascript" language="javascript">
        $(document).ready(function () {

            var dataTable = $('#sample_data').DataTable({
                "processing": true,
                "serverSide": true,
                "order": [],
                "ajax": {
                    url: "services/getProductData.php",
                    type: "POST"
                },
                "drawCallback": function (oSettings) {

                    // Delete Buttons Functionality 
                    let deleteBtns = document.querySelectorAll(".delete-btn");
                    deleteBtns.forEach(deleteBtn => {
                        deleteBtn.addEventListener("click", e => {
                            let id = deleteBtn.attributes["data-id"].value;
                            let deleteConfirm = confirm("Are you sure you want to delete");
                            if (deleteConfirm) {
                                deleteProduct(deleteBtn, id);
                            }
                        });
                    });
                    // Print Buttons Functionality 
                    // let printBtns = document.querySelectorAll(".print-barcode-btn");
                    // printBtns.forEach(printBtn => {
                    //     printBtn.addEventListener("click", e => {
                    //         let id = printBtn.attributes["data-id"].value;
                    //         printBarcode(printBtn, id);
                    //     });
                    // });

                }
            });

            function deleteProduct(btn, id) {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        var result = JSON.parse(this.responseText);
                        removeLoadingStateWithText(btn, "Delete");
                        showAlert(result.message, result.status);
                        if (result.status == "success") {
                            $('#' + result.id).remove();
                            $('#sample_data').DataTable().ajax.reload();
                        }
                    }
                };
                addLoadingStateWithText(btn, "Deleting...");
                xmlhttp.open("POST", `services/deleteProduct.php`, true);
                xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xmlhttp.send("id=" + id);
            }


            // function printBarcode(printBtn, id) {
            //     generateBarcodeMain(id);
            // }

        });
    </script>

</body>

</html>