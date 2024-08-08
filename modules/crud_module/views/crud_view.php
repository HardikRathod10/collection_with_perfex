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
                <!-- Website -->
                <?= render_input('website', 'Website', '', 'text', ['id' => 'website']); ?>
                <!-- Website -->
                <?= render_input('website', 'Website', '', 'text', ['id' => 'website']); ?>
                <!-- Checbox -->
                <?= render_input('is_active', 'Is Active', 'active', 'checkbox', ['id' => 'is_active']) ?>
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
                            <button class="btn btn-secondary" id="create-btn" data-toggle="modal"
                                data-target="#insertModal">Create Client</button>
                            <?php render_datatable([
                                '#',
                                'Company',
                                'Phone',
                                'Country',
                                'City',
                                'Zip',
                                'Active',
                                'Website',
                                'Action',
                            ]); ?>
                            <table class="table table-striped" border=1 id="crud-tbl">
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
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>

<script>
    // Function to show all clients in table
    function showClients() {
        $.ajax({
            url: "<?= admin_url('crud_module/show_clients') ?>",
            type: "post",
            dataType: "json",
            success: function (response) {
                let output = ``;
                if (response.status) {
                    $.each(response.clients, function (index, client) {
                        output += `
                                    <tr>
                                        <td>${client['userid']}</td>
                                        <td>${client['company']}</td>
                                        <td>${client['phonenumber']}</td>
                                        <td>${client['country']}</td>
                                        <td>${client['city']}</td>
                                        <td>${client['zip']}</td>
                                        <td><div class="onoffswitch" data-toggle="tooltip">
                                        <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="${client['userid']}" data-id="${client['userid']}" ${client['active'] == 1 ? 'checked' : ''}>
                                        <label class="onoffswitch-label" for="${client['userid']}"></label>
                                        </div></td>
                                        <td>${client['website']}</td>
                                        <td><button class='btn btn-success mright5 edit-btn' data-id='${client['userid']}' name='status-check'>Edit</button><button class='btn btn-danger dlt-btn' data-id='${client['userid']}'>Delete</button></td>
                                    </tr>`;
                    });
                    $('#t-body').html(output);
                }
            }
        });
    }
    showClients();
    $(document).ready(function () {
        // Saving clients data
        $('#save-btn').on('click', function (e) {
            e.preventDefault();
            $.ajax({
                url: "<?= admin_url('crud_module/create_client'); ?>",
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
                        showClients();
                    }
                }
            });
        });

        // Ajax request to edit client details
        $(document).on('click', 'button.edit-btn', function () {
            // $('#insertModal').modal('show');
            $.ajax({
                url: "<?= admin_url('crud_module/show_clients'); ?>",
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
                url: "<?= admin_url('crud_module/delete_client') ?>",
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

        $(document).on('change', '.onoffswitch-checkbox', function (e) {
            e.preventDefault();
            let id = $(this).data('id');
            let status = 0;
            if ($(this).is(':checked')) {
                status = 1;
            }
            $.ajax({
                url: "<?= admin_url('crud_module/change_status') ?>",
                type: "post",
                data: {
                    id: id,
                    status: status
                },
                dataType: "dataType",
                success: function (response) {
                    if (response.status) {
                        showClients();
                    } else {
                        console.log("Cant't update status.");
                    }
                }
            });
        });
    });
    initDataTable();
</script>