<?php require_once '../includes/header.php'; ?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box pb-2 d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Leaves</h4>
            <a href="./create.php" class="btn btn-primary d-flex"><i class="bx bx-plus me-1 fs-5"> </i>Add Leave</a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php
        $sql = "SELECT * FROM leaves";
        $query = mysqli_query($conn, $sql);
        if ($num = mysqli_num_rows($query) > 0) {
            $leaves = mysqli_fetch_all($query, MYSQLI_ASSOC);

            function canShowAction($userRole, $status)
            {
                if (in_array($userRole, ['admin', 'hr'])) return true;
                return $status === 'pending';
            }
        ?>
            <table class="table" id="leavesTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Leave Type</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Reason</th>
                        <?php
                        $userProfile = userProfile();
                        $userId = $userProfile['id'];
                        $userRole = $userProfile['role'];
                        if ($userRole === 'admin' || $userRole === 'hr' || array_filter($leaves, fn($row) => $row['status'] === 'pending')): ?>
                            <th>Action</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($leaves as $key => $row): ?>
                        <tr>
                            <td><?php echo $key + 1 ?></td>
                            <td><?php echo $row['leave_type'] ?></td>
                            <td><?php echo $row['start_date'] ?></td>
                            <td><?php echo $row['end_date'] ?></td>
                            <td><?php echo ucfirst($row['status']) ?></td>
                            <td><?php echo $row['reason'] ?></td>
                            <?php if (canShowAction($userRole, $row['status'])): ?>
                                <td>
                                    <a href='./edit.php?id=<?php echo $row['id'] ?>' class="btn btn-primary btn-sm"><i class="bx bx-edit fs-5"></i></a>
                                    <button class="btn btn-danger btn-sm delete-btn" data-table-name="leaves" data-id="<?php echo $row['id'] ?>"><i class="bx bx-trash fs-5"></i></button>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#leavesTable').DataTable({
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