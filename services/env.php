<?php

$otpValidTime = 15;

$env = "dev";
// $env = "qa";
// $env = "prod";

$HOST = "";
$USER = "";
$PASSWORD = "";
$DATABASE = "";

if ($env == "dev") {
    $HOST = "localhost";
    $USER = "root";
    $PASSWORD = "";
    $DATABASE = "nntest";
} else {
    $HOST = "";
    $USER = "";
    $PASSWORD = "";
    $DATABASE = "";
}


$backupEmail = "karandhanwani20@gmail.com";
$billingMainEmail = "";
$companyName = "NN Test";

$sendActionsEmails = [];

//$sendActionsEmails = [];
