<?php
require_once '../includes/header.php'; // if not already included
require_once '../includes/db.php';     // database connection

if (isset($_POST['submit_attendance'])) {
    $date = $_POST['date'];
    $employee_ids = $_POST['employee_id'];
    $statuses = $_POST['status'];
    $notes = $_POST['note'];

    // Basic validation
    if (!empty($date) && is_array($employee_ids)) {
        foreach ($employee_ids as $index => $employee_id) {
            $status = mysqli_real_escape_string($conn, $statuses[$index]);
            $note = mysqli_real_escape_string($conn, $notes[$index]);

            // Optional: Check for duplicate entry for same user and date before insert
            $checkQuery = "SELECT * FROM attendance WHERE employee_id = $employee_id AND date = '$date'";
            $checkResult = mysqli_query($conn, $checkQuery);

            if (mysqli_num_rows($checkResult) == 0) {
                $insertQuery = "INSERT INTO attendance (employee_id, status, note, date)
                                VALUES ($employee_id, '$status', '$note', '$date')";
                mysqli_query($conn, $insertQuery);
            } else {
                // Optional: You could update instead of skipping
                $updateQuery = "UPDATE attendance SET status = '$status', note = '$note'
                                WHERE employee_id = $employee_id AND date = '$date'";
                mysqli_query($conn, $updateQuery);
            }
        }
        header("Location: index.php");
        exit();
    }
}
if ($userProfile['role'] === 'admin' || $userProfile['role'] === 'hr') {
    $usersQuery = "SELECT id, name FROM users";
} else {
    $userId = $userProfile['id'];
    $usersQuery = "SELECT id, name FROM users WHERE id = $userId";
}

$result = mysqli_query($conn, $usersQuery);
$users = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<form method="post" id="attendance-form">
    <div class="col-md-2">
        <div class="mb-3">
            <label for="date">Date:</label>
            <input type="text" class="form-control" name="date" id="date" required
                value="<?php echo date('Y-m-d'); ?>" autocomplete="off">
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="container">
                <form method="post" id="attendance-form">
                    <table class="table table-sm" id="attendanceTable">
                        <thead>
                            <tr>
                                <th>#</th> 
                                <th>Name</th>
                                <th>Status</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $key => $user): ?>
                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <td>
                                        <?php echo $user['name']; ?>
                                        <input type="hidden" name="employee_id[]" value="<?php echo $user['id']; ?>">
                                    </td>
                                    <td>
                                        <select class="form-control status-select" name="status[]">
                                            <option value="present">Present</option>
                                            <option value="absent">Absent</option>
                                            <option value="late">Late</option>
                                            <option value="half_day">Half Day</option>
                                            <option value="short_leave">Short Leave</option>
                                        </select>

                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="note[]" placeholder="Optional note">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <button type="submit" class="btn btn-primary" name="submit_attendance">Save</button>
                </form>
            </div>
        </div>
    </div>
</form>
<!-- JavaScript Section -->
<script>
    $(document).ready(function() {
        // Datepicker setup - only allow past and current dates
        $('#date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            endDate: new Date(),
            daysOfWeekDisabled: [0, 6]
        });

        // Enhance select boxes
        $('.status-select').select2({
            width: '100%'
        });

        // jQuery Validation
        $('#attendance-form').validate({
            rules: {
                'date': "required",
                'status[]': {
                    required: true
                },
            },
            messages: {
                'status[]': "Please select a status",
                'date': "Please select a date"
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