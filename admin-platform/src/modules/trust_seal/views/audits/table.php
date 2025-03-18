<?php

defined('BASEPATH') or exit('No direct script access allowed');

$this->ci->load->model('Audits_model');

$idClientes = $this->ci->input->get('idClientes');

$aColumns = [
    'id',
    'id_customer',
    'id_seal',
    // 'date',
    'status',
];

if ($idClientes > 0) {
    $where = [
        ' AND id_customer = ' . $this->ci->db->escape_str($idClientes)
    ];
} else {
    $where = [];
}


$sIndexColumn = 'id';
$sTable       = db_prefix() . 'audits';

$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, [],  $where, ['id']);


$output  = $result['output'];
$rResult = $result['rResult'];

$num = 0;
foreach ($rResult as $aRow) {
    $num++;

    $row = [];

    // Id
    $_data = '<a href="' . admin_url('trust_seal/audits/audit/' . $aRow['id']) . '">' . $aRow['id'] . '</a>';
    $_data .= '<div class="row-options">';
    $_data .= '<a href="' . admin_url('trust_seal/audits/audit/' . $aRow['id']) . '">' . _l('view') . '</a>';

    if (has_permission('audits', '', 'delete') && $idClientes == 0) {
        $_data .= ' | <a href="' . admin_url('trust_seal/audits/delete/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
    }
    $_data .= '</div>';

    $row[] = $_data;

    // customer
    $row[] = '<a href="' . admin_url('clients/client/' . $aRow['id_customer']) . '" class="mbot10 display-block">' . $this->ci->audits_model->get_customer($aRow['id_customer'])[0]['company'] . '</a>';

    // website
    $row[] = '<a href="' .  $this->ci->audits_model->get_customer($aRow['id_customer'])[0]['website'] . '" class="mbot10 display-block">' . $this->ci->audits_model->get_customer($aRow['id_customer'])[0]['website'] . '</a>';

    // seal
    $row[] = '<a href="' . admin_url('trust_seal/seals/view/' . $aRow['id_seal']) . '" class="mbot10 display-block">' . $this->ci->audits_model->get_seal($aRow['id_seal'])[0]['title'] . '</a>';

    // Date
    // $row[] = '<p>'. $aRow['date'] . '</p>';

    // Status

    foreach (get_status_audits() as $qualification) {
        if ($qualification['status'] == $aRow['status']) {
            $row[] = get_status_audits_format($qualification);
            break;
        }
    }
    $output['aaData'][] = $row;
}
