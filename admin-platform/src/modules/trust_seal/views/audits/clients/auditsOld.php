<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tickets-summary-heading">
    <?php echo _l('audits_summary'); ?>
</h4>

<dl class="tw-grid tw-grid-cols-1 md:tw-grid-cols-5 tw-gap-2 sm:tw-gap-4 tw-mt-2 tw-mb-10">
    <?php
    foreach (get_clients_area_audits_summary(get_status_audits()) as $status) {

    ?>
        <a href="javascript:void(0);" class="tw-border tw-border-solid tw-border-neutral-200 tw-rounded-md hover:tw-bg-neutral-100 <?php
                                                                                                                                    echo in_array($status['status'], $list_statuses) ? 'tw-bg-white' : 'tw-bg-neutral-50 '; ?>">
            <div class="tw-px-4 tw-py-5 sm:tw-px-4 sm:tw-py-2">
                <dt class="tw-font-medium " style="color:<?php echo $status['status_color']; ?>">
                    <?php echo $status['translated_name']; ?>
                </dt>
                <dd class="tw-mt-1 tw-flex tw-items-baseline tw-justify-between md:tw-block lg:tw-flex">
                    <div class="tw-flex tw-items-baseline tw-text-base tw-font-semibold tw-text-primary-600">
                        <?php echo $status['total_audits'] ?>
                    </div>
                </dd>
            </div>
        </a>
    <?php } ?>
</dl>

<div class="tw-flex tw-items-center tw-justify-between tw-mb-3">
    <h4 class="tw-my-0 tw-font-semibold tw-text-lg tw-text-neutral-700 section-heading section-heading-tickets">
        <?php echo _l('audits'); ?>
    </h4>
</div>
<div class="panel_s">
    <div class="panel-body">
        <?php // defined('BASEPATH') or exit('No direct script access allowed'); 
        ?>
        <table class="table dt-table table-certifications" data-order-col="1" data-order-type="desc">
            <thead>
                <th width="10%" class="th-ticket-number"><?php echo _l('#'); ?></th>


                <th class="th-ticket-project"><?php echo _l('seal'); ?></th>
                <th class="th-ticket-priority"><?php echo _l('audit_qualification'); ?></th>


            </thead>
            <tbody>
                <?php foreach ($audits as $ticket) { ?>
                    <tr>
                        <td data-order="<?php echo $ticket['id']; ?>">
                            <a href="<?php echo site_url('trust_seal/clients/audidetails/' . $ticket['id']); ?>">
                                #<?php echo $ticket['id']; ?>
                            </a>
                        </td>


                        <td>
                            <?php
                            echo $this->audits_model->get_seal($ticket['id_seal'])[0]['title']
                            ?>
                        </td>

                        <td>
                            <?php
                            foreach (get_qualification_audits() as $qualification) {
                                if ($qualification['qualification'] == $ticket['qualification']) {
                                    echo get_qualification_format($qualification);
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
<script>
    $(function() {
        //tableApi.column(4).search(1).draw();


        $(".filterMyView").on("click", function() {

            //   var tableApi = $('.table-certifications').dataTable();

            let val = $(this).data("status");

            if (val != '-1') {
                $('.table-certifications').DataTable().column(4).search(val).draw();
            } else {
                $('.table-certifications').DataTable().columns().search("").draw();
            }
        });
    });
</script>