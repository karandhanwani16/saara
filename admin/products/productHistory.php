<?php

include("../services/config.php");
include("../../services/utils/generalFunctions.php");
session_start();
$user_id = $_SESSION["user_id"];

$productId = $_GET["product_id"];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product History</title>
    <link rel="stylesheet" href="../style/assets.css">
    <link rel="stylesheet" href="../style/forms.css">
    <link rel="stylesheet" href="../style/sales.css">
    <link rel="stylesheet" href="./style/product.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://markcell.github.io/jquery-tabledit/assets/js/tabledit.min.js"></script>

    <style>
        .product-history-title {
            font-size: 2rem;
            margin-left: 12px;
        }

        .inp-label-history {
            font-size: 1.8rem;
            margin-left: 12px;
            margin-bottom: 5px;
        }

        #ddlhistorytype {
            margin-left: 10px;
        }
    </style>
</head>

<body>

    <div onclick="window.history.back()" class="back-btn"><img src='../assets/icons/back.svg' alt=''></div>

    <div class="alert--cont">
    </div>

    <!-- alert cont end -->

    <div class="title product-history-title">Product History</div>
    <div class="spacer"></div>

    <form action="#">

        <div class="inp-row row-5 adj-5" style="justify-content: flex-start;gap: 24px;">
            <div class="inp-group">
                <div class="inp-label-history" style="font-size: 1.8rem;">History Type</div>
                <select class="ddl required" id="ddlhistorytype" data-id="ddlhistorytype">
                    <option value="">Select History Type</option>
                    <option value="purchase">Purchase</option>
                    <option value="sales">Sales</option>
                </select>
                <div class="error-text" data-id="txtdescription">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->
        </div>
        <!-- input row end -->
    </form>

    <!-- Product History Table -->
    <div class="table-responsive">
        <table id="purchase_history_data" class="table table-bordered table-striped hidden">
            <thead>
                <tr>
                    <th>Purchase ID</th>
                    <th>Purchase Date</th>
                    <th>Supplier Name</th>
                    <th>Purchase Type</th>
                    <th>Purchase Signed By</th>
                    <th>Purchase Qty</th>
                    <th>Purchase Cost Price</th>
                    <th>Purchase Batch Number</th>
                    <th>Purchase Total</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <div class="table-responsive">
        <table id="sales_history_data" class="table table-bordered table-striped hidden">
            <thead>
                <tr>
                    <th>Sales ID</th>
                    <th>Sales Date</th>
                    <th>Sales Attended By</th>
                    <th>Customer Name</th>
                    <th>Sales Qty</th>
                    <th>Sales Selling Price</th>
                    <th>Sales Batch Number</th>
                    <th>Sales Discount</th>
                    <th>Sales Discount Type</th>
                    <th>Sales Total</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>


    <script type="text/javascript" language="javascript">
        document.getElementById("ddlhistorytype").addEventListener("change", function (event) {
            const historyType = document.getElementById("ddlhistorytype").value;

            // Hide both tables initially to avoid showing both at the same time
            document.getElementById("purchase_history_data").classList.add("hidden");
            document.getElementById("sales_history_data").classList.add("hidden");

            // Destroy the current DataTable if it exists
            if ($.fn.dataTable.isDataTable('#purchase_history_data')) {
                $('#purchase_history_data').DataTable().clear().destroy();
            }
            if ($.fn.dataTable.isDataTable('#sales_history_data')) {
                $('#sales_history_data').DataTable().clear().destroy();
            }

            // If Purchase History is selected
            if (historyType == "purchase") {
                document.getElementById("purchase_history_data").classList.remove("hidden");
                $(document).ready(function () {
                    var dataTable = $('#purchase_history_data').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "order": [],
                        "ajax": {
                            url: "services/getProductHistory.php?product_id=<?php echo $productId; ?>",
                            type: "POST"
                        },
                        "drawCallback": function (oSettings) {
                        }
                    });
                });
            }

            // If Sales History is selected
            if (historyType == "sales") {
                document.getElementById("sales_history_data").classList.remove("hidden");
                $(document).ready(function () {
                    var salesTable = $('#sales_history_data').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "order": [],
                        "ajax": {
                            url: "services/getSalesHistory.php?product_id=<?php echo $productId; ?>",
                            type: "POST"
                        },
                        "drawCallback": function (oSettings) {
                        }
                    });
                });
            }
        });

    </script>
</body>

</html>