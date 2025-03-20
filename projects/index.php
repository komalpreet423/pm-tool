<?php require_once '../includes/header.php'; ?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box p-2 d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Projects</h4>
            <a href="./create.php" class="btn btn-primary">Add Project</a>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
    <?php
    $sql = "SELECT * FROM projects";
    $query = mysqli_query($conn, $sql);
    if ($num = mysqli_num_rows($query) > 0) {
        $projects = mysqli_fetch_all($query, MYSQLI_ASSOC);
    ?>
        <table class="table table-striped" id="employeeTable">
            <thead>
                <th>#</th>
                <th>Name</th>
                <th>Start_date</th>
                <th>Due_date</th>
                <th>Description</th>
                <th>Type</th>
                <th>Action</th>
            </thead>
            <tbody>
                <?php
                foreach ($projects as $key => $row) {
                ?>
                    <tr>
                        <td><?php echo  $key + 1 ?></td>
                        <td><?php echo $row['name'] ?></td>
                        <td><?php echo $row['start_date'] ?></td>
                        <td><?php echo $row['due_date'] ?></td>
                        <td><?php echo $row['description'] ?></td>
                        <td><?php echo $row['type'] ?></td>
                        <td>
                            <a href='./edit.php?id=<?php echo $row['id'] ?>' class="btn btn-success">Update</a>
                            <button class="btn btn-danger delete-btn" data-table-name="projects" data-id="<?php echo $row['id'] ?>">Delete</button>
                        </td>
                    <?php  } ?>
            </tbody>
</div>
<?php } ?>
</div>
<script>
    $(document).ready(function() {
        $('#employeeTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "lengthMenu": [10, 25, 50, 100],
            "autoWidth": false
        });
    });
</script>
<?php require_once '../includes/footer.php'; ?>