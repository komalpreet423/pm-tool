<?php
ob_start();
require '../includes/header.php';
$user_values = userProfile();

if($user_values['role'] && ($user_values['role'] !== 'hr' && $user_values['role'] !== 'admin'))
{
    $redirectUrl = $_SERVER['HTTP_REFERER'] ?? '/pm-tool';
    $_SESSION['toast'] = "Access denied. Employees only.";
    header("Location: " . $redirectUrl); 
    exit();
}

$errorMessage = '';
    
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_policies'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $uploadedFiles = [];

    if (empty($name) || empty($description) || empty($_FILES['file']['name'][0])) {
        $errorMessage = "All fields including at least one file are required.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM policies WHERE name = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $errorMessage = "This policy name already exists.";
        } else {
            $uploadDir = '../uploads/';
            foreach ($_FILES['file']['name'] as $key => $fileName) {
                $tmpName = $_FILES['file']['tmp_name'][$key];
                $targetPath = $uploadDir . basename($fileName);

                if (move_uploaded_file($tmpName, $targetPath)) {
                    $uploadedFiles[] = $fileName;
                }
            }

            if (!empty($uploadedFiles)) {
                $filesToStore = implode(',', $uploadedFiles);

                $insertStmt = $conn->prepare("INSERT INTO policies (name, file, description) VALUES (?, ?, ?)");
                $insertStmt->bind_param("sss", $name, $filesToStore, $description);

                if ($insertStmt->execute()) {
                    header('Location: ' . BASE_URL . '/policy/index.php');
                    exit();
                } else {
                    $errorMessage = "Error adding policy: " . $insertStmt->error;
                }

                $insertStmt->close();
            } else {
                $errorMessage = "Failed to upload files.";
            }
        }

        $stmt->close();
    }
}
?>

<div class="row">
    <div class="col-12">
        <div class="page-title-box pb-2 d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Add Policy</h4>
            <a href="./index.php" class="btn btn-primary">Go back</a>
        </div>
    </div>
</div>

<div class="card">
    <div class="p-3">
        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <?php include './form.php'; ?>
    </div>
</div>

<?php require '../includes/footer.php'; ?>
<?php ob_end_flush(); ?>