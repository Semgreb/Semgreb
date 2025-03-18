<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head();
?>
<div id="wrapper">
    <div class="content">
        <?php echo form_open($this->uri->uri_string(), ['id' => 'new_config_form']); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="tw-flex tw-items-center tw-mb-2">
                    <h4 class="tw-my-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-mr-4">
                        <?php //echo _l('label_config_seal_default'); 
                        ?>
                    </h4>

                </div>
                <div class="panel_s">
                    <div class="panel-body">

                        <!---Inicio bloque de consumidor-->

                        <?php //echo form_hidden('configid', isset($config_analyzes->id) ? $config_analyzes->id : 0);
                        ?>

                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="no-mbot font-medium">
                                    <?php echo  _l('label_config_seal_default'); ?>
                                </h4>
                                <p>&nbsp;</p>
                                <!-- <p>Overdue notices are sent when the invoice becomes overdue.</p> -->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">


                                <?php echo render_select('seal_default_departamens', $departments, ['departmentid', 'name'], 'ticket_settings_departments', (isset($config_analyzes->seal_default_departamens)) ? $config_analyzes->seal_default_departamens : '', ['required' => 'true']);
                                ?>
                            </div>


                            <div class="col-md-6">
                                <?php
                                echo render_select('seal_default_priority', $priorities, ['priorityid', 'name'], 'ticket_settings_priority', (isset($config_analyzes->seal_default_priority)) ? $config_analyzes->seal_default_priority : '', ['required' => 'true']); ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <?php echo render_select('seal_default_service', $services, ['serviceid', 'name'], 'ticket_settings_service', (isset($config_analyzes->seal_default_service)) ? $config_analyzes->seal_default_service : '', ['required' => 'true']); ?>
                            </div>

                        </div>

                        <hr />
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="no-mbot font-medium">
                                    <?php echo  _l('label_config_analisis_api'); ?>
                                </h4>
                                <p>&nbsp;</p>
                                <!-- <p>Overdue notices are sent when the invoice becomes overdue.</p> -->
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">

                                <?php echo render_input('seal_api_user', _l('seals_api_user'), isset($config_analyzes->seal_api_user) ? $config_analyzes->seal_api_user : set_value('seal_api_user'), 'text', ['maxlength' => '20', 'id' => 'seal_api_user', 'required' => 'true']); ?>

                            </div>

                            <div class="col-md-6">

                                <?php echo render_input('seal_api_password', _l('seals_api_password'), isset($config_analyzes->seal_api_password) ? $config_analyzes->seal_api_password : set_value('seal_api_password'), 'password', ['maxlength' => '20', 'id' => 'seal_api_password', 'required' => 'true']); ?>

                            </div>
                        </div>
                        <!---Final bloque de consumidor-->


                        <div class="btn-bottom-toolbar text-right">

                            <!-- <a href="#" class="btn btn-default" onclick="init_consumer(); return false;"><?php //echo _l('cancel'); 
                                                                                                                ?></a> -->
                            <button type="submit" data-form="#new_config_form" autocomplete="off" data-loading-text="<?php echo _l('wait_text'); ?>" class="btn btn-primary"><?php echo _l('settings_save'); ?></button>
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
            window.location.href = '<?php echo admin_url('vulnerabilities'); ?>';
        }



        $(function() {

            $("#seal_default_departamens").closest(".form-group").prepend('<i data-toggle="tooltip" data-title="<?php echo _l('tooltip_text_departament_seals'); ?>" class="fa fa-circle-question tw-mr-1"></i>');

            $("#seal_default_priority").closest(".form-group").prepend('<i data-toggle="tooltip" data-title="<?php echo _l('tooltip_text_priority_seals'); ?>" class="fa fa-circle-question tw-mr-1"></i>');

            $("#seal_default_service").closest(".form-group").prepend('<i data-toggle="tooltip" data-title="<?php echo _l('tooltip_text_service_seals'); ?>" class="fa fa-circle-question tw-mr-1"></i>');

            $("#seal_api_user").closest(".form-group").prepend('<i data-toggle="tooltip" data-title="<?php echo _l('tooltip_text_user_api'); ?>" class="fa fa-circle-question tw-mr-1"></i>');

            $("#seal_api_password").closest(".form-group").prepend('<i data-toggle="tooltip" data-title="<?php echo _l('tooltip_text_password_api'); ?>" class="fa fa-circle-question tw-mr-1"></i>');
            // <i class="fa-regular fa-circle-question pull-left tw-mt-0.5 tw-mr-1" data-toggle="tooltip"
            // data-title="<?php // echo _l('hour_of_day_perform_auto_operations_format'); 
                            ?>"></i>

            // init_ajax_search('contact', '#contactid.ajax-search', {
            //     tickets_contacts: true,
            //     contact_userid: function() {
            //         // when ticket is directly linked to project only search project client id contacts
            //         var uid = $('select[data-auto-project="true"]').attr('data-project-userid');
            //         if (uid) {
            //             return uid;
            //         } else {
            //             return '';
            //         }
            //     }
            // });


            $("#new_config_form").appFormValidator({
                rules: {
                    number_container: {
                        required: true,
                        digits: true
                    },
                    // birthday_date: {
                    //     required: true,
                    //     date: true
                    // }
                }
            });

            // validate_new_ticket_form();

            <?php //if (isset($project_id) || isset($contact)) { 
            ?>
            // $('body.ticket select[name="contactid"]').change();
            <?php // } 
            ?>

            <?php //if (isset($project_id)) { 
            ?>
            // $('body').on('selected.cleared.ajax.bootstrap.select', 'select[data-auto-project="true"]', function(e) {
            //     $('input[name="userid"]').val('');
            //     $(this).parents('.projects-wrapper').addClass('hide');
            //     $(this).prop('disabled', false);
            //     $(this).removeAttr('data-auto-project');
            //     $('body.ticket select[name="contactid"]').change();
            // });
            <?php //} 
            ?>
        });
    </script>
    </body>

    </html>