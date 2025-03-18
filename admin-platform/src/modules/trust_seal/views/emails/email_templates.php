<div class="col-md-12">
    <h4 class="tw-font-semibold email-template-heading">
        <?php echo _l('trust_seal'); ?>
        <?php if ($hasPermissionEdit) { ?>
            <a href="<?php echo admin_url('emails/disable_by_type/trust_seal'); ?>" class="pull-right mleft5 mright25"><small><?php echo _l('disable_all'); ?></small></a>
            <a href="<?php echo admin_url('emails/enable_by_type/trust_seal'); ?>" class="pull-right"><small><?php echo _l('enable_all'); ?></small></a>
        <?php } ?>
    </h4>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>
                        <span class="tw-font-semibold">
                            <?php echo _l('email_templates_table_heading_name'); ?>
                        </span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($trust_seal as $ticket_template) { ?>
                    <tr>
                        <td class="<?php if ($ticket_template['active'] == 0) {
                                        echo 'text-throught';
                                    } ?>">
                            <a href="<?php echo admin_url('emails/email_template/' . $ticket_template['emailtemplateid']); ?>"><?php echo $ticket_template['name']; ?></a>
                            <?php if (ENVIRONMENT !== 'production') { ?>
                                <br /><small><?php echo $ticket_template['slug']; ?></small>
                            <?php } ?>
                            <?php if ($hasPermissionEdit) { ?>
                                <a href="<?php echo admin_url('emails/' . ($ticket_template['active'] == '1' ? 'disable/' : 'enable/') . $ticket_template['emailtemplateid']); ?>" class="pull-right"><small><?php echo _l($ticket_template['active'] == 1 ? 'disable' : 'enable'); ?></small></a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>