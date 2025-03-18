<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="tw-mb-2 sm:tw-mb-4">

                    <?php if (has_permission('seals', '', 'create')) { ?>
                        <a href="<?php echo admin_url('trust_seal/seals/seal'); ?>" class="btn btn-primary">
                            <i class="fa-regular fa-plus tw-mr-1"></i>
                            <?php echo _l('new_seal'); ?>
                        </a>
                    <?php } ?>
                </div>
                <div class="panel_s">
                    <div class="panel-body">

                        <div class="mbot15">
                            <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-flex tw-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="tw-w-5 tw-h-5 tw-text-neutral-500 tw-mr-1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>

                                <span>
                                    <?php echo _l('seals'); ?>
                                </span>
                            </h4>
                            <div class="tw-grid tw-grid-cols-2 md:tw-grid-cols-3 lg:tw-grid-cols-6 tw-gap-2">

                                <a href="javascript:void(0);" data-status="-1" class="tw-text-neutral-600 hover:tw-opacity-70 tw-inline-flex tw-items-centernn filterMyView">
                                    <div class="md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center">
                                        <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                                            <?php echo total_rows(db_prefix() . 'seals'); ?>
                                        </span>
                                        <span class="text-dark tw-truncate sm:tw-text-clip"><?php echo _l('total_seals'); ?></span>
                                    </div>
                                </a>

                                <?php
                                foreach (get_clients_area_audits_summary(get_status_seals()) as $status) {

                                ?>
                                    <a href="javascript:void(0);" data-status="<?php echo $status['status']; ?>" class="tw-text-neutral-600 hover:tw-opacity-70 tw-inline-flex tw-items-centernn filterMyView">
                                        <div class="md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center">
                                            <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                                                <?php echo total_rows(db_prefix() . 'seals', 'status=' . $status['status']); ?></span>
                                            <span class="tw-truncate sm:tw-text-clip" style="color:<?php echo $status['status_color']; ?>">
                                                <?php echo $status['translate_name']; ?></span>
                                        </div>
                                    </a>

                                <?php } ?>
                            </div>
                        </div>

                        <div class="panel-table-full">
                            <?php render_datatable([
                                [
                                    'name'     =>    '#',
                                    'th_attrs' => ['class' => 'text-center'],
                                ],
                                _l('title'),
                                _l('exams'),
                                _l('seal_date'),
                                _l('seal_attach'),
                                _l('audit_status'),
                            ], 'seals', ['number-index-1']); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(function() {
        initDataTable('.table-seals', window.location.href, [], [], "", [0, "desc"]);

        var tableApi = $('.table-seals').DataTable();

        $(".filterMyView").on("click", function() {
            let val = $(this).data("status");
            if (val != '-1') {
                tableApi.column(5).search(val).draw();
            } else {
                tableApi.columns().search("").draw();
            }
        });

    });
</script>
</body>

</html>