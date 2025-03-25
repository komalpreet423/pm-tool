<?php
require_once '../includes/db.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['file_id'])) {
    $fileId = intval($_POST['file_id']);

    $query = "SELECT file_path FROM milestone_documents WHERE id = '$fileId'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo json_encode(["success" => false, "message" => "Database error: " . mysqli_error($conn)]);
        exit();
    }

    $file = mysqli_fetch_assoc($result);

    if ($file) {
        $filePath = $file['file_path'];

        $deleteQuery = "DELETE FROM milestone_documents WHERE id = '$fileId'";
        if (mysqli_query($conn, $deleteQuery)) {
            if (file_exists($filePath) && !unlink($filePath)) {
                echo json_encode(["success" => false, "message" => "File deletion failed on server"]);
                exit();
            }

            echo json_encode(["success" => true, "message" => "File deleted successfully"]);
            exit();
        } else {
            echo json_encode(["success" => false, "message" => "Database delete error: " . mysqli_error($conn)]);
            exit();
        }
    } else {
        echo json_encode(["success" => false, "message" => "File not found"]);
        exit();
    }
}

echo json_encode(["success" => false, "message" => "Invalid request"]);
exit();
?>
