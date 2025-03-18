<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row" style="padding: 42px 10px 12px 10px;">
</div>
<div class="row">
    <?php

    // if ($complaints->project_id != 0) {


    ?>
    <!-- <div class="col-md-12 single-ticket-project-area">
        <div class="alert alert-info">
            <?php //echo _l('ticket_linked_to_project', '<a href="' . site_url('clients/project/' . $complaints->project_id) . '"><b>' . get_project_name_by_id($complaints->project_id) . '</b></a>'); 
            ?>
        </div>
    </div> -->
    <?php //} 
    ?>


    <?php set_complaint_open($complaints->clientread, $complaints->complaintid, false); ?>


    <?php echo form_hidden('complaint_id', $complaints->complaintid); ?>
    <div class="col-md-4 ticket-info">
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-inline-flex tw-items-center">
            <?php echo _l('clients_single_complaints_information_heading'); ?>
        </h4>
        <div class="panel_s">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="tw-font-medium tw-my-0">
                            #<?php echo $complaints->complaintid; ?> - <?php echo $complaints->subject; ?>
                        </h4>
                        <div class="tw-divide-solid tw-divide-y tw-divide-neutral-100 tw-mt-4 [&>p:last-child]:tw-pb-0">
                            <p class="tw-py-2.5 tw-mb-0 tw-text-neutral-500">
                                <?php echo _l('clients_ticket_single_department', '<span class="tw-font-medium tw-text-neutral-700">' . $complaints->department_name . '</span>'); ?>
                            </p>
                            <p class="tw-py-2.5 tw-mb-0 tw-text-neutral-500">
                                <?php echo _l('clients_ticket_single_submitted', '<span class="tw-font-medium tw-text-neutral-700">' . _dt($complaints->date) . '</span>'); ?>
                            </p>
                            <p class="tw-py-2.5 tw-mb-0 tw-text-neutral-500">
                                <?php echo _l('ticket_dt_submitter'); ?>:
                                <span class="tw-font-medium tw-text-neutral-700">
                                    <?php echo $complaints->submitter; ?>
                                </span>
                            </p>
                            <div class="tw-py-2">
                                <div class="tw-flex tw-items-center tw-space-x-2">
                                    <span class="tw-text-neutral-500">
                                        <?php echo _l('clients_ticket_single_status'); ?>
                                    </span>
                                    <div class="ticket-status-inline">
                                        <span class="label tw-font-medium" style="background:<?php echo $complaints->statuscolor; ?>">
                                            <?php echo complaint_status_translate($complaints->status); ?>
                                            <?php if (get_option('allow_customer_to_change_ticket_status') == 1) { ?>
                                                <i class="fa-regular fa-pen-to-square pointer toggle-change-ticket-status"></i>
                                        </span>
                                    <?php } ?>
                                    </div>

                                </div>
                            </div>
                            <p class="tw-py-2.5 tw-mb-0 tw-text-neutral-500">
                                <?php echo _l('clients_ticket_single_priority', '<span class="tw-font-medium tw-text-neutral-700">' . ticket_priority_translate($complaints->priorityid) . '</span>'); ?>
                            </p>
                            <?php if (get_option('services') == 1 && !empty($complaints->service_name)) { ?>
                                <p class="tw-py-2.5 tw-mb-0 tw-text-neutral-500">
                                    <?php echo _l('service') . ': <span class="tw-font-medium tw-text-neutral-700">' . $complaints->service_name . '</span>'; ?>
                                </p>
                            <?php } ?>
                            <?php
                            $custom_fields = get_custom_fields('tickets', ['show_on_client_portal' => 1]);
                            foreach ($custom_fields as $field) {
                                $cfValue = get_custom_field_value($complaints->complaintid, $field['id'], 'tickets');
                                if (empty($cfValue)) {
                                    continue;
                                } ?>
                                <p class="tw-py-2.5 tw-mb-0 tw-text-neutral-500">
                                    <?php echo $field['name']; ?>:
                                    <span class="tw-font-medium tw-text-neutral-700"><?php echo $cfValue; ?></span>
                                </p>
                            <?php
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <?php echo form_open_multipart($this->uri->uri_string(), ['id' => 'ticket-reply']); ?>
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-inline-flex tw-items-center">
            <?php echo _l('clients_ticket_single_add_reply_heading'); ?>
        </h4>
        <div class="panel_s single-ticket-reply-area">
            <div class="panel-body">
                <div class="form-group">
                    <textarea name="message" class="form-control" rows="8"></textarea>
                    <?php echo form_error('message'); ?>
                </div>
                <div class="attachments_area">
                    <div class="attachments">
                        <div class="attachment tw-max-w-md">
                            <div class="form-group">
                                <label for="attachment" class="control-label"><?php echo _l('clients_ticket_attachments'); ?></label>
                                <div class="input-group">
                                    <input type="file" extension="<?php echo str_replace(['.', ' '], '', get_option('ticket_attachments_file_extensions')); ?>" filesize="<?php echo file_upload_max_size(); ?>" class="form-control" name="attachments[0]" accept="<?php echo get_ticket_form_accepted_mimes(); ?>">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default add_more_attachments " data-max="<?php echo get_option('maximum_allowed_ticket_attachments'); ?>" type="button">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer text-right">
                <button class="btn btn-primary" type="submit" data-form="#ticket-reply" autocomplete="off" data-loading-text="<?php echo _l('wait_text'); ?>">
                    <?php echo _l('ticket_single_add_reply'); ?>
                </button>
            </div>
        </div>
        <?php echo form_close(); ?>
        <div class="panel_s<?php echo $complaints->admin == null ? ' client-reply' : ''; ?>">
            <div class="panel-heading">
                <h4 class="panel-title"><?php echo _l('clients_single_complaint_string'); ?></h4>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3 border-right tw-font-medium">
                        <?php if ($complaints->admin == null || $complaints->admin == 0) { ?>
                            <p><?php echo $complaints->submitter; ?></p>
                        <?php } else { ?>
                            <p><?php echo $complaints->opened_by; ?></p>
                            <p class="text-muted">
                                <?php echo _l('ticket_staff_string'); ?>
                            </p>
                        <?php } ?>
                    </div>
                    <div class="col-md-9">
                        <?php echo check_for_links($complaints->message); ?><br />
                        <p>-----------------------------</p>
                        <?php if (count($complaints->attachments) > 0) {
                            echo '<hr />';
                            foreach ($complaints->attachments as $attachment) { ?>
                                <?php
                                $path     = COMPLAINTS_ATTACHMENTS_FOLDER . $complaints->complaintid . '/' . $attachment['file_name'];
                                $is_image = is_image($path);

                                if ($is_image) {
                                    echo '<div class="preview_image">';
                                }
                                ?>
                                <a href="<?php echo site_url('complaints/download_complaints/file/complaint/' . $attachment['id']); ?>" class="display-block mbot5">
                                    <i class="<?php echo get_mime_class($attachment['filetype']); ?>"></i>
                                    <?php echo $attachment['file_name']; ?>
                                    <?php if ($is_image) { ?>
                                        <img src="<?php echo site_url('download/preview_image?path=' . protected_file_url_by_path($path) . '&type=' . $attachment['filetype']); ?>" class="mtop5">
                                    <?php } ?>
                                </a>
                        <?php if ($is_image) {
                                    echo '</div>';
                                }
                                echo '<hr />';
                            }
                        } ?>
                    </div>
                </div>
            </div>
        </div>
        <?php foreach ($complaint_replies as $reply) { ?>
            <div class="panel_s<?php echo $reply['admin'] == null ? ' client-reply' : ''; ?>">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3 border-right tw-font-medium">
                            <p><?php echo $reply['submitter']; ?></p>
                            <p class="text-muted">
                                <?php if ($reply['admin'] !== null) {
                                    echo _l('ticket_staff_string');
                                }
                                ?>
                            </p>
                        </div>
                        <div class="col-md-9">
                            <?php echo check_for_links($reply['message']); ?><br />
                            <p>-----------------------------</p>
                            <?php if (count($reply['attachments']) > 0) {
                                echo '<hr />';
                                foreach ($reply['attachments'] as $attachment) {
                                    $path     = COMPLAINTS_ATTACHMENTS_FOLDER . $complaints->complaintid . '/' . $attachment['file_name'];
                                    $is_image = is_image($path);
                                    if ($is_image) {
                                        echo '<div class="preview_image">';
                                    } ?>
                                    <a href="<?php echo site_url('complaints/download_complaints/file/complaint/' . $attachment['id']); ?>" class="inline-block mbot5">
                                        <i class="<?php echo get_mime_class($attachment['filetype']); ?>"></i>
                                        <?php echo $attachment['file_name']; ?>
                                        <?php if ($is_image) { ?>
                                            <img src="<?php echo site_url('download/preview_image?path=' . protected_file_url_by_path($path) . '&type=' . $attachment['filetype']); ?>" class="mtop5">
                                        <?php } ?>
                                    </a>
                            <?php if ($is_image) {
                                        echo '</div>';
                                    }
                                    echo '<hr />';
                                }
                            } ?>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <span><?php echo _l('clients_single_ticket_replied', _dt($reply['date'])); ?></span>
                </div>
            </div>
        <?php } ?>
    </div>

</div>
<?php if (count($complaint_replies) > 1) { ?>
    <a href="#top" id="toplink">↑</a>
    <a href="#bot" id="botlink">↓</a>
<?php } ?>