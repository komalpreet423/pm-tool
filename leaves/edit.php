<?php
ob_start();
require_once '../includes/header.php';
if (isset($_POST['edit_leave'])) {
    $employee_id = $_GET['employee_id'];
    $leave_type = $_POST['leave_type'];
    $start_date =$_POST['start_date'];
    $end_date =  $_POST['end_date'];
    $status =  $_POST['status'];
    $reason = $_POST['reason'];
    $sql = "UPDATE leaves SET  leave_type = '$leave_type', start_date = '$start_date', end_date = '$end_date', status = '$status' ,reason='$reason'WHERE id = '$id' ";
    $result = mysqli_query($conn, $sql);
    header('Location: ' . BASE_URL . './leaves/index.php');
    exit();
}
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sqlquery = "SELECT * FROM leaves WHERE id={$id} ";
    $result = mysqli_query($conn, $sqlquery);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
?>
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box pb-2 d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Edit Leave </h4>
                        <a href="./index.php" class="btn btn-primary d-flex"><i class="bx bx-left-arrow-alt me-1 fs-4"></i>Go Back</a>
                    </div>
                </div>
            </div>
            <div class="card">
                <?php
                include 'form.php';
                ?>
            </div>

<?php }
    }
} ?>
<?php require_once '../includes/footer.php'; ?>