<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<h4 class="customer-profile-group-heading"><?php echo _l('client_reminders_tab'); ?></h4>
<?php if (isset($client)) { ?>
    <!-- <a href="#" data-toggle="modal" data-target=".reminder-modal-customer-<?php //echo $client->userid; 
                                                                                ?>" class="btn btn-primary mbot15">
        <i class="fa-regular fa-bell"></i> <?php //echo _l('set_reminder'); 
                                            ?>
    </a> -->
    <div class="clearfix"></div>
<?php
    render_datatable([_l('reminder_description'), _l('reminder_date')], 'reminders-certifications');
    // $this->load->view('admin/includes/modals/reminder', ['id' => $client->userid, 'name' => 'customer', 'members' => $members, 'reminder_title' => _l('set_reminder')]);
} ?>
<div id="contact_data"></div>
<div id="consent_data"></div>
<script>
    window.addEventListener('load', function() {
        initDataTable('.table-reminders-certifications', '<?php echo admin_url('trust_seal/certifications/get_reminders_certifications'); ?>/' + customer_id + '/' + 'certifications',
            undefined, undefined, undefined, [1, 'asc']);
    });
</script>