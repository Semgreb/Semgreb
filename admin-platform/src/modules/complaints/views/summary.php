<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row">
    <?php
    $statuses = $this->complaints_model->get_complaint_status();
    ?>


    <div class="_filters _hidden_inputs hidden complaints_filters">
        <?php

        echo form_hidden('my_complaints');
        echo form_hidden('merged_complaints');

        if (is_admin()) {

            $ticket_assignees = $this->complaints_model->get_complaints_assignes_disctinct();

            foreach ($ticket_assignees as $assignee) {
                echo form_hidden('complaint_assignee_' . $assignee['assigned']);
            }
        }
        foreach ($statuses as $status) {
            $val = '';
            if ($chosen_ticket_status != '') {
                if ($chosen_ticket_status == $status['complaintsstatusid']) {
                    $val = $chosen_ticket_status;
                }
            } else {
                if (in_array($status['complaintsstatusid'], $default_tickets_list_statuses)) {
                    $val = 1;
                }
            }
            echo form_hidden('complaint_status_' . $status['complaintsstatusid'], $val);
        } ?>
    </div>
    <div class="col-md-12">
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-flex tw-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="tw-w-5 tw-h-5 tw-text-neutral-500 tw-mr-1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
            </svg>

            <span>
                <?php echo _l('support_complaints'); ?>
            </span>
        </h4>
    </div>


    <div class="col-md-2 col-xs-6 md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 last:tw-border-r-0">
        <a href="#" class="tw-text-neutral-600 hover:tw-opacity-70" data-cview="all" onclick="dt_custom_view_c('.table-complaints',4,''); return false;">

            <!-- <a href="javascript:void(0);" data-status="<?php //echo $status['complaintsstatusid']; 
                                                            ?>" class="tw-text-neutral-600 hover:tw-opacity-70 filterMyView"> -->

            <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                <?php echo total_rows(db_prefix() . 'complaints', 'merged_complaint_id IS NULL'); ?>
            </span>


            <span>
                <?php echo _l('Total'); ?>
            </span>
        </a>
    </div>





    <?php
    $where = '';
    if (!is_admin()) {


        if (get_option('staff_access_only_assigned_departments') == 1) {


            $departments_ids = [];


            if (count($staff_deparments_ids) == 0) {
                $departments = $this->departments_model->get();
                foreach ($departments as $department) {
                    array_push($departments_ids, $department['departmentid']);
                }
            } else {
                $departments_ids = $staff_deparments_ids;
            }


            if (count($departments_ids) > 0) {
                $where = 'AND department IN (SELECT departmentid FROM ' . db_prefix() . 'staff_departments WHERE departmentid IN (' . implode(',', $departments_ids) . ') AND staffid="' . get_staff_user_id() . '")';
            }
        }
    }
    foreach ($statuses as $status) {
        $_where = '';

        if ($where == '') {
            $_where = 'status=' . $status['complaintsstatusid'];
        } else {
            $_where = 'status=' . $status['complaintsstatusid'] . ' ' . $where;
        }

        // if (isset($project_id)) {
        //     $_where = $_where . ' AND project_id=' . $project_id;
        // }

        $_where = $_where . ' AND merged_complaint_id IS NULL'; ?>


        <div class="col-md-2 col-xs-6 md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 last:tw-border-r-0">
            <a href="#" class="tw-text-neutral-600 hover:tw-opacity-70" data-cview="complaint_status_<?php echo $status['complaintsstatusid']; ?>" onclick="dt_custom_view('complaint_status_<?php echo $status['complaintsstatusid']; ?>','.table-complaints','complaint_status_<?php echo $status['complaintsstatusid']; ?>', true); return false;">

                <!-- <a href="javascript:void(0);" data-status="<?php //echo $status['complaintsstatusid']; 
                                                                ?>" class="tw-text-neutral-600 hover:tw-opacity-70 filterMyView"> -->

                <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                    <?php echo total_rows(db_prefix() . 'complaints', $_where); ?>
                </span>


                <span style="color:<?php echo $status['statuscolor']; ?>">
                    <?php echo complaint_status_translate($status['complaintsstatusid']); ?>
                </span>
            </a>
        </div>
    <?php
    } ?>
</div>