<?php
ob_start();
require_once '../includes/header.php';
if (isset($_POST['update_project'])) {
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
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Edit Project </h4>
        </div>
    </div>
</div>
        <div class="card">
            <div class="card-body">
                <form method="POST" class="p-3" id="update-project">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" name="name" required value="<?php echo $row['name']; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="currencycode">Currency Code</label>
                                        <select class="form-select" name="currencycode" required>
                                        <option value="" disabled>Select Currency Code</option>
                                            <option value="INR" <?php if ($row['currency_code'] == 'INR') echo 'selected'; ?>>INR</option>
                                            <option value="USD" <?php if ($row['currency_code'] == 'USD') echo 'selected'; ?>>USD</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="type">Type</label>
                                        <select class="form-select" name="type" required>
                                        <option value="" disabled>Select Type</option>
                                            <option value="hourly" <?php if ($row['type'] == 'hourly') echo 'selected'; ?>>Hourly</option>
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
                                <option value="" disabled>Select Status</option>
                                    <option value="planned" <?php if ($row['status'] == 'planned') echo 'selected'; ?>>Planned</option>
                                    <option value="in_progress" <?php if ($row['status'] == 'in_progress') echo 'selected'; ?>>In Progress</option>
                                    <option value="completed" <?php if ($row['status'] == 'completed') echo 'selected'; ?>>Completed</option>
                                    <option value="on_hold" <?php if ($row['status'] == 'on_hold') echo 'selected'; ?>>OnHold</option>
                                    <option value="cancelled" <?php if ($row['status'] == 'cancelled') echo 'selected'; ?>>Cancelled</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="description">Description</label>
                                <textarea class="form-control" name="description" required><?php echo $row['description']; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" name="update_project">Update Project</button>
                </form>
            </div>
        </div>
        </div>
    <?php
    } else {
        echo "Project not found.";
    }
}
require_once '../includes/footer.php';
    ?>
    <script>
     $(document).ready(function() {
                 $('#update-project').validate({
                                rules: {
                                    name: {
                                        required: true,
                                        minlength: 2
                                    },
                                    type: {
                                        required: true
                                    },
                                    currencycode: {
                                        required: true
                                    },
                                    st: {
                                        required: true,
                                        date: true,
                                        maxDate: true
                                    },
                                    dd: {
                                        date: true
                                    },
                                    status: {
                                        required: true
                                    },
                                    description: {
                                        required: true,
                                        minlength: 10
                                    }
                                },
                                messages: {
                                    name: {
                                        required: "Please enter the project name",
                                        minlength: "The name must be at least 2 characters long"
                                    },
                                    type: {
                                        required: "Please select the project type"
                                    },
                                    currencycode: {
                                        required: "Please select a currency code"
                                    },
                                    st: {
                                        required: "Please select a start date",
                                        date: "Please enter a valid date"
                                    },
                                    dd: {
                                        required: "Please select a due date",
                                        date: "Please enter a valid due date"
                                    },
                                    status: {
                                        required: "Please select the project status"
                                    },
                                    description: {
                                        required: "Please provide a description",
                                        minlength: "Description must be at least 10 characters long"
                                    }
                                },
                            });
                        });
                        </script>
<?php require_once '../includes/footer.php'; ?>