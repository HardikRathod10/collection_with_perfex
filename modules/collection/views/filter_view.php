<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-3">
                <div class="panel">
                    <div class="panel-s">
                        <div class="panel-body">
                            <ul class="list-group">
                                <li class="list-group-item" id="filter" data-action="filter">Filter Paid</li>
                                <li class="list-group-item" id="where" data-action="where">Where Unpaid Invoices</li>
                                <li class="list-group-item" id="where-more" data-action="where-more">Where amount more
                                    than 1000</li>
                                <li class="list-group-item" id="where-in" data-action="where-in">WhereIn amount in [500,
                                    700]</li>
                                <li class="list-group-item" id="where-between" data-action="where-between">WhereIn
                                    amoung between [400, 800]</li>
                                <li class="list-group-item" id="first" data-action="first">First invoice</li>
                                <li class="list-group-item" id="last" data-action="last">Last Invoice</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="panel">
                    <div class="panel-s">
                        <div class="panel-body" id="output-container">
                            <table class="table table-striped" border=1>
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Client</th>
                                        <th>Date</th>
                                        <th>Due Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="t-body">
                                    <?php foreach ($invoices as $invoice): ?>
                                        <tr>
                                            <td><?= $invoice['id'] ?></td>
                                            <td><?= $invoice['client'] ?></td>
                                            <td><?= $invoice['date'] ?></td>
                                            <td><?= $invoice['duedate'] ?></td>
                                            <td><?= $invoice['total'] ?></td>
                                            <td>
                                                <?php
                                                if ($invoice['status'] == 1):
                                                    echo "<span class='label label-danger s-status invoice-status-1'>Unpaid</span>";
                                                elseif ($invoice['status'] == 2):
                                                    echo "<span class='label label-success s-status invoice-status-2'>Paid</span>";
                                                else:
                                                    echo "<span class='label label-default s-status invoice-status-5'>Cancelled</span>";
                                                endif;
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
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
    $(document).ready(function () {
        $('#filter, #where, #where-more, #where-in, #where-between, #first, #last').on('click', function () {
            $.ajax({
                url: "<?= admin_url('collection/apply_collection_action') ?>",
                type: "post",
                data: {
                    'action': $(this).data('action')
                },
                dataType: "json",
                success: function (res) {
                    let output = ``;
                    if (res.status) {
                        if (res.action === "first" || res.action === "last") {
                            output += `<tr>
                                            <td>${res.invoices.id}</td>
                                            <td>${res.invoices.client}</td>
                                            <td>${res.invoices.date}</td>
                                            <td>${res.invoices.duedate}</td>
                                            <td>${res.invoices.total}</td>
                                            <td>`;
                            // Conditions to add invoice status based on status number
                            if (res.invoices.status == 1) {
                                output += `<span class="label label-danger s-status invoice-status-1">Unpaid</span>`;
                            } else if (res.invoices.status == 2) {
                                output += `<span class="label label-success s-status invoice-status-2">Paid</span>`;
                            }
                            else {
                                output += `<span class="label label-Secondary s-status invoice-status-5">Cancelled</span>`;
                            }
                            output += `</td></tr>`;
                        } else {
                            $.each(res.invoices, function (index, value) {
                                output += `<tr>
                                                <td>${value.id}</td>
                                                <td>${value.client}</td>
                                                <td>${value.date}</td>
                                                <td>${value.duedate}</td>
                                                <td>${value.total}</td>
                                                <td>`;
                                // Conditions to add invoice status based on status number
                                if (value.status == 1) {
                                    output += `<span class="label label-danger s-status invoice-status-1">Unpaid</span>`;
                                }
                                else if (value.status == 2) {
                                    output += `<span class="label label-success s-status invoice-status-2">Paid</span>`;
                                }
                                else {
                                    output += `<span class="label label-secondary s-status invoice-status-5">Cancelled</span>`;
                                }
                                output += `</td>
                                            </tr>`;
                            });
                        }
                        $('#t-body').html(output);
                    }
                    else {
                        console.log("No records found.");
                    }
                }
            });
        });
    });
</script>