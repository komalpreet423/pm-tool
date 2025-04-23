<?php
ob_start();
require_once '../includes/header.php';
$userProfile = userProfile();
$userId = $userProfile['id'];
$userRole = $userProfile['role'];
$userId = $_SESSION['userId'];
if (isset($_POST['add_leave'])) {
    $employee_id = $userId;
    $leave_type = $_POST['leave_type'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $status = $_POST['status'];
    $reason =  $_POST['reason'];
    $status = 'pending';
    $query = "INSERT INTO leaves (employee_id, leave_type, start_date, end_date,status,reason) 
              VALUES ('$employee_id', '$leave_type', '$start_date', '$end_date', '$status','$reason')";

    if (mysqli_query($conn, $query)) {

        $leaveMessage = "New leave request submitted by " . $userProfile['name'];
        $leaveLink = BASE_URL . "/leaves/index.php";
        $rolesToNotify = ['admin', 'hr'];
        $rolesPlaceholder = implode("','", array_map(function ($role) use ($conn) {
            return mysqli_real_escape_string($conn, $role);
        }, $rolesToNotify));

        $roleQuery = "SELECT id FROM users WHERE role IN ('$rolesPlaceholder')";
        $roleResult = mysqli_query($conn, $roleQuery);

        while ($row = mysqli_fetch_assoc($roleResult)) {
            $recipientId = $row['id'];
            $stmt = $conn->prepare("INSERT INTO notifications (user_id, message, link, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->bind_param("iss", $recipientId, $leaveMessage, $leaveLink);
            $stmt->execute();
        }

        header('Location: ' . BASE_URL . './leaves/index.php');
        exit();
    }
} else {
    $errorMessage = mysqli_error($conn);
};

?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box pb-3 d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Apply Leave</h4>
            <a href="./index.php" class="btn btn-primary d-flex"><i class="bx bx-left-arrow-alt me-1 fs-4"></i>Go Back</a>
        </div>
    </div>
</div>
<div class="card">
    <?php include './form.php'; ?>
</div>
<?php require_once '../includes/footer.php'; ?>