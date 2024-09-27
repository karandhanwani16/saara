<?php

require '../../../libraries/vendor/autoload.php';
include "../../services/config.php";
include "../../services/helperFunctions.php";

// get saleId from POST request
$saleId = isset($_POST['saleId']) ? json_decode($_POST['saleId'], true) : null;
// $saleId = 7;

if (!$saleId) {
    die(json_encode(['status' => 'error', 'message' => 'Sale ID is required']));
}

// get user_id from session
session_start();
$user_id = $_SESSION["user_id"] ?? null;

if (!$user_id) {
    die(json_encode(['status' => 'error', 'message' => 'User not authenticated']));
}

$finalObject = new \stdClass();
$finalObject->status = "";
$finalObject->user_id = $user_id;
$finalObject->sale_id = $saleId;

// load DOM PDF
use Dompdf\Dompdf;

// Function to delete other files in the folder
function deleteOtherFiles($currentFile, $folderPath) {
    $files = glob($folderPath . '*');
    foreach ($files as $file) {
        if (is_file($file) && basename($file) !== $currentFile) {
            unlink($file);
        }
    }
}

try {
    // Fetch sale data
    $saleQuery = "select * from sales where sale_id = ?";
    $saleStmt = $con->prepare($saleQuery);
    $saleStmt->bind_param("i", $saleId);
    $saleStmt->execute();
    $saleResult = $saleStmt->get_result();
    $saleData = $saleResult->fetch_assoc();

    if (!$saleData) {
        throw new Exception("Sale not found");
    }

    // Fetch sale items
    $itemsQuery = "select * from sale_items where sale_id = ?";
    $itemsStmt = $con->prepare($itemsQuery);
    $itemsStmt->bind_param("i", $saleId);
    $itemsStmt->execute();
    $itemsResult = $itemsStmt->get_result();
    $saleItems = $itemsResult->fetch_all(MYSQLI_ASSOC);

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
                    <p>Invoice No: <b>{$saleData['sale_id']}</b></p>
                    <p>Invoice Date: <b>" . formatDate($saleData['sale_date']) . "</b></p>
                    <p>Customer: <b>{$saleData['customer_name']}</b></p>
                    <p>Phone: <b>{$saleData['customer_phone']}</b></p>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>MRP</th>
                            <th>Qty</th>
                            <th>Disc</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>";




    foreach ($saleItems as $item) {
        $discountDisplay = $item['discount_type'] === 'percentage' ? $item['discount'] . '%' : 'Rs. ' . moneyFormatIndia($item['discount']);
        $html .= "
                        <tr>
                            <td>{$item['product_name']}</td>
                            <td>Rs. " . moneyFormatIndia($item['product_price']) . "</td>
                            <td>{$item['quantity']}</td>
                            <td>{$discountDisplay}</td>
                            <td>Rs. " . moneyFormatIndia($item['total']) . "</td>
                        </tr>";
    }

    $html .= "
                    </tbody>
                </table>

                <div class='summary'>
                    <p>Total Before Tax: <b>Rs. " . moneyFormatIndia($saleData['sale_total_before_tax']) . "</b></p>
                    <p>SGST: <b>Rs. " . moneyFormatIndia($saleData['sale_sgst_amount']) . "</b></p>
                    <p>CGST: <b>Rs. " . moneyFormatIndia($saleData['sale_cgst_amount']) . "</b></p>
                    <p>IGST: <b>Rs. " . moneyFormatIndia($saleData['sale_igst_amount']) . "</b></p>
                    <p>Total Before Discount: <b>Rs. " . moneyFormatIndia($saleData['sale_total_after_tax']) . "</b></p>
                    <p>Product Wise Discount: <b>Rs. " . moneyFormatIndia($saleData['sale_discount_amount']) . "</b></p>
                    <p>Final Discount: <b>Rs. " . moneyFormatIndia($saleData['sale_final_discount_amount']) . "</b></p>
                    <p>Round Off: <b>Rs. " . moneyFormatIndia($saleData['sale_roundoff']) . "</b></p>
                    <p>Final Amount: <b>Rs. " . moneyFormatIndia($saleData['sale_net_amount']) . "</b></p>
                </div>
                <p style='text-align:center; font-size:12px; margin-top:20px;'>Thanks for shopping with us!</p>
                
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

    // Output the generated PDF to Browser
    // $dompdf->stream("receipt_{$saleId}.pdf", array("Attachment" => false));

    //save pdf to folder
    // Generate a unique filename
    $filename = "invoice_" . $saleId . "_" . $user_id . ".pdf";

    // Define the path where the PDF will be saved
    $savePath = "../../../assets/temp/invoices/" . $filename;

    // Save the PDF to the filesystem
    file_put_contents($savePath, $dompdf->output());

    // Delete other files in the folder
    $folderPath = "../../../assets/temp/invoices/";
    deleteOtherFiles($filename, $folderPath);

    // Add the file path to the response object
    $finalObject->fileName = $filename;
    $finalObject->status = 'success';
    $finalObject->message = 'Invoice generated successfully';

} catch (Exception $e) {
    $finalObject->status = 'error';
    $finalObject->message = 'Error in generating invoice: ' . $e->getMessage();
}

echo json_encode($finalObject);
