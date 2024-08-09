<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'userid',
    'company',
    'phonenumber',
    'country',
    'city',
    'zip',
    'active',
    'website'
];

$sIndexColumn = 'userid';
$sTable = 'tblclients';

$result = data_tables_init($aColumns, $sIndexColumn, $sTable);
$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    $row[] = $aRow['userid'];
    $action_btn = '<div class="row-options"><a href="" id="edt-client" data-id="'.$aRow['userid'].'">View</a> | <a href="" class="text-danger _delete" id="delete-client" data-id="'.$aRow['userid'].'">Delete </a></div>';
    $row[] = $aRow['company'].$action_btn;
    $row[] = $aRow['phonenumber'];
    $row[] = $aRow['country'];
    $row[] = $aRow['city'];
    $row[] = $aRow['zip'];
    $is_checked = $aRow['active'] == 1 ? 'checked' : '';
    $switch_btn = '<div class="onoffswitch" data-toggle="tooltip" data-title="' . _l('customer_active_inactive_help') . '">
    <input type="checkbox" data-switch-url="' . admin_url() . 'crud_module/change_status" name="onoffswitch" class="onoffswitch-checkbox" id="' . $aRow['userid'] . '" data-id="' . $aRow['userid'] . '" ' . ($aRow['active'] == 1 ? 'checked' : '') . '>
    <label class="onoffswitch-label" for="' . $aRow['userid'] . '"></label>
    </div>';
    $row[] = $switch_btn;
    $row[] = $aRow['website'];

    $output['aaData'][] = $row;
}