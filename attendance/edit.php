<?php
require_once '../includes/db.php';
$user_values = userProfile();

if($user_values['role'] && ($user_values['role'] !== 'hr' && $user_values['role'] !== 'admin'))
{
    $redirectUrl = $_SERVER['HTTP_REFERER'] ?? '/test/pm-tool';
    $_SESSION['toast'] = "Access denied. Employees only.";
    header("Location: " . $redirectUrl); 
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $status = $_POST['status'];
    $note = $_POST['note'];
    $date = $_POST['date'];

    $query = "UPDATE attendance SET status = $status, note =  $note, date =  $date WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $status, $note, $date, $id);

    if ($stmt->execute()) {
        header("Location: index.php?message=updated");
    } else {
        echo "Failed to update.";
    }
}
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sqlquery = "SELECT * FROM attendance  WHERE id = '$id'";
    $result = mysqli_query($conn, $sqlquery);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box pb-3 d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Edit Attendance </h4>
            <a href="./index.php" class="btn btn-primary d-flex"><i class="bx bx-left-arrow-alt me-1 fs-4"></i>Go Back</a>
        </div>
    </div>
</div>
<?php
    } else {
        echo "Attendance  not found.";
    }
}
?> 
<?php include './form.php' ?>
<?php require_once '../includes/footer.php';?>