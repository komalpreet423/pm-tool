<?php
ob_start();
require_once '../includes/header.php';
if (isset($_POST['edit_attendance'])) {
    $id = $_GET['id'];
    $employee_id= $_POST['employee'];
    $date = $_POST['date'];
    $status = $_POST['status'];
    $note = $_POST['note'];
    $sql = "UPDATE attendance SET employee_id = '$employee_id', date = '$date', status = '$status', note ='$note' WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        header('Location: ' . BASE_URL . './attendance /index.php');
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sqlquery = "SELECT * FROM attendance  WHERE id = '$id'";
    $result = mysqli_query($conn, $sqlquery);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box pb-3 d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Edit Attendance </h4>
            <a href="./index.php" class="btn btn-primary d-flex"><i class="bx bx-left-arrow-alt me-1 fs-4"></i>Go Back</a>
        </div>
    </div>
</div>
<?php
    } else {
        echo "Attendance  not found.";
    }
}
?> 
<?php include './form.php' ?>
<?php require_once '../includes/footer.php';?>