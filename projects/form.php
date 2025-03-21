<?php
$clients = mysqli_query($conn, "SELECT * FROM `clients`");
$managers = mysqli_query($conn, "SELECT * FROM `users` WHERE `role`='manager' ");
?>
<div class="card">
    <form method="POST" name="project-form" id="project-form" class="p-3" enctype="multipart/form-data">
        <input type="hidden" name="project_id" value="<?php echo isset($row['id']) ? $row['id'] : ''; ?>">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="name">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="name" required minlength="2"
                        value="<?php echo isset($row['name']) ? $row['name'] : ''; ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="client">Client<span class="text-danger">*</span></label>
                    <select id="client" class="form-select" name="client" required>
                        <option value="" selected disabled>Select Client</option>
                        <?php
                        $selectedClientId = isset($existingClientId) ? $existingClientId : (isset($row['client_id']) ? $row['client_id'] : null);
                        while ($client = mysqli_fetch_assoc($clients)) {
                            $selected = ($client['id'] == $selectedClientId) ? 'selected' : '';
                            echo '<option value="' . $client['id'] . '" ' . $selected . '>' . $client['name'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="manager">Manager<span class="text-danger">*</span></label>
                    <select id="manager" class="form-select" name="manager" required>
                        <option value="" selected disabled>Select Manager</option>
                        <?php
                        $selectedManagerId = isset($row['manager_id']) ? $row['manager_id'] : null;
                        while ($manager = mysqli_fetch_assoc($managers)) {
                            $selected = ($manager['id'] == $selectedManagerId) ? 'selected' : '';
                            echo '<option value="' . $manager['id'] . '" ' . $selected . '>' . $manager['name'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="type">Type<span class="text-danger">*</span></label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="" selected disabled>Select Type</option>
                                <option value="hourly" <?php echo isset($row['type']) && $row['type'] == 'hourly' ? 'selected' : ''; ?>>Hourly</option>
                                <option value="fixed" <?php echo isset($row['type']) && $row['type'] == 'fixed' ? 'selected' : ''; ?>>Fixed</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="currencycode">Currency Code<span class="text-danger">*</span></label>
                            <select class="form-select" id="currency-code" name="currencycode" required>
                                <option value="" selected disabled>Select Currency Code</option>
                                <option value="INR" <?php echo isset($row['currency_code']) && $row['currency_code'] == 'INR' ? 'selected' : ''; ?>>INR</option>
                                <option value="USD" <?php echo isset($row['currency_code']) && $row['currency_code'] == 'USD' ? 'selected' : ''; ?>>USD</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6">
                        <label>Start Date<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="start_date" id="start_date" required autocomplete="off"
                            value="<?php echo isset($row['start_date']) ? $row['start_date'] : ''; ?>">
                    </div>
                    <div class="col-md-6">
                        <label>Due Date</label>
                        <input type="text" class="form-control" name="due_date" id="due_date" autocomplete="off"
                            value="<?php echo isset($row['due_date']) ? $row['due_date'] : ''; ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="status">Status<span class="text-danger">*</span></label>
                    <select class="form-select" id="project-status" name="status" required>
                        <option value="" selected disabled>Select Status</option>
                        <option value="Planned" <?php echo isset($row['status']) && $row['status'] == 'planned' ? 'selected' : ''; ?>>Planned</option>
                        <option value="In Progress" <?php echo isset($row['status']) && $row['status'] == 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                        <option value="Completed" <?php echo isset($row['status']) && $row['status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                        <option value="On Hold" <?php echo isset($row['status']) && $row['status'] == 'on_hold' ? 'selected' : ''; ?>>On Hold</option>
                        <option value="Cancelled" <?php echo isset($row['status']) && $row['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="mb-3">
                <label for="description">Description<span class="text-danger">*</span></label>
                <textarea class="form-control" name="description" id="description" required><?php echo isset($row['description']) ? $row['description'] : ''; ?></textarea>
            </div>
        </div>
        <input type="hidden" name="project_id" value="<?php echo isset($row['id']) ? $row['id'] : ''; ?>">
        <button type="submit" class="btn btn-primary" name=<?php echo isset($row['id']) ? 'edit_project' : 'add_project'; ?>>
            <?php echo isset($row['id']) ? 'Update' : 'Submit'; ?>
        </button>
    </form>
</div>
<script>
    $(document).ready(function() {
        $("#start_date, #due_date").datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
        $('#manager, #client, #project-status, #type, #currency-code').select2();
        $('#description').summernote();
        $('#project-form').validate({
            rules: {
                name: {
                    required: true,
                    minlength: 2
                },
                client: {
                    required: true
                },
                manager: {
                    required: true
                },
                type: {
                    required: true
                },
                currencycode: {
                    required: true
                },
                start_date: {
                    required: true,
                    date: true
                },
                due_date: {
                    date: true
                },
                status: {
                    required: true
                },
                description: {
                    required: true,
                    minlength: 10
                }
            },
            messages: {
                name: {
                    required: "Please enter the project name",
                    minlength: "The name must be at least 2 characters long"
                },
                client: {
                    required: "Please select a client"
                },
                manager: {
                    required: "Please select a manager"
                },
                type: {
                    required: "Please select the project type"
                },
                currencycode: {
                    required: "Please select a currency code"
                },
                start_date: {
                    required: "Please select a start date",
                    date: "Please enter a valid start date"
                },
                due_date: {
                    date: "Please enter a valid due date"
                },
                status: {
                    required: "Please select the project status"
                },
                description: {
                    required: "Please provide a description",
                    minlength: "Description must be at least 10 characters long"
                }
            }
        });
    });
</script>