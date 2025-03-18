<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">

            <div class="col-md-12">
                <h3><?php


                    // echo $analisis_vulnerabilities->id_client . '-' . $client_details->company;

                    ?></h3>
                <?php
                if ($analisis_vulnerabilities->state !=  1 &&  $analisis_vulnerabilities->state !=  4) {
                    if (has_permission('vulnerabilities', '', 'create')) {  ?>
                        <button type="button" data-id_analyzes="<?php echo $analisis_vulnerabilities->id; ?>" data-client="<?php echo $analisis_vulnerabilities->id_client; ?>" data-idscan="<?php echo $analisis_vulnerabilities->analisis_id; ?>" data-idscanspider="<?php echo $analisis_vulnerabilities->spider_analisis_id; ?>" data-loading-text="<?php echo _l('wait_text'); ?>" data-action="1" data-url="<?php echo $analisis_vulnerabilities->web_site;  ?>" ; class="table-btn btn_current_scan hide" data-table=".table-detalles_vulnerabilities"><?php echo _l('table_new_vulnerabilities'); ?></button>
                <?php  }
                }
                ?>

                <?php
                if ($analisis_vulnerabilities->state ==  1 ||  $analisis_vulnerabilities->state ==  4) {
                    if (has_permission('vulnerabilities', '', 'edit')) {  ?>
                        <button type="button" data-id_analyzes="<?php echo $analisis_vulnerabilities->id; ?>" data-client="<?php echo $analisis_vulnerabilities->id_client; ?>" data-idscan="<?php echo $analisis_vulnerabilities->analisis_id; ?>" data-url="<?php echo  $analisis_vulnerabilities->web_site;  ?>" data-idscanspider="<?php echo $analisis_vulnerabilities->spider_analisis_id; ?>" data-loading-text="<?php echo _l('wait_text'); ?>" data-action="3" class="table-btn btn_current_scan hide" data-table=".table-detalles_vulnerabilities"><?php echo _l('table_stop_vulnerabilities'); ?></button>
                <?php  }
                } ?>

                <?php if (has_permission('vulnerabilities', '', 'delete')) {  ?>
                    <button type="button" data-id_analyzes="<?php echo $analisis_vulnerabilities->id; ?>" data-client="<?php echo $analisis_vulnerabilities->id_client; ?>" data-idscan="<?php echo $analisis_vulnerabilities->analisis_id; ?>" data-url="<?php echo  $analisis_vulnerabilities->web_site;  ?>" data-idscanspider="<?php echo $analisis_vulnerabilities->spider_analisis_id; ?>" data-loading-text="<?php echo _l('wait_text'); ?>" data-action="2" class="table-btn btn_current_scan hide" data-table=".table-detalles_vulnerabilities"><?php echo _l('table_remove_vulnerabilities'); ?></button>
                <?php  } ?>

                <div class="panel_s">
                    <div class="panel-body panel-table-full">
                        <?php
                        $this->load->view('analysis_detail/_summary');
                        ?>
                        <?php
                        render_datatable([
                            '#',
                            _l('table_warning_vulnerabilities'),
                            _l('table_resource_vulnerabilities'),
                            _l('table_trust_vulnerabilities'),
                            _l('table_trust_label_vulnerabilities'),
                        ], 'detalles_vulnerabilities'); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail();

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
            url: "<?php echo admin_url('vulnerabilities/scan_specific_details'); ?>",
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
            url: "<?php echo admin_url('vulnerabilities/scan_progress/'); ?>" + id_scan,
            success: function(res) {
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

    $(function() {

        $(".my_btn_reload").click(function() {
            setTimeout(function() {
                location.reload();
            }, 100);

            var loadingBtn = $(this).attr("[data-loading-text]");
            loadingBtn.button("loading");
        });

        var tableApi = initDataTable('.table-detalles_vulnerabilities', window.location.href);
        tablePlugin = initDataTable('.table_alert_progress_list');
        //$('.table_alert_progress_list').DataTable();

        $(".filterMyView").on("click", function() {
            let val = $(this).data("status");
            if (val != '-1') {
                tableApi.column(3).search(val).draw();
            } else {
                tableApi.columns().search("").draw();
            }
        });

        $('.btn_current_scan').click(function(event) {

            if (confirm_executions()) {

                let action = $(this).data("action");
                let id_client = $(this).data("client");
                let id_analyzes = $(this).data("id_analyzes");
                let target = $(this).data("url");
                let idscan = $(this).data("idscan");
                let idscanspider = $(this).data("idscanspider");

                var newForm = $('<form>', {
                    'action': "<?php echo admin_url('vulnerabilities/store'); ?>/" + action,
                    'method': 'post'
                });

                newForm.append($('<input>', {
                    'name': csrfData.token_name,
                    'value': csrfData.hash,
                    'type': 'hidden'
                }));

                newForm.append($('<input>', {
                    'name': "clientid",
                    'value': id_client,
                    'type': 'hidden'
                }));

                newForm.append($('<input>', {
                    'name': "web_site",
                    'value': target,
                    'type': 'hidden'
                }));

                newForm.append($('<input>', {
                    'name': "idscan",
                    'value': idscan,
                    'type': 'hidden'
                }));

                newForm.append($('<input>', {
                    'name': "idspiderscan",
                    'value': idscanspider,
                    'type': 'hidden'
                }));

                newForm.append($('<input>', {
                    'name': "id_analyzes",
                    'value': id_analyzes,
                    'type': 'hidden'
                }));

                event.preventDefault();
                newForm.appendTo($('body'));
                newForm.submit();

            }
        });

    });
</script>
</script>
</body>

</html>