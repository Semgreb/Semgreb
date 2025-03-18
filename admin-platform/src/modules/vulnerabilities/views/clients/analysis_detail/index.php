<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">

                <div class="panel_s">
                    <div class="panel-body panel-table-full">
                        <?php
                        $this->load->view('clients/analysis_detail/_summary');
                        render_datatable([
                            '#',
                            _l('table_warning_vulnerabilities'),
                            _l('table_resource_vulnerabilities'),
                            _l('table_trust_vulnerabilities'),
                            _l('table_trust_label_vulnerabilities'),
                        ], 'detalles_vulnerabilities');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php

$this->load->view('modal/modal_alert_details');
$this->load->view('modal/modal_alert_details_specific');

?>

<script>
    var tablePlugin = undefined;

    function ColorFormat(cualFormato, label) {
        switch (cualFormato) {

            case 1: //verde
                return `<span class="label ticket-status-${cualFormato}" style="border:1px solid  #a7e8bf; color: #22c55e; background:#f6fdf9;">${label}</span>`;

            case 2: //amarrillo
                return `<span class="label ticket-status-${cualFormato}" style="border:1px solid #ead09b; color: #CA8A04; background:#fdfbf5;">${label}</span>`;

            case 3: //Rojo
                return `<span class="label ticket-status-${cualFormato}" style="border:1px solid #ffabb3; color: #ff2d42; background:#fff7f8;">${label}</span>`;

            case 4: //Proceso
                return `<span class="label ticket-status-${cualFormato}" style="border:1px solid #a8c1f7; color: #2563eb; background:#f6f9fe;">${label}</span>`;
        }
    }


    function shoDetailsAlert(id_alert, id_client, target) {

        $.ajax({
            type: "POST",
            dataType: 'json',
            data: {
                'id_alert': id_alert,
                'id_client': id_client,
                'target': target
            },
            url: "<?php echo  site_url('vulnerabilities/VulnerabilitiesClients/scan_specific_details'); ?>",
            success: function(res) {
                $('#alert_details_specific').html(res.html);
                $('#alert_specific_modal_vulnerabilities').modal('show');
            }
        });

        // $('.id_audit_modal').val(id_audit)
        // $('.id_question_modal').val(id_question)
        // $('.modal-title').text(id_question + '. ' + name)

    }

    function showListAlertProgress(id_scan) {

        $.ajax({
            type: "GET",
            dataType: 'json',
            url: "<?php echo site_url('vulnerabilities/VulnerabilitiesClients/scan_progress/'); ?>" + id_scan,
            success: function(res) {

                $('#alert_modal_vulnerabilities').modal('show');
                //  res = JSON.parse(res);
                var rows = [];
                $('.table_alert_progress_list').dataTable().fnClearTable();
                var template = "";

                if (res.length >= 1) {
                    res.map(function(item) {

                        let cualFormato = 4;
                        if (item.state == "Complete")
                            cualFormato = 1;
                        else if (item.state == "Pending")
                            cualFormato = 2;
                        else
                            cualFormato = 4;

                        let label = ColorFormat(cualFormato, item.state);

                        rows.push(
                            [
                                item.plugin, item.version, item.request, item.warnings, label
                            ]
                        );

                    });

                    if (rows.length > 0) {
                        $('.table_alert_progress_list').dataTable().fnAddData(rows);
                    }
                }
                //$('#table_alert_progress_list tbody').html(template);
                $('#alert_modal_vulnerabilities').modal('show');
            }
        });

        // $('.id_audit_modal').val(id_audit)
        // $('.id_question_modal').val(id_question)
        // $('.modal-title').text(id_question + '. ' + name)

    }

    $(() => {
        var tableApi = initDataTableServerSide('.table-detalles_vulnerabilities', window.location.href);

        $(".my_btn_reload").click(function() {
            setTimeout(function() {
                location.reload();
            }, 100);

            var loadingBtn = $(this).attr("[data-loading-text]");
            loadingBtn.button("loading");
        });

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