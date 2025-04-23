<?php ob_start();
require_once '../includes/header.php';
?>

<?php
if (isset($_POST['project_status'])) {
    $employee_id = 1; 

    $project_id = $_GET['id'];
    $chargable_hours = $_POST['chargable_hours'];
    $non_chargable_hours = isset($_POST['non_chargable_hours']) ? $_POST['non_chargable_hours'] : 0;

    $update = $_POST['update'];
    $query = "INSERT INTO project_status (employee_id, project_id, chargable_hours, non_chargable_hours, `update`) 
              VALUES ('$employee_id', '$project_id', '$chargable_hours', '$non_chargable_hours', '$update')";

    if (mysqli_query($conn, $query)) {
        header('Location: ' . BASE_URL . '/projects/index.php');
        exit();
    } else {
        $errorMessage = mysqli_error($conn);
        echo "<div class='alert alert-danger'>Error: $errorMessage</div>";
    }
}
?>

<div class="row">
    <div class="col-12">
        <div class="page-title-box pb-3 d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Add Status</h4>
            <a href="./index.php" class="btn btn-primary d-flex"><i class="bx bx-left-arrow-alt me-1 fs-4"></i>Go Back</a>
        </div>
    </div>
</div>
<?php
$sql = "SELECT * FROM projects WHERE id = " . $_GET['id'];
$query = mysqli_query($conn, $sql);
if (mysqli_num_rows($query) > 0) {
    $project = mysqli_fetch_assoc($query);
?>
    <div class="card ">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name">Project Name: <?php echo $project['name']; ?></label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name"><?php echo $project['description']; ?></label>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>
<div class="card">
    <div class="card-body">
        <form method="POST">
            <div class="row ">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="chargable_hours">Chargable Hours</label>
                        <select class="form-control" name="chargable_hours" id="chargable_hours">
                            <option value="" selected disabled>Select Hours</option>
                            <?php
                            $numbers = range(1, 9);
                            foreach ($numbers as $number) {
                                echo "<option value=\"$number\">$number</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <?php if ($project['type'] == 'hourly') { ?>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="non_chargable_hours">Non Billable Hours</label>
                            <select class="form-control" name="non_chargable_hours" id="non_chargable_hours" required>
                                <option value="" selected disabled>Select Hours</option>
                                <?php
                                $numbers = range(1, 9);
                                foreach ($numbers as $number) {
                                    echo "<option value=\"$number\">$number</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                <?php }  ?>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="update">Update</label>
                        <textarea class="form-control" name="update" id="update" required></textarea>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary" name="project_status">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $(function() {
        $('#update').summernote();
        $('#non_chargable_hours,#chargable_hours').select2();
    });
</script>
<?php require_once '../includes/footer.php'; ?>