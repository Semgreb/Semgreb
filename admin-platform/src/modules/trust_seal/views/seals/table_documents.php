<?php

defined('BASEPATH') or exit('No direct script access allowed');

$extension = "";

$listExtension = ["png", "jpg", "jpeg", "gif"];

$aColumns = [
    'file', 'id_seal'
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'seal_files';

$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, [], [], ['id', 'id_seal']);

$output  = $result['output'];
$rResult = $result['rResult'];

$num = 0;
foreach ($rResult as $aRow) {
    $num++;

    $extension = pathinfo($aRow['file'], PATHINFO_EXTENSION);

    $row = [];

    // Id
    $row[] = $num;

    $path = site_url(PATH_SEALS . $aRow['id_seal'] . '/' . $aRow['file']);

    if (in_array(strtolower($extension), $listExtension)) {
        $fileName = '<img width="50"  src="' . $path . '"></img>';
    } else {
        $fileName = $aRow['file'];
    }

    // Name
    $row[] = '<a href="' . $path . '"  target="_blank">' . $fileName . '</a>';

    // Delete
    $row[] = '<a href="' . admin_url('trust_seal/seals/delete_file/' . $aRow['id'] . "/" . $aRow['id_seal']) . '?file=' . $aRow['file'] . '"><i class="fa-regular fa-trash-can fa-lg" style="color:gray;"></i></a>';

    $output['aaData'][] = $row;
}
