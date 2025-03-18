<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Zip Estimates -->
<div class="modal fade" id="audit_commet" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?php echo form_open('trust_seal/audits/add_comment/' . $audit->id); ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo _l('audit_comment'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <h4><?php echo _l('trust_comment_firtsupper'); ?></h4>
                    </div>
                    <div class="col-md-12" id="comments_list">

                    </div>
                </div>
            </div>
            <div class="modal-footer" style="display:flex;">
                <div style="width:85%;">
                    <input type="hidden" name="id_audit" class="id_audit_modal">
                    <input type="hidden" name="id_question" class="id_question_modal">
                    <?php echo render_input('comment', '',  isset($value) ? $value : '', 'text', isset($attrs) ? $attrs : ['placeholder' => _l('placehorlder_comment')]); ?>
                </div>
                <div style="width:15%;">
                    <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>