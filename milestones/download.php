<?php
require_once '../vendor/autoload.php'; // Correct path depending on your project
require_once '../includes/db.php'; // Your database connection

use Dompdf\Dompdf;
use Dompdf\Options;

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Fetch milestone
    $sql = "SELECT pm.*, p.name as project_name 
            FROM project_milestones pm
            JOIN projects p ON pm.project_id = p.id
            WHERE pm.id = '$id' AND pm.status = 'completed'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $milestone = mysqli_fetch_assoc($result);

        // Create HTML for PDF
        $html = '
   <style>
    h2 {
        color:rgba(99, 107, 213, 0.98);
    }

    h1 {
        color:rgba(61, 39, 233, 0.71);
    }

    tr {
        background-color: rgba(99, 99, 213, 0.22);
    }

    td {
        height: 70px;
        width: 330px;
        border-radius: 20px;
        padding-left: 10px;
        padding: 8px;
        margin: 0px;
    }

    .width {
        width: 700px;
    }

    .row {
        height: 50px;
        width: 700px;
        background-color: rgba(99, 107, 213, 0.98);
        border-radius: 20px;
      
    }

    lft,
    rght {
        height: 10px;
        width: 10px;
    }
</style>

<h1>Invoice</h1>
<h5>Invoice No # <b>000027</b></h5>
<h5>Invoice Date  <b>April 17,2025</b></h5>


    <table>
    <tr>
        <td class="rght">
            <h1>Milestone Report</h1>
            <div><strong>Project:</strong> ' . htmlspecialchars($milestone['project_name']) . ' <br>
            <strong>Milestone:</strong> ' . htmlspecialchars($milestone['milestone_name']) . '<br>
            <strong>Description:</strong> ' . (($milestone['description'])) . '<br>
            <strong>Due Date:</strong> ' . $milestone['due_date'] . '<br>
           <strong>Amount:</strong> ' . ($milestone['amount'] ? number_format($milestone['amount'], 2) : '-') . ' ' . htmlspecialchars($milestone['currency_code']) . ' <br>
            <strong>Status:</strong> Completed</div>
        </td>
          <td class="lft">
            <h1>Milestone Report</h1>
            <div><strong>Project:</strong> ' . htmlspecialchars($milestone['project_name']) . ' <br>
            <strong>Milestone:</strong> ' . htmlspecialchars($milestone['milestone_name']) . '<br>
            <strong>Description:</strong> ' . nl2br(htmlspecialchars($milestone['description'])) . '<br>
            <strong>Due Date:</strong> ' . $milestone['due_date'] . '<br>
           <strong>Amount:</strong> ' . ($milestone['amount'] ? number_format($milestone['amount'], 2) : '-') . ' ' . htmlspecialchars($milestone['currency_code']) . ' <br>
            <strong>Status:</strong> Completed</div>
        </td>
    </tr>
</table><br>
<br>
<table class="width">
    <div class="row"></div>
    <tr>
        <td>item</td>
    </tr>
</table>
        ';

        // Setup DOMPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Output PDF
        $dompdf->stream('milestone_' . $milestone['id'] . '.pdf', ['Attachment' => false]);
        exit();
    } else {
        echo "Milestone not found or not completed.";
        exit();
    }
} else {
    echo "Invalid request.";
    exit();
}
?>

td {
height: 200px;
width: 300px;
}

.row {
height: 50px;
width: 700px;
}
</style>

<h2>Invoice</h2>
<h5>Invoice No # <b>000027</b></h5>
<h5>Invoice Date <b>April 17,2025</b></h5>

<table>
    <tr>
        <td>
            <h1>Milestone Report</h1>
            <p><strong>Project:</strong> ' . htmlspecialchars($milestone['project_name']) . '</p>
            <p><strong>Milestone:</strong> ' . htmlspecialchars($milestone['milestone_name']) . '</p>
            <p><strong>Description:</strong> ' . nl2br(htmlspecialchars($milestone['description'])) . '</p>
            <p><strong>Due Date:</strong> ' . $milestone['due_date'] . '</p>
            <p><strong>Amount:</strong> ' . ($milestone['amount'] ? number_format($milestone['amount'], 2) : '-') . ' ' . htmlspecialchars($milestone['currency_code']) . '</p>
            <p><strong>Status:</strong> Completed</p>
        </td>
        <td>
            <div class="right">
                <h1>Milestone Report</h1>
                <p><strong>Project:</strong> ' . htmlspecialchars($milestone['project_name']) . '</p>
                <p><strong>Milestone:</strong> ' . htmlspecialchars($milestone['milestone_name']) . '</p>
                <p><strong>Description:</strong> ' . nl2br(htmlspecialchars($milestone['description'])) . '</p>
                <p><strong>Due Date:</strong> ' . $milestone['due_date'] . '</p>
                <p><strong>Amount:</strong> ' . ($milestone['amount'] ? number_format($milestone['amount'], 2) : '-') . ' ' . htmlspecialchars($milestone['currency_code']) . '</p>
                <p><strong>Status:</strong> Completed</p>
        </td>
    </tr>
</table>