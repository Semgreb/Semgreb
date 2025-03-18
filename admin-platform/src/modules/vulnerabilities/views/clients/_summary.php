<h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-flex tw-items-center">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="tw-w-5 tw-h-5 tw-text-neutral-500 tw-mr-1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
    </svg>

    <span>
        <?php echo _l('vulnerability_resume'); ?>
    </span>
</h4>
<div class="row">
    <div class="col-md-2 col-xs-6 md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 last:tw-border-r-0 hover:tw-opacity-70 ">
        <a href="javascript:void(0);" data-status="-1" class="tw-text-neutral-600 filterMyView">
            <div class="tw-flex tw-items-center">
                <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                    <?php echo total_rows(db_prefix() . 'vulnerabilities',  ['id_client' => $client_user_id]); ?>
                </span>
                <span style="color:#464646;">
                    <?php echo _l("total_scans_vulnerabilities"); ?>
                </span>
            </div>
        </a>
    </div>

    <?php foreach (get_status_scan() as $status) {
    ?>

        <div class="col-md-2 col-xs-6 md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 last:tw-border-r-0 hover:tw-opacity-70 ">
            <a href="javascript:void(0);" data-status="<?php echo $status['status']; ?>" class="tw-text-neutral-600 filterMyView">
                <div class="tw-flex tw-items-center">
                    <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                        <?php echo total_rows(db_prefix() . 'vulnerabilities', ['state' => $status['status'], 'id_client' => $client_user_id]); ?>
                    </span>
                    <span style="color:<?php echo $status['status_color']; ?>">
                        <?php echo $status['translate_name']; ?>
                    </span>
                </div>
            </a>
        </div>


    <?php } ?>
</div>
<hr class="hr-panel-separator" />