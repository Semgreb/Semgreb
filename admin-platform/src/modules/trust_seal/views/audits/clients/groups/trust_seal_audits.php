<?php
if (isset($client)) {
?>

    <?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
    <?php //init_head(); 
    ?>
    <div class="col-md-12">
        <div class="tw-mb-2 sm:tw-mb-4">
            <a href="<?php echo admin_url('trust_seal/audits/audit/0/' . $client->userid); ?>" class="btn btn-primary">
                <i class="fa-regular fa-plus tw-mr-1"></i>
                <?php echo _l('new_audit'); ?>
            </a>
        </div>
        <!-- <div class="panel_s">
            <div class="panel-body"> -->

        <div class="mbot15">
            <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-flex tw-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="tw-w-5 tw-h-5 tw-text-neutral-500 tw-mr-1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>

                <span>
                    <?php echo _l('audits'); ?>
                </span>
            </h4>

        </div>

        <!-- <div class="panel-table-full"> -->
        <?php render_datatable([
            [
                'name'     =>    '#',
                'th_attrs' => ['class' => 'text-center'],
            ],
            _l('seals'),
            _l('audit_qualification'),
            _l('audit_status'),
        ], 'client-audits', ['number-index-1']); ?>
        <!-- </div> -->
        <!-- </div>
        </div> -->
        <!-- </div>
    </div> -->

        <?php init_tail();
        ?>
        <script>
            $(function() {
                initDataTable('.table-client-audits', '<?php echo admin_url('trust_seal/audits/manage') . '?idClientes=' . $client->userid; ?>', [1], [1]);
            });
        </script>
    <?php
}

    ?>