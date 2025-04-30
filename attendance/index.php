<?php require_once '../includes/header.php';
$userProfile = userProfile(); ?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box pb-3 d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Attendance </h4>
            <?php if ($userProfile['role'] === 'admin' || $userProfile['role'] === 'hr') { ?>
                <a href="./create.php" class="btn btn-primary d-flex">
                    <i class="bx bx-plus me-1 fs-5"></i>Add Attendance
                </a>
            <?php } ?>  
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <?php
        $sql = "SELECT attendance.id, attendance.date, attendance.status, attendance.note, attendance.employee_id, users.name 
       FROM attendance 
       LEFT JOIN users ON attendance.employee_id = users.id";

        if ($userProfile['role'] !== 'admin' && $userProfile['role'] !== 'hr') {
            $userId = $userProfile['id'];
            $sql .= " WHERE attendance.employee_id = $userId";
        }

        $query = mysqli_query($conn, $sql);

        if ($userProfile['role'] === 'admin' || $userProfile['role'] === 'hr') {
            $sql = "SELECT attendance.*, users.name FROM attendance 
                    LEFT JOIN users ON attendance.employee_id = users.id";
        } else {
            $userId = $userProfile['id'];
            $sql = "SELECT attendance.*, users.name FROM attendance 
                    LEFT JOIN users ON attendance.employee_id = users.id
                    WHERE attendance.employee_id = $userId";
        }

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
                        <th>Note</th>
                        <?php if ($userProfile['role'] === 'admin' || $userProfile['role'] === 'hr') { ?>
                            <th>Action</th>
                        <?php } ?>

                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($clients as $key => $row): ?>
                        <tr>
                            <td><?php echo $key + 1; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['date']; ?></td>
                            <td>
                                <span class="badge bg-<?php
                                                        echo ($row['status'] == 'present') ? 'success' : (($row['status'] == 'short_leave') ? 'warning' : (($row['status'] == 'absent') ? 'danger' : (($row['status'] == 'late') ? 'info' : (($row['status'] == 'half_day') ? 'secondary' : 'dark'))));
                                                        ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $row['status'])); ?>
                                </span>
                            </td>
                            <td><?php echo $row['note']; ?></td>

                            <?php if ($userProfile['role'] === 'admin' || $userProfile['role'] === 'hr'): ?>
                                <td>
                                    <a href='./edit.php?id=<?php echo $row['id']; ?>' class="btn btn-primary btn-sm">
                                        <i class="bx bx-edit fs-5"></i>
                                    </a>
                                    <button class="btn btn-danger delete-btn btn-sm" data-table-name="attendance" data-id="<?php echo $row['id']; ?>">
                                        <i class="bx bx-trash fs-5"></i>
                                    </button>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>
    </div>
</div>
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