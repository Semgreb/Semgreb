<?php
function complaint_status_translate($id, $withStyle = false)
{
    if ($id == '' || is_null($id)) {
        return '';
    }

    $line = _l('complaint_status_db_' . $id, '', false);
    //$line == 'db_translate_not_found'
    if ($withStyle) {

        $CI = &get_instance();
        $CI->db->where('complaintsstatusid', $id);
        $status = $CI->db->get(db_prefix() . 'complaints_status')->row();

        if (!$status)
            return '';

        if ($withStyle) {


            $statusColor = adjust_hex_brightness($status->statuscolor, 0.4);
            $statusColorBorder = adjust_hex_brightness($status->statuscolor, 0.04);

            return sprintf('<span class="label ticket-status-%s" style="border:1px solid  %s; color: %s; background:%s;">%s</label>', $id, $statusColor, $status->statuscolor, $statusColorBorder, $line);
        } else {
            return $status->name;
        }
    }

    return $line;
}

function get_consumer_user_id($complaintid, $consumerid = null)
{
    $CI = &get_instance();
    $CI->db->where('complaintid', $complaintid);
    $rs = $CI->db->get(db_prefix() . 'complaints')->row();

    if ($consumerid == null) {

        if (!$rs)
            return '';

        $consumerid = $rs->contactid;
    }

    $CI->db->where('consumerid', $consumerid);
    $rs = $CI->db->get(db_prefix() . 'consumers')->row();


    return $rs->userid;
}

function complaint_services_translate($id, $withStyle = false)
{
    if ($id == '' || is_null($id)) {
        return '';
    }

    $line = _l('ticket_status_db_' . $id, '', false);
    //$line == 'db_translate_not_found'
    if ($withStyle) {

        $CI = &get_instance();
        $CI->db->where('complaintsstatusid', $id);
        $status = $CI->db->get(db_prefix() . 'complaints_status')->row();

        if (!$status)
            return '';

        if ($withStyle) {


            $statusColor = adjust_hex_brightness($status->statuscolor, 0.4);
            $statusColorBorder = adjust_hex_brightness($status->statuscolor, 0.04);

            return sprintf('<span class="label ticket-status-%s" style="border:1px solid  %s; color: %s; background:%s;">%s</label>', $id, $statusColor, $status->statuscolor, $statusColorBorder, $line);
        } else {
            return $status->name;
        }
    }

    return $line;
}

function formatt_render_subject($colunm, $aRow)
{
    $url   = admin_url('complaints/complaint/' . $aRow['complaintid']);

    $content = '<a href="' . $url . '" class="valign">' . $colunm . '</a>';
    $content .= '<div class="row-options">';
    $content .= '<a href="' . $url . '">' . _l('view') . '</a>';

    if (has_permission('complaints', '', 'edit')) {
        $content .= ' | <a href="' . $url . '?tab=settings">' . _l('edit') . '</a>';
    }

    $content .= ' | <a href="' . get_complaint_public_url($aRow) . '" target="_blank">' . _l('view_public_form') . '</a>';

    if (has_permission('complaints', '', 'delete')) {
        $content .= ' | <a href="' . admin_url('complaints/delete/' . $aRow['complaintid']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
    }

    $content .= '</div>';

    return  $content;
}

function complaint_service_translate($id)
{
    if ($id == '' || is_null($id)) {
        return '';
    }

    $line = _l('complaint_service_translate_db_' . $id, '', false);

    if ($line == 'db_translate_not_found') {
        $CI = &get_instance();
        $CI->db->where('serviceid', $id);
        $status = $CI->db->get(db_prefix() . 'complaints_services')->row();

        return !$status ? '' : $status->name;
    }

    return $line;
}


function set_complaint_open($current, $id, $admin = true)
{
    if ($current == 1) {
        return;
    }

    $field = ($admin == false ? 'clientread' : 'adminread');

    $CI = &get_instance();
    $CI->db->where('complaintid', $id);

    $CI->db->update(db_prefix() . 'complaints', [
        $field => 1,
    ]);
}

function get_complaint_public_url($complaint)
{
    if (is_array($complaint)) {
        $complaint = array_to_object($complaint);
    }

    $CI = &get_instance();

    if (!$complaint->complaintid) {
        $CI->db->where('complaintid', $complaint->complaintid);
        $CI->db->update('complaints', ['complaintkey' => $key = app_generate_hash()]);
    } else {
        $key = $complaint->complaintkey;
    }

    return site_url('complaints/clients_complaints/complaint/' .  $key);
}

function get_task_column($id, $type)
{

    $CI = &get_instance();

    switch ($type) {

        case "not_finished_timer_by_current_staff":

            $query = 'SELECT MAX(id) as not_finished_timer_by_current_staff FROM ' . db_prefix() . 'taskstimers WHERE task_id=' . $id . ' and staff_id=' . get_staff_user_id() . ' and end_time IS NULL';

            break;

        case "is_assigned":

            $query = 'SELECT staffid  as is_assigned FROM ' . db_prefix() . 'task_assigned WHERE taskid=' . $id . ' AND staffid=' . get_staff_user_id();

            break;

        case "current_user_is_assigned":

            $query = ' SELECT staffid as current_user_is_assigned FROM ' . db_prefix() . 'task_assigned WHERE taskid=' . $id . ' AND staffid=' . get_staff_user_id();

            break;


        case "assignees_ids":

            $query = ' SELECT GROUP_CONCAT(staffid ORDER BY ' . db_prefix() . 'task_assigned.id ASC SEPARATOR ",") AS assignees_ids FROM ' . db_prefix() . 'task_assigned WHERE taskid=' . $id;

            break;

        case "assignees":

            $query = ' SELECT GROUP_CONCAT(CONCAT(firstname, \' \', lastname) ORDER BY ' . db_prefix() . 'task_assigned.id ASC SEPARATOR ",") AS assignees  FROM ' . db_prefix() . 'task_assigned JOIN ' . db_prefix() . 'staff ON ' . db_prefix() . 'staff.staffid = ' . db_prefix() . 'task_assigned.staffid WHERE taskid=' . $id;

            break;

        case "tags":

            $query = '  SELECT GROUP_CONCAT(name SEPARATOR ",") AS tags FROM ' . db_prefix() . 'taggables JOIN ' . db_prefix() . 'tags ON ' . db_prefix() . 'taggables.tag_id = ' . db_prefix() . 'tags.id WHERE rel_id = ' . $id . ' and rel_type="task" ORDER by tag_order ASC ';

            break;
    }

    return $CI->db->query($query)->result();
}


function get_relation_data_complaint_module($type, $rel_id = '', $extra = [])
{
    $CI = &get_instance();
    $q  = '';
    if ($CI->input->post('q')) {
        $q = $CI->input->post('q');
        $q = trim($q);
    }

    $data = [];
    if ($type == 'consumers' || $type == 'consumer') {

        $CI->load->model('consumers/consumers_model');

        if ($rel_id != '') {
            $data = $CI->consumers_model->get($rel_id);
        } else {
            $where_clients = "";
            $where_clients .= ' CONCAT(firstname, " ", lastname) LIKE "%' . $CI->db->escape_like_str($q) . '%"';
            $data = $CI->consumers_model->searchCustomer($where_clients);
        }
    } elseif ($type == 'complaint' || $type == 'complaints') {

        $CI->load->model('complaints_model');

        if ($rel_id != '') {
            $data = $CI->complaints_model->get($rel_id);
        }
    } elseif ($type == 'customer' || $type == 'customers') {
        $CI->load->model('consumers/consumers_model');

        if ($rel_id != '') {
            $data = $CI->consumers_model->getClients($rel_id);
        } else {
            $where_clients = "";
            $where_clients .= ' company LIKE "%' . $CI->db->escape_like_str($q) . '%"';
            $data = $CI->consumers_model->searchClient($where_clients);
        }
    }

    $data = hooks()->apply_filters('get_relation_data', $data, compact('type', 'rel_id', 'extra'));

    return $data;
}

function get_relation_values_complaint_module($relation, $type)
{
    if ($relation == '') {
        return [
            'name'      => '',
            'id'        => '',
            'link'      => '',
            'addedfrom' => 0,
            'subtext'   => '',
        ];
    }

    $addedfrom = 0;
    $name      = '';
    $id        = '';
    $link      = '';
    $subtext   = '';

    if ($type == 'consumer' || $type == 'consumers') {
        if (is_array($relation)) {
            // $userid = $relation['userid'];
            $id     = $relation['consumerid'];
            $name   = $relation['firstname'] . ' ' . $relation['lastname'];
        } else {
            //  $userid = $relation->userid;
            $id     = $relation->consumerid;
            $name   = $relation->firstname . ' ' . $relation->lastname;
        }
        // $subtext = get_company_name($userid);
        //$link    = admin_url('clients/client/' . $userid . '?contactid=' . $id);
    } elseif ($type == 'complaint' || $type == 'complaint') {
        if (is_array($relation)) {
            //$userid = isset($relation['userid']) ? $relation['userid'] : $relation['relid'];
            $id     = $relation['complaintid'];
            $name   = $relation['subject'];
        } else {
            // $userid = $relation->userid;
            $id     = $relation->complaintid;
            $name   = $relation->subject;
        }
    } elseif ($type == 'contact' || $type == 'contacts') {
        if (is_array($relation)) {
            // $userid = isset($relation['userid']) ? $relation['userid'] : $relation['relid'];
            $id     = $relation['consumerid'];
            $name   = $relation['firstname'] . ' ' . $relation['lastname'];
        } else {
            //$userid = $relation->userid;
            $id     = $relation->complaintid;
            $name   = $relation->firstname . ' ' . $relation->lastname;
        }
    } elseif ($type == 'customer' || $type == 'customer') {
        if (is_array($relation)) {
            // $userid = isset($relation['userid']) ? $relation['userid'] : $relation['relid'];
            $id     = $relation['userid'];
            $name   = $relation['company'];
        } else {
            //$userid = $relation->userid;
            $id     = $relation->userid;
            $name   = $relation->company;
        }
    }

    return hooks()->apply_filters('relation_values', [
        'id'        => $id,
        'name'      => $name,
        'link'      => $link,
        'addedfrom' => $addedfrom,
        'subtext'   => $subtext,
        'type'      => $type,
    ]);
}

if (!function_exists('isNull')) {
    function isNull($value)
    {
        return is_null($value) ? "" : $value;
    }
}
/**
 * Tasks html table used all over the application for relation tasks
 * This table is not used for the main tasks table
 * @param  array  $table_attributes
 * @return string
 */
function init_relation_tasks_table_complaint($table_attributes = [])
{
    $table_data = [
        _l('the_number_sign'),
        [
            'name'     => _l('tasks_dt_name'),
            'th_attrs' => [
                'style' => 'width:200px',
            ],
        ],
        _l('task_status'),
        [
            'name'     => _l('tasks_dt_datestart'),
            'th_attrs' => [
                'style' => 'width:75px',
            ],
        ],
        [
            'name'     => _l('task_duedate'),
            'th_attrs' => [
                'style' => 'width:75px',
                'class' => 'duedate',
            ],
        ],
        [
            'name'     => _l('task_assigned'),
            'th_attrs' => [
                'style' => 'width:75px',
            ],
        ],
        _l('tags'),
        _l('tasks_list_priority'),
    ];

    array_unshift($table_data, [
        'name'     => '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="rel-tasks"><label></label></div>',
        'th_attrs' => ['class' => ($table_attributes['data-new-rel-type'] !== 'project' ? 'not_visible' : '')],
    ]);

    $custom_fields = get_custom_fields('tasks', [
        'show_on_table' => 1,
    ]);

    foreach ($custom_fields as $field) {
        array_push($table_data, [
            'name'     => $field['name'],
            'th_attrs' => ['data-type' => $field['type'], 'data-custom-field' => 1],
        ]);
    }

    $table_data = hooks()->apply_filters('tasks_related_table_columns', $table_data);

    $name = 'rel-tasks';
    if ($table_attributes['data-new-rel-type'] == 'lead') {
        $name = 'rel-tasks-leads';
    }

    $table      = '';
    $CI         = &get_instance();
    $table_name = '.table-' . $name;
    $CI->load->view('admin/tasks/tasks_filter_by', [
        'view_table_name' => $table_name,
    ]);
    if (has_permission('tasks', '', 'create')) {
        $disabled   = '';
        $table_name = addslashes($table_name);
        if ($table_attributes['data-new-rel-type'] == 'customer' && is_numeric($table_attributes['data-new-rel-id'])) {
            if (total_rows(db_prefix() . 'clients', [
                'active' => 0,
                'userid' => $table_attributes['data-new-rel-id'],
            ]) > 0) {
                $disabled = ' disabled';
            }
        }
        // projects have button on top
        if ($table_attributes['data-new-rel-type'] != 'project') {
            echo "<a href='#' class='btn btn-primary pull-left mright5 new-task-relation" . $disabled . "' onclick=\"new_task_from_relation_complaint('$table_name'); return false;\" data-rel-id='" . $table_attributes['data-new-rel-id'] . "' data-rel-type='" . $table_attributes['data-new-rel-type'] . "'><i class=\"fa-regular fa-plus tw-mr-1\"></i>" . _l('new_task') . '</a>';
        }
    }

    if ($table_attributes['data-new-rel-type'] == 'project') {
        echo "<a href='" . admin_url('tasks/list_tasks?project_id=' . $table_attributes['data-new-rel-id'] . '&kanban=true') . "' class='btn btn-default mright5 mbot15 hidden-xs' data-toggle='tooltip' data-title='" . _l('view_kanban') . "' data-placement='top'><i class='fa-solid fa-grip-vertical'></i></a>";
        echo "<a href='" . admin_url('tasks/detailed_overview?project_id=' . $table_attributes['data-new-rel-id']) . "' class='btn btn-success pull-rigsht mbot15'>" . _l('detailed_overview') . '</a>';
        echo '<div class="clearfix"></div>';
        echo $CI->load->view('admin/tasks/_bulk_actions', ['table' => '.table-rel-tasks'], true);
        echo $CI->load->view('admin/tasks/_summary', ['rel_id' => $table_attributes['data-new-rel-id'], 'rel_type' => 'project', 'table' => $table_name], true);
        echo '<a href="#" data-toggle="modal" data-target="#tasks_bulk_actions" class="hide bulk-actions-btn table-btn" data-table=".table-rel-tasks">' . _l('bulk_actions') . '</a>';
    } elseif ($table_attributes['data-new-rel-type'] == 'customer') {
        echo '<div class="clearfix"></div>';
        echo '<div id="tasks_related_filter" class="mtop15">';
        echo '<p class="bold">' . _l('task_related_to') . ': </p>';

        echo '<div class="checkbox checkbox-inline">
<input type="checkbox" checked value="customer" disabled id="ts_rel_to_customer" name="tasks_related_to[]">
<label for="ts_rel_to_customer">' . _l('client') . '</label>
</div>

<div class="checkbox checkbox-inline">
<input type="checkbox" value="project" id="ts_rel_to_project" name="tasks_related_to[]">
<label for="ts_rel_to_project">' . _l('projects') . '</label>
</div>

<div class="checkbox checkbox-inline">
<input type="checkbox" value="invoice" id="ts_rel_to_invoice" name="tasks_related_to[]">
<label for="ts_rel_to_invoice">' . _l('invoices') . '</label>
</div>

<div class="checkbox checkbox-inline">
<input type="checkbox" value="estimate" id="ts_rel_to_estimate" name="tasks_related_to[]">
<label for="ts_rel_to_estimate">' . _l('estimates') . '</label>
</div>

<div class="checkbox checkbox-inline">
<input type="checkbox" value="contract" id="ts_rel_to_contract" name="tasks_related_to[]">
<label for="ts_rel_to_contract">' . _l('contracts') . '</label>
</div>

<div class="checkbox checkbox-inline">
<input type="checkbox" value="ticket" id="ts_rel_to_ticket" name="tasks_related_to[]">
<label for="ts_rel_to_ticket">' . _l('tickets') . '</label>
</div>

<div class="checkbox checkbox-inline">
<input type="checkbox" value="expense" id="ts_rel_to_expense" name="tasks_related_to[]">
<label for="ts_rel_to_expense">' . _l('expenses') . '</label>
</div>

<div class="checkbox checkbox-inline">
<input type="checkbox" value="proposal" id="ts_rel_to_proposal" name="tasks_related_to[]">
<label for="ts_rel_to_proposal">' . _l('proposals') . '</label>
</div>';

        echo '</div>';
    }
    echo "<div class='clearfix'></div>";

    // If new column is added on tasks relations table this will not work fine
    // In this case we need to add new identifier eq task-relation
    $table_attributes['data-last-order-identifier'] = 'tasks';
    $table_attributes['data-default-order']         = get_table_last_order('tasks');
    if ($table_attributes['data-new-rel-type'] != 'project') {
        echo '<hr />';
    }
    $table .= render_datatable($table_data, $name, ['number-index-1'], $table_attributes);

    return $table;
}

function init_relation_options_complaint($data, $type, $rel_id = '')
{
    $_data = [];

    $has_permission_projects_view  = has_permission('projects', '', 'view');
    $has_permission_customers_view = has_permission('customers', '', 'view');
    $has_permission_contracts_view = has_permission('contracts', '', 'view');
    $has_permission_invoices_view  = has_permission('invoices', '', 'view');
    $has_permission_estimates_view = has_permission('estimates', '', 'view');
    $has_permission_expenses_view  = has_permission('expenses', '', 'view');
    $has_permission_proposals_view = has_permission('proposals', '', 'view');
    $is_admin                      = is_admin();
    $CI                            = &get_instance();
    $CI->load->model('projects_model');

    foreach ($data as $relation) {
        $relation_values = get_relation_values_complaint_module($relation, $type);
        if ($type == 'project') {
            if (!$has_permission_projects_view) {
                if (!$CI->projects_model->is_member($relation_values['id']) && $rel_id != $relation_values['id']) {
                    continue;
                }
            }
        } elseif ($type == 'lead') {
            if (!has_permission('leads', '', 'view')) {
                if ($relation['assigned'] != get_staff_user_id() && $relation['addedfrom'] != get_staff_user_id() && $relation['is_public'] != 1 && $rel_id != $relation_values['id']) {
                    continue;
                }
            }
        } elseif ($type == 'customer') {
            if (!$has_permission_customers_view && !have_assigned_customers() && $rel_id != $relation_values['id']) {
                continue;
            } elseif (have_assigned_customers() && $rel_id != $relation_values['id'] && !$has_permission_customers_view) {
                if (!is_customer_admin($relation_values['id'])) {
                    continue;
                }
            }
        } elseif ($type == 'contract') {
            if (!$has_permission_contracts_view && $rel_id != $relation_values['id'] && $relation_values['addedfrom'] != get_staff_user_id()) {
                continue;
            }
        } elseif ($type == 'invoice') {
            if (!$has_permission_invoices_view && $rel_id != $relation_values['id'] && $relation_values['addedfrom'] != get_staff_user_id()) {
                continue;
            }
        } elseif ($type == 'estimate') {
            if (!$has_permission_estimates_view && $rel_id != $relation_values['id'] && $relation_values['addedfrom'] != get_staff_user_id()) {
                continue;
            }
        } elseif ($type == 'expense') {
            if (!$has_permission_expenses_view && $rel_id != $relation_values['id'] && $relation_values['addedfrom'] != get_staff_user_id()) {
                continue;
            }
        } elseif ($type == 'proposal') {
            if (!$has_permission_proposals_view && $rel_id != $relation_values['id'] && $relation_values['addedfrom'] != get_staff_user_id()) {
                continue;
            }
        }

        $_data[] = $relation_values;
        //  echo '<option value="' . $relation_values['id'] . '"' . $selected . '>' . $relation_values['name'] . '</option>';
    }

    $_data = hooks()->apply_filters('init_relation_options', $_data, compact('data', 'type', 'rel_id'));

    return $_data;
}

function _my_maybe_create_upload_path($path)
{
    if (!file_exists($path)) {
        mkdir($path, 0777, true);
        fopen(rtrim($path, '/') . '/' . 'index.html', 'w');
    }
}

function handle_complaint_attachments($complaintid, $index_name = 'attachments')
{
    $path           = COMPLAINTS_ATTACHMENTS_FOLDER . $complaintid . '/';
    $uploaded_files = [];

    if (isset($_FILES[$index_name])) {
        _file_attachments_index_fix($index_name);

        for ($i = 0; $i < count($_FILES[$index_name]['name']); $i++) {
            hooks()->do_action('before_upload_complaint_attachment', $complaintid);
            if ($i <= get_option('maximum_allowed_ticket_attachments')) {
                // Get the temp file path
                $tmpFilePath = $_FILES[$index_name]['tmp_name'][$i];
                // Make sure we have a filepath
                if (!empty($tmpFilePath) && $tmpFilePath != '') {
                    // Getting file extension
                    $extension = strtolower(pathinfo($_FILES[$index_name]['name'][$i], PATHINFO_EXTENSION));

                    $allowed_extensions = explode(',', get_option('ticket_attachments_file_extensions'));
                    $allowed_extensions = array_map('trim', $allowed_extensions);
                    // Check for all cases if this extension is allowed
                    if (!in_array('.' . $extension, $allowed_extensions)) {
                        continue;
                    }
                    _my_maybe_create_upload_path($path);
                    $filename    = unique_filename($path, $_FILES[$index_name]['name'][$i]);
                    $newFilePath = $path . $filename;
                    // Upload the file into the temp dir
                    if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                        array_push($uploaded_files, [
                            'file_name' => $filename,
                            'filetype'  => $_FILES[$index_name]['type'][$i],
                        ]);
                    }
                }
            }
        }
    }
    if (count($uploaded_files) > 0) {
        return $uploaded_files;
    }

    return false;
}

if (!function_exists('validarCedulas')) {

    function validarCedulas($cedulaDigitada)
    {
        $total = 0;
        $cedulaDigitada = str_replace("-", "", $cedulaDigitada);


        if ((int) $cedulaDigitada == 0)
            return false;

        $separar = 1;
        $arrayMultiPlicacion = array(1, 2, 1, 2, 1, 2, 1, 2, 1, 2);
        $arrayCedula = desglosar($cedulaDigitada, $separar);

        for ($i = 9; $i >= 0; $i--) {

            $multiPlica = $arrayMultiPlicacion[$i] * $arrayCedula[$i];

            if (strlen($multiPlica) > 1) {
                $total += array_sum(desglosar($multiPlica, $separar));
            } else {
                $total += $multiPlica;
            }
        }

        $decena = $total;
        $ultimoDigitoResultado = (((floor($decena / 10) + 1) * 10) - $total);
        $ultimoDigito = desglosar($ultimoDigitoResultado, 1);
        if ($arrayCedula[10] == $ultimoDigito[(count($ultimoDigito) - 1)]) {

            return true;
        } else {
            return false;
        }
    }
}

/**
 * funcion para desglosar cadena caracteres
 * @param string $cadena
 * @param int $cantidadDigito
 * @return array
 */

if (!function_exists('desglosar')) {
    function desglosar($cadena, $cantidadDigito)
    {
        $arrayTemp = array();
        $long = strlen($cadena);
        for ($i = 0; $i < $long; $i += $cantidadDigito) {
            $sub = substr($cadena, $i, $cantidadDigito);

            $arrayTemp[] = $sub;
        }

        return $arrayTemp;
    }
}

if (!function_exists('my_encrypt')) {
    function my_encode($valor)
    {
        return base64_encode(base64_encode(base64_encode($valor)));
    }
}

if (!function_exists('my_decrypt')) {
    function my_decode($valor)
    {
        return  base64_decode(base64_decode(base64_decode($valor)));
    }
}
