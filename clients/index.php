<?php require_once '../includes/header.php'; ?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box pb-2 d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Clients</h4>
            <a href="./create.php" class="btn btn-primary">Add Client</a>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <?php
        $sql = "SELECT * FROM clients";
        $query = mysqli_query($conn, $sql);
        if ($num = mysqli_num_rows($query) > 0) {
            $clients = mysqli_fetch_all($query, MYSQLI_ASSOC);
        ?>
            <div class="container">
                <table class="table table-striped" id="clientTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Address</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($clients as $key => $row) {
                        ?>
                            <tr>
                                <td><?php echo $key + 1; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['phone']; ?></td>
                                <td><?php echo $row['address']; ?></td>
                                <td><a href='./edit.php?id=<?php echo $row['id']; ?>' class="btn btn-success">Update</a>
                                    <button class="btn btn-danger delete-btn" data-table-name="clients" data-id="<?php echo $row['id'] ?>">Delete</button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
    </div>
</div>
<?php } ?>

<script>
    $(document).ready(function() {
        $('#clientTable').DataTable({
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