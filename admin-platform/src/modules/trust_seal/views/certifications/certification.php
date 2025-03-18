<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="tw-flex tw-justify-between tw-mb-2">
                    <h4 class="tw-mt-0 tw-font-semibold tw-text-neutral-700">
                        <span class="tw-text-lg"><?php echo $title; ?></span>
                        <?php if (has_permission('certifications', '', 'delete') || is_admin()) {

                            $idCt =  isset($certification->id) ? $certification->id : 0;

                            if ($idCt > 0) {

                        ?>
                                <div class="btn-group">
                                    <a href="#" class="dropdown-toggle btn-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <?php if (has_permission('certifications', '', 'delete')) {


                                        ?>
                                            <li>
                                                <a href="<?php echo admin_url('trust_seal/certifications/delete/' . $idCt); ?>" class="text-danger delete-text _delete"><i class="fa fa-remove"></i>
                                                    <?php echo _l('delete'); ?>
                                                </a>
                                            </li>
                                        <?php

                                        }
                                        ?>
                                    </ul>
                                </div>
                        <?php
                            }
                        } ?>
                    </h4>
                    <div>
                    </div>
                </div>

                <?php
                $displayIfCompleteAndApproved =  'style="display: none;"';
                $notificationCheck = '';
                $reminderCheck = '';
                $attrsAll = [];


                ?>

                <div class="row">
                    <div class="col-md-12">

                        <div class="panel_s">
                            <div class="panel-body">

                                <div class="horizontal-scrollable-tabs panel-full-width-tabs">
                                    <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
                                    <div class="horizontal-tabs">
                                        <ul class="nav nav-tabs nav-tabs-horizontal" role="tablist">
                                            <li role="presentation" class="tablinks active" id="tab-detail">
                                                <a aria-controls="tab_detail" role="tab" data-toggle="tab">
                                                    <?php echo _l('about_certification'); ?>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div role="tabpanel" class="tab-pane<?php if (!$this->input->get('tab')) {
                                                                        echo ' active';
                                                                    }; ?> tabcontent" id="tab_detail">
                                    <?php echo form_open($this->uri->uri_string(), ['id' => 'certification-form']); ?>

                                    <?php
                                    if (isset($certification) && isset($certification->id_customer)) {
                                        if ($certification->status == 1) {

                                            $displayIfCompleteAndApproved = '';
                                            $attrsAll = ['disabled' => true];
                                            $attrsAllString = 'disabled';

                                            echo form_hidden('id_customer', $certification->id_customer);
                                            echo form_hidden('id_seal', $certification->id_seal);
                                        }
                                    }

                                    ?>



                                    <?php $attrs = (isset($certification) ? [] : ['autofocus' => true]); ?>
                                    <?php $value = (isset($certification) && isset($certification->namespace) ? $certification->namespace : ''); ?>
                                    <?php

                                    echo render_input('certificationskey', 'certification', isset($certification) ? $certification->certificationkey : '', 'text', ['disabled' => true]);

                                    //echo render_input('name', _l('name'), (isset($certification) ? $certification->name : ''), 'text', $attrs); 
                                    ?>
                                    <?php
                                    if ($cliente  == '') {
                                        $cliente = isset($certification) && isset($certification->id_customer) ? $certification->id_customer : '';
                                    }

                                    if ($sello == '') {
                                        $sello = isset($certification) ? $certification->id_seal : '';
                                    }

                                    if ($client_selected > 0) {
                                        $cliente = $client_selected;
                                    }

                                    echo render_select('id_customer', $customers, ['userid', 'company'], _l('customers'), $cliente,  $attrsAll, [], '', '', false); ?>
                                    <?php

                                    echo render_select('id_seal', $seals, ['id', 'title'], _('seals'), $sello,  $attrsAll, [], '', '', false); ?>
                                    <?php if (isset($certification) == true) { ?>
                                        <div style="display:flex;">
                                            <div style="width:50%;padding-right:10px;box-sizing:border-box;">
                                                <?php echo render_select('status', $status, ['id', 'name'], 'audit_status', (isset($certification) ? $certification->status : ''), [], [], '', '', false); ?>
                                            </div>
                                            <div style="width:50%;">
                                                <?php
                                                echo render_date_input('date_expiration', _l('certification_date_expired'), (isset($certification) ? _d($certification->date_expiration) : ''), []);

                                                ?>


                                            </div>
                                        </div>

                                        <div class="checkbox row_auto_certifications" <?php echo $displayIfCompleteAndApproved; ?>>
                                            <input type="checkbox" name="notification" id="notification" <?php echo $notificationCheck; ?> />
                                            <label for="notification"><?php echo _l('audits_send_notification'); ?></label>
                                        </div>

                                        <div class="checkbox row_auto_certifications" <?php echo $displayIfCompleteAndApproved; ?>>
                                            <input type="checkbox" name="reminder" id="reminder" <?php echo $reminderCheck; ?> />
                                            <label for="reminder"><?php echo _l('audits_send_reminder'); ?></label>
                                        </div>

                                    <?php } else {
                                        echo render_date_input('date_expiration', _l('certification_date_expired'), (isset($certification) ? _d($certification->date_expiration) : ''), []);
                                    } ?>



                                    <div style="text-align:right;">


                                        <a href="#" class="btn btn-default " onclick="init_seal(); return false;"><?php echo _l('cancel'); ?></a>
                                        <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
                                    </div>

                                    <?php echo form_close(); ?>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
        <?php init_tail(); ?>
        </body>
        <script>
            function init_seal() {
                window.location.href = '<?php echo admin_url('trust_seal/certifications/manage'); ?>';
            }

            $(function() {
                $("#status").change(function() {
                    if ($(this).val() == 1) {
                        $(".row_auto_certifications").show();
                    } else {
                        $("#reminder, #notification").prop('checked', false);
                        $(".row_auto_certifications").hide();
                    }
                });
            });
        </script>

        </html>