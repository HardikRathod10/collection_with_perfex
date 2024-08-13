<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-s">
                        <div class="panel-body">
                            <a href="<?php echo admin_url('crud_module/file_crud/load_form'); ?>"
                                class="btn btn-primary mright5 test pull-left display-block">
                                <i class="fa-regular fa-plus tw-mr-1"></i>
                                <?php echo _l('new_customer'); ?></a>
                            <div class="mtop5">
                                <?php render_datatable([
                                    //'<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="clients"><label></label></div>',
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
    initDataTable('.table-crud_tbl', admin_url + "crud_module/show_clients", undefined, [0], undefined, [0, 'DESC']);

    // Resetting form on modal hide event 
    $('#insertModal').on('hide.bs.modal', function () {
        $('#hidden_id').html("");
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
    appValidateForm(
        // From ID
        '#create-form',
        // Validation rules
        {
            cmp_nm: {
                required: true,
                remote: {
                    url: '<?= admin_url('crud_module/client_name_exists'); ?>',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        cname: function () {
                            return $('#cmp_nm').val();
                        },
                        id: function () {
                            return $('#client_id').val();
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
        // Form submit handling function.
        formSubmitHandling,
        // Custom validation messages.
        {
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
        });

    // Function to handle form submission in successfull validation.    
    function formSubmitHandling(form) {
        // console.log("Form validation goes done.");
        // return;
        $.ajax({
            url: "<?php echo admin_url('crud_module/create_client'); ?>",
            type: "post",
            data: $(form).serialize(),
            dataType: "json",
            success: function (response) {
                if (!response.status) {
                    $.each(response.errors, function (key, value) {
                        if (value != '') {
                            $(`div[app-field-wrapper=${key}]`).addClass('has-error');
                            $(`div[app-field-wrapper=${key}]`).append(value);
                        }
                    });
                }
                else {
                    $('#hidden_id').html("");
                    $('#insertModal').modal('hide');
                    alert_float('success', response.message);
                    $('.table-crud_tbl').DataTable().ajax.reload();
                }
            }
        });
    }
    
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
                    $('#create-form #hidden_id').html(`<input type='hidden' name='id' id='client_id' value='${response.client[0].userid}'>`);
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