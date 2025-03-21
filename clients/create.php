<?php
ob_start();
$plugins = ['datepicker'];
require_once '../includes/header.php';
$errorMessage = '';
if (isset($_POST['add_client'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phoneno = $_POST['phone'];
    $address = $_POST['address'];
    $query = "SELECT * FROM clients WHERE email = '$email' OR phone = '$phoneno'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $errorMessage =  "This email or phone number already exists";
    } else {
        $insert = "INSERT INTO clients (name, email, phone, address) VALUES ('$name', '$email', '$phoneno', '$address')";
        header('Location: ' . BASE_URL . './clients/index.php');
        if (mysqli_query($conn, $insert)) {
        } else {
            $errorMessage = mysqli_error($conn);
        }
    }
}
?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box pb-3 d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Add New Client</h4>
            <a href="./index.php" class="btn btn-primary d-flex"><i class="bx bx-left-arrow-alt me-1 fs-4"></i>Go Back</a>

        </div>
    </div>
</div>
    <?php
    if ($errorMessage) {
        echo $errorMessage;
    }
    ?>
    <?php include './form.php' ?>
<?php require_once '../includes/footer.php'; ?>