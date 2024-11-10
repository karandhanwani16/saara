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


</head>

<body>
    <div class="alert--cont">
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">Parties</div>
        <div class="panel-body">
            <div class="table-responsive">
                <table id="customer_data" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Select</th>
                            <th>Delete</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Location</th>
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


    <script src="../scripts/helperFunctions.js"></script>
    <script type="text/javascript" language="javascript">
        $(document).ready(function () {

            var dataTable = $('#customer_data').DataTable({
                "processing": true,
                "serverSide": true,
                "order": [],
                "ajax": {
                    url: "services/getSupplierData.php",
                    type: "POST"
                },
                "drawCallback": function (oSettings) {

                    // Delete Buttons Functionality 
                    let deleteBtns = document.querySelectorAll(".delete-btn");
                    deleteBtns.forEach(deleteBtn => {
                        deleteBtn.addEventListener("click", e => {
                            let id = deleteBtn.attributes["data-id"].value;
                            let deleteConfirm = confirm("Are you Sure You want to delete");
                            if (deleteConfirm) {
                                deleteSupplier(deleteBtn, id);
                            }
                        });
                    });

                }
            });
            function deleteSupplier(btn, id) {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        var result = JSON.parse(this.responseText);
                        removeLoadingStateWithText(btn, "Delete");
                        showAlert(result.message, result.status);
                        if (result.status == "success") {
                            $('#' + result.id).remove();
                            $('#customer_data').DataTable().ajax.reload();
                        }
                    }
                };
                addLoadingStateWithText(btn, "Deleting...");
                xmlhttp.open("POST", `services/deleteSupplier.php`, true);
                xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xmlhttp.send("id=" + id);
            }


        });
    </script>


</body>

</html>