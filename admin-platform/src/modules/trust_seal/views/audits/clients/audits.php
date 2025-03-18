<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="tw-flex tw-items-center tw-justify-between tw-mb-3">
    <h4 class="tw-my-0 tw-font-semibold tw-text-lg tw-text-neutral-700 section-heading section-heading-tickets">
        <?php echo _l('audits'); ?>
    </h4>
</div>
<div class="panel_s">
    <div class="panel-body">
        <?php // defined('BASEPATH') or exit('No direct script access allowed'); 
        ?>


        <div class="mbot15">

            <div class="tw-grid tw-grid-cols-2 md:tw-grid-cols-3 lg:tw-grid-cols-6 tw-gap-2">

                <a href="javascript:void(0);" data-status="-1" class="tw-text-neutral-600 hover:tw-opacity-70 filterMyView">
                    <div class="md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center">
                        <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                            <?php echo total_rows(db_prefix() . 'audits', ['id_customer' => $clientId]); ?>
                        </span>
                        <span class="text-dark tw-truncate sm:tw-text-clip"><?php echo _l('total_audits'); ?></span>
                    </div>
                </a>

                <?php
                foreach (get_clients_area_audits_summary_qualification(get_qualification_audits()) as $status) {
                    // if (in_array($status['status'], [2, 3, 4])) {
                    //     continue;
                    // }
                ?>
                    <a href="javascript:void(0);" data-status="<?php echo $status['translate_name']; ?>" class="tw-text-neutral-600 hover:tw-opacity-70 filterMyView">
                        <div class="md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center">
                            <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                                <?php echo $status['total_audits']; ?></span>
                            <span class="tw-truncate sm:tw-text-clip" style="color:<?php echo $status['statuscolor']; ?>">
                                <?php echo $status['translate_name']; ?></span>
                        </div>
                    </a>

                <?php } ?>
            </div>
        </div>

        <table class="table dt-table table-audits" data-order-col="1" data-order-type="desc">
            <thead>
                <th width="10%" class="th-ticket-number"><?php echo _l('#'); ?></th>
                <th class="th-ticket-project"><?php echo _l('seal'); ?></th>
                <th class="th-ticket-priority"><?php echo _l('audit_qualification'); ?></th>


            </thead>
            <tbody>
                <?php foreach ($audits as $ticket) {

                    $idAudi = (int) $ticket['id'];
                ?>
                    <tr>
                        <td data-order="<?php echo $idAudi; ?>">
                            <a href="<?php echo site_url('trust_seal/clients/audidetails/' . $idAudi); ?>">
                                <?php echo $idAudi; ?>
                            </a>
                        </td>

                        <td>
                            <a href="<?php echo site_url('trust_seal/clients/audidetails/' . $idAudi); ?>">
                                <?php echo  $this->audits_model->get_seal($ticket['id_seal'])[0]['title']; ?>
                            </a>
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
        $(".filterMyView").on("click", function() {

            let val = $(this).data("status");

            if (val != '-1') {
                $('.table-audits').DataTable().column(2).search(val).draw();
            } else {
                $('.table-audits').DataTable().columns().search("").draw();
            }
        });
    });
</script>