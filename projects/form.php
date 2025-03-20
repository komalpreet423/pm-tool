<div class="card">
    <form method="POST" name="project-form" id="project-form" class="p-3" enctype="multipart/form-data">
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
                        <option value="" disabled>Select Client</option>
                        <?php
                        $selectedClientId = isset($existingClientId) ? $existingClientId : null;
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
                        while ($manager = mysqli_fetch_assoc($managers)) {
                            echo '<option value="' . $manager['id'] . '">' . $manager['name'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="type">Type<span class="text-danger">*</span></label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="" selected disabled>Select Type</option>
                                    <option value="hourly">Hourly</option>
                                    <option value="fixed">Fixed</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="currencycode">Currency Code<span class="text-danger">*</span></label>
                            <select class="form-select" id="currency-code" name="currencycode" required>
                                <option value="" selected disabled>Select Currency Code</option>
                                <option value="INR">INR</option>
                                <option value="USD">USD</option>
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
                        <div class="mb-3">
                            <label>Start Date<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="startdate" id="startdate" required autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Due Date</label>
                        <input type="text" class="form-control" name="duedate" id="duedate" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="status">Status<span class="text-danger">*</span></label>
                    <select class="form-select" id="s" name="status" required>
                        <option value="" selected disabled>Select Status</option>
                        <option value="Planned">Planned</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Completed">Completed</option>
                        <option value="On Hold">On Hold</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="mb-3">
                <label for="description">Description<span class="text-danger">*</span></label>
                <textarea class="form-control" name="description" id="description" required></textarea>
            </div>
        </div>
        <button type="submit" class="btn btn-primary" name="add_project">Add Project</button>
    </form>
</div>