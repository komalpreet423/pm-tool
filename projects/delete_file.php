<?php
require_once '../includes/db.php';

if (isset($_POST['file_id'])) {
    $fileId = $_POST['file_id'];

    $query = mysqli_query($conn, "SELECT file_path FROM project_documents WHERE id = '$fileId'");
    
    if (!$query) {
        echo json_encode(["success" => false, "message" => "Database error: " . mysqli_error($conn)]);
        exit();
    }

    $file = mysqli_fetch_assoc($query);

    if ($file) {
        $filePath = $file['file_path'];

        $deleteQuery = "DELETE FROM project_documents WHERE id = '$fileId'";
        if (mysqli_query($conn, $deleteQuery)) {
            if (file_exists($filePath)) {
                if (!unlink($filePath)) {
                    echo json_encode(["success" => false, "message" => "File deletion failed on server"]);
                    exit();
                }
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
