<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<!-- Modal -->
<div class="modal fade" id="insertModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body">
                <?= form_open('', ['id' => 'create-form']); ?>
                <!-- Company Name -->
                <?= render_input('cmp_nm', 'Company name', '', 'text', ['id' => 'cmp_nm'], []); ?>
                <!-- Phone number -->
                <?= render_input('phone_no', 'Phone number', '', 'number', ['id' => 'phone_no']); ?>
                <!-- country -->
                <?= render_select('country', $countries, ['country_id', 'short_name'], "Select country"); ?>
                <!-- City -->
                <?= render_input('city', 'City', '', 'text', ['id' => 'city']); ?>
                <!-- Zip -->
                <?= render_input('zip', 'Zip', '', 'number', ['id' => 'zip']) ?>
                <!-- Website -->
                <?= render_input('website', 'Website', '', 'text', ['id' => 'website']); ?>
                <!-- Checbox -->
                <div class="form-group">
                    <label class="mright10" for="is_active">Active Status</label>
                    <!-- <input type="checkbox" name="is_active" id="is_active"> -->
                    <div class="onoffswitch">
                        <input type="checkbox" id="active" class="onoffswitch-checkbox" name="is_active">
                        <label class="onoffswitch-label" for="active" data-toggle="tooltip" title=""></label>
                    </div>
                </div>
                <div class="mb-3">
                    <button class="btn btn-primary" id="save-btn">SAVE</button>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-s">
                        <div class="panel-body">
                            <button class="btn btn-primary" id="create-btn" data-toggle="modal"
                                data-target="#insertModal">Create Client</button>
                            <div class="mtop5">
                                <?php render_datatable([
                                    '#',
                                    'Company',
                                    'Phone',
                                    'Country',
                                    'City',
                                    'Zip',
                                    'Active',
                                    'Website'
                                ], 'crud_tbl'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>

<script>
    initDataTable('.table-crud_tbl', admin_url + "crud_module/show_clients", undefined, undefined, undefined, [0, 'desc']);

    // Resetting form on modal hide event 
    $('#insertModal').on('hide.bs.modal', function () {
        $('#create-form').trigger('reset');
    });

    // Function to validate website
    function validWebsite(url) {
        var pattern = new RegExp('^(https?:\\/\\/)?' + // protocol
            '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.?)+[a-z]{2,}|' + // domain name
            '((\\d{1,3}\\.){3}\\d{1,3}))' + // ip (v4) address
            '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + //port
            '(\\?[;&amp;a-z\\d%_.~+=-]*)?' + // query string
            '(\\#[-a-z\\d_]*)?$', 'i');
        return pattern.test(url);
    }

    // Adding url validation function
    $.validator.addMethod('valid_url', function (value) {
        return validWebsite(value);
    }, "Invalid Website URL");

    // Custom validatio method to validate phone number
    $.validator.addMethod('valid_phone', function (value, element) {
        return this.optional(element) || /^\d{3}-?\d{3}-?\d{4}$/.test(value);
    }, "Invalid phone number");

    // Validation rules for insertion
    $('#create-form').validate({
        errorClass: "text-danger",
        rules: {
            cmp_nm: {
                required: true,
                remote: {
                    url: '<?= admin_url('crud_module/client_name_exists'); ?>',
                    type: 'post',
                    data: {
                        cname: function () {
                            return $('#cmp_nm').val();
                        }
                    }
                }
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
            zip: {
                required: true
            },
            website: {
                required: true,
                valid_url: true
            }
        },
        messages: {
            cmp_nm: {
                required: "Company name is required.",
                remote: "Company name already exists."
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
            zip: {
                required: "Please provide zip code."
            },
            website: {
                required: "Please provide website.",
                valid_url: "Please enter valid website url."
            }
        },
        highlight: function (element, errorClass) {
            $(element).parent('div.form-group').addClass("has-error");
        },
        unhighlight: function (element) {
            $(element).parent('div.form-group').removeClass("has-error");
        },
        submitHandler: function (form) {
            $.ajax({
                url: "<?php echo admin_url('crud_module/create_client'); ?>",
                type: "post",
                data: $(form).serialize(),
                dataType: "json",
                success: function (response) {
                    console.log(response);

                    if (!response.status) {
                        $.each(response.errors, function (key, value) {
                            if (value != '') {
                                $(`div[app-field-wrapper=${key}]`).addClass('has-error');
                                $(`div[app-field-wrapper=${key}]`).append(value);
                            }
                        });
                    }
                    else {
                        $('#insertModal').modal('hide');
                        alert_float('success', response.message);
                        $('.table-crud_tbl').DataTable().ajax.reload();
                    }
                }
            });
        }
    });

    // Ajax request to edit client details
    $(document).on('click', '#edt-client', function (e) {
        e.preventDefault();
        $.ajax({
            url: "<?php echo admin_url('crud_module/edit_fetch_client'); ?>",
            type: "post",
            data: {
                id: $(this).data('id')
            },
            dataType: "json",
            success: function (response) {
                if (response.status) {
                    $('#cmp_nm').val(response.client[0].company);
                    $('#phone_no').val(response.client[0].phonenumber);
                    $(`#country option[value='${response.client[0].country}']`).prop('selected', true);
                    $('#city').val(response.client[0].city);
                    $('#zip').val(response.client[0].zip);
                    $('#website').val(response.client[0].website);
                    response.client[0].active == 1 ? $('#active').prop('checked', true) : $('#is_active').prop('checked', false)
                    $('#create-form').append(`<input type='hidden' name='id' value='${response.client[0].userid}'>`);
                    $('#insertModal').modal('show');
                } else {
                    // log error
                }
            }
        });
    });

    // Ajax request to delete records
    $(document).on('click', '#delete-client', function () {
        $.ajax({
            type: "post",
            url: "<?= admin_url('crud_module/delete_client') ?>",
            data: {
                id: $(this).data('id')
            },
            dataType: "json",
            success: function (response) {
                alert_float('danger', response.message);
                $('.table-crud_tbl').DataTable().ajax.reload();
            }
        });
    });

</script>