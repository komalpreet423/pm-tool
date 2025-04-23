<div class="card-body">
    <form method="POST" name="leave-form" id="leave-form" class="p-3" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="leave-type">Leave Type</label>
                    <select class="form-select" id="leave_type" name="leave_type" required>
                        <option value="" selected disabled>Select Leave Type</option>
                        <option value="casual" <?php echo (isset($row['leave_type']) && $row['leave_type'] == 'casual') ? 'selected' : ''; ?>>Casual</option>
                        <option value="annual" <?php echo (isset($row['leave_type']) && $row['leave_type'] == 'annual') ? 'selected' : ''; ?>>Annual</option>
                        <option value="maternity" <?php echo (isset($row['leave_type']) && $row['leave_type'] == 'maternity') ? 'selected' : ''; ?>>Maternity</option>
                        <option value="paternity" <?php echo (isset($row['leave_type']) && $row['leave_type'] == 'paternity') ? 'selected' : ''; ?>>Paternity</option>
                        <option value="unpaid" <?php echo (isset($row['leave_type']) && $row['leave_type'] == 'unpaid') ? 'selected' : ''; ?>>Unpaid</option>
                    </select>
                </div>
            </div>
            <?php if ($userRole === 'admin' || $userRole === 'hr'): ?>
                <div class="col-md-6">
                    <?php
                    $currentStatus = isset($row['status']) ? $row['status'] : 'pending';
                    ?>
                    <label for="status">Status</label>
                    <select class="form-select" id="leave-status" name="status" required>
                        <option value="" disabled <?php echo $currentStatus == '' ? 'selected' : ''; ?>>Select Status</option>
                        <option value="pending" <?php echo $currentStatus == 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="approved" <?php echo $currentStatus == 'approved' ? 'selected' : ''; ?>>Approved</option>
                        <option value="rejected" <?php echo $currentStatus == 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                    </select>
                </div>

            <?php endif; ?>

            <?php if ($userRole === 'employee'): ?>
                <div class="col-md-3">
                    <label>Start Date<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="start_date" id="start_date" required autocomplete="off"
                        value="<?php echo isset($row['start_date']) ? $row['start_date'] : ''; ?>">
                </div>
                <div class="col-md-3">
                    <label>End Date<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="end_date" id="end_date" autocomplete="off"
                        value="<?php echo isset($row['end_date']) ? $row['end_date'] : ''; ?>">
                </div>
            <?php endif; ?>
        </div>

        <?php if ($userRole !== 'employee'): ?>
            <div class="row">
                <div class="col-md-6">
                    <label>Start Date<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="start_date" id="start_date" required autocomplete="off"
                        value="<?php echo isset($row['start_date']) ? $row['start_date'] : ''; ?>">
                </div>
                <div class="col-md-6">
                    <label>End Date<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="end_date" id="end_date" autocomplete="off"
                        value="<?php echo isset($row['end_date']) ? $row['end_date'] : ''; ?>">
                </div>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6 mt-1">
                <div class="mb-3">
                    <label for="reason">Reason<span class="text-danger">*</span></label>
                    <textarea class="form-control" name="reason" id="reason" required><?php echo isset($row['reason']) ? $row['reason'] : ''; ?></textarea>
                </div>
            </div>
        </div>


        <input type="hidden" name="employee_id" value="<?php echo isset($employee_id) ? $employee_id : ''; ?>">
        <button type="submit" class="btn btn-primary" name=<?php echo isset($row['id']) ? 'edit_leave' : 'add_leave'; ?>>
            <?php echo isset($row['id']) ? 'Update' : 'Submit'; ?>
        </button>

    </form>
</div>


<script>
    $(document).ready(function() {
        $('#start_date,#end_date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
        if ($('#leave-status').length) {
            $('#leave-status').select2();
        }
        $('#leave_type').select2();
        $('#leave-form').validate({
            rules: {
                leave_type: {
                    required: true
                },
                status: {
                    required: true
                },
                start_date: {
                    required: true,
                    date: true
                },
                end_date: {
                    required: true,
                    date: true
                },
                reason: {
                    required: true,

                }
            },
            messages: {
                leave_type: {
                    required: "Please select a leave type."
                },
                status: {
                    required: "Please select a status."
                },
                start_date: {
                    required: "Please enter the start date.",
                    date: "Please enter a valid date."
                },
                end_date: {
                    required: "Please enter the end date.",
                    date: "Please enter a valid date."
                },
                reason: {
                    required: "Please enter the reason for the leave.",
                }
            },
            errorPlacement: function(error, element) {
                if (element.hasClass('select2-hidden-accessible')) {
                    error.insertAfter(element.next('.select2'));
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function(element) {
                if ($(element).hasClass('select2-hidden-accessible')) {
                    $(element).removeClass('is-invalid');
                    $(element).next('.select2').find('.select2-selection').addClass('is-invalid');
                } else {
                    $(element).addClass('is-invalid');
                }
            },
            unhighlight: function(element) {
                if ($(element).hasClass('select2-hidden-accessible')) {
                    $(element).next('.select2').find('.select2-selection').removeClass('is-invalid');
                } else {
                    $(element).removeClass('is-invalid');
                }
            }

        });
    });
</script>