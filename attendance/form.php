<?php
$users = mysqli_query($conn, "SELECT * FROM `users`");
?>
<div class="card">
    <div class="card-body">
        <form method="POST" name="attendance-form" id="attendance-form" class="p-3" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="employee">Employee Name<span class="text-danger">*</span></label>
                        <select id="employee" class="form-select" name="employee" required>
                            <option value="" selected disabled>Select Employee</option>
                            <?php
                            $selectedemployeeid = isset($existingemployeeid) ? $existingemployeeid : (isset($row['employee_id']) ? $row['employee_id'] : null);

                            while ($Ausers = mysqli_fetch_assoc($users)) {
                                $selected = ($Ausers['id'] == $selectedemployeeid) ? 'selected' : '';
                                echo '<option value="' . $Ausers['id'] . '" ' . $selected . '>' . htmlspecialchars($Ausers['name']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="date">Date<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="date" id="date" autocomplete="off"
                            value="<?php echo isset($row['date']) ? $row['date'] : ''; ?>">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="note">Note<span class="text-danger">*</span></label>
                        <textarea class="form-control" name="note" id="note"><?php echo isset($row['note']) ? strip_tags($row['note']) : ''; ?></textarea>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="status">Status</label>
                        <select class="form-select" id="attendance-status" name="status">
                            <option value="" selected disabled>Select Status</option>
                            <option value="present" <?php echo (isset($row['status']) && $row['status'] == 'present') ? 'selected' : ''; ?>>Present</option>
                            <option value="short_leave" <?php echo (isset($row['status']) && $row['status'] == 'short_leave') ? 'selected' : ''; ?>>Short Leave</option>
                            <option value="absent" <?php echo (isset($row['status']) && $row['status'] == 'absent') ? 'selected' : ''; ?>>Absent</option>
                            <option value="late" <?php echo (isset($row['status']) && $row['status'] == 'late') ? 'selected' : ''; ?>>Late</option>
                            <option value="half_day" <?php echo (isset($row['status']) && $row['status'] == 'half_day') ? 'selected' : ''; ?>>Half Day</option>
                        </select>
                    </div>
                </div>
            </div>
            <input type="hidden" name="employee_id" value="<?php echo isset($employee_id) ? $employee_id : ''; ?>">
            <button type="submit" class="btn btn-primary" name="<?php echo isset($row['id']) ? 'edit_attendance' : 'add_attendance'; ?>">
                <?php echo isset($row['id']) ? 'Update' : 'Submit'; ?>
            </button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });

        $('#attendance-status, #employee').select2();

        $('#attendance-form').validate({
            rules: {
                employee: "required",
                date: "required",
                note: "required",
                status: "required",
            },
            messages: {
                employee: "Please select an employee",
                date: "Please select a date",
                note: "Please enter a note",
                status: "Please select a status"
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