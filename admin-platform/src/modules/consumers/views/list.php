<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- <div class="_buttons tw-mb-2 sm:tw-mb-4"> -->

                <?php if (has_permission('consumers', '', 'create')) {  ?>
                    <a href="<?php echo admin_url('consumers/add'); ?>" class="btn btn-primary pull-left display-block mright5">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?php echo _l('new_consumer'); ?>
                    </a>
                <?php }  ?>

                <br />
                <br />

                <!-- <div class="btn-group pull-right mleft4 btn-with-tooltip-group _filter_data" data-toggle="tooltip" data-title="<?php echo _l('filter_by'); ?>">

                    </div> -->
                <!-- </div> -->
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="weekly-ticket-opening no-shadow tw-mb-10" style="display:none;">
                            <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-flex tw-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="tw-w-5 tw-h-5 tw-mr-1.5 tw-text-neutral-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                                </svg>

                                <?php echo _l('home_weekend_ticket_opening_statistics'); ?>
                            </h4>
                            <div class="relative" style="max-height:350px;">
                                <canvas class="chart" id="weekly-ticket-openings-chart" height="350"></canvas>
                            </div>
                        </div>

                        <?php //hooks()->do_action('before_render_tickets_list_table'); 
                        ?>
                        <div class="col-md-12">
                            <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-flex tw-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="tw-w-5 tw-h-5 tw-text-neutral-500 tw-mr-1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>

                                <span>
                                    <?php echo _l('consumers_menu'); ?>
                                </span>
                            </h4>
                        </div>
                        <?php //$this->load->view('complaints/summary');
                        ?>
                        <hr class="hr-panel-separator" />


                        <!-- <a href="#" data-toggle="modal" data-target="#tickets_bulk_actions" class="bulk-actions-btn table-btn hide" data-table=".table-consumers"><?php echo _l('bulk_actions'); ?></a> -->


                        <div class="clearfix"></div>
                        <div class="panel-table-full">
                            <?php

                            //echo AdminTicketsTableStructure('', true); 
                            render_datatable([
                                [
                                    'name'     =>    '#',
                                    'th_attrs' => ['class' => 'text-center'],
                                ],
                                _l('consumer_open_complaint_firstname'),
                                // _l('consumer_open_complaint_lastname'),
                                _l('consumer_open_complaint_birthday_date'),
                                _l('consumer_open_complaint_email'),
                                _l('consumer_open_complaint_phonenumber'),
                                _l('consumers_dt_datecreated'),
                                _l('consumers_dt_dateupdated'),
                            ], 'consumers', ['number-index-1']); ?>


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
                    <?php //echo render_select('move_to_status_tickets_bulk', $statuses, ['ticketstatusid', 'name'], 'ticket_single_change_status'); 
                    ?>
                    <?php //echo render_select('move_to_department_tickets_bulk', $departments, ['departmentid', 'name'], 'department'); 
                    ?>
                    <?php //echo render_select('move_to_priority_tickets_bulk', $priorities, ['priorityid', 'name'], 'ticket_priority'); 
                    ?>
                    <div class="form-group">
                        <?php echo '<p><b><i class="fa fa-tag" aria-hidden="true"></i> ' . _l('tags') . ':</b></p>'; ?>
                        <input type="text" class="tagsinput" id="tags_bulk" name="tags_bulk" value="" data-role="tagsinput">
                    </div>
                    <?php if (get_option('services') == 1) { ?>
                        <?php //echo render_select('move_to_service_tickets_bulk', $services, ['serviceid', 'name'], 'service'); 
                        ?>
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
                            <?php //foreach ($statuses as $status) { 
                            ?>
                            <!-- <option value="<?php //echo $status['ticketstatusid']; 
                                                ?>"><?php //echo $status['name']; 
                                                    ?>
                            </option> -->
                            <?php //} 
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <a href="#" class="btn btn-primary" return false;"><?php echo _l('confirm'); ?></a>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php init_tail(); ?>
<script>
    $(function() {
        initDataTable('.table-consumers', window.location.href, [5], [5]);
        // $('.table-goals').DataTable().on('draw', function() {
        //     var rows = $('.table-goals').find('tr');
        //     $.each(rows, function() {
        //         var td = $(this).find('td').eq(6);
        //         var percent = $(td).find('input[name="percent"]').val();
        //         $(td).find('.goal-progress').circleProgress({
        //             value: percent,
        //             size: 45,
        //             animation: false,
        //             fill: {
        //                 gradient: ["#28b8da", "#059DC1"]
        //             }
        //         })
        //     })
        // })
    });
</script>
</body>

</html>