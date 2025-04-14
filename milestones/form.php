<form method="POST" name="milestone-form" id="milestone-form" class="p-3" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-3">
            <div class="mb-3">
                <label for="project_id">Project</label> <span class="text-danger">*</span>
                <select class="form-select" name="project_id">
                    <option value="">Select Project</option>
                    <?php
                    $projectQuery = mysqli_query($conn, "SELECT id, name FROM projects WHERE type = 'fixed'");
                    while ($project = mysqli_fetch_assoc($projectQuery)) {
                        $selected = (isset($row['project_id']) && $row['project_id'] == $project['id']) ? 'selected' : '';
                        echo "<option value='{$project['id']}' $selected>{$project['name']}</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3">
                <label for="budget">Budget</label>
                <input type="number" step="0.01" class="form-control" name="budget"
                    value="<?php echo isset($row['budget']) ? $row['budget'] : ''; ?>">
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3">
                <label for="currency_code">Currency</label>
                <select class="form-select" name="currency_code" required>
                    <option value="INR" <?php echo (isset($row['currency_code']) && $row['currency_code'] == 'INR') ? 'selected' : ''; ?>>INR</option>
                    <option value="USD" <?php echo (isset($row['currency_code']) && $row['currency_code'] == 'USD') ? 'selected' : ''; ?>>USD</option>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3">
                <label for="status">Status</label>
                <select class="form-select" name="status" required>
                    <option value="not_started" <?php echo (isset($row['status']) && $row['status'] == 'not_started') ? 'selected' : ''; ?>>Not Started</option>
                    <option value="in_progress" <?php echo (isset($row['status']) && $row['status'] == 'in_progress') ? 'selected' : ''; ?>>In Progress</option>
                    <option value="completed" <?php echo (isset($row['status']) && $row['status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="milestone_name">Milestone Name</label> <span class="text-danger">*</span>
                <input type="text" class="form-control" name="milestone_name" required minlength="2"
                    value="<?php echo isset($row['milestone_name']) ? $row['milestone_name'] : ''; ?>">
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3">
                <label for="due_date">Due Date</label> <span class="text-danger">*</span>
                <input type="text" class="form-control" name="due_date" id="due_date" required
                    value="<?php echo isset($row['due_date']) ? $row['due_date'] : ''; ?>" autocomplete="off">
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3">
                <label for="completed_date">Completed Date</label>
                <input type="text" class="form-control" name="completed_date" id="completed_date"
                    value="<?php echo isset($row['completed_date']) ? $row['completed_date'] : ''; ?>" autocomplete="off">
            </div>
        </div>
    </div>


    <div class="mb-3">
        <label for="description">Description<span class="text-danger">*</span></label>
        <textarea class="form-control" name="description" id="description" ><?php echo isset($row['description']) ? $row['description'] : ''; ?></textarea>
    </div>
    <div class="mb-3">
        <label for="milestone_documents">Upload Files</label>
        <input type="file" class="form-control" id="milestone_documents" name="milestone_documents[]" multiple
            accept="image/*, .doc, .docx, .txt, .pdf, .mp4, .avi, .mov">
        <small class="text-muted">Allowed file types: Images, DOC, TXT, PDF, Videos</small>
    </div>
    <?php if (isset($row['milestone_id']) && !empty($row['milestone_id'])): ?>
        <?php
        $filesQuery = mysqli_query($conn, "SELECT * FROM milestone_documents WHERE milestone_id = '{$row['milestone_id']}'");

        if (mysqli_num_rows($filesQuery) > 0) {
            echo "<h5>Uploaded Files:</h5><ul>";
            while ($file = mysqli_fetch_assoc($filesQuery)) {
                $fileId = $file['id'];
                $filePath = $file['file_path'];
                echo "<li>
                <a href='$filePath' target='_blank'>" . basename($filePath) . "</a>
                <a href='#' class='btn btn-sm btn-danger ms-2 m-1 delete-file' data-id='$fileId'>Delete</a>
            </li>";
            }
            echo "</ul>";
        }
        ?>
    <?php endif; ?>
    <input type="hidden" name="milestone_id" value="<?php echo isset($row['milestone_id']) ? $row['milestone_id'] : ''; ?>">

    <button type="submit" class="btn btn-primary" name="<?php echo isset($row['milestone_id']) ? 'edit-milestone' : 'add_milestone'; ?>">
        <?php echo isset($row['milestone_id']) ? 'Update' : 'Submit'; ?>
    </button>
</form>

<script>
    $(document).ready(function() {
        $("#due_date, #completed_date").datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });

        $('#description').summernote();

        $('select[name="project_id"], select[name="currency_code"], select[name="status"]').select2({
            width: '100%'
        });

        $(".delete-file").click(function(e) {
            e.preventDefault();
            let fileId = $(this).data("id");
            let fileItem = $(this).closest("li");

            if (confirm("Are you sure you want to delete this file?")) {
                $.ajax({
                    url: "delete_file.php",
                    type: "POST",
                    data: {
                        file_id: fileId
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            fileItem.remove();
                            alert("File deleted successfully!");
                        } else {
                            alert("Error: " + response.message);
                            console.log(response);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("AJAX Error: " + error);
                        console.log(xhr.responseText);
                    }
                });
            }
        });

        $("#milestone-form").validate({
            ignore: [],
            rules: {
                project_id: {
                    required: true
                },
                budget: {
                    required: true,
                    number: true,
                    min: 0
                },
                currency_code: {
                    required: true
                },
                status: {
                    required: true
                },
                milestone_name: {
                    required: true,
                    minlength: 2
                },
                due_date: {
                    required: true,
                    date: true
                },
                description: {
                    required: true
                },
               
            },
            messages: {
                project_id: {
                    required: "Please select a project."
                },
                budget: {
                    required: "Please enter a budget.",
                    number: "Please enter a valid number.",
                    min: "Budget must be a positive number."
                },
                currency_code: {
                    required: "Please select a currency."
                },
                status: {
                    required: "Please select the status."
                },
                
                due_date: {
                    required: "Please enter the due date.",
                    date: "Please enter a valid date."
                },
                description: {
                    required: "Please provide a description."
                },
            
            },
            errorPlacement: function(error, element) {
                if (element.attr("name") === "description") {
                    error.insertAfter($("#description").next('.note-editor'));
                } else if (element.hasClass('select2-hidden-accessible')) {
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