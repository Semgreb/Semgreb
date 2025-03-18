<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade" id="complaint-service-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('complaints/services/add'), ['id' => 'complaint-service-form']); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="edit-title"><?php echo _l('complaints_service_edit'); ?></span>
                    <span class="add-title"><?php echo _l('new_service'); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="additional"></div>
                        <?php echo render_input('name', 'service_add_edit_name'); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
    window.addEventListener('load', function() {
        appValidateForm($('#complaint-service-form'), {
            name: 'required'
        }, manage_complaint_services);
        $('#complaint-service-modal').on('hidden.bs.modal', function(event) {
            $('#additional').html('');
            $('#complaint-service-modal input[name="name"]').val('');
            $('.add-title').removeClass('hide');
            $('.edit-title').removeClass('hide');
        });
    });

    function manage_complaint_services(form) {
        var data = $(form).serialize();
        var url = form.action;
        var complaint = $('body').hasClass('complaint');
        if (complaint) {
            data += '&complaint_area=true';
        }
        $.post(url, data).done(function(response) {
            if (complaint) {
                response = JSON.parse(response);
                if (response.success == true && typeof(response.id) != 'undefined') {
                    var group = $('select#service');
                    group.find('option:first').after('<option value="' + response.id + '">' + response.name + '</option>');
                    group.selectpicker('val', response.id);
                    group.selectpicker('refresh');
                }
                $('#complaint-service-modal').modal('hide');
            } else {
                window.location.reload();
            }
        });
        return false;
    }
</script>