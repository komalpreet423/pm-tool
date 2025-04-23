<?php 
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (isset($_SESSION['toast'])):
?>
<script>
    // alert("<?= $_SESSION['toast'] ?>");
</script>
<?php
    unset($_SESSION['toast']);
?>
<?php endif; ?>
<?php require_once './includes/header.php'; ?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Dashboard</h4>
        </div>
    </div>
    <div class="col-xl-12">
        <div class="row">
            <div class="col-md-4">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium fs-3">Employees</p>
                                <h4 class="mb-0"> <?php $count = "SELECT COUNT(*)  FROM users ";
                                                    $result = mysqli_query($conn, $count);
                                                    $row = mysqli_fetch_array($result);
                                                    $totalUsers = $row[0];
                                                    ?>
                                    <?php echo $totalUsers; ?>
                                </h4>
                            </div>
                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                    <span class="avatar-title">
                                        <i class="bx bxs-user fs-2"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium fs-3">Projects</p>
                                <h4 class="mb-0"> <?php $count = "SELECT COUNT(*)  FROM projects ";
                                                    $result = mysqli_query($conn, $count);
                                                    $row = mysqli_fetch_array($result);
                                                    $totalUsers = $row[0];
                                                    ?>
                                    <?php echo $totalUsers; ?>
                                </h4>
                            </div>
                            <div class="flex-shrink-0 align-self-center ">
                                <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                    <span class="avatar-title rounded-circle bg-primary">
                                        <i class="bx bx-briefcase-alt-2 fs-2"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium fs-3">clients</p>
                                <h4 class="mb-0"> <?php $count = "SELECT COUNT(*)  FROM clients ";
                                                    $result = mysqli_query($conn, $count);
                                                    $row = mysqli_fetch_array($result);
                                                    $totalUsers = $row[0];
                                                    ?>
                                    <?php echo $totalUsers; ?>
                                </h4>
                            </div>

                            <div class="flex-shrink-0 align-self-center">
                                <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                    <span class="avatar-title rounded-circle bg-primary">
                                        <i class="bx bxs-user fs-2"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

$currentDate = date('Y-m-d');
$milestones = [];

$sql = "SELECT pm.milestone_name, pm.due_date, pm.status, p.name AS project_name
FROM project_milestones pm
JOIN projects p ON pm.project_id = p.id
WHERE pm.due_date <= '$currentDate'";


$query = mysqli_query($conn, $sql);

$milestones = [];
if ($query) {
    while ($row = mysqli_fetch_assoc($query)) {
        $milestones[] = $row;
    }
}  ?>

<div class="row">
    <div class="col-12">
        <div class="page-title-box pb-3 d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Due Dates Of Projects</h4>
        </div>
    </div>
    <div class="card p-3">
        <div class="card-body">
            <table class="table table-sm" id="daily-report">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Project Name</th>
                        <th>Name</th>
                        <th>Due Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($milestones as $key => $row) { ?>
                        <tr>
                            <td><?php echo $key + 1; ?></td>
                            <td><?php echo $row['project_name']; ?></td>
                            <td><?php echo $row['milestone_name']; ?></td>
                            <td><?php echo $row['due_date']; ?></td>
                            <td>
                                <span class="badge bg-<?php echo ($row['status'] == 'completed') ? 'success' : (($row['status'] == 'in_progress') ? 'warning' : 'secondary'); ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $row['status'])); ?>
                                </span>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>



<script>
    $(document).ready(function() {
        $('#daily-report').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "lengthMenu": [10, 25, 50, 100],
            "autoWidth": false
        });
    });
</script>



<?php require_once './includes/footer.php'; ?>