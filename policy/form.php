<form method="POST" name="policies-form" id="policies-form" class="p-3" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-6">
            <div class="mb-2">
                <label for="name">Name</label> <span class="text-danger">*</span>
                <input type="text" class="form-control" name="name" required minlength="2"
                    value="<?php echo isset($row['name']) ? $row['name'] : ''; ?>">
            </div>
        </div>

        <div class="col-md-3">
            <div class="mb-2">
                <label for="file"> Document</label>
                <input type="file" class="form-control" id="file" name="file[]" required multiple
                    accept="image/*, .doc, .docx, .txt, .pdf, .mp4, .avi, .mov">
                <small class="text-muted">Allowed file types: Images, DOC, TXT, PDF, Videos</small>
            </div>
        </div>
        <?php if (isset($row['file']) && !empty($row['file'])): ?>
            <div class="col-md-2">
                <div class="mb-2">
                    <label>Uploaded Files</label>
                    <ul class="list-u   nstyled" style="max-height: 150px; overflow-y: auto; border: 1px solid #ccc; padding: 10px; border-radius: 5px;">
                        <?php
                        $files = is_array($row['file']) ? $row['file'] : explode(',', $row['file']);
                        $base_url = '/uploads/';
                        $image_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                        foreach ($files as $file):
                            $file = trim($file);
                            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                        ?>
                            <li style="margin-bottom: 10px;">
                                <?php if (in_array($ext, $image_extensions)): ?>
                                    <a href="<?php echo BASE_URL . '/' . htmlspecialchars($file); ?>" target="_blank">
                                        <img src="<?php echo htmlspecialchars($file); ?>" alt="Uploaded Image" style="max-width: 100%; height: auto; max-height: 100px; border-radius: 5px;" />
                                    </a>
                                <?php else: ?>
                                    <a href="<?php echo BASE_URL . '/' . htmlspecialchars($file); ?>" target="_blank">
                                        <?php echo htmlspecialchars(basename($file)); ?>
                                    </a>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="mb-2">
                <label for="description">Description<span class="text-danger">*</span></label>
                <textarea class="form-control" name="description" id="description"><?php echo isset($row['description']) ? $row['description'] : ''; ?></textarea>
            </div>
        </div>
    </div>

    <input type="hidden" name="id" value="<?php echo isset($row['id']) ? $row['id'] : ''; ?>">

    <div class="col-md-3">
        <button type="submit" class="btn btn-primary " name="<?php echo isset($row['id']) ? 'edit-policies' : 'add_policies'; ?>">
            <?php echo isset($row['id']) ? 'Update' : 'Submit'; ?>
        </button>
    </div>

</form>


<script>
    $(document).ready(function() {
        $('[data-bs-toggle="tooltip"]').tooltip();

        $('select[name="type"]').select2({
            width: '100%'
        });


        var isEditMode = $("input[name='id']").val() !== "";
        $('#description').summernote();

        $('#policies-form').validate({
            ignore: [],
            rules: {
                name: "required",
                file: "required",
            },

            messages: {
                name: "Please enter policy name",
                file: "Please select a file",

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

        $('select[name="type"]').on('change', function() {
            $(this).valid();
        });


        $("#recurring").on("change", function() {
            if ($(this).is(":checked")) {
                $(this).val(1);
            } else {
                $(this).val(0);
            }

        });
    });
</script>