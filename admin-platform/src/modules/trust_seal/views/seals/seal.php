<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="tw-flex tw-justify-between tw-mb-2">
                    <h4 class="tw-mt-0 tw-font-semibold tw-text-neutral-700">
                        <span class="tw-text-lg"><?php echo $title; ?></span>
                    </h4>
                    <div>

                        <?php if (isset($section)) { ?>
                            <p>
                                <?php if (has_permission('role', '', 'create')) { ?>
                                    <a href="<?php echo admin_url('trust_seal/seal'); ?>" class="btn btn-success pull-right"><?php echo _l('new_seal'); ?></a>
                                <?php } ?>
                                <?php if (has_permission('role', '', 'delete')) { ?>
                                    <a href="<?php echo admin_url('trust_seal/seal/' . $section->id); ?>" class="btn btn-danger _delete pull-right mright5"><?php echo _l('delete'); ?></a>
                                <?php } ?>
                            <div class="clearfix"></div>
                            </p>
                        <?php } ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">

                        <div class="panel_s">
                            <div class="panel-body">
                                <?php echo form_open($this->uri->uri_string(), ['id' => 'seal-form']); ?>
                                <?php $attrs = (isset($seal) ? [] : ['autofocus' => true]); ?>
                                <?php $value = (isset($seal) ? $seal->title : ''); ?>
                                <?php echo render_input('title', _l('title'), (isset($seal) ? $seal->title : ''), 'text', $attrs); ?>
                                <?php echo render_select('exams', $exams, ['id', 'name'], 'exams', (isset($seal) ? $seal->exams : ''), [], [], '', '', false); ?>
                                <?php

                                // echo render_input('date_start', _('seal_date'), (isset($seal) ? $seal->date_start : ''), 'date', []);

                                echo render_date_input('date_start', _l('seal_date'), (isset($seal) ? _d($seal->date_start) : ''), []);

                                ?>


                                <?php //echo render_input('short_description', _('short_description'), (isset($seal) ? $seal->short_description : ''), 'text', $attrs); 
                                ?>

                                <?php echo render_textarea('requirements', _('clients_seal_request_required'), $seal->requirements, [], [], '', '');
                                ?>

                                <?php echo render_textarea('description', _('description'), (isset($seal) ? $seal->description : ''), [], [], ''); ?>

                                <div style="margin-top:7px;" class="text-right">
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
        <?php init_tail(); ?>
        <script>
            function init_seal() {
                window.location.href = '<?php echo admin_url('trust_seal/seals/manage_seals'); ?>';
            }
            $(function() {

                init_editor('#requirements');

                appValidateForm($('#seal-form'), {
                    title: {
                        required: true,
                        maxlength: 150
                    },
                    exams: {
                        required: true,
                    },
                    date_start: {
                        required: true,
                        date: true
                    },
                    // requirements: {
                    //     required: true
                    // },
                    // description: {
                    //     required: true,
                    //     maxlength: 250
                    // },
                });
            });
        </script>
        </body>

        </html>