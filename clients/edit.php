<?php
ob_start();
require_once '../includes/header.php';
if (isset($_POST['update_client'])) {
    $id = $_GET['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phoneno = $_POST['phone'];
    $address = $_POST['address'];
    $sql = "UPDATE clients SET name = '$name', email = '$email', phone = '$phoneno', address = '$address' WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        header('Location: ' . BASE_URL . './clients/index.php');
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sqlquery = "SELECT * FROM clients WHERE id = '$id'";
    $result = mysqli_query($conn, $sqlquery);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Update Client</h4>
        </div>
    </div>
</div>
        <div class="card">
            <div class="card-body">
                <form method="POST" class="p-3" id="client-form">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" name="name" id="name" required value="<?php echo $row['name']; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" name="email" required value="<?php echo $row['email']; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phoneno">Phone Number</label>
                                <input type="number" class="form-control" name="phone" required value="<?php echo $row['phone']; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="address">Address</label>
                                <textarea class="form-control" name="address" required><?php echo $row['address']; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" name="update_client">Update Client</button>
                </form>
            </div>
        </div>
<?php
    } else {
        echo "Client not found.";
    }
}
?>
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
<?php require_once '../includes/footer.php';?>