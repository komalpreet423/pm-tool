<?php
ob_start();
require_once '../includes/header.php';

// Handle update submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit-policies'])) {
    $id = $_POST['id'];
    $name =  $_POST['name'];
    $file =  $_POST['file'];

    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $stmt = $conn->prepare("UPDATE policies SET name = ?, file = ?, description = ? WHERE id = ?");
    $stmt->bind_param("sssi", $name, $file, $description, $id);

    if ($stmt->execute()) {
        header('Location: ' . BASE_URL . './policy/index.php');
        exit();
    } else {
        echo "<div class='alert alert-danger'>Failed to update policy: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Load data for editing
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM policies WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
?>
        <div class="row">
            <div class="col-12">
                <div class="page-title-box pb-2 d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Edit Policies</h4>
                    <a href="./index.php" class="btn btn-primary">Go back</a>
                </div>
            </div>
        </div>
        <div class="card">
            <?php include 'form.php'; ?>
        </div>
<?php
    } else {
        echo "<div class='alert alert-warning'>Policy not found.</div>";
    }
    $stmt->close();
}
require_once '../includes/footer.php';
ob_end_flush();
?>