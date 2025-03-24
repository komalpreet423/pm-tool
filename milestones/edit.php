<?php
ob_start();
require_once '../includes/header.php';
$plugins = ['datepicker', 'select2'];

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $milestone_id = intval($_GET['id']);

    $query = "SELECT * FROM project_milestones WHERE milestone_id = '$milestone_id'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        $docQuery = "SELECT * FROM milestone_documents WHERE milestone_id = '$milestone_id'";
        $docResult = mysqli_query($conn, $docQuery);
        $documents = mysqli_fetch_all($docResult, MYSQLI_ASSOC);
    } else {
        echo "<p class='text-danger'>Milestone not found!</p>";
        exit;
    }
} else {
    echo "<p class='text-danger'>Invalid request!</p>";
    exit;
}

if (isset($_POST['edit-milestone'])) {
    $project_id = intval($_POST['project_id']);
    $milestone_name = mysqli_real_escape_string($conn, $_POST['milestone_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $due_date = date('Y-m-d', strtotime($_POST['due_date']));
    $amount = !empty($_POST['amount']) ? floatval($_POST['amount']) : NULL;
    $currency_code = mysqli_real_escape_string($conn, $_POST['currency_code']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $completed_date = !empty($_POST['completed_date']) ? date('Y-m-d', strtotime($_POST['completed_date'])) : NULL;

    $projectCheckQuery = "SELECT id FROM projects WHERE id = '$project_id' AND type = 'fixed'";
    $result = mysqli_query($conn, $projectCheckQuery);

    if (mysqli_num_rows($result) > 0) {
        $updateQuery = "UPDATE project_milestones 
                        SET project_id='$project_id', milestone_name='$milestone_name', description='$description',
                            due_date='$due_date', amount=" . ($amount ? "'$amount'" : "NULL") . ", 
                            currency_code='$currency_code', status='$status', 
                            completed_date=" . ($completed_date ? "'$completed_date'" : "NULL") . "
                        WHERE milestone_id='$milestone_id'";

        if (mysqli_query($conn, $updateQuery)) {
            if (!empty($_FILES['milestone_documents']['name'][0])) {
                $uploadDir = "../uploads/milestones/";
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $allowedTypes = ['image/png', 'image/jpeg', 'image/jpg', 'application/pdf', 'text/plain', 'video/mp4', 'video/avi', 'video/mov'];

                foreach ($_FILES['milestone_documents']['name'] as $key => $filename) {
                    $tmpName = $_FILES['milestone_documents']['tmp_name'][$key];
                    $fileType = $_FILES['milestone_documents']['type'][$key];

                    if (!in_array($fileType, $allowedTypes)) {
                        $errorMessage = "Invalid file type: $filename";
                        continue;
                    }

                    $newFileName = time() . "-" . basename($filename);
                    $filePath = $uploadDir . $newFileName;

                    if (move_uploaded_file($tmpName, $filePath)) {
                        $fileInsertQuery = "INSERT INTO milestone_documents (milestone_id, file_path, file_name) 
                                            VALUES ('$milestone_id', '$filePath', '$newFileName')";
                        mysqli_query($conn, $fileInsertQuery);
                    }
                }
            }

            header('Location: ' . BASE_URL . './milestones/index.php');
            exit();
        } else {
            $errorMessage = "Database Error: " . mysqli_error($conn);
        }
    } else {
        $errorMessage = "Invalid project selection. Please select a valid fixed project.";
    }
}
?>

<div class="row">
    <div class="col-12">
        <div class="page-title-box pb-3 d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Edit Milestone</h4>
            <a href="./index.php" class="btn btn-primary d-flex"><i class="bx bx-left-arrow-alt me-1 fs-4"></i>Go Back</a>
        </div>
    </div>
</div>

<?php include './form.php'; ?>

<?php require_once '../includes/footer.php'; ?>