<?php
ob_start();
require_once '../includes/header.php';
$plugins = ['datepicker', 'select2'];
if (isset($_POST['add_project'])) {
    $name = $_POST['name'];
    $st = date('Y-m-d', strtotime($_POST['st']));
    $dd = date('Y-m-d', strtotime($_POST['dd']));
    $currencycode = $_POST['currencycode'];
    $status = $_POST['status'];
    $type = $_POST['type'];
    $description = $_POST['description'];
    $client = $_POST['client'];
    $manager = $_POST['manager'];
    $insertquery = "INSERT INTO projects (name, start_date, due_date, currency_code, status, type, description, client_id, manager_id) 
                    VALUES ('$name', '$st', '$dd', '$currencycode', '$status', '$type', '$description', '$client', '$manager')";
    if (mysqli_query($conn, $insertquery)) {
        header('Location: ' . BASE_URL . './projects/index.php');
    } else {
        $errorMessage = mysqli_error($conn);
    }
}
$clients = mysqli_query($conn, "SELECT * FROM `clients` ");
$managers = mysqli_query($conn, "SELECT * FROM `users` WHERE `role`='manager' ");
?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box  p-0 d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Add New Project </h4>
        </div>
    </div>
</div>
<div class="card">
    <form method="POST" name="project-form" id="project-form" class="p-3" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="name">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="name" required minlength="2">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="client">Client<span class="text-danger">*</span></label>
                    <select id="client" class="form-select" name="client" required>
                        <option value="" selected disabled>Select Client</option>
                        <?php
                        while ($client = mysqli_fetch_assoc($clients)) {
                            echo '<option value="' . $client['id'] . '">' . $client['name'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="manager">Manager<span class="text-danger">*</span></label>
                    <select id="manager" class="form-select" name="manager" required>
                        <option value="" selected disabled>Select Manager</option>
                        <?php
                        while ($manager = mysqli_fetch_assoc($managers)) {
                            echo '<option value="' . $manager['id'] . '">' . $manager['name'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="type">Type<span class="text-danger">*</span></label>
                                <select class="form-select"  id="type" name="type" required>
                                    <option value="" selected disabled>Select Type</option>
                                    <option value="hourlyrate">Hourly</option>
                                    <option value="fixed">Fixed</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="currencycode">Currency Code<span class="text-danger">*</span></label>
                            <select class="form-select"  id="currency-code" name="currencycode" required>
                                <option value="" selected disabled>Select Currency Code</option>
                                <option value="INR">INR</option>
                                <option value="USD">USD</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Start Date<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="st" id="st" required autocomplete="off">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Due Date</label>
                    <input type="text" class="form-control" name="dd" id="dd" autocomplete="off">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="status">Status<span class="text-danger">*</span></label>
                    <select class="form-select"  id="s" name="status" required>
                        <option value="" selected disabled>Select Status</option>
                        <option value="Planned">Planned</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Completed">Completed</option>
                        <option value="On Hold">On Hold</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="description">Description<span class="text-danger">*</span></label>
                    <textarea class="form-control" name="description" id="description" required></textarea>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary" name="add_project">Add Project</button>
    </form>
</div>
<script>
    $(document).ready(function() {
        $("#st").datepicker();
        $("#dd").datepicker();
        $('#manager').select2();
        $('#client').select2();
        $('#s').select2();
        $('#type').select2();
        $('#currency-code').select2();
        $('#project-form').validate({
            rules: {
                name: {
                    required: true,
                    minlength: 2
                },
                client: {
                    required: true
                },
                manager: {
                    required: true
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
                client: {
                    required: "Please select a client"
                },
                manager: {
                    required: "Please select a manager"
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