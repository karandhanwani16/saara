<?php

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


$con = mysqli_connect($HOST, $USER, $PASSWORD, $DATABASE);

// Check connection
if (mysqli_connect_errno()) {
  echo "<script>alert('Failed to connect to MySQL: " . $mysqli->connect_error . "')</script>";
  exit();
}