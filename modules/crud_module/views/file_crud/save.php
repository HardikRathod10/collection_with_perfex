<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Initializes heading -->
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-s">
                        <div class="panel-body">
                            <?= form_open_multipart('', ['id' => 'customer-form']); ?>
                            <!-- Company Name -->
                            <?= render_input('name', 'Customer name', '', 'text', ['id' => 'name'], []); ?>
                            <!-- country -->
                            <?= render_select('country', $countries, ['country_id', 'short_name'], "Select country"); ?>
                            <!-- City -->
                            <?= render_input('city', 'City', '', 'text', ['id' => 'city']); ?>
                            <!-- Phone number -->
                            <?= render_input('phone_no', 'Phone number', '', 'number', ['id' => 'phone_no']); ?>
                            <!-- Profile pic -->
                            <?= render_input('profile_pic', 'Profile Picture', '', 'file', ['id' => 'profile_pic']); ?>
                            <div class="mb-3">
                                <button class="btn btn-primary">SAVE</button>
                            </div>
                            <?= form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Initalizes footer -->
<?php init_tail(); ?>
<script>
    // Custom validatio method to validate phone number
    $.validator.addMethod('valid_phone', function (value, element) {
        return this.optional(element) || /^\d{3}-?\d{3}-?\d{4}$/.test(value);
    }, "Invalid phone number");

    // Client side validations
    appValidateForm(
        '#customer-form',
        {
            name: {
                required: true
            },
            phone_no: {
                required: true,
                valid_phone: true
            },
            country: {
                required: true
            },
            city: {
                required: true
            },
            profile_pic: {
                required: true
            }
        },
        // Form submit handling function.
        formSubmitHandling,
        // Custom validation messages.
        {
            name: {
                required: "Company name is required."
            },
            phone_no: {
                required: "Phone number is required."
            },
            country: {
                required: "Please select country."
            },
            city: {
                required: "Please provide city name."
            },
            profile_pic: {
                required: "Please select profile picture."
            }
        }
    );

    // Function to handle form submission in successfull validation.    
    function formSubmitHandling(form) {
        const formData = new FormData(form);
        $.ajax({
            url: "<?php echo admin_url('crud_module/File_crud/save'); ?>",
            type: "post",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (res) {
                console.log(res);

                // if (!response.status) {
                //     $.each(response.errors, function (key, value) {
                //         if (value != '') {
                //             $(`div[app-field-wrapper=${key}]`).addClass('has-error');
                //             $(`div[app-field-wrapper=${key}]`).append(value);
                //         }
                //     });
                // }
                // else {
                //     $('#hidden_id').html("");
                //     $('#insertModal').modal('hide');
                //     alert_float('success', response.message);
                //     $('.table-crud_tbl').DataTable().ajax.reload();
                // }
            }
        });
    }
</script>