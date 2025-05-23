<?php
ob_start();
require_once '../includes/header.php';
$plugins = ['datepicker', 'select2'];
$user_values = userProfile();

if($user_values['role'] && ($user_values['role'] !== 'hr' && $user_values['role'] !== 'admin'))
{
    $redirectUrl = $_SERVER['HTTP_REFERER'] ?? '/pm-tool';
    $_SESSION['toast'] = "Access denied. Employees only.";
    header("Location: " . $redirectUrl); 
    exit();
}
if (isset($_POST['add_milestone'])) {
    $project_id = $_POST['project_id'];
    $milestone_name = $_POST['milestone_name'];
    $description = isset($_POST['description']) ? strip_tags($_POST['description']) : '';
    $due_date = date('Y-m-d', strtotime($_POST['due_date']));
    $amount = $_POST['amount'] ? $_POST['amount'] : NULL;
    $currency_code = $_POST['currency_code'];
    $status = $_POST['status'];

    $projectCheckQuery = "SELECT id FROM projects ";
    $result = mysqli_query($conn, $projectCheckQuery);

    if (empty($amount)) {
        $errorMessage = "Amount is required.";
    }
    if (mysqli_num_rows($result) > 0) {
        $insertQuery = "INSERT INTO project_milestones (project_id, milestone_name, due_date, amount, currency_code, description, status) 
                        VALUES ('$project_id', '$milestone_name', '$due_date', '$amount', '$currency_code', '$description', '$status')";

        if (mysqli_query($conn, $insertQuery)) {
            $milestone_id = mysqli_insert_id($conn);

            if (!empty($_FILES['milestone_documents']['name'][0])) {
                $uploadDir = "../uploads/milestones/";
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                foreach ($_FILES['milestone_documents']['name'] as $key => $filename) {
                    $tmpName = $_FILES['milestone_documents']['tmp_name'][$key];
                    $fileType = $_FILES['milestone_documents']['type'][$key];
                    $fileSize = $_FILES['milestone_documents']['size'][$key];

                    $allowedTypes = ['image/png', 'image/jpeg', 'image/jpg', 'application/pdf', 'text/plain', 'video/mp4', 'video/avi', 'video/mov'];
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

            header('Location: ' . BASE_URL . '/milestones/index.php');
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
            <h4 class="mb-sm-0 font-size-18">Add New Milestone</h4>
            <a href="./index.php" class="btn btn-primary d-flex"><i class="bx bx-left-arrow-alt me-1 fs-4"></i>Go Back</a>
        </div>
    </div>
</div>
<div class="card">
    <?php include './form.php' ?>
</div>

<?php require_once '../includes/footer.php'; ?>