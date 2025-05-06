<?php
require('../vendor/autoload.php');
use Dompdf\Dompdf;

require_once '../includes/db.php';

$invoiceId = $_GET['id'] ?? 0;


$invoiceSql = "SELECT * FROM invoices WHERE id = ?";
$stmt = $conn->prepare($invoiceSql);
$stmt->bind_param("i", $invoiceId);
$stmt->execute();
$invoiceResult = $stmt->get_result();
$invoice = $invoiceResult->fetch_assoc();

if (!$invoice) {
    die("Invoice not found.");
}


$itemSql = "SELECT * FROM invoice_items WHERE invoice_id = ?";
$stmt = $conn->prepare($itemSql);
$stmt->bind_param("i", $invoiceId);
$stmt->execute();
$itemResult = $stmt->get_result();

$totalAmount = 0;

$html = '<meta charset="UTF-8">
<style>
    * { font-family: Sans-serif,  DejaVu Sans; }
    .btr {
        width: 305px;
        background-color: rgba(18, 35, 45, 0.53);
        border-radius: 7px;
        padding: 19px;
        color: white;
    }
    td {
        width: 106px;
        background-color:  rgba(18, 35, 45, 0.53);
        padding: 5px;
        color: white;
    }
    .row {
        height: 50px;
        width: 700px;
    }
    h1 {
        margin-bottom: 5px;
        margin-top: 0px;
        font-size: 20px;
    }
    h5 {
        margin-bottom: 15px;
        margin-top: 15px;
        color: grey;
    }
    th {
        color:white;
        background-color:  #F3C100;
        border-radius: 2px 0px 0px 0px;
        padding: 7px;
    }
    pre {
        text-align: right;
        font-size: 19px;
    }
    p {
        margin-bottom: 0px;
        margin-top: 0px;
    }
</style>

<h1 style="color: #F3C100; font-size: 25px;">Invoice</h1>
<h5>Invoice No <b style="color:black;">#' . htmlspecialchars($invoice['invoice_id']) . '</b></h5>
<h5>Invoice Date: <b style="color:black;">' . htmlspecialchars($invoice['invoice_date']) . '</b></h5>

<table>
    <tr>
        <td class="btr" style="padding-bottom:90px;">
            <h1 style="color: #F3C100 ;margin-bottom:10px;">Billed By</h1>
            <strong style="color:white;font-size: 15px;">' . htmlspecialchars($invoice['billed_by_name']) . '</strong> 
            <p style="color:white;font-size: 15px;">' . nl2br(htmlspecialchars($invoice['billed_by_address'])) . '</p>
            <p style="color:white;font-size: 15px;"><strong>PAN:</strong> ' . htmlspecialchars($invoice['billed_by_pan']) . '</p>
        </td>
        <td class="btr" style="padding-bottom:70px;">
            <h1 style="color: #F3C100;margin-bottom:10px;">Billed To</h1>
            <strong style="color:white;font-size: 15px;">' . htmlspecialchars($invoice['billed_to_client_company_name']) . '</strong>
            <p style="color:white;font-size: 15px;">' . nl2br(htmlspecialchars($invoice['billed_to_address'])) . '</p>
            <p style="color:white;font-size: 15px;"><strong>PAN:</strong> ' . htmlspecialchars($invoice['billed_to_pan']) . '</p>
        </td>
    </tr>
</table>
<br>
<table>
    <tr style=" padding: 2px;">
        <th style="width: 0px; border-radius: 7px 0px 0px 0px;">No.</th>
        <th style="width: 200px;">Task Title</th>
        <th style="width: 80px;">Hours</th>
        <th style="width: 80px;">Rate</th>
        <th style="width: 100px; border-radius: 0px 7px 0px 0px;">Total</th>
    </tr>';

$count = 1;
while ($item = $itemResult->fetch_assoc()) {
    $lineTotal = $item['hours'] * $item['rate'];
    $totalAmount += $lineTotal;

    $html .= '
    <tr>
        <td style="background-color: #12232D;text-align:center; ">' . $count++ . '</td>
        <td style="background-color: #12232D">' . htmlspecialchars($item['task_title']) . '</td>
        <td style="background-color: #12232D; text-align:center;">' . htmlspecialchars($item['hours']) . '</td>
        <td style="background-color: #12232D; text-align:center;">₹' . number_format($item['rate'], 2) . '</td>
        <td style="background-color: #12232D; text-align:center;">₹' . number_format($lineTotal, 2) . '</td>
    </tr>';
}

$html .= '</table>
<br>
<hr>
<pre style="padding-right:40px;">Total : ₹' . number_format($totalAmount, 2) . '</pre>
<hr>';


$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("invoice_{$invoice['invoice_id']}.pdf", ["Attachment" => 1]);
?>
