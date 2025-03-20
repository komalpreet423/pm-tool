<?php
ob_start();
require_once '../includes/header.php';
$plugins = ['datepicker', 'select2'];
if (isset($_POST['add_project'])) {
    $name = $_POST['name'];
    $startdate = date('Y-m-d', strtotime($_POST['start_date']));
    $duedate = date('Y-m-d', strtotime($_POST['due_date']));
    $currencycode = $_POST['currencycode'];
    $status = $_POST['status'];
    $type = $_POST['type'];
    $description = $_POST['description'];
    $client = $_POST['client'];
    $manager = $_POST['manager'];
    $insertquery = "INSERT INTO projects (name, start_date, due_date, currency_code, status, type, description, client_id, manager_id) 
                    VALUES ('$name', '$startdate', '$duedate', '$currencycode', '$status', '$type', '$description', '$client', '$manager')";
    if (mysqli_query($conn, $insertquery)) {
        header('Location: ' . BASE_URL . './projects/index.php');
    } else {
        $errorMessage = mysqli_error($conn);
    }
}
$clients = mysqli_query($conn, "SELECT * FROM `clients` ");
$managers = mysqli_query($conn, "SELECT * FROM `users` WHERE `role`='manager' ");
?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box  p-2 d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Add New Project </h4>
        </div>
    </div>
</div>
<?php include './form.php' ?>

<?php require_once '../includes/footer.php'; ?>