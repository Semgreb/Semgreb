<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tickets-summary-heading">
    <?php echo _l('seals_summary'); ?>
</h4>

<dl class="tw-grid tw-grid-cols-1 md:tw-grid-cols-5 tw-gap-2 sm:tw-gap-4 tw-mt-2 tw-mb-10">
    <?php
    foreach (get_status_seals() as $status) {
        if ($status['status'] != 1) {
            continue;
        }
    ?>
        <a href="javascript:void(0);" class="tw-border tw-border-solid tw-border-neutral-200 tw-rounded-md hover:tw-bg-neutral-100 
        <?php
        echo in_array($status['status'], [1]) ? 'tw-bg-white' : 'tw-bg-neutral-50 '; ?>">
            <div class="tw-px-4 tw-py-5 sm:tw-px-4 sm:tw-py-2">
                <dt class="tw-font-medium " style="color:<?php echo $status['status_color']; ?>">
                    <?php echo $status['translate_name']; ?>
                </dt>
                <dd class="tw-mt-1 tw-flex tw-items-baseline tw-justify-between md:tw-block lg:tw-flex">
                    <div class="tw-flex tw-items-baseline tw-text-base tw-font-semibold tw-text-primary-600">
                        <?php echo count($seals);
                        ?>
                    </div>
                </dd>
            </div>
        </a>
    <?php } ?>
</dl>

<!-- <div class="tw-flex tw-items-center tw-justify-between tw-mb-3">
    <h4 class="tw-my-0 tw-font-semibold tw-text-lg tw-text-neutral-700 section-heading section-heading-tickets">
        <?php echo _l('audits'); ?>
    </h4>
</div> -->
<div class="tw-flex tw-items-center tw-justify-between tw-mb-3">
    <h4 class="tw-my-0 tw-font-semibold tw-text-lg tw-text-neutral-700 section-heading section-heading-tickets">
        <?php echo _l('seals'); ?>
    </h4>
    <a href="<?php echo site_url('trust_seal/clients/request_seals');

                //site_url('clients/open_ticket'); 
                ?>" class="btn btn-primary new-ticket">
        <i class="fa-regular fa-plus tw-mr-1"></i>
        <?php echo _l('request_new_seal'); ?>
    </a>
</div>
<div class="panel_s">
    <div class="panel-body">
        <?php // defined('BASEPATH') or exit('No direct script access allowed'); 
        ?>
        <table class="table dt-table table-certifications" data-order-col="1" data-order-type="desc">
            <thead>
                <th width="10%" class="th-ticket-number"><?php echo _l('#'); ?></th>
                <th class="th-ticket-department"><?php echo _l('title'); ?></th>
                <th class="th-ticket-project"><?php echo _l('exams'); ?></th>
                <th class="th-ticket-priority"><?php echo _l('seal_date'); ?></th>
                <th class="th-ticket-priority"><?php echo _l('seal_attach'); ?></th>
                <th class="th-ticket-priority"><?php echo _l('status'); ?></th>
            </thead>
            <tbody>
                <?php foreach ($seals as $ticket) { ?>
                    <tr>
                        <td data-order="<?php echo $ticket['id']; ?>">
                            #<?php echo $ticket['id']; ?>
                        </td>
                        <td>
                            <a href="<?php echo site_url('trust_seal/clients/sealdetails/' . $ticket['id']); ?>">
                                <?php echo $ticket['title']; ?>
                            </a>
                        </td>


                        <td>
                            <?php
                            echo   '1 ' . _l('exams');
                            ?>
                        </td>
                        <td>
                            <?php
                            echo '<p>' . date_format(date_create($ticket['date_start']), 'd-m-Y') . '</p>';
                            ?>
                        </td>

                        <td>
                            <?php
                            echo '0 ' . _l('seal_attach');
                            ?>
                        </td>

                        <td>
                            <?php
                            foreach (get_status_seals() as $qualification) {
                                if ($qualification['status'] == $ticket['status']) {
                                    echo get_status_audits_format($qualification);
                                    break;
                                }
                            }
                            ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>