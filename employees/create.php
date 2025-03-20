<?php
ob_start();
$plugins = ['datepicker'];
require '../includes/header.php';
$errorMessage = '';
if (isset($_POST['add_employee'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phoneno = $_POST['phoneno'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $jobtitle = $_POST['jobt'];
    $role = $_POST['role'];
    $status = $_POST['status'];
    $dob = date('Y-m-d', strtotime($_POST['dob']));
    $doj = date('Y-m-d', strtotime($_POST['doj']));
    $password = $_POST['password'];
    $epass = md5($password);
    $query = "SELECT * FROM users WHERE email = '$email' OR phone_number = '$phoneno'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $errorMessage =  "This email or phone number already exists";
    } else {
        $insert = "INSERT INTO users (name, email,date_of_birth,date_of_joining,gender,phone_number,address,job_title,role,status,password_hash)  VALUES 
        ('$name', '$email',  '$dob','$doj', '$gender','$phoneno', '$address','$jobtitle','$role ','$status','$epass')";
        header('Location: ' . BASE_URL . './employees/index.php');
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
            <h4 class="mb-sm-0 font-size-18">Add Employee </h4>
        </div>
    </div>
</div>
<div class="card">
    <?php
    if ($errorMessage) {
        echo $errorMessage;
    }
    ?>
    <form method="POST" name="employee-form" id="employee-form" class="p-3" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="name">Name<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="name" value="<?php echo $name ?? '' ?>" required minlength="2">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="email">Email<span class="text-danger">*</span></label>
                    <input type="email" class="form-control" name="email" value="<?php echo $email ?? '' ?>" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="phoneno">Phone Number<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="phoneno"  value="<?php echo $phoneno ?? '' ?>" required pattern="\d{10}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="gender">Gender<span class="text-danger">*</span></label>
                    <select class="form-select"  id="gender" name="gender"  required>
                    <option value="" disabled>Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Date Of Birth<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="dob" id="dob" required   value="<?php echo $dob ?? '' ?>" autocomplete="of">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Date Of Joining<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="doj" id="doj" required autocomplete="of"  value="<?php echo $doj ?? '' ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="address">Address</label>
                    <textarea class="form-control" name="address" id="address" required><?php echo $address ?? "" ?></textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="jobt">Job Title</label>
                    <select class="form-select"   id="jobt" name="jobt"  required>
                    <option value="" disabled>Select Job Title</option>
                        <option value="phpdeveloper">PHP Developer</option>
                        <option value="frontendd">Frontend Developer</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="role">Role<span class="text-danger">*</span></label>
                    <select class="form-select"  id="role" name="role" required>
                    <option value="" disabled>Select Role</option>
                        <option value="Admin">Admin</option>
                        <option value="Manager">Manager</option>
                        <option value="HR">HR</option>
                        <option value="employee">employee</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="status">Status<span class="text-danger">*</span></label>
                    <select class="form-select"  name="status"    required>
                    <option value="" disabled>Select Status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="password">Password<span class="text-danger">*</span></label>
                    <input type="password" class="form-control" name="password" id="password" required minlength="6">
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary" name="add_employee">Add Employee</button>
    </form>
</div>
<script>
    $(document).ready(function() {
        $("#dob").datepicker();
        $("#doj").datepicker();
        $('#role').select2();
        $('#jobt').select2();
        $('#gender').select2();
        $('#status').select2();
        $(document).ready(function() {
            $('#employee-form').validate({
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
                    address: "required",
                    dob: "required",
                    doj: "required",
                    password: {
                        required: true,
                        minlength: 6
                    }
                },
                messages: {
                    name: "Please enter employee name",
                    email: "Please enter a valid email address",
                    phoneno: {
                        required: "Please enter a 10-digit phone number",
                        minlength: "Phone number must be exactly 10 digits",
                        maxlength: "Phone number must be exactly 10 digits",
                        digits: "Phone number can only contain digits"
                    },
                    address: "Please enter an address.",
                    dob: "Please enter Date Of Birth",
                    doj: "Please enter Date Of Joining",
                    password: "Please enter a password with at least 6 characters"
                },

            });
        });
    });
</script>
<?php require '../includes/footer.php' ?>