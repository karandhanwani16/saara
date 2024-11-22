<?php

require '../../../libraries/vendor/autoload.php';
include "../../services/config.php";
include "../../services/helperFunctions.php";


// get user_id from session
session_start();
$user_id = $_SESSION["user_id"] ?? null;

if (!$user_id) {
    die(json_encode(['status' => 'error', 'message' => 'User not authenticated']));
}

$finalObject = new \stdClass();
$finalObject->status = "";
$finalObject->user_id = $user_id;
$finalObject->fileName = "";


$data = $_POST["data"];
$data = json_decode($data, true);

// load DOM PDF
use Dompdf\Dompdf;

// Function to delete other files in the folder
function deleteOtherFiles($currentFile, $folderPath)
{
    $files = glob($folderPath . '*');
    foreach ($files as $file) {
        if (is_file($file) && basename($file) !== $currentFile) {
            unlink($file);
        }
    }
}

try {
    if (isset($data['products']) && isset($data['brand']) && isset($data['category'])) {
        $products = $data['products'];
        $brand = $data['brand'];
        $category = $data['category'];

        if ($brand === "novalue") {
            $brand = "";
        }
        if ($category === "novalue") {
            $category = "";
        }

    } else {
        throw new Exception("Required data is missing");
    }
    // Initialize Dompdf
    $dompdf = new Dompdf();

    // Generate HTML content
    $html = "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Receipt</title>
            <style>
                body { font-family: Arial, sans-serif; }
                * { margin: 0; padding: 0; }
                .receipt-container { margin: 0 auto; padding: 10px; border: 1px solid #ddd; }
                .header { text-align: center; }
                .header h2 { margin: 0; font-size: 18px; }
                .invoice-details { margin: 6px 0; font-size: 12px; }
                .header p { margin: 0; font-size: 12px; }
                table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                table th, table td { font-size: 10px; padding: 4px; text-align: left; border-bottom: 1px solid #ddd; }
                .summary { margin-top: 10px; font-size: 12px; }
                .summary p { margin: 2px 0;text-align:right;padding-right:20px }
                .footer { margin-top: 10px; }
                .footer h2 { margin: 0; font-size: 12px; }
                .footer p { margin: 0; font-size: 10px; }
            </style>
        </head>
        <body>
            <div class='receipt-container'>
                <div class='header'>
                    <h2>Saara Gift & Beauty Center</h2>
                    <p>Shop No.1, Poonawala Building, Mumbai, Maharashtra 431208</p>
                    <p>+91 251 252 3003</p>
                </div>

                <div class='invoice-details'>
                    <p>Order Date: <b>" . date("Ymd") . "</b></p>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Product Name</th>
                            <th>Product Minimum Stock</th>
                            <th>Product Current Stock</th>
                            <th>Brand Name</th>
                            <th>Category Name</th>
                        </tr>
                    </thead>
                    <tbody>";

    $index = 1;
    foreach ($products as $product) {
        $html .= "
                        <tr>
                            <td>{$index}</td>
                            <td>{$product['product_name']}</td>
                            <td>{$product['product_minimum_stock']}</td>
                            <td>{$product['product_current_stock']}</td>
                            <td>{$brand}</td>
                            <td>{$category}</td>
                        </tr>";
        $index++;
    }

    $html .= "
                    </tbody>
                </table>
                
                <div class='footer'>
                    <h2>Terms & Conditions</h2>
                    <p>1. Monday Closed.</p>
                    <p>2. Goods once sold will not be exchanged or returned.</p>
                    <p>3. For any product fault or problem bill is necessary.</p>
                </div>
            </div>
        </body>
        </html>
    ";

    // Load the HTML content into Dompdf
    $dompdf->loadHtml($html);

    // Set the paper size and orientation
    $dompdf->setPaper('A4', 'portrait');

    // Render the PDF
    $dompdf->render();

    $filename = "order_" . date("Ymd") . ".pdf";

    // Define the path where the PDF will be saved
    $savePath = "../../../assets/temp/order/" . $filename;

    // Save the PDF to the filesystem
    file_put_contents($savePath, $dompdf->output());

    // Delete other files in the folder
    $folderPath = "../../../assets/temp/order/";
    deleteOtherFiles($filename, $folderPath);

    // Add the file path to the response object
    $finalObject->fileName = $filename;
    $finalObject->status = 'success';
    $finalObject->message = 'Order downloaded successfully';

} catch (Exception $e) {
    $finalObject->status = 'error';
    $finalObject->message = 'Error in downloading order: ' . $e->getMessage();
}

echo json_encode($finalObject);
