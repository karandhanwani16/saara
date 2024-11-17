<?php

require ("../../services/config.php");

$response = new \stdClass();
$response->email = "";
$response->phone = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the raw POST data
    $postData = file_get_contents('php://input');

    // Decode JSON data into associative array
    $requestData = json_decode($postData, true);

    // Check if required data is present
    if (isset($requestData['name'])) {
        $name = $requestData['name'];

        $query = "select customer_email,customer_phone from customers where customer_name = '" . $name . "' order by customer_id desc limit 1";

        $result = $con->query($query);
        $rowCount = $result->num_rows;

        if ($rowCount > 0) {
            $row = $result->fetch_assoc();
            $response->email = $row['customer_email'];
            $response->phone = $row['customer_phone'];
        }

    }
}

echo json_encode($response);

?>