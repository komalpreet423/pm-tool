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

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Expiery Data</h4>
        </div>
    </div>
    <div class="col-xl-12">
        <div class="row">


            <?php
            $currentDate = date('Y-m-d');
            $sql = "SELECT due_date FROM project_milestones WHERE due_date < '$currentDate'";
            $query = mysqli_query($conn, $sql);
            ?>
            <?php
            while ($row = mysqli_fetch_assoc($query)) {
            ?>
            <li><?php echo $row['due_date']; ?></li>
            <?php
            }
            ?>


        </div>
    </div>
</div>



<?php require_once './includes/footer.php'; ?>