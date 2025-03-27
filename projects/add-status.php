<?php ob_start();
require_once '../includes/header.php'; ?>
<?php
if (isset($_POST['project_status'])) {
    $employee_id = 6;
    $project_id = $_GET['id'];
    $hours = $_POST['hours'];
    $status =  $_POST['status'];
    $query = "INSERT INTO project_status (employee_id, project_id, hours, status) VALUES ('$employee_id', '$project_id', '$hours', '$status')";
    if (mysqli_query($conn, $query)) {
        header('Location: ' . BASE_URL . './projects/index.php');
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
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="date">Hours</label>
                        <select class="form-control" name="hours" id="hours" required>
                            <option value="" selected disabled>Select Hours</option>
                            <?php  
                            $numbers = range(1, 9);
                            foreach ($numbers as $number) {
                                echo "<option value=\"$number\">$number</option>";
                            }                    
                            ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="status">Status</label>
                        <textarea class="form-control" name="status" id="status" required></textarea>
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
       
        $('#status').summernote();
    });
</script>
<?php require_once '../includes/footer.php'; ?>
