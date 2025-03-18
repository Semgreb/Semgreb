<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'title',
    'status',
    'visibility',
    'date_start',
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'seals';

$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, [], [], ['id']);

$output  = $result['output'];
$rResult = $result['rResult'];

$num = 0;
foreach ($rResult as $aRow) {
    $num++;

    $row = [];

    // Id
    $row[] = $num;

    // Name
    $_data = '<a href="' . admin_url('trust_seal/seals/view/' . $aRow['id']) . '">' . $aRow['title'] . '</a>';
    $_data .= '<div class="row-options">';
    $_data .= '<a href="' . admin_url('trust_seal/seals/view/' . $aRow['id']) . '">' . _l('view') . '</a>';

    if (has_permission('seals', '', 'delete')) {
        $_data .= ' | <a href="' . admin_url('trust_seal/seals/delete_seal/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
    }
    $_data .= '</div>';

    $row[] = $_data;

    // Exams
    $row[] = '1 ' . _l('exams');

    // Date
    $row[] = '<p>' . date_format(date_create($aRow['date_start']), 'd-m-Y') . '</p>';

    // Attach
    $row[] = '0 ' . _l('seal_attach');

    // Visibility
    // if ($aRow['visibility'] == '1') {
    //     $row[] = '<span class="btn btn-sm bg-primary">' . _l('seal_public') . '</span>';
    // } else if ($aRow['visibility'] == '2') {
    //     $row[] = '<span class="btn btn-sm bg-warning">' . _l('seal_private') . '</span>';
    // }

    // Status
    foreach (get_status_seals() as $qualification) {
        if ($qualification['status'] == $aRow['status']) {
            $row[] = get_status_audits_format($qualification);
            break;
        }
    }

    $output['aaData'][] = $row;
}
