<?php
ob_start();
require_once '../includes/header.php';
if (isset($_POST['edit_project'])) {
    $id = $_GET['id'];
    $name = $_POST['name'];
    $startdate = date('Y-m-d', strtotime($_POST['start_date']));
    $duedate = date('Y-m-d', strtotime($_POST['due_date']));
    $currencycode = $_POST['currencycode'];
    $status = $_POST['status'];
    $type = $_POST['type'];
    $description = $_POST['description'];
    $hourly_rate = ($type === 'hourly' && isset($_POST['hourly_rate'])) ? $_POST['hourly_rate'] : NULL;
    $sql = "UPDATE projects SET name = '$name', start_date = '$startdate', due_date = '$duedate', currency_code = '$currencycode', status = '$status', type = '$type',description = '$description', hourly_rate = '$hourly_rate' WHERE id = '$id'";
    if (mysqli_query($conn, $sql)) {
        header('Location: ' . BASE_URL . './projects/index.php');
        exit();
    } else {
        echo "Error updating project: " . mysqli_error($conn);
    }
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sqlquery = "SELECT * FROM projects WHERE id = '$id'";
    $result = mysqli_query($conn, $sqlquery);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $existingClientId = $row['client_id'];
        $existingManagerId = $row['manager_id'];
?>
        <div class="row">
            <div class="col-12">
                <div class="page-title-box pb-3 d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Edit Project </h4>
                    <a href="./index.php" class="btn btn-primary d-flex"><i class="bx bx-left-arrow-alt me-1 fs-4"></i>Go Back</a>
                </div>
            </div>
        </div>
        <?php include './form.php' ?>
<?php
    } else {
        echo "Project not found.";
    }
}
require_once '../includes/footer.php';
?>
<?php require_once '../includes/footer.php'; ?>