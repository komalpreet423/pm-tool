<div class="card">
<form method="POST" name="client-form" id="client-form" class="p-3" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name" id="name" required minlength="2"
                value="<?php echo isset($row['name']) ? $row['name'] : ''; ?>">  
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" required value="<?php echo isset($row['email']) ? $row['email'] : ''; ?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="phoneno">Phone Number</label>
                    <input type="number" class="form-control" name="phone" required pattern="\d{10}" value="<?php echo isset($row['phone']) ? $row['phone'] : ''; ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="address">Address</label>
                    <textarea class="form-control" name="address" id="address" required><?php echo isset($row['address']) ? $row['address'] : ''; ?></textarea>
                </div>
            </div>
        </div>
        <input type="hidden" name="client_id" value="<?php echo isset($row['id']) ? $row['id'] : ''; ?>">
        <button type="submit" class="btn btn-primary" name=<?php echo isset($row['id']) ? 'edit_client' : 'add_client'; ?>>
            <?php echo isset($row['id']) ? 'Update' : 'Submit'; ?>
        </button>
    </form>
</div>
<script>
     $(document).ready(function() {
        $('#client-form').validate({
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
                address: "required"
            },
            messages: {
                name: "Please enter client name",
                email: "Please enter a valid email address",
                phoneno: {
                    required: "Please enter a 10-digit phone number",
                    minlength: "Phone number must be 10 digits",
                    maxlength: "Phone number cannot be more than 10 digits"
                },
                address: "Please enter address."
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