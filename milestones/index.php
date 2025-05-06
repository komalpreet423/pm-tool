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
            $sql = "SELECT pm.*, p.name as project_name, p.hourly_rate 
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
                                <a href="./edit.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">
                                    <i class="bx bx-edit fs-5"></i>
                                </a>

                                <button class="btn btn-danger delete-btn btn-sm" data-table-name="project_milestones" data-id="<?php echo $row['id']; ?>">
                                    <i class="bx bx-trash fs-5"></i>
                                </button>

                                <?php if ($row['status'] == 'completed') { ?>
                                    <a href="download.php?id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">
                                        <i class="fa fa-download"></i>
                                    </a>
                                <?php } ?>

                                <button
                                    type="button"
                                    class="btn btn-info btn-sm request-btn"
                                    data-bs-toggle="modal"
                                    data-bs-target="#hourlyRateModal"
                                    data-hourly-rate="<?php echo htmlspecialchars($row['hourly_rate']); ?>"
                                    data-currency-code="<?php echo htmlspecialchars($row['currency_code']); ?>"> <!-- Add currency code -->
                                    <i class="bx bx-time"></i> Request
                                </button>

                            </td>

                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>


    <!-- Hourly Rate Modal -->
    <div class="modal fade" id="hourlyRateModal" tabindex="-1" aria-labelledby="hourlyRateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                   <label for="">Due Dates</label>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- Amount Field -->
                    <div class="mb-3">
                        <?php $currency_code = $row['currency_code']; // e.g., 'INR', 'USD'
                        $currency_symbols = [
                            'INR' => '₹',
                            'USD' => '$',
                            // Add more if needed
                        ];

                        $currency_symbol = isset($currency_symbols[$currency_code]) ? $currency_symbols[$currency_code] : '';

                        ?>
                        <label for="amount" class="form-label">Amount</label>
                        <div class="input-group">
                            <span class="input-group-text" id="currencySign">
                                <?php echo $currency_symbol; ?>
                            </span>
                            <input type="number" class="form-control" id="amount" name="amount" placeholder="Enter amount"
                                value="<?php echo isset($row['amount']) ? htmlspecialchars($row['amount']) : ''; ?>">
                        </div>
                    </div>


                    <!-- Due Date Field -->
                    <div class="mb-3">
                        <label for="due_date" class="form-label">Due Date</label>
                        <input type="text" class="form-control" id="due_date" name="due_date"
                            value="<?php echo  $row['due_date'];  ?>">
                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
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