<?php

defined('BASEPATH') or exit('No direct script access allowed');

$this->ci->load->model('Certifications_model');

$idClientes = $this->ci->input->get('idClientes');

$aColumns = [
    'certificationkey',
    'id_customer',
    'id_seal',
    'date_expiration',
    'date',
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
$sTable       = db_prefix() . 'certifications';

$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, [], $where, ['id']);


$output  = $result['output'];
$rResult = $result['rResult'];

$num = 0;
foreach ($rResult as $aRow) {
    $num++;

    $row = [];

    // Id
    $_data = '<a href="' . admin_url('trust_seal/certifications/certification/' . $aRow['id']) . '">' . $aRow['id'] . '</a>';
    $_data .= '<div class="row-options">';
    $_data .= '<a href="' . admin_url('trust_seal/certifications/certification/' . $aRow['id']) . '">' . _l('view') . '</a>';

    if (has_permission('certifications', '', 'delete') && $idClientes == 0) {
        $_data .= ' | <a href="' . admin_url('trust_seal/certifications/delete/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
    }
    $_data .= '</div>';

    $row[] = $_data;

    // customer
    $row[] = '<a href="' . admin_url('clients/client/' . $aRow['id_customer']) . '" class="mbot10 display-block">' . $this->ci->Certifications_model->get_customer($aRow['id_customer'])[0]['company'] . '</a>';

    // seal
    $row[] = '<a href="' . admin_url('trust_seal/seals/view/' . $aRow['id_seal']) . '" class="mbot10 display-block">' . $this->ci->Certifications_model->get_seal($aRow['id_seal'])[0]['title'] . '</a>';

    // Date
    $row[] = '<p>' . date_format(date_create($aRow['date']), 'd-m-Y') . '</p>';

    // Expiration date
    $row[] = '<p>' . date_format(date_create($aRow['date_expiration']), 'd-m-Y') . '</p>';

    // Status

    foreach (get_status_certifications() as $qualification) {
        if ($qualification['status'] == $aRow['status']) {
            $row[] = get_status_audits_format($qualification);
            break;
        }
    }

    $output['aaData'][] = $row;
}
