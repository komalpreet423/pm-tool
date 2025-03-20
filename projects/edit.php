<?php
ob_start();
require_once '../includes/header.php';
if (isset($_POST['edit_project'])) {
    $id = $_GET['id'];
    $name = $_POST['name'];
    $st = date('Y-m-d', strtotime($_POST['st']));
    $dd = date('Y-m-d', strtotime($_POST['dd']));
    $currencycode = $_POST['currencycode'];
    $status = $_POST['status'];
    $type = $_POST['type'];
    $description = $_POST['description'];
    $sql = "UPDATE projects SET name = '$name', start_date = '$st', due_date = '$dd', currency_code = '$currencycode', status = '$status', type = '$type', description = '$description' WHERE id = '$id'";
    if (mysqli_query($conn, $sql)) {
        header('Location: ' . BASE_URL . './projects/index.php');
        exit();
    } else {
        echo "Error updating project: " . mysqli_error($conn);
    }
}
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sqlquery = "SELECT * FROM projects WHERE id = '$id'";
    $result = mysqli_query($conn, $sqlquery);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
?>
        <div class="card">
            <form method="POST" class="p-3">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" name="name" required value="<?php echo htmlspecialchars($row['name']); ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="currencycode">Currency Code</label>
                                    <select class="form-select" name="currencycode" required>
                                        <option value="INR" <?php if ($row['currency_code'] == 'INR') echo 'selected'; ?>>INR</option>
                                        <option value="USD" <?php if ($row['currency_code'] == 'USD') echo 'selected'; ?>>USD</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type">Type</label>
                                    <select class="form-select" name="type" required>
                                        <option value="hourlyrate" <?php if ($row['type'] == 'hourlyrate') echo 'selected'; ?>>Hourly</option>
                                        <option value="fixed" <?php if ($row['type'] == 'fixed') echo 'selected'; ?>>Fixed</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>Start Date</label>
                            <input type="text" class="form-control" name="st" required value="<?php echo $row['start_date']; ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>Due Date</label>
                            <input type="text" class="form-control" name="dd" required value="<?php echo $row['due_date']; ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
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
                            <label for="description">Description</label>
                            <textarea class="form-control" name="description" required><?php echo htmlspecialchars($row['description']); ?></textarea>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" name="edit_project">Save Project</button>
            </form>
        </div>
<?php
    } else {
        echo "Project not found.";
    }
}

require_once '../includes/footer.php';
?>