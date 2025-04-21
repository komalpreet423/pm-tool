<?php require_once '../includes/header.php'; ?>

<div class="row">
    <div class="col-12">
        <div class="page-title-box pb-3 d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Milestones</h4>
            <a href="./create.php" class="btn btn-primary d-flex"><i class="bx bx-plus me-1 fs-5"> </i>Add Milestone</a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php
        $sql = "SELECT pm.*, p.name as project_name 
                FROM project_milestones pm
                JOIN projects p ON pm.project_id = p.id";
        $query = mysqli_query($conn, $sql);
            $milestones = mysqli_fetch_all($query, MYSQLI_ASSOC);
        ?>
            <table class="table table-sm" id="milestoneTable">
                <thead>
                    <th>#</th>
                    <th>Project Name</th>
                    <th>Name</th>
                    <th>Due Date</th>
                    <th>Amount</th>
                    <th>Currency</th>
                    <th>Status</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    <?php foreach ($milestones as $key => $row) { ?>
                        <tr>
                            <td><?php echo $key + 1; ?></td>
                            <td><?php echo $row['project_name']; ?></td>
                            <td><?php echo $row['milestone_name']; ?></td>
                            <td><?php echo $row['due_date']; ?></td>
                            <td><?php echo $row['amount'] ? number_format($row['amount'], 2) : '-'; ?></td>
                            <td><?php echo $row['currency_code']; ?></td>
                            <td>
                                <span class="badge bg-<?php echo ($row['status'] == 'completed') ? 'success' : (($row['status'] == 'in_progress') ? 'warning' : 'secondary'); ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $row['status'])); ?>
                                </span>
                            </td>
                            <td>
                                <a href='./edit.php?id=<?php echo $row['id']; ?>' class="btn btn-primary btn-sm"><i class="bx bx-edit fs-5"></i></a>
                                <button class="btn btn-danger delete-btn btn-sm" data-table-name="project_milestones" data-id="<?php echo $row['id']; ?>"><i class="bx bx-trash fs-5"></i></button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#milestoneTable').DataTable({
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