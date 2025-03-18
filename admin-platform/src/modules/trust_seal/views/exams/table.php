<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'name',
    'active',
    'status',
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'exams';

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
    $_data = '<a href="' . admin_url('trust_seal/exams/view_exam/' . $aRow['id']) . '">' . $aRow['name'] . '</a>';
    $_data .= '<div class="row-options">';
    $_data .= '<a href="' . admin_url('trust_seal/exams/view_exam/' . $aRow['id']) . '">' . _l('view') . '</a>';

    if (has_permission('exams', '', 'delete')) {
        $_data .= ' | <a href="' . admin_url('trust_seal/exams/delete_exam/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
    }
    $_data .= '</div>';

    $row[] = $_data;

    // Total sections
    $row[]   = $this->ci->exams_model->get_sections_from_exam($aRow['id']);

    // Total question
    $row[]   = $this->ci->exams_model->get_quizs_from_exam($aRow['id']);

    // status

    foreach (get_status_exams() as $qualification) {
        if ($qualification['status'] == $aRow['status']) {
            $row[] = get_status_audits_format($qualification);
            break;
        }
    }

    $output['aaData'][] = $row;
}
