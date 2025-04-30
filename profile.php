<?php
ob_start();
require_once './includes/header.php';
$userProfile = userProfile();

if (isset($_POST['submit'])) {
    $id = $_GET['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone_no = $_POST['phoneno'];
    $address = $_POST['address'];
    $password = $_POST['password'];
    $epassword = md5($password);
if (isset($_FILES['profile-pic']) && $_FILES['profile-pic']['error'] === UPLOAD_ERR_OK) {
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    $fileInfo = pathinfo($_FILES['profile-pic']['name']);
    $fileExtension = strtolower($fileInfo['extension']);
    if (in_array($fileExtension, $allowedExtensions)) {
        $targetDir = 'images/';
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $newFileName = uniqid() . '.' . $fileExtension;
        $targetFile = $targetDir . $newFileName;
        if (move_uploaded_file($_FILES['profile-pic']['tmp_name'], $targetFile)) {
            $profilePicPath = $targetFile;
        }
    }
}
    $sqlquery = "UPDATE users 
        SET name = '$name', email = '$email', phone_number = '$phone_no', address = '$address', password_hash = '$epassword', profile_pic = '$profilePicPath'
        WHERE id = '$id'";
    $result = mysqli_query($conn, $sqlquery);

  
    header('Location: ' . BASE_URL . '/index.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sqlquery = "SELECT * FROM users WHERE id = '$id'";
    $result = mysqli_query($conn, $sqlquery);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    }
}
?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box pb-3 d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Profile</h4>
            <a href="./index.php" class="btn btn-primary d-flex"><i class="bx bx-left-arrow-alt me-1 fs-4"></i>Go Back</a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body"> 
        <form method="POST" name="employee-form" id="employee-form" class="p-3" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name">Name</label> <span class="text-danger">*</span>
                        <input type="text" class="form-control" name="name" value="<?php echo $userProfile['name']; ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email">Email</label> <span class="text-danger">*</span>
                        <input type="email" class="form-control" name="email" value="<?php echo $userProfile['email']; ?>" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="gender">Phone Number</label>
                        <input type="number" class="form-control" name="phoneno" value="<?php echo $userProfile['phone_number']; ?>" required pattern="\d{10}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="gender">Gender</label>
                                <input type="text" class="form-control" name="gender" value="<?php echo $userProfile['gender']; ?>" disabled readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jobt">Job Title</label>
                                <input type="text" class="form-control" name="jobt" value="<?php echo $userProfile['job_title']; ?>"  disabled readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="dob">Date Of Birth <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="dob" id="dob" value="<?php echo $userProfile['date_of_birth']; ?>" readonly disabled>
                    </div>
                </div>
                <div class="col-md-3">
                    <label>Date Of Joining</label> <span class="text-danger">*</span>
                    <input type="text" class="form-control" name="doj" value="<?php echo $userProfile['date_of_joining']; ?>" disabled readonly>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="address">Address</label> <span class="text-danger">*</span>
                        <textarea class="form-control" name="address" required><?php echo $userProfile['address']; ?></textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="profile-pic">Profile Pic</label>
                        <input type="file" id="profile-pic" class="form-control" name="profile-pic" accept="image/png, image/jpeg">
                        <?php if ($userProfile['profile_pic']) { ?>
                            <img src="<?php echo $userProfile['profile_pic']; ?>" alt="Profile Picture" class="img-thumbnail mt-2" width="100">
                        <?php } ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password">
                    </div>
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php require_once './includes/footer.php'; ?>
