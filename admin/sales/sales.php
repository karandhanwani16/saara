<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <meta name="viewport" content="width=device-width, height=device-height,  initial-scale=1.0, user-scalable=no;user-scalable=0;"/>
    <link href="../style/spinner.css" rel="stylesheet" />
    <link href="../style/page.css" rel="stylesheet" />
</head>

<body>
    <div class="tab-main-cont">
        <div class="tab-cont">
            <div class="tab fc active-tab" data-link="salesUpload.php">Upload</div>
            <div class="tab fc" data-link="salesView.php">View</div>
        </div>
        <div class="viewport">
            <iframe class="viewport-screen" src="salesUpload.php"></iframe>
        </div>
    </div>



    <div class="loading-screen fc">
        <div class="loader"></div>
        <p>Loading...</p>
    </div>

    <script src="../scripts/loading.js"></script>
    <script src="../scripts/tabs.js"></script>


</body>

</html>