<?php
if (isset($client)) {

    $this->load->model('complaints/complaints_model');
    $this->load->model('departments_model');
    $statuses = $this->complaints_model->get_complaint_status();
    $staff_deparments_ids =
        $this->departments_model->get_staff_departments(get_staff_user_id(), true);
    $departments = $this->departments_model->get();
    $priorities = $this->tickets_model->get_priority();
    $services = $this->complaints_model->get_service();
    $ticket_assignees =  $this->tickets_model->get_tickets_assignes_disctinct();
    $bodyclass = 'tickets-page';
    $chosen_ticket_status              = '';
    add_admin_tickets_js_assets();
    $default_tickets_list_statuses = hooks()->apply_filters('default_tickets_list_statuses', [1, 2, 4]);
?>

    <div class="_buttons tw-mb-2 md:tw-mb-4">
        <a href="<?php echo admin_url('complaints/add'); ?>" class="btn btn-primary pull-left display-block mright5">
            <i class="fa-regular fa-plus tw-mr-1"></i>
            <?php echo _l('new_complaints'); ?>
        </a>
        <br />
        <br />
    </div>
    <!-- <div class="panel_s">
        <div class="panel-body"> -->
    <div class="weekly-ticket-opening no-shadow tw-mb-10" style="display:none;">
        <h4 class="tw-font-semibold tw-mb-8 tw-flex tw-items-center tw-text-lg">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="tw-w-5 tw-h-5 tw-mr-1.5 tw-text-neutral-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
            </svg>

            <?php echo _l('home_weekend_ticket_opening_statistics'); ?>
        </h4>
        <div class="relative" style="max-height:350px;">
            <canvas class="chart" id="weekly-ticket-openings-chart" height="350"></canvas>
        </div>
    </div>

    <?php hooks()->do_action('before_render_tickets_list_table'); ?>
    <?php // $this->load->view('complaints/summary'); 
    ?>
    <!-- <hr class="hr-panel-separator" />
            <a href="#" data-toggle="modal" data-target="#tickets_bulk_actions" class="bulk-actions-btn table-btn hide" data-table=".table-tickets"><?php echo _l('bulk_actions'); ?></a>
            <div class="clearfix"></div> -->
    <!-- <div class="panel-table-full"> -->
    <?php

    //echo AdminTicketsTableStructure('', true); 
    render_datatable([
        [
            'name'     =>    '#',
            'th_attrs' => ['class' => 'text-center'],
        ],
        _l('complaint_dt_subject'),
        _l('complaints_dt_consumer'),
        _l('complaint_dt_service'),
        _l('complaint_dt_status'),
        _l('complaint_dt_priority'),
        _l('complaint_dt_last_reply'),
        _l('complaint_dt__date_created'),
    ], 'complaints  table no-footer',  ['number-index-1']); ?>


    <!-- </div> -->
    <!-- </div>
    </div> -->

    <div class="modal fade bulk_actions" id="tickets_bulk_actions" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><?php echo _l('bulk_actions'); ?></h4>
                </div>
                <div class="modal-body">
                    <div class="checkbox checkbox-primary merge_tickets_checkbox">
                        <input type="checkbox" name="merge_tickets" id="merge_tickets">
                        <label for="merge_tickets"><?php echo _l('merge_tickets'); ?></label>
                    </div>
                    <?php if (is_admin()) { ?>
                        <div class="checkbox checkbox-danger mass_delete_checkbox">
                            <input type="checkbox" name="mass_delete" id="mass_delete">
                            <label for="mass_delete"><?php echo _l('mass_delete'); ?></label>
                        </div>
                        <hr class="mass_delete_separator" />
                    <?php } ?>
                    <div id="bulk_change">
                        <?php echo render_select('move_to_status_tickets_bulk', $statuses, ['complaintsstatusid', 'name'], 'ticket_single_change_status'); ?>
                        <?php echo render_select('move_to_department_tickets_bulk', $departments, ['departmentid', 'name'], 'department'); ?>
                        <?php echo render_select('move_to_priority_tickets_bulk', $priorities, ['priorityid', 'name'], 'ticket_priority'); ?>
                        <div class="form-group">
                            <?php echo '<p><b><i class="fa fa-tag" aria-hidden="true"></i> ' . _l('tags') . ':</b></p>'; ?>
                            <input type="text" class="tagsinput" id="tags_bulk" name="tags_bulk" value="" data-role="tagsinput">
                        </div>
                        <?php if (get_option('services') == 1) { ?>
                            <?php echo render_select('move_to_service_tickets_bulk', $services, ['serviceid', 'name'], 'service'); ?>
                        <?php } ?>
                    </div>
                    <div id="merge_tickets_wrapper">
                        <div class="form-group">
                            <label for="primary_ticket_id">
                                <span class="text-danger">*</span> <?php echo _l('primary_ticket'); ?>
                            </label>
                            <select id="primary_ticket_id" class="selectpicker" name="primary_ticket_id" data-width="100%" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex') ?>" required>
                                <option></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="primary_ticket_status">
                                <span class="text-danger">*</span> <?php echo _l('primary_ticket_status'); ?>
                            </label>
                            <select id="primary_ticket_status" class="selectpicker" name="primary_ticket_status" data-width="100%" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex') ?>" required>
                                <?php foreach ($statuses as $status) { ?>
                                    <option value="<?php echo $status['complaintsstatusid']; ?>"><?php echo $status['name']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                    <a href="#" class="btn btn-primary" onclick="tickets_bulk_action(this); return false;"><?php echo _l('confirm'); ?></a>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <?php init_tail(); ?>
    <script>
        $(function() {
            initDataTable('.table-complaints', '<?php echo admin_url('complaints/index/') . '0/' . $client->userid; ?>');

            var tableApi = $('.table-complaints').DataTable();
            //tableApi.column(4).search(1).draw();

            $(".filterMyView").on("click", function() {
                let val = $(this).data("status");
                tableApi.column(4).search(val).draw();
            });
        });
    </script>
<?php } ?>