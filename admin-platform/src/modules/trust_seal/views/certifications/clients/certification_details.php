<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
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
                            <?php $attrs = (isset($certification) ? [] : ['autofocus' => true]); ?>
                            <?php $value = (isset($certification) && isset($certification->namespace) ? $certification->namespace : ''); ?>
                            <?php

                            echo render_input('certificationskey', 'certification', $certification->certificationkey, 'text', ['disabled' => true]);

                            //echo render_input('name', _l('name'), (isset($certification) ? $certification->name : ''), 'text', $attrs); 
                            ?>


                            <?php

                            echo render_input('id_seal', 'seals', $this->certifications_model->get_seal($certification->id_seal)[0]['title'], 'text', ['disabled' => true]);


                            ?>

                            <?php if (isset($certification) == true) { ?>
                                <div style="display:flex;">
                                    <div style="width:50%;padding-right:10px;box-sizing:border-box;">
                                        <?php

                                        //echo render_select('status', $status, ['id', 'name'], 'Status', (isset($certification) ? $certification->status : ''), [], [], '', '', false);

                                        foreach (get_status_certifications() as $qualification) {
                                            if ($qualification['status'] == $certification->status) {
                                                $statusDetails = $qualification['translate_name'];
                                                break;
                                            }
                                        }

                                        echo render_input('status', 'audit_status',  $statusDetails, 'text', ['disabled' => true]);

                                        ?>
                                    </div>
                                    <div style="width:50%;">
                                        <?php
                                        echo render_input('date_expiration', 'certification_date_expired', _d($certification->date_expiration), 'text', ['disabled' => true]);

                                        ?>


                                    </div>
                                </div>
                            <?php } else {
                                //echo render_date_input('date_expiration', _l('certification_date_expired'), (isset($certification) ?: ''), []);

                                echo render_input('date_expiration', 'certification_date_expired', _d($certification->date_expiration), 'text', ['disabled' => true]);
                            } ?>

                            <div class="form-group" app-field-wrapper="short_description">
                                <label for="short_description" class="control-label">
                                    <?php echo _l('seals_code'); ?>
                                </label>
                                <br />

                                <code>

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

                            </div>
                            <div style="margin-top:7px;margin-left:3px;">
                                <!-- <button type="submit" class="btn btn-primary pull-right"><?php //echo _l('submit'); 
                                                                                                ?></button> -->
                            </div>

                            <?php echo form_close(); ?>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>
</div>