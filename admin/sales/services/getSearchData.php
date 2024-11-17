<?php

require ("../../services/config.php");

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the raw POST data
    $postData = file_get_contents('php://input');

    // Decode JSON data into associative array
    $requestData = json_decode($postData, true);

    // Check if required data is present
    if (isset($requestData['value'])) {
        $value = $requestData['value'];

        $query = "select distinct customer_name from customers where customer_name like '%" . $value . "%'";

        $result = $con->query($query);
        $rowCount = $result->num_rows;

        if ($rowCount > 0) {
            while ($row = $result->fetch_assoc()) {
                $response[] = $row['customer_name'];
            }
        }

    }
}

echo json_encode($response);

?>