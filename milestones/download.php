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
            <h1>Milestone Report</h1>
            <p><strong>Project:</strong> ' . htmlspecialchars($milestone['project_name']) . '</p>
            <p><strong>Milestone:</strong> ' . htmlspecialchars($milestone['milestone_name']) . '</p>
            <p><strong>Description:</strong> ' . nl2br(htmlspecialchars($milestone['description'])) . '</p>
            <p><strong>Due Date:</strong> ' . $milestone['due_date'] . '</p>
            <p><strong>Amount:</strong> ' . ($milestone['amount'] ? number_format($milestone['amount'], 2) : '-') . ' ' . htmlspecialchars($milestone['currency_code']) . '</p>
            <p><strong>Status:</strong> Completed</p>
        ';

        // Setup DOMPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Output PDF
        $dompdf->stream('milestone_' . $milestone['id'] . '.pdf', ['Attachment' => true]);
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
