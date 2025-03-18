<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head();
$seal->id = (int) $seal->id;
?>
<div id="wrapper">
    <div class="content">

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="tw-flex tw-justify-between tw-mb-2">
                    <h4 class="tw-mt-0 tw-font-semibold tw-text-neutral-700">
                        <span class="tw-text-lg"><?php echo $title; ?></span>
                        <?php if (has_permission('seals', '', 'delete') || is_admin()) { ?>
                            <div class="btn-group">
                                <a href="#" class="dropdown-toggle btn-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <?php if (has_permission('certifications', '', 'delete')) { ?>
                                        <li>
                                            <a href="<?php echo admin_url('trust_seal/seals/delete/' . $seal->id); ?>" class="text-danger delete-text _delete"><i class="fa fa-remove"></i>
                                                <?php echo _l('delete'); ?>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        <?php } ?>
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
                                <div class="horizontal-scrollable-tabs panel-full-width-tabs">
                                    <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
                                    <div class="horizontal-tabs">
                                        <ul class="nav nav-tabs nav-tabs-horizontal" role="tablist">
                                            <li role="presentation" class="tablinks" onclick="changeTab(event, 'tab_detail')" id="tab-detail">
                                                <a aria-controls="tab_detail" role="tab" data-toggle="tab">
                                                    <?php echo _l('about_seal'); ?>
                                                </a>
                                            </li>
                                            <li role="presentation" class="tablinks" onclick="changeTab(event, 'tab_badge')" id="tab-badge">
                                                <a aria-controls="tab_badge" role="tab" data-toggle="tab">
                                                    <?php echo _l('seal_badge'); ?>
                                                </a>
                                            </li>
                                            <li role="presentation" class="tablinks" onclick="changeTab(event, 'tab_documents')" id="tab-documents">
                                                <a aria-controls="tab_documents" role="tab" data-toggle="tab">
                                                    <?php echo _l('seal_documents'); ?>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>


                                <div role="tabpanel" class="tab-pane<?php if (!$this->input->get('tab')) {
                                                                        echo ' active';
                                                                    }; ?> tabcontent" id="tab_detail">
                                    <?php $attrs = (isset($seal) ? [] : ['autofocus' => true]); ?>
                                    <?php $value = (isset($seal) ? $seal->title : ''); ?>
                                    <?php echo render_input('title', _l('title'), $seal->title, 'text', $attrs); ?>
                                    <?php 
                                    $selected = [];
                                    if (isset($exams_groups)) {
                                        foreach ($exams_groups as $group) {
                                            array_push($selected, $group['id_exams']);
                                        }
                                    }

                                   
                                    echo render_select('groups_exams[]', $exams, ['id', 'name'], 'exams', $selected, ['multiple' => true, 'data-actions-box' => true], [], '', '', false);                                    
                                    ?>

                                    <div style="display:flex;">
                                        <div style="width:50%;padding-right:10px;box-sizing:border-box;">
                                            <?php echo render_select('status', $status, ['id', 'name'], 'audit_status', $seal->status, [], [], '', '', false); ?>
                                        </div>
                                        <div style="width:50%;">
                                            <?php

                                            echo render_date_input('date_start', _l('seal_date'), (isset($seal) ? _d($seal->date_start) : ''), []);

                                            ?>
                                        </div>
                                    </div>

                                    <?php echo render_textarea('requirements', _('clients_seal_request_required'), $seal->requirements, [], [], '', 'tinymce tinymce-manual');
                                    ?>
                                    <?php echo render_textarea('description', _('description'), $seal->description, [], [], '');

                                    // echo render_textarea('description', _('description'), $seal->description, [], [], '', 'tinymce tinymce-manual');

                                    ?>

                                    <div style="margin-top:7px;" class="text-right">


                                        <a href="#" class="btn btn-default " onclick="init_seal(); return false;"><?php echo _l('cancel'); ?></a>
                                        <button type="submit" name="action" value="update_seal" class="btn btn-primary"><?php echo _l('submit'); ?></button>
                                    </div>
                                    <?php
                                    echo form_close();
                                    ?>
                                </div>

                                <div role="tabpanel" class="tab-pane tabcontent" id="tab_badge" style="display:none;">

                                    <?php

                                    // echo form_open();
                                    ?>


                                    <p><?php echo _l('insignia_seal_active'); ?></p>
                                    <?php if ($seal->logo_active != null) { ?>
                                        <div style="width: 100%;display:flex;justify-content:space-between;align-items: center;">
                                            <img src="<?php echo base_url(PATH_SEALS . '/' . $seal->id . '/' . $seal->logo_active); ?>" alt="Logo active" style="width:200px;border-radius:5px 5px;">
                                            <a href="<?php echo admin_url('trust_seal/seals/attachment_active/' . $seal->id . '?logo=' . $seal->logo_active); ?>" style="color:red;"><b>
                                                    <h4>X</h4>
                                                </b></a>
                                        </div>
                                        <hr>
                                    <?php }
                                    if ($seal->logo_active == null) { ?>

                                        <?php echo form_open_multipart(admin_url('trust_seal/seals/attachment_active/' . $seal->id), ['class' => 'dropzone', 'id' => 'seal-attachments-active']); ?>
                                        <input type="file" name="file_active" />
                                        <?php echo form_close(); ?>
                                    <?php } ?>


                                    <p><?php echo _l('insignia_seal_inactive'); ?></p>
                                    <?php if ($seal->logo_inactive != null) { ?>
                                        <div style="width: 100%;display:flex;justify-content:space-between;align-items: center;">
                                            <img src="<?php echo base_url(PATH_SEALS . '/' . $seal->id . '/' . $seal->logo_inactive); ?>" alt="Logo active" style="width:200px;border-radius:5px 5px;">
                                            <a href="<?php echo admin_url('trust_seal/seals/attachment_inactive/' . $seal->id . '?logo=' . $seal->logo_inactive); ?>" style="color:red;"><b>
                                                    <h4>X</h4>
                                                </b></a>
                                        </div>
                                        <hr>
                                    <?php }
                                    if ($seal->logo_inactive == null) { ?>
                                        <?php echo form_open_multipart(admin_url('trust_seal/seals/attachment_inactive/' . $seal->id), ['class' => 'dropzone', 'id' => 'seal-attachments-inactive']); ?>
                                        <input type="file" name="file_inative" />
                                        <?php echo form_close(); ?>
                                    <?php } ?>

                                </div>

                                <div role="tabpanel" class="tab-pane tabcontent" id="tab_documents" style="display:none;">
                                    <?php echo form_open_multipart(admin_url('trust_seal/seals/attachment_files/' . $seal->id), ['class' => 'dropzone', 'id' => 'seal-attachments-file']); ?>
                                    <input type="file" name="file" multiple />
                                    <?php echo form_close(); ?>

                                    <div class="panel-table-full" style="margin-top:20px;">
                                        <?php render_datatable([
                                            '#',
                                            _l('file'),
                                            _l('options'),
                                        ], 'documents'); ?>
                                    </div>

                                </div>


                            </div>
                        </div>

                        <?php // echo form_close(); 
                        ?>

                    </div>
                </div>

            </div>
        </div>
        <?php init_tail(); ?>
        <script>
            initDataTable('.table-documents', window.location.href, [1], [1]);



            // $("#seal-form").appFormValidator({
            //         rules: {
            //             title: {
            //                 required: true
            //             },
            //         }
            //     });

            if ($("#seal-attachments-inactive").length > 0) {
                new Dropzone(
                    "#seal-attachments-inactive",
                    appCreateDropzoneOptions({
                        paramName: "file",
                        accept: function(file, done) {
                            done();
                        },
                        success: function(file, response) {

                            if (
                                this.getUploadingFiles().length === 0 &&
                                this.getQueuedFiles().length === 0
                            ) {
                                window.location.href = "<?php echo admin_url('trust_seal/seals/view/' . $seal->id . '?tab=badge') ?>";
                            }
                        }
                    })
                );
            }

            if ($("#seal-attachments-active").length > 0) {
                new Dropzone(
                    "#seal-attachments-active",
                    appCreateDropzoneOptions({
                        paramName: "file",
                        accept: function(file, done) {
                            done();
                        },
                        success: function(file, response) {

                            if (
                                this.getUploadingFiles().length === 0 &&
                                this.getQueuedFiles().length === 0
                            ) {

                                window.location.href = "<?php echo admin_url('trust_seal/seals/view/' . $seal->id . '?tab=badge') ?>";
                            }
                        }
                    })
                );
            }

            if ($("#seal-attachments-file").length > 0) {
                new Dropzone(
                    "#seal-attachments-file",
                    appCreateDropzoneOptions({
                        paramName: "file",
                        accept: function(file, done) {
                            done();
                        },
                        success: function(file, response) {

                            if (
                                this.getUploadingFiles().length === 0 &&
                                this.getQueuedFiles().length === 0
                            ) {
                                window.location.href = "<?php echo admin_url('trust_seal/seals/view/' . $seal->id . '?tab=documents') ?>";
                            }
                        }
                    })
                );
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

            function init_seal() {
                window.location.href = '<?php echo admin_url('trust_seal/seals/manage_seals'); ?>';
            }


            <?php if ($this->input->get('tab') == 'detail' || !$this->input->get('tab')) { ?>
                document.getElementById("tab-detail").click();
            <?php } ?>
            <?php if ($this->input->get('tab') == 'badge') { ?>
                document.getElementById("tab-badge").click();
            <?php } ?>
            <?php if ($this->input->get('tab') == 'documents') { ?>
                document.getElementById("tab-documents").click();
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
        </script>
        </body>

        </html>