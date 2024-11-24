<?php

require ("../../services/config.php");

$response = new \stdClass();
$response->email = "";
$response->name = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the raw POST data
    $postData = file_get_contents('php://input');

    // Decode JSON data into associative array
    $requestData = json_decode($postData, true);

    // Check if required data is present
    if (isset($requestData['phone'])) {
        $phone = $requestData['phone'];

        $query = "select customer_email,customer_name from customers where customer_phone = '" . $phone . "' order by customer_id desc limit 1";

        $result = $con->query($query);
        $rowCount = $result->num_rows;

        if ($rowCount > 0) {
            $row = $result->fetch_assoc();
            $response->email = $row['customer_email'];
            $response->name = $row['customer_name'];
        }

    }
}

echo json_encode($response);

?>