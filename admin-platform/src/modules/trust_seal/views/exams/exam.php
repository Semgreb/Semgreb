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
                    </div>
                </div>

                <div class="row">
                    <?php echo form_open($this->uri->uri_string(), ['id' => 'exam-form']); ?>

                    <div class="col-md-12">

                        <div class="panel_s">
                            <div class="panel-body">

                                <div class="horizontal-scrollable-tabs panel-full-width-tabs">
                                    <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
                                    <div class="horizontal-tabs">
                                        <ul class="nav nav-tabs nav-tabs-horizontal" role="tablist">
                                            <li role="presentation" class="active">
                                                <a href="#tab_project" aria-controls="tab_project" role="tab" data-toggle="tab">
                                                    <?php echo _l('about_exam'); ?>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="tab-content mtop15">
                                    <?php $attrs = (isset($section) ? [] : ['autofocus' => true]); ?>
                                    <?php $value = (isset($section) ? $section->name : ''); ?>
                                    <?php echo render_input('name', 'name_exam', $value, 'text', $attrs); ?>
                                    <?php echo render_textarea('description', 'description', '', [], [], ''); ?>
                                </div>

                            </div>

                            <div class="panel-footer text-right">
                                <?php if (!isset($section)) { ?>
                                    <button type="submit" class="btn btn-primary" data-form="#exams_form" data-loading-text="<?php echo _l('wait_text'); ?>" autocomplete="off"><?php echo _l('submit'); ?></button>
                                <?php } else { ?>
                                    <button type="button" class="btn btn-info" data-form="#exams_form" data-loading-text="<?php echo _l('wait_text'); ?>" autocomplete="off"><?php echo _l('submit'); ?></button>
                                <?php } ?>
                            </div>

                        </div>

                    </div>

                    <?php echo form_close(); ?>
                </div>

            </div>
        </div>
        <?php init_tail(); ?>
        <script>
            $(function() {
                // init_editor('#description', {
                //     append_plugins: 'stickytoolbar'
                // });
                appValidateForm($('#exam-form'), {
                    name: 'required',
                    id_seal: 'required'
                });
            });
        </script>
        </body>

        </html>