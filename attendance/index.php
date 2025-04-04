<?php require_once '../includes/header.php';
$userProfile = userProfile(); ?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box pb-3 d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Attendance </h4>
            <a href="./create.php" class="btn btn-primary d-flex"><i class="bx bx-plus me-1 fs-5"> </i>Add Attendance </a>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <?php
        $sql = "SELECT * FROM attendance ";
        $query = mysqli_query($conn, $sql);
        if ($num = mysqli_num_rows($query) > 0) {
            $clients = mysqli_fetch_all($query, MYSQLI_ASSOC);
        ?>
            <div class="container">
                <table class="table table-sm" id="attendanceTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Note </th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($clients as $key => $row) {
                        ?>
                            <tr>
                            <td><?php echo $key + 1; ?></td>
                            <td><?php echo $userProfile['name']; ?></td>
                               <td><?php echo $row['date']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                                 <td><?php echo $row['note']; ?></td>
                                       <td>
                              <?php if ($userProfile['role'] === 'admin' || $userProfile['role'] === 'hr') { ?>
                              <a href='./edit.php?id=<?php echo $row['id']; ?>' class="btn btn-primary btn-sm">
                                <i class="bx bx-edit fs-5"></i>
                                      </a>
                                <button class="btn btn-danger delete-btn btn-sm" data-table-name="attendance" data-id="<?php echo $row['id'] ?>">
                                    <i class="bx bx-trash fs-5"></i>
                                           </button>
                                  <?php } else { ?>
                               <span class="text-muted">No action available</span>
                              <?php } ?>
                              </td>
                    </tbody>
                </table>
            </div>
    </div>
</div>
<?php }} ?>
<script>
    $(document).ready(function() {
        $('#attendanceTable').DataTable({
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














