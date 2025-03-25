<?php
ob_start();
require_once '../includes/header.php';
$plugins = ['datepicker', 'select2'];

if (isset($_GET['id'])) {
    $milestone_id = $_GET['id'];
    $query = "SELECT * FROM project_milestones WHERE milestone_id = '$milestone_id'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    } else {
        echo "<p class='text-danger'>Milestone not found!</p>";
        exit;
    }
} else {
    echo "<p class='text-danger'>Invalid request!</p>";
    exit;
}

if (isset($_POST['edit-milestone'])) {
    $project_id = $_POST['project_id'];
    $milestone_name = $_POST['milestone_name'];
    $description = $_POST['description'];
    $due_date = date('Y-m-d', strtotime($_POST['due_date']));
    $amount = $_POST['amount'] ? $_POST['amount'] : NULL;
    $currency_code = $_POST['currency_code'];
    $status = $_POST['status'];
    $completed_date = !empty($_POST['completed_date']) ? date('Y-m-d', strtotime($_POST['completed_date'])) : NULL;

    $projectCheckQuery = "SELECT id FROM projects WHERE id = '$project_id' AND type = 'fixed'";
    $result = mysqli_query($conn, $projectCheckQuery);

    if (mysqli_num_rows($result) > 0) {
        $updateQuery = "UPDATE project_milestones 
                        SET project_id='$project_id', milestone_name='$milestone_name', description='$description',
                            due_date='$due_date', amount='$amount', currency_code='$currency_code', 
                            status='$status', completed_date='$completed_date' 
                        WHERE milestone_id='$milestone_id'";

        if (mysqli_query($conn, $updateQuery)) {
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