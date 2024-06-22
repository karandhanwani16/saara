<?php
include ("services/config.php");
include ("services/helperFunctions.php");
session_start();
$user_id = $_SESSION["user_id"];
$user_type = $_SESSION["user_type"];

$currentEmail = getCurrentEmail($user_id, $con);

?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" class="light--theme">

<head runat="server">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Woodland | Admin</title>
    <link rel="stylesheet" href="style/assets.css">
    <link rel="stylesheet" href="style/main.css">
    <style>
        .chip {
            margin-left: 8px;
            background: none;
            border: 2px solid #c48fd9;
            padding: 2px 16px;
            font-size: 1rem;
            border-radius: 15px;
        }
    </style>
</head>

<body>

    <div class="main-container">
        <div class="sidebar">
            <ul class="menu-cont">

                <?php
                // $currentPage = "./dashboard/html/index.php";
                $currentPage = "./category/category.php";

                ?>

                <li class="menu-item">
                    <a class="menu-item-main f-link mainactive" data-link="./category/category.php">
                        <img src="assets/icons/dashboard.svg" alt="">
                        <div class="list-item-value">Category</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a class="menu-item-main f-link" data-link="./brand/brand.php">
                        <img src="assets/icons/dashboard.svg" alt="">
                        <div class="list-item-value">Brand</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a class="menu-item-main f-link" data-link="./products/products.php">
                        <img src="assets/icons/dashboard.svg" alt="">
                        <div class="list-item-value">Products</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a class="menu-item-main f-link" data-link="./suppliers/suppliers.php">
                        <img src="assets/icons/dashboard.svg" alt="">
                        <div class="list-item-value">Suppliers</div>
                    </a>
                </li>

                <li class="menu-item ddl <?php echo $user_type == "employee" ? "hidden" : ""; ?>">
                    <div class="menu-item-main">
                        <img src="assets/icons/catalogue.svg" alt="">
                        <div class="list-item-value">Super Admin</div>
                    </div>
                    <ul class="sub-menu hidden">
                        <li class="submenu-list-item f-link" data-link="superadmin/addusers/addusers.php"><a>Add
                                User</a>
                        </li>
                        <li class="submenu-list-item f-link" data-link="superadmin/logs/logs.php"><a>Logs</a></li>
                        <li class="submenu-list-item f-link" data-link="superadmin/backup/backup.php"><a>Backup</a></li>
                    </ul>
                </li>


                <li class="menu-item">
                    <a class="menu-item-main f-link"
                        data-link="../changepassword.php?admin=1&email=<?php echo $currentEmail; ?>">
                        <img src="assets/icons/dashboard.svg" alt="">
                        <div class="list-item-value">Change Password</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a class="menu-item-main" href="logout.php">
                        <img src="assets/icons/logout.svg" alt="">
                        <div class="list-item-value">Logout</div>
                    </a>
                </li>
            </ul>
        </div>
        <div class="main-view">
            <div class="top-nav">
                <div class="ham-menu f-center">
                    <img src="assets/icons/ham-menu.svg" alt="">
                </div>
            </div>
            <div class="main-content-page">
                <iframe style="width:100%;height:100%;border:none;" class="target-container"
                    src="<?php echo $currentPage; ?>"></iframe>
            </div>
        </div>
    </div>

    <!-- scripts start -->
    <script src="scripts/sidebar.js"></script>
    <!-- scripts end -->

</body>

</html>