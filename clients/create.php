<?php
ob_start();
$plugins = ['datepicker'];
require_once '../includes/header.php';
$user_values = userProfile();

if($user_values['role'] && ($user_values['role'] !== 'hr' && $user_values['role'] !== 'admin'))
{
    $redirectUrl = $_SERVER['HTTP_REFERER'] ?? '/pm-tool';
    $_SESSION['toast'] = "Access denied. Employees only.";
    header("Location: " . $redirectUrl); 
    exit();
}
$errorMessage = '';

if (isset($_POST['add_client'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phoneno = $_POST['phone'];
    $address = $_POST['address'];
    $cname = $_POST['cname'];

    $query = "SELECT * FROM clients WHERE email = '$email' OR phone = '$phoneno'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $errorMessage = "This email or phone number already exists";
    } else {
        // ✅ FIXED: Added cname to the columns list
        $insert = "INSERT INTO clients (name, email, phone, address, cname) VALUES ('$name', '$email', '$phoneno', '$address', '$cname')";

        if (mysqli_query($conn, $insert)) {
            header('Location: ' . BASE_URL . '/clients/index.php');
            exit();
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