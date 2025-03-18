<h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-flex tw-items-center">
    <span>
        <i class="fa fa-globe" aria-hidden="true"></i>
        <?php echo $analisis_vulnerabilities->web_site
        ?>
    </span>
    <?php if (
        $analisis_vulnerabilities->state == 1
        ||  $analisis_vulnerabilities->state_spider == 1
    ) { ?>
        &nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-default btn-sm my_btn_reload" data-loading-text="<?php echo _l('wait_text'); ?>" type="button"><span><i class="fa fa-refresh"></i></span></button>

    <?php } ?>
</h4>


<div class="row">
    <div class="col-md-9">
        &nbsp;
    </div>
    <div class="col-md-3 text-center">


        <?php

        $labelScanner =  _l('vulnerabilities_scan_bar');

        if ($analisis_vulnerabilities->state == 3) {
            $statusAnalisis = 100;
            $labelScanner =  _l('vulnerabilities_scan_bar_complete');
        }

        if (
            $analisis_vulnerabilities->state_spider != 2
            &&  $analisis_vulnerabilities->state != 2
        ) { ?>
            <?php
            //if ($analisis_vulnerabilities->state == 1 || $analisis_vulnerabilities->state_spider == 1) {
            ?>
            <p class="project-info tw-mb-0 tw-font-small tw-text-base tw-tracking-tight" style="font-size:.9rem; margin-bottom:8px;">
                <?php echo $labelScanner; ?> <span class="tw-text-neutral-500"><?php echo $statusAnalisis == 100 ? $statusAnalisis : $statusAnalisis; ?>%</span>
            </p>


            <div class="progress progress-bar-mini">
                <div class="progress-bar progress-bar-success no-percent-text not-dynamic" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $statusAnalisis; ?>%;" data-percent="<?php echo $statusAnalisis; ?>">
                </div>
            </div>

            <?php //}
            ?>

            <p class="project-info tw-mb-0 tw-font-small tw-text-base" style="font-size:.8rem; margin-top:-10px;">
                <?php
                if (
                    $analisis_vulnerabilities->state_spider == 3
                ) {
                ?>

                    <a href="javascript:void(0);" onclick="showListAlertProgress('<?php echo $analisis_vulnerabilities->id; ?>')" data-idscan="<?php echo $analisis_vulnerabilities->analisis_id; ?>" class="see_detail">
                        <?php echo _l("table_see_details_vulnerabilities"); ?>
                    </a>

                <?php } elseif (
                    $analisis_vulnerabilities->state == 4
                    ||  $analisis_vulnerabilities->state == 5
                ) {
                    $status = '';

                    foreach (get_status_scan() as $qualification) {
                        if ($qualification['status'] ==  $analisis_vulnerabilities->state) {
                            $status = get_status_audits_format($qualification);
                            break;
                        }
                    }
                ?>
                    <?php echo  $status; ?>

                <?php } else { ?>
                    <?php echo _l("vulnerabilities_spider_run"); ?>
                <?php } ?>
            </p>

        <?php } ?>


    </div>
</div>

<div class="row">

    <div class="col-md-2 col-xs-6 md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 last:tw-border-r-0 hover:tw-opacity-70 ">
        <a href="javascript:void(0);" data-status="-1" class="tw-text-neutral-600 filterMyView">
            <div class="tw-flex tw-items-center">
                <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                    <?php echo total_rows_alert_db(db_prefix() . 'list_alert_vulnerabilities', 'id_analyzes=' . $id_analyzes); ?>
                </span>
                <span style="color:#464646;">
                    <?php echo _l("table_trust_vulnerabilities_all"); ?>
                </span>
            </div>
        </a>
    </div>

    <?php

    $row = 0;
    foreach (get_risk_scan() as $status) {
    ?>

        <div class="col-md-2 col-xs-6 md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 last:tw-border-r-0 hover:tw-opacity-70 ">
            <a href="javascript:void(0);" data-status="<?php echo ucfirst(strtolower($status['status'])); ?>" class="tw-text-neutral-600 filterMyView">
                <div class="tw-flex tw-items-center">
                    <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                        <?php echo total_rows_alert_db(db_prefix() . 'list_alert_vulnerabilities', 'id_analyzes=' . $id_analyzes . ' AND risk="' . $status['status'] . '"'); ?>
                    </span>
                    <span style="color:<?php echo $status['status_color']; ?>">
                        <?php
                        if ($row > 0) {
                            echo  _l('table_trust_vulnerabilities') . " " . $status['translate_name'];
                        } else {
                            echo $status['translate_name'];
                        }
                        ?>
                    </span>
                </div>
            </a>
        </div>


    <?php
        $row++;
    } ?>


    <!-- <div class="col-md-2 col-xs-6 md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 last:tw-border-r-0">
        <div class="tw-flex tw-items-center">
            <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                <?php //echo $informative_count 
                ?>
            </span>
            <span>
                Informativo
            </span>
        </div>

    </div> -->

    <!--  <div class="col-md-2 col-xs-6 md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 last:tw-border-r-0">
        <div class="tw-flex tw-items-center">
            <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                <?php echo $analysis_in_process ?>

            </span>
            <span style="color:blue">
                <?php echo _l('in_process'); ?>

            </span>
        </div>

    </div> -->
</div>
<hr class="hr-panel-separator" />