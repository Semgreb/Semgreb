<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tickets-summary-heading">
    <?php echo _l('seals'); ?>
</h4>



<div class="tw-flex tw-items-center tw-justify-between tw-mb-3">
    <h4 class="tw-my-0  tw-text-sm tw-text-neutral-30 section-heading section-heading-tickets">
        <?php echo _l('seals_request_desc'); ?>
    </h4>
</div>

<div class="panel_s">
    <div class="panel-body">
        <?php // defined('BASEPATH') or exit('No direct script access allowed'); 
        ?>
        <table class="table dt-table table-seals-request" data-order-col="1" data-order-type="desc">
            <thead>
                <tr width="10%" class="th-ticket-number" style="background-color: none;">&nbsp;</tr>
            </thead>
            <tbody>
                <?php foreach ($sealsList as $seal) {
                ?>
                    <tr>
                        <td data-order="">
                            <div class="media">
                                <div class="media-left media-top ">
                                    <a href="#">
                                        <img class="img-rounded" src="<?php echo base_url(PATH_SEALS . '/' . $seal['id'] . '/' . $seal['logo_active']); ?>" alt=" <?php echo $seal['title']; ?>" style="width: 64px;height: 64px;">
                                    </a>
                                </div>
                                <div class="media-body">
                                    <h4 class="media-heading" style="color:#0038b4;"><?php echo $seal['title']; ?></h4>

                                    <div class="row">
                                        <div class="col-md-10">
                                            <?php echo $seal['description']; ?>
                                        </div>
                                        <div class="col-md-2">
                                            <a href="<?php echo site_url('trust_seal/clients/open_ticket/' . $seal['id']);

                                                        //site_url('clients/open_ticket'); 
                                                        ?>" class="btn btn-primary new-ticket">
                                                <?php echo _l('request_seals_f'); ?>

                                                <i class="fa-regular fa-arrow-right tw-mr-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php }
                ?>
            </tbody>
        </table>
    </div>
</div>