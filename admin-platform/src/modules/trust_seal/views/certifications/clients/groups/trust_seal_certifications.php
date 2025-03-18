<?php
if (isset($client)) {

    defined('BASEPATH') or exit('No direct script access allowed'); ?>
    <?php //init_head(); 
    ?>
    <div class="col-md-12">
        <div class="tw-mb-2 sm:tw-mb-4">
            <a href="<?php echo admin_url('trust_seal/certifications/certification/0/' . $client->userid); ?>" class="btn btn-primary">
                <i class="fa-regular fa-plus tw-mr-1"></i>
                <?php echo _l('new_certification'); ?>
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
                    <?php echo _l('certifications'); ?>
                </span>
            </h4>

        </div>

        <!-- <div class="panel-table-full"> -->
        <?php render_datatable([
            [
                'name'     =>         _l('seal_nui'),
                'th_attrs' => ['class' => 'text-center'],
            ],
            _l('seal'),
            // _l('certification_customer'),
            _l('date'),
            _l('certification_date_expired'),
            _l('audit_status'),
            [
                'name'     => _l('you_seals_digital'),
                'th_attrs' => ['class' => 'not-export'],
            ],
        ], 'clients-certifications', ['number-index-1']); ?>
        <!-- </div> -->
        <!-- </div>
        </div> -->
    </div>
    </div>
    </div>
    <code id="sourceCode" style="display: none;">

        var data;
        $.ajax({
        type: "GET",
        url: "js-tutorials.com_sample_file.csv",
        dataType: "text",
        success: function(response)
        {
        data = $.csv.toArrays(response);
        generateHtmlTable(data);
        }
        });

    </code>
    <?php $this->load->view('trust_seal/certifications/download.php'); ?>
    <?php init_tail(); ?>
    <?php $this->load->view('trust_seal/utility.php'); ?>
    <script>
        $(function() {
            initDataTable('.table-clients-certifications', '<?php echo admin_url('trust_seal/certifications/manage') . '?idClientes=' . $client->userid; ?>', [1], [1]);


            $(".table-clients-certifications th").last().append('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i data-toggle="tooltip" data-title="<?php echo _l('tooltip_text_seals'); ?>" class="fa fa-circle-info tw-mr-1"></i>');

            $(".table-clients-certifications th").first().append('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i data-toggle="tooltip" data-title="<?php echo _l('tooltip_text_nui_seals'); ?>" class="fa fa-circle-info tw-mr-1"></i>');

            $(".table-clients-certifications tbody").on("click", ".btn_file_enable", function(e) {
                e.preventDefault();
                let seal = $(this).data("seal");
                let sealinactive = $(this).data("sealinactive");
                let nameseal = $(this).data("nameseal");

                if (seal != "" && sealinactive != "") {

                    showLogoDownload(nameseal, seal, sealinactive);
                    //window.open(seal, '_blank');
                } else {

                    alert_float('danger', "<?php echo _l('file_no_found_seal'); ?>");
                }
            });
        });
    </script>

<?php } ?>