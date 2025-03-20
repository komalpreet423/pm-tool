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
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Add New Client</h4>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
    <?php
    if ($errorMessage) {
        echo $errorMessage;
    }
    ?>
    <form method="POST" name="client-form" id="client-form" class="p-3" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name" id="name" required minlength="2">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="phoneno">Phone Number</label>
                    <input type="number" class="form-control" name="phone" required pattern="\d{10}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="address">Address</label>
                    <textarea class="form-control" name="address" id="address" required></textarea>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary" name="add_client">Add Client</button>
    </form>
</div>
</div>
<script>
    $(document).ready(function() {
        $('#client-form').validate({
            rules: {
                name: "required",
                email: {
                    required: true,
                    email: true
                },
                phoneno: {
                    required: true,
                    minlength: 10,
                    maxlength: 10,
                    digits: true
                },
                address: "required"
            },
            messages: {
                name: "Please enter client name",
                email: "Please enter a valid email address",
                phoneno: {
                    required: "Please enter a 10-digit phone number",
                    minlength: "Phone number must be 10 digits",
                    maxlength: "Phone number cannot be more than 10 digits"
                },
                address: "Please enter address."
            }
        });
    });
</script>
<?php require_once '../includes/footer.php'; ?>