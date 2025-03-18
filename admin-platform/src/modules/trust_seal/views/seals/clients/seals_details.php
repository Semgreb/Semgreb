<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="tw-flex tw-justify-between tw-mb-2">
            <h4 class="tw-mt-0 tw-font-semibold tw-text-neutral-700">
                <span class="tw-text-lg"><?php echo $title; ?></span>

            </h4>
        </div>

        <div class="row">
            <?php echo form_open($this->uri->uri_string(), ['id' => 'seal-form']); ?>

            <div class="col-md-12">

                <div class="panel_s">
                    <div class="panel-body">

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


                            <?php echo render_input('title', _l('title'), $seal->title, 'text', ['disabled' => true]); ?>


                            <?php

                            foreach ($exams as $qualification) {
                                if ($qualification['id'] == $seal->exams) {
                                    $valText = $qualification['name'];
                                    break;
                                }
                            }

                            echo render_input('exams', _l('exam'), $valText, 'text', ['disabled' => true]);

                            //echo render_select('exams', $exams, ['id', 'name'], 'Exams', $seal->exams, ['disabled' => true], [], '', '', false); 
                            ?>

                            <div style="display:flex;">
                                <div style="width:50%;padding-right:10px;box-sizing:border-box;">
                                    <?php

                                    foreach ($status as $qualification) {
                                        if ($qualification['id'] == $seal->status) {
                                            $valText = $qualification['name'];
                                            break;
                                        }
                                    }
                                    echo render_input('status', _l('audit_status'), $valText, 'text', ['disabled' => true]);

                                    ?>
                                </div>
                                <div style="width:50%;">
                                    <?php

                                    echo render_date_input('date_start', _l('seal_date'), (isset($seal) ? _d($seal->date_start) : ''), ['disabled' => true]);

                                    ?>
                                </div>
                            </div>

                            <?php echo render_input('short_description', _('short_description'), $seal->short_description, 'text', ['disabled' => true]); ?>
                            <?php echo render_textarea('description', _('description'), $seal->description, ['disabled' => true], [], '', 'tinymce tinymce-manual'); ?>

                            <div style="margin-top:7px;margin-left:3px;">
                                <!-- <button type="submit" name="action" value="update_seal" class="btn btn-primary pull-right"><?php echo _l('submit'); ?></button> -->
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane tabcontent" id="tab_badge" style="display:none;">

                            <?php echo form_open();
                            echo form_close(); ?>

                            <p><?php echo _l('insignia_seal_active'); ?></p>
                            <?php if ($seal->logo_active != null) { ?>
                                <div style="width: 100%;display:flex;justify-content:space-between;align-items: center;">
                                    <img src="<?php echo base_url(PATH_SEALS . '/' . $seal->id . '/' . $seal->logo_active); ?>" alt="Logo active" style="width:200px;border-radius:5px 5px;" disabled>

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
                                    <img src="<?php echo base_url(PATH_SEALS . '/' . $seal->id . '/' . $seal->logo_inactive); ?>" alt="Logo active" style="width:200px;border-radius:5px 5px;" disabled>

                                </div>
                                <hr>
                            <?php } ?>


                        </div>

                        <div role="tabpanel" class="tab-pane tabcontent" id="tab_documents" style="display:none;">

                            <div class="panel-table-full" style="margin-top:20px;">

                                <table class="table dt-table table-certifications" data-order-col="1" data-order-type="desc">
                                    <thead>
                                        <th width="10%" class="th-ticket-number"><?php echo _l('#'); ?></th>
                                        <th class="th-ticket-subject"><?php echo _l('file'); ?></th>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($document as $ticket) { ?>
                                            <tr>
                                                <td data-order="<?php echo $ticket['id']; ?>">
                                                    <?php echo $ticket['id']; ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    echo  $ticket['file'];
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>

                            </div>

                        </div>

                    </div>
                </div>



            </div>
        </div>

    </div>
</div>
<script>
    //initDataTable('.table-documents', window.location.href, [1], [1]);

    $(function() {
        init_editor('#description');
    });

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