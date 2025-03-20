<form method="POST" name="employee-form" id="employee-form" class="p-3" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="name">Name</label> <span class="text-danger">*</span>
                <input type="text" class="form-control" name="name" required minlength="2"
                    value="<?php echo isset($row['name']) ? $row['name'] : ''; ?>">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="email">Email</label> <span class="text-danger">*</span>
                <input type="email" class="form-control" name="email"
                    value="<?php echo isset($row['email']) ? $row['email'] : ''; ?>" required>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="phoneno">Phone Number</label> <span class="text-danger">*</span>
                <input type="number" class="form-control" name="phoneno"
                    value="<?php echo isset($row['phone_number']) ? $row['phone_number'] : ''; ?>"
                    required pattern="\d{10}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="gender">Gender</label>
                <select class="form-select" name="gender" required>
                    <option value="Male" <?php echo (isset($row['gender']) && $row['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?php echo (isset($row['gender']) && $row['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label>Date Of Birth</label> <span class="text-danger">*</span>
                <input type="text" class="form-control" name="dob" id="dob"
                    value="<?php echo isset($row['date_of_birth']) ? $row['date_of_birth'] : ''; ?>" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label>Date Of Joining</label> <span class="text-danger">*</span>
                <input type="text" class="form-control" name="doj" id="doj"
                    value="<?php echo isset($row['date_of_joining']) ? $row['date_of_joining'] : ''; ?>" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="address">Address</label> <span class="text-danger">*</span>
                <textarea class="form-control" name="address" id="address" required><?php echo isset($row['address']) ? $row['address'] : ''; ?></textarea>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="jobt">Job Title</label>
                <select class="form-select" name="jobt" required>
                    <option value="phpdeveloper" <?php echo (isset($row['job_title']) && $row['job_title'] == 'phpdeveloper') ? 'selected' : ''; ?>>PHP Developer</option>
                    <option value="frontendd" <?php echo (isset($row['job_title']) && $row['job_title'] == 'frontendd') ? 'selected' : ''; ?>>Frontend Developer</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="role">Role</label>
                <select class="form-select" name="role" required>
                    <option value="Admin" <?php echo (isset($row['role']) && $row['role'] == 'Admin') ? 'selected' : ''; ?>>Admin</option>
                    <option value="Manager" <?php echo (isset($row['role']) && $row['role'] == 'Manager') ? 'selected' : ''; ?>>Manager</option>
                    <option value="HR" <?php echo (isset($row['role']) && $row['role'] == 'HR') ? 'selected' : ''; ?>>HR</option>
                    <option value="employee" <?php echo (isset($row['role']) && $row['role'] == 'employee') ? 'selected' : ''; ?>>Employee</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="status">Status</label>
                <select class="form-select" name="status" required>
                    <option value="Active" <?php echo (isset($row['status']) && $row['status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
                    <option value="Inactive" <?php echo (isset($row['status']) && $row['status'] == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="password">Password</label> <span class="text-danger">*</span>
                <input type="password" class="form-control" name="password" id="password">
            </div>
        </div>
    </div>

    <input type="hidden" name="employee_id" value="<?php echo isset($row['id']) ? $row['id'] : ''; ?>">

    <button type="submit" class="btn btn-primary" name="<?php echo isset($row['id']) ? 'edit-employee' : 'add_employee'; ?>">
        <?php echo isset($row['id']) ? 'Update' : 'Submit'; ?>
    </button>
</form>

<script>
    $(document).ready(function() {
        $("#dob").datepicker();
        $("#doj").datepicker();
        $('#role').select2();
        $('#jobt').select2();
        $('#gender').select2();
        $('#status').select2();

        var isEditMode = $("input[name='employee_id']").val() !== "";

        $('#employee-form').validate({
            rules: {
                name: "required",
                email: {
                    required: true,
                    email: true
                },
                phoneno: {
                    required: true,
                    minlength: 10,
                    maxlength: 10,
                    digits: true
                },
                address: "required",
                dob: "required",
                doj: "required",
                password: {
                    <?php if (!isset($row['id'])) { ?>
                        required: true, 
                    <?php } ?>
                    minlength: 6
                }
            },
            messages: {
                name: "Please enter employee name",
                email: "Please enter a valid email address",
                phoneno: {
                    required: "Please enter a 10-digit phone number",
                    minlength: "Phone number must be exactly 10 digits",
                    maxlength: "Phone number must be exactly 10 digits",
                    digits: "Phone number can only contain digits"
                },
                address: "Please enter an address.",
                dob: "Please enter Date Of Birth",
                doj: "Please enter Date Of Joining",
                password: {
                    required: "Please enter a password with at least 6 characters",
                    minlength: "Password must be at least 6 characters"
                }
            }
        });

    });
</script>