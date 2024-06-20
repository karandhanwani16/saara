<?php

include ("../../services/config.php");
include ("../../services/helperFunctions.php");
session_start();
$user_id = $_SESSION["user_id"];

$userId = $_GET["id"];


$userData = getUserData($userId, $con);

function getUserData($userId, $con)
{
    $userDetails = new \stdClass();

    $query = "select * from users where user_id = $userId";

    $result = $con->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $userDetails->user_id = $row["user_id"];
        $userDetails->user_email = $row["user_email"];
        $userDetails->user_first_name = $row["user_first_name"];
        $userDetails->user_last_name = $row["user_last_name"];
        $userDetails->user_type = $row["user_type"];
        $userDetails->user_last_login = $row["user_last_login"];
    }

    return $userDetails;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Update</title>
    <link rel="stylesheet" href="../../style/assets.css">
    <link rel="stylesheet" href="../../style/forms.css">
</head>

<body>

    <div onclick="window.history.back()" class="back-btn"><img src='../../assets/icons/back.svg' alt=''></div>
    <div class="alert--cont">
    </div>

    <!-- alert cont end -->

    <div class="title">User</div>
    <div class="spacer"></div>

    <form action="#">
        <div class="inp-row row-full row-4">
            <div class="inp-group">
                <div class="inp-label">User Email</div>
                <input value="<?php echo $userData->user_email ?>" type="text" class="inp required"
                    id="txtuseremail" placeholder="User Email" data-id="txtuseremail" />
                <div class="error-text" data-id="txtuseremail">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->
            <div class="inp-group">
                <div class="inp-label">User Password</div>
                <input type="text" value="" class="inp required" id="txtuserpassword"
                    placeholder="User Password" data-id="txtuserpassword" />
                <div class="error-text" data-id="txtuserpassword">Cannot leave this field blank</div>
            </div>
            
            <!-- inp group end -->
            <div class="inp-group">
                <div class="inp-label">User First Name</div>
                <input type="text" value="<?php echo $userData->user_first_name ?>" class="inp required" id="txtuserfirstname" placeholder="User First Name"
                    data-id="txtuserfirstname" />
                <div class="error-text" data-id="txtuserfirstname">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->
            <div class="inp-group">
                <div class="inp-label">User Last Name</div>
                <input type="text" value="<?php echo $userData->user_last_name ?>" class="inp required"
                    id="txtuserlastname" placeholder="User Last Name" data-id="txtuserlastname" />
                <div class="error-text" data-id="txtuserlastname">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->


        </div>
        <!-- input row end -->

        <div class="inp-row row-full row-4">

            <div class="inp-group">
                <div class="inp-label">User Type</div>
                <select id="ddlusertype" class="ddl" data-id="ddlusertype">
                <option value="">Select User Type</option>
                    <option value="admin">Admin</option>
                    <option value="employee">Employee</option>
                </select>
                <div class="error-text" data-id="ddlusertype">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->

        </div>
        <!-- input row end -->
        <div class="btn-row">
            <div class="primary-btn btn f-center submit--btn">Submit</div>
        </div>
    </form>

    <script src="../../scripts/helperFunctions.js"></script>

    <script>
        const currentUserId = <?php echo $userData->user_id ? $userData->user_id : 0; ?>;
        let inputs = document.querySelectorAll("input.required");
        let dropdowns = document.querySelectorAll("select");
        let errorTexts = document.querySelectorAll(".error-text");
    </script>

    <script src="../../scripts/validation.js"></script>

    <script>

        let userObject = {
            "id":currentUserId,
            "email": "",
            "fname": "",
            "lname": "",
            "type": "",
            "password": "",
        };

        let submitBtn = document.querySelector(".submit--btn");
        submitBtn.addEventListener("click", e => {

            //if form is valid
            if (isValid()) {

                // assigning the values
                userObject.id = currentUserId;
                userObject.email = document.getElementById("txtuseremail").value;
                userObject.fname = document.getElementById("txtuserfirstname").value;
                userObject.lname = document.getElementById("txtuserlastname").value;
                userObject.type = document.getElementById("ddlusertype").value;
                userObject.password = document.getElementById("txtuserpassword").value;

                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        var result = JSON.parse(this.responseText);
                        removeLoadingState(submitBtn);
                        showAlert(result.message, result.status);
                    }
                };

                addLoadingState(submitBtn);

                xmlhttp.open("POST", `services/updateUser.php`, true);
                xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xmlhttp.send("data=" + encodeURIComponent(JSON.stringify(userObject)));
            }

        });

    </script>
</body>

</html>