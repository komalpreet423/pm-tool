<?php
ob_start();
require_once '../includes/header.php';
if (isset($_POST['edit_project'])) {
    $id = $_GET['id'];
    $name = $_POST['name'];
    $startdate= date('Y-m-d', strtotime($_POST['start_date']));
    $duedate = date('Y-m-d', strtotime($_POST['due_date']));
    $currencycode = $_POST['currencycode'];
    $status = $_POST['status'];
    $type = $_POST['type'];
    $description = $_POST['description'];
    $sql = "UPDATE projects SET name = '$name', start_date = '$startdate', due_date = '$startdate', currency_code = '$currencycode', status = '$status', type = '$type', description = '$description' WHERE id = '$id'";
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
                <?php include './form.php' ?>
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