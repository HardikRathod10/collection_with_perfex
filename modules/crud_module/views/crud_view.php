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
                <div class="mb-3">
                    <label for="country">Country</label>
                    <select class="form-control" name="country" id="country">
                        <?php foreach ($countries as $country): ?>
                            <option value="<?= $country->country_id ?>"><?= $country->short_name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <!-- City -->
                <?= render_input('city', 'City', '', 'text', ['id' => 'city']); ?>
                <!-- Zip -->
                <?= render_input('zip', 'Zip', '', 'number', ['id' => 'zip']) ?>
                <!-- Website -->
                <?= render_input('website', 'Website', '', 'text', ['id' => 'website']); ?>
                <!-- Checbox -->
                <label class="mright10" for="is_active">Active Status</label>
                <input type="checkbox" name="is_active" id="is_active">
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
                            <!-- <table class="table table-striped" border=1 id="crud-tbl">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Company</th>
                                        <th>Phone</th>
                                        <th>Country</th>
                                        <th>City</th>
                                        <th>Zip</th>
                                        <th>Active</th>
                                        <th>Website</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="t-body">

                                </tbody>
                            </table> -->
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

    // Saving clients data
    $('#save-btn').on('click', function (e) {
        e.preventDefault();
        $.ajax({
            url: "<?php echo admin_url('crud_module/create_client'); ?>",
            type: "post",
            data: $('#create-form').serialize(),
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
                    $('#create-form').trigger('reset');
                    $('#insertModal').modal('hide');
                    alert_float('success', response.message);
                    $('.table-crud_tbl').DataTable().ajax.reload();
                }
            }
        });
    });

    // Ajax request to edit client details
    $(document).on('click', '#edt-client', function (e) {
        e.preventDefault();
        // $('#insertModal').modal('show');
        $.ajax({
            url: "<?php echo admin_url('crud_module/edit_fetch_client'); ?>",
            type: "post",
            data: {
                id: $(this).data('id')
            },
            dataType: "json",
            success: function (response) {
                // console.log(response.clients[0].userid);
                if (response.status) {
                    $('#cmp_nm').val(response.client[0].company);
                    $('#phone_no').val(response.client[0].phonenumber);
                    $('#country').val(response.client[0].country);
                    $('#city').val(response.client[0].city);
                    $('#zip').val(response.client[0].zip);
                    $('#website').val(response.client[0].website);
                    response.client[0].active == 1 ? $('#is_active').prop('checked', true) : $('#is_active').prop('checked', false)
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
<!-- <script>

    $(document).ready(function () {
        

        // Ajax request to edit client details
        $(document).on('click', '#edt-client', function () {
            // $('#insertModal').modal('show');
            $.ajax({
                url: "<?php // admin_url('crud_module/show_clients'); ?>",
                type: "post",
                data: {
                    id: $(this).data('id')
                },
                dataType: "json",
                success: function (response) {
                    // console.log(response.clients[0].userid);
                    if (response.status) {
                        $('#cmp_nm').val(response.clients[0].company);
                        $('#phone_no').val(response.clients[0].phonenumber);
                        $('#country').val(response.clients[0].country_id);
                        $('#city').val(response.clients[0].city);
                        $('#zip').val(response.clients[0].zip);
                        $('#website').val(response.clients[0].website);
                        response.clients[0].active == 1 ? $('#is_active').prop('checked', true) : $('#is_active').prop('checked', false)
                        $('#create-form').append(`<input type='hidden' name='id' value='${response.clients[0].userid}'>`);
                        $('#insertModal').modal('show');
                    } else {
                        // log error
                    }
                }
            });
        });

        // Ajax request to delete records
        $(document).on('click', 'button.dlt-btn', function () {
            $.ajax({
                type: "post",
                url: "<?php // admin_url('crud_module/delete_client') ?>",
                data: {
                    id: $(this).data('id')
                },
                dataType: "json",
                success: function (response) {
                    if (response.status) {
                        showClients();
                    } else {
                        console.log("can't delete.");
                    }
                }
            });
        });

    });
    initDataTable();
</script> -->