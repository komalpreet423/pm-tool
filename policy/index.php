<?php require_once '../includes/header.php';
$userProfile = userProfile(); ?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box pb-2 d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Policies</h4>
            <a href="./create.php" class="btn btn-primary">Add Policy</a>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <?php
        $sql = "SELECT * FROM policies";
        $query = mysqli_query($conn, $sql);
        $num = mysqli_num_rows($query);
        if ($num > 0) {

            $policies = mysqli_fetch_all($query, MYSQLI_ASSOC);
        } else {
            echo "<p class='text-muted'>No policies found.</p>";
        }
        ?>



        <table class="table table-striped" id="policyTable">
            <thead>
                <th>#</th>
                <th>Name</th>
                <th>Document</th>
                <th>Description</th>
                <th>Action</th>
            </thead>
            <tbody>
                <?php
                foreach ($policies as $key => $row) {
                ?>
                    <tr>
                        <td><?php echo  $key + 1 ?></td>
                        <td><?php echo $row['name'] ?></td>
                        <td><?php echo $row['file'] ?></td>
                        <td><?php echo $row['description'] ?></td>
                        <td>
                            <a href='./edit.php?id=<?php echo $row['id'] ?>' class="btn btn-success btn-sm"><i class="bx bx-edit fs-5"></i></a>
                            <button class="btn btn-danger btn-sm delete-btn" data-table-name="policies" data-id="<?php echo $row['id'] ?>"><i class="bx bx-trash fs-5"></i></button>
                        </td>
                    <?php  } ?>
            </tbody>
    </div>
    <?php  ?>
</div>
<script>
    $(document).ready(function() {
        $('#policyTable').DataTable({
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