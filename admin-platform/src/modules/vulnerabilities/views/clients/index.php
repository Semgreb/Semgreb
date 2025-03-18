<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">

                <div class="panel_s">
                    <div class="panel-body panel-table-full">
                        <?php
                        $this->load->view('clients/_summary');
                        render_datatable([
                            '#',
                            _l('web_site'),
                            _l('date'),
                            _l('table_warning_vulnerabilities'),
                            _l('table_trust_vulnerabilities'),
                            _l('state'),
                        ], 'vulnerabilities');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(() => {
        initDataTableServerSide('.table-vulnerabilities', window.location.href);

        var tableApi = $('.table-vulnerabilities').DataTable();

        $(".filterMyView").on("click", function() {
            let val = $(this).data("status");

            if (val != '-1') {
                tableApi.column(3).search(val).draw();
            } else {
                tableApi.columns().search("").draw();
            }
        });

    });
</script>
</body>

</html>