<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">

        <?php

        if (isset($audit->id)) {
            $audit->id = (int) $audit->id;
        }


        $displayAlert = "display='none'";
        if ($exist_audit) {
            $displayAlert = "";
        }
        ?>
        <div class="alert alert-warning alert_seal_exist" <?php echo $displayAlert; ?>>
            <?php echo _l('audit_certification_exist'); ?>
        </div>


        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="tw-flex tw-justify-between tw-mb-2">
                    <h4 class="tw-mt-0 tw-font-semibold tw-text-neutral-700">
                        <span class="tw-text-lg"><?php echo $title; ?></span>
                        <?php


                        if (has_permission('audits', '', 'delete') || is_admin()) {
                            $idAudit = isset($audit->id) ? $audit->id : 0;

                            if ($idAudit > 0) {

                        ?>
                                <div class="btn-group">
                                    <a href="#" class="dropdown-toggle btn-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <?php

                                        if (has_permission('audits', '', 'delete')) {

                                        ?>
                                            <li>
                                                <a href="<?php echo admin_url('trust_seal/audits/delete/' . $idAudit); ?>" class="text-danger delete-text _delete"><i class="fa fa-remove"></i>
                                                    <?php echo _l('delete'); ?>
                                                </a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                        <?php
                            }
                        } ?>
                    </h4>
                    <div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">

                        <div class="panel_s">
                            <div class="panel-body">

                                <div class="horizontal-scrollable-tabs panel-full-width-tabs">
                                    <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
                                    <div class="horizontal-tabs">
                                        <ul class="nav nav-tabs nav-tabs-horizontal" role="tablist">
                                            <li role="presentation" class="tablinks active" onclick="changeTab(event, 'tab_detail')" id="tab-detail">
                                                <a aria-controls="tab_detail" role="tab" data-toggle="tab">
                                                    <?php echo _l('about_audit'); ?>
                                                </a>
                                            </li>
                                            <?php if (isset($audit) == true) { ?>
                                                <li role="presentation" class="tablinks" onclick="changeTab(event, 'tab_exams')" id="tab-exams">
                                                    <a aria-controls="tab_exams" role="tab" data-toggle="tab">
                                                        <?php echo _l('exams'); ?>
                                                    </a>
                                                </li>

                                                <!-- <li role="presentation" class="tablinks" onclick="changeTab(event, 'tab_questionario')" id="tab_questionario">
                                                    <a aria-controls="tab_questionario" role="tab" data-toggle="tab">
                                                        <?php //echo _l('exams'); ?>
                                                    </a>
                                                </li> -->

                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>

                                <?php
                                $attrsAll = [];
                                $autoAsignar = "";
                                $attrsAllString = "";

                                if (isset($audit) == true) {
                                    $autoAsignar = $audit->auto_asignar;
                                ?>

                                <?php } ?>

                                <div role="tabpanel" class="tab-pane<?php if (!$this->input->get('tab')) {
                                                                        echo ' active';
                                                                    }; ?> tabcontent" id="tab_detail">
                                    <?php echo form_open($this->uri->uri_string(), ['id' => 'audit-form']); ?>
                                    <?php $attrs = (isset($audit) ? [] : ['autofocus' => true]); ?>
                                    <?php $value = (isset($audit) && isset($audit->namespace) ? $audit->namespace : ''); ?>
                                    <?php echo render_textarea('description', _('description'), (isset($audit) ? $audit->description : ''), [], [], ''); ?>


                                    <?php

                                    $idCustomerMarcado = '';
                                    $attrsCustomer = [];
                                    $displayIfCompleteAndApproved =  'style="display: none;"';
                                    $notificationCheck = '';
                                    $reminderCheck = '';
                                    $idCustomerMarcado = $client_selected > 0 ? $client_selected : '';

                                    if (isset($audit) && isset($audit->id_customer)) {
                                        $idCustomerMarcado  = $audit->id_customer;
                                        $attrsCustomer = ['disabled' => true];

                                        echo render_input('id_customer', '', $audit->id_customer, 'hidden');
                                        echo render_input('id_seal', '', $audit->id_seal, 'hidden');

                                        if ($audit->status ==  2 &&  $audit->qualification == 2) {
                                            $displayIfCompleteAndApproved = '';
                                            $attrsAll = ['disabled' => true];
                                            $attrsAllString = 'disabled';
                                        }

                                        if ($audit->reminder == 1) {
                                            $notificationCheck = 'checked';
                                        }
                                        if ($audit->reminder == 1) {
                                            $reminderCheck = 'checked';
                                        }
                                    }

                                    if ($autoAsignar == 1) {
                                        $attrsAll = ['disabled' => true];
                                        $attrsAllString = 'disabled';
                                    }


                                    echo render_select('id_customer', $customers, ['userid', 'company'], _l('trust_customer'), $idCustomerMarcado,  $attrsCustomer, [], '', '', false); ?>


                                    <?php echo render_select('id_seal', $seals, ['id', 'title'], _('seal'), (isset($audit) ? $audit->id_seal : ''),  $attrsAll, [], '', '', false); ?>
                                    <?php if (isset($audit) == true) { ?>
                                        <?php echo render_select('status', $status, ['id', 'name'], 'audit_status', (isset($audit) ? $audit->status : ''), array_merge(['id' => 'status'],  []), [], '', '', false); ?>
                                    <?php } ?>
                                    <?php if (isset($audit) == true) { ?>
                                        <?php echo render_select('qualification', $qualification, ['id', 'name'], 'audit_qualification', (isset($audit) ? $audit->qualification : ''), array_merge(['id' => 'qualification'], []), [], '', '', false); ?>
                                    <?php } ?>



                                    <div class="checkbox row_auto_certifications" <?php echo $displayIfCompleteAndApproved; ?>>
                                        <input type="checkbox" name="auto_asignar" id="auto_asignar" data-auts="<?php echo $autoAsignar; ?>">
                                        <label for="auto_asignar"><?php echo _l('audits_auto_asignar'); ?></label>

                                    </div>


                                    <div class="checkbox row_auto_certifications" <?php echo $displayIfCompleteAndApproved; ?>>
                                        <input type="checkbox" name="notification" id="notification" <?php echo $notificationCheck; ?> value="1">
                                        <label for="notification"><?php echo _l('audits_send_notification'); ?></label>
                                    </div>




                                    <div style="text-align:right;">
                                        <a href="#" class="btn btn-default " onclick="init_seal(); return false;"><?php echo _l('cancel'); ?></a>
                                        <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
                                    </div>

                                    <?php echo form_close(); ?>
                                </div>
                                <div role="tabpanel" class="tab-pane tabcontent" id="tab_exams" style="display:none;">
                                  <div class="col-12">

                                    <div class="mbot15">
                                        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-flex tw-items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="tw-w-5 tw-h-5 tw-text-neutral-500 tw-mr-1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                            </svg>

                                            <span>
                                                <?php echo _l('exams'); ?>
                                            </span>
                                        </h4>
                         
                                    </div>

                                        <div class="panel-table-full">
                                            <?php

                                            $table_data  = [];
                                            $_table_data = [
                                                [
                                                    'name'     => '#',
                                                    'th_attrs' => ['class' => 'toggleable'],
                                                ],
                                                [
                                                    'name'     => _l('exams'),
                                                    'th_attrs' => ['class' => 'toggleable'],
                                                ],
                                                [
                                                    'name'     => "% "._l('trust_seal_audit_compliance'),
                                                    'th_attrs' => ['class' => 'toggleable'],
                                                ],
                                                [
                                                    'name'     => _l('trust_seal_audit_progress'),
                                                    'th_attrs' => ['class' => 'toggleable'],
                                                ]
                                            ];

                                            foreach ($_table_data as $_t) {
                                                array_push($table_data, $_t);
                                            }

                                            render_datatable($_table_data, 'exams', ['number-index-1'], [
                                                'data-last-order-identifier' => 'exams',
                                                'data-default-order'         => get_table_last_order('exams'),
                                            ]); ?>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
        <?php init_tail(); ?>
        <script>
            function init_seal() {
                window.location.href = '<?php echo admin_url('trust_seal/audits/manage'); ?>';
            }
            $(function() {
                //http://perfex.crm.indotel/admin/trust_seal/audits/audit/1
                initDataTable('.table-exams', '<?php echo admin_url('trust_seal/audits/table_exams_group/').$audit->id_seal."/".$audit->id; ?>', [], [], "", [0, "desc"]);


                $("#id_seal, #id_customer").change(function() {
                    let id_seal = $("#id_seal").val();
                    let id_customer = $("#id_customer").val();

                    $.get('<?php echo   admin_url('trust_seal/audits/if_exist_certifications_with_this_seal_and_client/'); ?>' + id_seal + '/' + id_customer, function(response) {
                        if (response.exist_audit) {
                            $('.alert_seal_exist').show();
                        } else {
                            $('.alert_seal_exist').hide();
                        }
                    }, 'json');
                });

                // init_editor('#description');

                $("#status, #qualification").change(function() {
                    let qualification = $('#qualification').val();
                    let status = $('#status').val();
                    // &&  $audit->status == 2
                    //                     &&  $audit->qualification == 2
                    //if (auts != 1) {
                    if (qualification == 2 && status == 2) {
                        $(".row_auto_certifications").show();
                    } else {
                        $("#auto_asignar, #reminder, #notification").prop('checked', false);
                        $(".row_auto_certifications").hide();
                    }
                    //}
                });

                $("#status, #qualification").trigger('change');
            });

            <?php if ($this->input->get('tab') == 'detail' || !$this->input->get('tab')) { ?>
                document.getElementById("tab-detail").click();
            <?php } ?>
            <?php if ($this->input->get('tab') == 'exams') { ?>
                document.getElementById("tab-exams").click();
            <?php } ?>

            function changeTab(evt, cityName) {
                var i, tabcontent, tablinks;
                tabcontent = document.getElementsByClassName("tabcontent");
                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }
                tablinks = document.getElementsByClassName("tablinks");
                for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                }
                document.getElementById(cityName).style.display = "block";
                evt.currentTarget.className += " active";
            }

            function show_hide(id) {
                let val = document.getElementById('content_sections_' + id + '_val');
                if (val.value == 0) {
                    val.value = 1;
                    document.getElementById('content_sections_' + id).style.display = "none";
                    // document.getElementById('btn_ds_' + id).style.display = "none";
                } else {
                    val.value = 0;
                    document.getElementById('content_sections_' + id).style.display = "block";
                    //document.getElementById('btn_ds_' + id).style.display = "block";
                }
            }


            $('.btnCheck, .btnNone').click(function() {


                let id_audit = $(this).data('cod');
                let id_customer = $(this).data('customer');
                let id_question = $(this).data('pregunta');
                let approved = $(this).data('status');

                if (approved == 1) {

                    $(this).css({
                        "color": "white",
                        "background-color": "#22c55e"
                    });

                    let btnNone = $(this).closest('div').find('.btnNone');

                    btnNone.css({
                        "color": "#8c8c8c ",
                        "background-color": "white"
                    });

                } else {
                    $(this).css({
                        "color": "white",
                        "background-color": "red"
                    });

                    let btnCheck = $(this).closest('div').find('.btnCheck');

                    btnCheck.css({
                        "color": "#8c8c8c ",
                        "background-color": "white"
                    });
                }

                audit_exam(id_audit, id_customer, id_question, approved);
            });



            function audit_exam(id_audit, id_customer, id_question, approved) {

                let datos = {
                    "id_audit": id_audit,
                    "id_customer": id_customer,
                    "id_question": id_question,
                    "approved": approved
                };
                $.ajax({
                    url: '<?php echo admin_url('trust_seal/audits/add_audit_exam/'); ?>',
                    type: 'POST',
                    data: datos
                });
                // alert_float("success", "");
            }

            function showComments(name, id_audit, id_question) {
                $('#comments_list').html("");
                $.ajax({
                    type: "GET",
                    url: "<?php echo admin_url('trust_seal/audits/get_comment/'); ?>" + id_audit + "/" + id_question,
                    success: function(res) {
                        res = JSON.parse(res);
                        console.log(res);

                        let template = '<ul>';

                        if (res.length >= 1) {
                            res.map(function(item) {

                                template += `<li style="margin-bottom:30px;"><h4>${item}</h4></li>`;
                            });
                            template += '</ul>';

                            $('#comments_list').html(template);
                        }

                    }
                });

                $('.id_audit_modal').val(id_audit)
                $('.id_question_modal').val(id_question)
                $('.modal-title').text(id_question + '. ' + name)
                $('#audit_commet').modal('show')
            }
        </script>
        </body>

        </html>