<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Zip Estimates -->
<div class="modal fade" id="audit_commet" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?php //echo form_open('trust_seal/audits/add_comment/' . $audit->id); 
            ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 sm:tw-border-r sm:tw-border-solid sm:tw-border-neutral-200" style="text-align:center;">

                        <h4><?php echo _l('insignia_seal_active'); ?></h4>

                        <div class="col-md-12" style="min-height: 200px; max-height: 200px;">
                            <img src="" id="imgActive" alt="<?php echo _l('insignia_seal_active'); ?>" style="width:200px;border-radius:5px 5px;">
                        </div>

                        <button type="button" class="btn btn-primary btn_active_download"><?php echo _l('download'); ?></button>

                    </div>

                    <div class="col-md-6" style="text-align:center;">

                        <h4><?php echo _l('insignia_seal_inactive'); ?></h4>

                        <div class="col-md-12" style="min-height: 200px; max-height: 200px;">
                            <img src="" id="imgInactive" alt="<?php echo _l('insignia_seal_inactive'); ?>" style="width:200px;border-radius:5px 5px;">
                        </div>

                        <button type="button" class="btn btn-primary btn_inactive_download"><?php echo _l('download'); ?></button>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="text-align:right;">
                <!-- <div style="width:85%;">
                    <input type="hidden" name="id_audit" class="id_audit_modal">
                    <input type="hidden" name="id_question" class="id_question_modal">
                    <?php //echo render_input('comment', '',  isset($value) ? $value : '', 'text', isset($attrs) ? $attrs : ['placeholder' => _l('placehorlder_comment')]); 
                    ?>
                </div> -->
                <!-- <div style="width:15%;"> -->
                <button type="button" data-dismiss="modal" class="btn btn-default"><?php echo _l('close'); ?></button>
                <!-- </div> -->
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>