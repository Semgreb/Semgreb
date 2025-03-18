<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head();
?>
<div id="wrapper">
    <div class="content">
        <?php echo form_open($this->uri->uri_string(), ['id' => 'new_consumer_form']); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="tw-flex tw-items-center tw-mb-2">
                    <h4 class="tw-my-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-mr-4">
                        <?php echo _l('consumer_information_heading'); ?>
                    </h4>

                </div>
                <div class="panel_s">
                    <div class="panel-body">

                        <!---Inicio bloque de consumidor-->

                        <?php echo form_hidden('consumerid', isset($consumer->consumerid) ? $consumer->consumerid : 0); ?>

                        <div class="row">
                            <div class="col-md-6">

                                <?php echo render_input('document', _l('consumer_open_complaint_id'), isset($consumer->document) ? str_pad($consumer->document, 11, '0', STR_PAD_LEFT) : set_value('document'), 'text', ['maxlength' => '11', 'id' => 'document', 'onkeypress' => 'return _checkOnlyDigits(event)']); ?>

                            </div>


                            <div class="col-md-6">
                                <?php echo render_input('firstname', _l('consumer_open_complaint_firstname'), isset($consumer->firstname) ? $consumer->firstname : set_value('firstname'), 'text', ['maxlength' => '60', 'id' => 'firstname']); ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">

                                <?php echo render_input('lastname', _l('consumer_open_complaint_lastname'), isset($consumer->lastname) ? $consumer->lastname : set_value('lastname'), 'text', ['maxlength' => '60', 'id' => 'lastname']); ?>
                            </div>


                            <div class="col-md-6">
                                <?php
                                $new_end_date_assume = date('Y-m-d', strtotime('-18 years'));

                                echo render_date_input('birthday_date', _l('consumer_open_complaint_birthday_date'), isset($consumer->birthday_date) ? $consumer->birthday_date : _d($new_end_date_assume), ['readonly' => 'true', 'style' => 'background-color:#fff;color:#000;cursor:text;', 'maxlength' => '10', 'id' => 'birthday_date', 'data-date-end-date' => _d($new_end_date_assume)]); ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <?php echo render_input('email', _l('consumer_open_complaint_email'), isset($consumer->email) ? $consumer->email : set_value('email'), 'text', ['maxlength' => '100', 'id' => 'email']); ?>
                            </div>


                            <div class="col-md-6">
                                <?php echo render_input('phonenumber', _l('consumer_open_complaint_phonenumber'), isset($consumer->phonenumber) ? $consumer->phonenumber : set_value('phonenumber'), 'text', ['maxlength' => '10',  'id' => 'phonenumber']); ?>
                            </div>
                        </div>

                        <!---Final bloque de consumidor-->


                        <div class="btn-bottom-toolbar text-right">

                            <a href="#" class="btn btn-default" onclick="init_consumer(); return false;"><?php echo _l('cancel'); ?></a>
                            <button type="submit" data-form="#new_consumer_form" autocomplete="off" data-loading-text="<?php echo _l('wait_text'); ?>" class="btn btn-primary"><?php echo _l('save'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
    <div class="tw-py-10"></div>
    <?php //$this->load->view('admin/tickets/services/service'); 
    ?>
    <?php init_tail(); ?>
    <?php //hooks()->do_action('new_ticket_admin_page_loaded'); 
    ?>
    <script>
        function _checkOnlyDigits(e) {
            e = e ? e : window.event;
            var charCode = e.which ? e.which : e.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {

                return false;
            } else {

                return true;
            }
        }

        function init_consumer() {
            window.location.href = '<?php echo admin_url('consumers'); ?>';
        }

        $(function() {

            init_ajax_search('contact', '#contactid.ajax-search', {
                tickets_contacts: true,
                contact_userid: function() {
                    // when ticket is directly linked to project only search project client id contacts
                    var uid = $('select[data-auto-project="true"]').attr('data-project-userid');
                    if (uid) {
                        return uid;
                    } else {
                        return '';
                    }
                }
            });


            $("#new_consumer_form").appFormValidator({
                rules: {
                    document: {
                        required: true,
                        digits: true
                    },
                    birthday_date: {
                        required: true,
                        date: true
                    },
                    firstname: {
                        required: true,
                        maxlength: 60
                    },
                    lastname: {
                        required: true,
                        maxlength: 60
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    phonenumber: {
                        required: true,
                        digits: true,
                        maxlength: 10
                    }
                }
            });

            // validate_new_ticket_form();

            <?php if (isset($project_id) || isset($contact)) { ?>
                $('body.ticket select[name="contactid"]').change();
            <?php } ?>

            <?php if (isset($project_id)) { ?>
                $('body').on('selected.cleared.ajax.bootstrap.select', 'select[data-auto-project="true"]', function(e) {
                    $('input[name="userid"]').val('');
                    $(this).parents('.projects-wrapper').addClass('hide');
                    $(this).prop('disabled', false);
                    $(this).removeAttr('data-auto-project');
                    $('body.ticket select[name="contactid"]').change();
                });
            <?php } ?>
        });
    </script>
    </body>

    </html>