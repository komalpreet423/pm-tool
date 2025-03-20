<?php 
ob_start();
require_once '../includes/header.php';
if (isset($_POST['edit-employee'])) {
  $id = $_GET['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $doj = $_POST['doj'];
    $gender = $_POST['gender'];
    $phoneno = $_POST['phoneno'];
    $address = $_POST['address'];
    $jobt = $_POST['jobt'];
    $role = $_POST['role'];
    $status = $_POST['status'];
    $password = $_POST['password'];
    $sql = "UPDATE users SET name = '$name', email = '$email', date_of_birth = '$dob', date_of_joining = '$doj', gender = '$gender', phone_number = '$phoneno', address = '$address', job_title = '$jobt', role = '$role', status = '$status' WHERE id = '$id' ";
    $result = mysqli_query($conn, $sql);
    header('Location: ' . BASE_URL . './employees/index.php');
    exit();
}
if (isset($_GET['id'])) {
    $id = $_GET['id'];
$sqlquery = "SELECT * FROM users WHERE id={$id} ";
$result = mysqli_query($conn, $sqlquery);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
?>
        <form method="POST" class="p-3" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" name="name" required minlength="2" value="<?php echo $row['name']; ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" value="<?php echo $row['email']; ?>" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="phoneno">Phone Number</label>
                        <input type="number" class="form-control" name="phoneno" value="<?php echo $row['phone_number']; ?>" required pattern="\d{10}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="gender">Gender</label>
                        <select class="form-select" name="gender" value="<?php echo $row['gender']; ?>" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label>Date Of Birth</label>
                        <input type="text" class="form-control" name="dob" id="dob" value="<?php echo $row['date_of_birth']; ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label>Date Of Joining</label>
                        <input type="text" class="form-control" name="doj" id="doj" value="<?php echo $row['date_of_joining']; ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="address">Address</label>
                        <textarea class="form-control" name="address" id="address" required><?php echo $row['address']; ?></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="jobt">Job Title</label>
                        <select class="form-select" name="jobt" required>
                            <option value="phpdeveloper" <?php if ($row['job_title'] == 'phpdeveloper') echo 'selected'; ?>>PHP Developer</option>
                            <option value="frontendd" <?php if ($row['job_title'] == 'frontendd') echo 'selected'; ?>>Frontend Developer</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="role">Role</label>
                        <select class="form-select" name="role" required>
                            <option value="Admin" <?php if ($row['role'] == 'Admin') echo 'selected'; ?>>Admin</option>
                            <option value="Manager" <?php if ($row['role'] == 'Manager') echo 'selected'; ?>>Manager</option>
                            <option value="HR" <?php if ($row['role'] == 'HR') echo 'selected'; ?>>HR</option>
                            <option value="employee" <?php if ($row['role'] == 'employee') echo 'selected'; ?>>Employee</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="status">Status</label>
                        <select class="form-select" name="status" required>
                            <option value="Active" <?php if ($row['status'] == 'Active') echo 'selected'; ?>>Active</option>
                            <option value="Inactive" <?php if ($row['status'] == 'Inactive') echo 'selected'; ?>>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="password" >
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" name="edit-employee">Edit Employee</button>
        </form>
<?php }
} }?>
<?php require_once '../includes/footer.php'; ?>
