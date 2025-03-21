<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="_buttons tw-mb-2 sm:tw-mb-4">

                    <?php if (has_permission('complaints', '', 'create')) { ?>
                        <a href="<?php echo admin_url('complaints/add'); ?>" class="btn btn-primary pull-left display-block mright5">
                            <i class="fa-regular fa-plus tw-mr-1"></i>
                            <?php echo _l('new_complaints'); ?>
                        </a>
                    <?php } ?>


                    <a href="#" class="btn btn-default btn-with-tooltip" data-toggle="tooltip" data-placement="bottom" data-title="<?php echo _l('tickets_chart_weekly_opening_stats'); ?>" onclick="slideToggle('.weekly-ticket-opening',init_tickets_weekly_chart); return false;"><i class="fa fa-bar-chart"></i>
                    </a>


                    <div class="btn-group pull-right mleft4 btn-with-tooltip-group _filter_data" data-toggle="tooltip" data-title="<?php echo _l('filter_by'); ?>">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-filter" aria-hidden="true"></i>
                        </button>


                        <ul class="dropdown-menu dropdown-menu-right width300">
                            <li>
                                <a href="#" data-cview="all" onclick="dt_custom_view_c('.table-complaints',4,''); return false;">
                                    <?php echo _l('task_list_all'); ?>
                                </a>
                            </li>
                            <li>
                                <a href="" data-cview="my_complaints" onclick="dt_custom_view('my_complaints','.table-complaints','my_complaints'); return false;"><?php echo _l('my_complaints_assigned'); ?></a>
                            </li>
                            <li>
                                <a href="" data-cview="merged_complaints" onclick="dt_custom_view('merged_complaints','.table-complaints','merged_complaints'); return false;"><?php echo _l('merged'); ?></a>
                            </li>
                            <li class="divider"></li>
                            <?php foreach ($statuses as $status) {

                            ?>
                                <li class="<?php if ($status['complaintsstatusid'] == $chosen_ticket_status || $chosen_ticket_status == '' && in_array($status['complaintsstatusid'], $default_tickets_list_statuses)) {
                                                echo 'active';
                                            } ?>">
                                    <a href="#" data-cview="complaint_status_<?php echo $status['complaintsstatusid']; ?>" onclick="dt_custom_view('complaint_status_<?php echo $status['complaintsstatusid']; ?>','.table-complaints','complaint_status_<?php echo $status['complaintsstatusid']; ?>'); return false;">
                                        <?php echo complaint_status_translate($status['complaintsstatusid']); ?>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if (count($ticket_assignees) > 0 && is_admin()) {

                            ?>
                                <div class="clearfix"></div>
                                <li class="divider"></li>
                                <li class="dropdown-submenu pull-left">
                                    <a href="#" tabindex="-1"><?php echo _l('filter_by_assigned'); ?></a>
                                    <ul class="dropdown-menu dropdown-menu-left">
                                        <?php foreach ($ticket_assignees as $as) {

                                        ?>
                                            <li>
                                                <a href="#" data-cview="complaint_assignee_<?php echo $as['assigned']; ?>" onclick="dt_custom_view(<?php echo $as['assigned']; ?>,' .table-complaints','complaint_assignee_<?php echo $as['assigned']; ?>'); return false;"><?php echo get_staff_full_name($as['assigned']); ?></a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="weekly-ticket-opening no-shadow tw-mb-10" style="display:none;">
                            <h4 class="tw-font-semibold tw-mb-8 tw-flex tw-items-center tw-text-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="tw-w-5 tw-h-5 tw-mr-1.5 tw-text-neutral-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                                </svg>

                                <?php echo _l('home_weekend_complaint_opening_statistics'); ?>
                            </h4>
                            <div class="relative" style="max-height:350px;">
                                <canvas class="chart" id="weekly-ticket-openings-chart" height="350"></canvas>
                            </div>
                        </div>

                        <?php //hooks()->do_action('before_render_tickets_list_table'); 
                        ?>

                        <?php $this->load->view('complaints/summary'); ?>
                        <hr class="hr-panel-separator" />
                        <a href="#" data-toggle="modal" data-target="#tickets_bulk_actions" class="bulk-actions-btn table-btn hide" data-table=".table-tickets"><?php echo _l('bulk_actions'); ?></a>
                        <div class="clearfix"></div>
                        <div class="panel-table-full">
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
                            ], 'complaints', ['number-index-1']); ?>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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
    var chart;
    var chart_data = <?php echo $weekly_complaints_opening_statistics; ?>;

    function init_tickets_weekly_chart() {
        if (typeof(chart) !== 'undefined') {
            chart.destroy();
        }
        // Weekly ticket openings statistics
        chart = new Chart($('#weekly-ticket-openings-chart'), {
            type: 'line',
            data: chart_data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: false,
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                        }
                    }]
                }
            }
        });
    }

    function dt_custom_view_c(table, column, val) {
        var tableApi = $(table).DataTable();

        var _cinput = do_filter_active(val);

        if (Array.isArray(val)) {
            tableApi.column(column).search(val.join("|"), true, false).draw();
        } else {
            tableApi.column(column).search(val).draw();
        }
    }


    //  $(function() {

    var ComplaintServerParams = {},
        Complaints_Filters = $("._hidden_inputs._filters.complaints_filters input");
    var complaints_date_created_index = $(
        "table.complaints-table thead .complaint_created_column"
    ).index();
    $.each(Complaints_Filters, function() {
        ComplaintServerParams[$(this).attr("name")] =
            '[name="' + $(this).attr("name") + '"]';
    });


    initDataTable('.table-complaints', window.location.href, [], [], ComplaintServerParams,
        [0, "desc"]);

    // var tableApi = $('.table-complaints').DataTable();

    // // $(".filterMyView").on("click", function() {
    // //     let val = $(this).data("status");
    // //     tableApi.column(4).search(val).draw();
    // //     var _cinput = do_filter_active(`complaint_status_${val}`);
    // // });
    // });
</script>
</body>

</html>