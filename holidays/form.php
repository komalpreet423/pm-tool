<form method="POST" name="holiday-form" id="holiday-form" class="p-3" enctype="multipart/form-data">
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
                <label for="date">Date</label> <span class="text-danger">*</span>
                <input type="text" class="form-control" name="date" id="date"
                    value="<?php echo isset($row['date']) ? $row['date'] : ''; ?>" required>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="mb-3">
                <label for="type">Type</label> <span class="text-danger">*</span>
                <select class="form-select" name="type" required>
                    <option value="public" <?php echo (isset($row['type']) && $row['type'] == 'public') ? 'selected' : ''; ?>>Public</option>
                    <option value="company" <?php echo (isset($row['type']) && $row['type'] == 'company') ? 'selected' : ''; ?>>Company</option>
                    <option value="regional" <?php echo (isset($row['type']) && $row['type'] == 'regional') ? 'selected' : ''; ?>>Regional</option>
                </select>
            </div>
        </div>
        <div class="col-md-3 d-flex align-items-center">
            <div class="mb-3 form-check">
                <input type="hidden" name="recurring" value="0">

                <input class="form-check-input" type="checkbox" id="recurring" name="recurring" value="1"
                    <?php echo isset($row['recurring']) && $row['recurring'] ? 'checked' : ''; ?>
                    data-bs-toggle="tooltip" data-bs-placement="top" title="Enable if this holiday repeats every year">

                <label class="form-check-label ms-2 fw-semibold" for="recurring">
                    <i class="bi bi-check-square"></i> Every Year
                </label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="description">Description</label>
                <textarea class="form-control" name="description" id="description"><?php echo isset($row['description']) ? $row['description'] : ''; ?></textarea>
            </div>
        </div>
    </div>

    <input type="hidden" name="id" value="<?php echo isset($row['id']) ? $row['id'] : ''; ?>">

    <button type="submit" class="btn btn-primary" name="<?php echo isset($row['id']) ? 'edit-holiday' : 'add_holiday'; ?>">
        <?php echo isset($row['id']) ? 'Update' : 'Submit'; ?>
    </button>
</form>

<script>
    $(document).ready(function() {
        $('[data-bs-toggle="tooltip"]').tooltip();

        $("#date").datepicker({
            format: 'yyyy-mm-dd'
        });

        $('select[name="type"]').select2({
            width: '100%'
        });

        var isEditMode = $("input[name='id']").val() !== "";

        $('#holiday-form').validate({
            rules: {
                name: "required",
                date: "required",
            },
            messages: {
                name: "Please enter holiday name",
                date: "Please select a date",
            },
            errorPlacement: function(error, element) {
                error.addClass("invalid-feedback");

                if (element.hasClass("form-select")) {
                    error.insertAfter(element.next('.select2'));
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function(element) {
                $(element).addClass("is-invalid");
                if ($(element).hasClass("form-select")) {
                    $(element).next('.select2').find('.select2-selection').addClass('is-invalid');
                }
            },
            unhighlight: function(element) {
                $(element).removeClass("is-invalid");
                if ($(element).hasClass("form-select")) {
                    $(element).next('.select2').find('.select2-selection').removeClass('is-invalid');
                }
            }
        });
    });
</script>