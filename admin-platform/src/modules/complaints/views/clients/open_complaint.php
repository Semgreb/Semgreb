<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row" style="padding: 40px 10px 12px 10px;">
</div>
<h4 class="tw-mt-0 tw-mb-3 tw-font-semibold tw-text-lg section-heading section-heading-open-ticket">
    <?php echo _l('clients_complaints_open_subject') . " - " . $clients->company; ?>
</h4>
<?php echo form_open_multipart('complaints/clients_complaints/open_complaints/' . urlencode($userIdHash), ['id' => 'open-new-complaints-form']); ?>
<?php echo form_hidden('userid', $userIdHash); ?>
<div class="row">
    <div class="col-md-12">
        <?php // hooks()->do_action('before_client_open_ticket_form_start'); 
        ?>
        <div class="panel_s">
            <div class="panel-body">

                <div class="row">
                    <div class="col-md-12">

                        <!---Inicio bloque de consumidor-->

                        <div class="row">
                            <div class="col-md-6">
                                <?php echo render_input('document', _l('consumer_open_complaint_id'), set_value('document'), 'text', ['maxlength' => '11', 'id' => 'document', 'required' => 'required', 'data-loading-text-complaint' =>  _l('wait_text'), 'onkeypress' => 'return _checkOnlyDigits(event)']); ?>
                            </div>


                            <div class="col-md-6">
                                <?php

                                $new_end_date_assume = date('Y-m-d', strtotime('-18 years'));

                                echo render_date_input('birthday_date', _l('consumer_open_complaint_birthday_date'), _d($new_end_date_assume), ['maxlength' => '10', 'readonly' => 'true', 'id' => 'birthday_date', 'required' => 'required', 'style' => 'background-color:#fff;color:#000;cursor:text;', 'data-loading-text-complaint' =>  _l('wait_text'), 'data-date-end-date' => _d($new_end_date_assume)]); ?>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <?php echo render_input('firstname', _l('consumer_open_complaint_firstname'), set_value('firstname'), 'text', ['maxlength' => '191', 'id' => 'firstname', 'required' => 'required', 'data-loading-text-complaint' =>  _l('wait_text')]); ?>
                            </div>

                            <div class="col-md-6">
                                <?php echo render_input('lastname', _l('consumer_open_complaint_lastname'), set_value('lastname'), 'text', ['maxlength' => '191', 'id' => 'lastname', 'required' => 'required', 'data-loading-text-complaint' =>  _l('wait_text')]); ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <?php echo render_input('email', _l('consumer_open_complaint_email'), set_value('email'), 'text', ['maxlength' => '100', 'id' => 'email', 'required' => 'required', 'data-loading-text-complaint' =>  _l('wait_text')]); ?>
                            </div>


                            <div class="col-md-6">
                                <?php echo render_input('phonenumber', _l('consumer_open_complaint_phonenumber'), set_value('phonenumber'), 'text', ['maxlength' => '10',  'id' => 'phonenumber', 'required' => 'required', 'data-loading-text-complaint' =>  _l('wait_text')]); ?>
                            </div>
                        </div>
                        <!---Final bloque de consumidor-->



                        <div class="row">
                            <div class="col-md-6">
                                <?php echo render_input('subject', _l('customer_ticket_subject'), set_value('subject'), 'text', ['id' => 'subject', 'required' => 'required']); ?>
                            </div>

                            <div class="col-md-6">

                                <?php echo render_select('service', $services, ['serviceid', 'name'], 'clients_ticket_open_service', '', ['required' => 'required']);
                                ?>
                                <?php //echo render_select('priority', $priorities, ['priorityid', 'name'], 'clients_ticket_open_priority', '', ['required' => 'required']);
                                ?>

                            </div>
                        </div>



                        <div class="custom-fields">
                            <?php echo render_custom_fields('complaints', '', ['show_on_client_portal' => 1]); ?>
                        </div>
                    </div>
                </div>



                <?php echo render_textarea('message', _l('clients_complaint_open_body'), set_value('message'), ['rows' => '8', 'required' => 'required']); ?>


                <div class="attachments_area open-ticket-attachments-area">
                    <div class="attachments">
                        <div class="attachment tw-max-w-md">
                            <div class="form-group">
                                <label for="attachment" class="control-label">
                                    <?php echo _l('clients_ticket_attachments'); ?>
                                </label>


                                <div class="input-group">

                                    <?php


                                    echo render_input('attachments[0]', '', '', 'file', ['extension' => str_replace(['.', ' '], '', get_option('ticket_attachments_file_extensions')), 'filesize' =>  file_upload_max_size(), 'accept' => get_ticket_form_accepted_mimes()], []);

                                    ?>

                                    <span class="input-group-btn">
                                        <button class="btn btn-default add_more_attachments" data-max="<?php echo get_option('maximum_allowed_ticket_attachments');
                                                                                                        ?>" type="button">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer text-right">
                <button type="submit" class="btn btn-primary" data-form="#open-new-ticket-form" autocomplete="off" data-loading-text="<?php echo _l('wait_text'); ?>">
                    <?php echo _l('submit'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>
<script>
    $("#open-new-complaints-form select").parents().removeClass("select-placeholder");
    $(function() {

        $("#open-new-complaints-form").appFormValidator({
            rules: {
                document: {
                    required: true,
                    digits: true
                },
                birthday_date: {
                    required: true,
                    date: true
                },
                firstname: "required",
                lastname: "required",
                email: "required",
                phonenumber: "required",
                subject: "required",
                service: "required",
                message: "required",
            }
        });


        let textDocument = "";

        $('#document').keyup(function() {

            if ($("#document").val().length == 11) {

                textDocument = $(this).val();

                var $form = $("#open-new-complaints-form");

                var loadingBtn = $form.find("[data-loading-text-complaint]");

                if (loadingBtn.length > 0) {
                    loadingBtn.button("loading");
                }

                $("#firstname").val('');
                $("#lastname").val('');
                $("#birthday_date").val('');
                $("#email").val('');
                $("#phonenumber").val('');


                $.get(site_url + 'complaints/clients_complaints/get_consumer/' + $("#document").val(), function(data) {

                    $("#document").val(textDocument);

                    loadingBtn.prop("disabled", false);

                    if (data != null) {

                        $("#firstname").val(data.firstname);
                        $("#lastname").val(data.lastname);
                        $("#birthday_date").val(data.birthday_date);
                        $("#email").val(data.email);
                        $("#phonenumber").val(data.phonenumber);



                    } else {

                        $("#firstname").val('');
                        $("#lastname").val('');
                        $("#birthday_date").val('');
                        $("#email").val('');
                        $("#phonenumber").val('');

                    }


                }, 'json');

            }
        });
    });

    function _checkOnlyDigits(e) {
        e = e ? e : window.event;
        var charCode = e.which ? e.which : e.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {

            return false;
        } else {

            return true;
        }
    }
    // init_selectpicker();
    //  init_datepicker();
    //  appValidateForm($('#credit_note_refund_form'),{amount:'required',refunded_on:'required', payment_mode: 'required'});
</script>