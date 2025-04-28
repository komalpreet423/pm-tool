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

<?php foreach ($milestones as $key => $row): ?>
    <?php
    $status = $row['status'];
    if ($status === 'not_started' || $status === 'completed') {
        continue;
    }
    $alertClass = ($status === 'in_progress') ? 'warning' : 'secondary';
    $displayStatus = ($status === 'in_progress') ? 'Pending' : ucfirst(str_replace('_', ' ', $status));
    ?>
    <div class="alert alert-<?php echo $alertClass; ?> mb-3" role="alert">
         <?php echo htmlspecialchars($row['project_name']); ?>'s
        milestone <?php echo htmlspecialchars($row['milestone_name']); ?> is
        due on <?php echo htmlspecialchars($row['due_date']); ?>  

    </div>
<?php endforeach; ?>




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