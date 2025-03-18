<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="sm:tw-flex tw-space-y-3 sm:tw-space-y-0 tw-gap-6">
            <div class="sm:tw-border-r sm:tw-border-solid sm:tw-border-neutral-200 tw-pr-10 tw-w-96">
                <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center">
                    <svg style="fill: #929292" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 384 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                        <path d="M173.8 5.5c11-7.3 25.4-7.3 36.4 0L228 17.2c6 3.9 13 5.8 20.1 5.4l21.3-1.3c13.2-.8 25.6 6.4 31.5 18.2l9.6 19.1c3.2 6.4 8.4 11.5 14.7 14.7L344.5 83c11.8 5.9 19 18.3 18.2 31.5l-1.3 21.3c-.4 7.1 1.5 14.2 5.4 20.1l11.8 17.8c7.3 11 7.3 25.4 0 36.4L366.8 228c-3.9 6-5.8 13-5.4 20.1l1.3 21.3c.8 13.2-6.4 25.6-18.2 31.5l-19.1 9.6c-6.4 3.2-11.5 8.4-14.7 14.7L301 344.5c-5.9 11.8-18.3 19-31.5 18.2l-21.3-1.3c-7.1-.4-14.2 1.5-20.1 5.4l-17.8 11.8c-11 7.3-25.4 7.3-36.4 0L156 366.8c-6-3.9-13-5.8-20.1-5.4l-21.3 1.3c-13.2 .8-25.6-6.4-31.5-18.2l-9.6-19.1c-3.2-6.4-8.4-11.5-14.7-14.7L39.5 301c-11.8-5.9-19-18.3-18.2-31.5l1.3-21.3c.4-7.1-1.5-14.2-5.4-20.1L5.5 210.2c-7.3-11-7.3-25.4 0-36.4L17.2 156c3.9-6 5.8-13 5.4-20.1l-1.3-21.3c-.8-13.2 6.4-25.6 18.2-31.5l19.1-9.6C65 70.2 70.2 65 73.4 58.6L83 39.5c5.9-11.8 18.3-19 31.5-18.2l21.3 1.3c7.1 .4 14.2-1.5 20.1-5.4L173.8 5.5zM272 192a80 80 0 1 0 -160 0 80 80 0 1 0 160 0zM1.3 441.8L44.4 339.3c.2 .1 .3 .2 .4 .4l9.6 19.1c11.7 23.2 36 37.3 62 35.8l21.3-1.3c.2 0 .5 0 .7 .2l17.8 11.8c5.1 3.3 10.5 5.9 16.1 7.7l-37.6 89.3c-2.3 5.5-7.4 9.2-13.3 9.7s-11.6-2.2-14.8-7.2L74.4 455.5l-56.1 8.3c-5.7 .8-11.4-1.5-15-6s-4.3-10.7-2.1-16zm248 60.4L211.7 413c5.6-1.8 11-4.3 16.1-7.7l17.8-11.8c.2-.1 .4-.2 .7-.2l21.3 1.3c26 1.5 50.3-12.6 62-35.8l9.6-19.1c.1-.2 .2-.3 .4-.4l43.2 102.5c2.2 5.3 1.4 11.4-2.1 16s-9.3 6.9-15 6l-56.1-8.3-32.2 49.2c-3.2 5-8.9 7.7-14.8 7.2s-11-4.3-13.3-9.7z" />
                    </svg>

                    &nbsp; <?php echo _l('certification'); ?>
                </h4>
                <ul class="reports tw-space-y-1">
                    <?php foreach (get_reports_certifications() as $status) { ?>
                        <li class="my_menu_reports" id="report_certification<?php echo $status['code'];
                                                                            ?>">
                            <a href="#" class="report_certification group tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-500 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md data-[active=true]:tw-bg-neutral-200 data-[active=true]:tw-text-neutral-800">
                                <i class="fa fa-angle-down menu-icon"></i>
                                &nbsp;<?php echo $status['translate_name'];
                                        ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>

            <div class="sm:tw-border-r sm:tw-border-solid sm:tw-border-neutral-200 tw-pr-10 tw-w-96">
                <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-flex tw-items-center">
                    <svg style="fill: #929292" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                        <path d="M40 48C26.7 48 16 58.7 16 72v48c0 13.3 10.7 24 24 24H88c13.3 0 24-10.7 24-24V72c0-13.3-10.7-24-24-24H40zM192 64c-17.7 0-32 14.3-32 32s14.3 32 32 32H480c17.7 0 32-14.3 32-32s-14.3-32-32-32H192zm0 160c-17.7 0-32 14.3-32 32s14.3 32 32 32H480c17.7 0 32-14.3 32-32s-14.3-32-32-32H192zm0 160c-17.7 0-32 14.3-32 32s14.3 32 32 32H480c17.7 0 32-14.3 32-32s-14.3-32-32-32H192zM16 232v48c0 13.3 10.7 24 24 24H88c13.3 0 24-10.7 24-24V232c0-13.3-10.7-24-24-24H40c-13.3 0-24 10.7-24 24zM40 368c-13.3 0-24 10.7-24 24v48c0 13.3 10.7 24 24 24H88c13.3 0 24-10.7 24-24V392c0-13.3-10.7-24-24-24H40z" />
                    </svg>
                    <span>
                        &nbsp; <?php echo _l('audit'); ?>
                    </span>
                </h4>
                <ul class="reports tw-space-y-1">

                    <?php foreach (get_reports_audits() as $status) { ?>
                        <li class="my_menu_reports" id="report_audit<?php echo $status['code'];
                                                                    ?>">
                            <a href="#" class="report_audit group tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-500 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md data-[active=true]:tw-bg-neutral-200 data-[active=true]:tw-text-neutral-800">
                                <i class="fa fa-angle-down menu-icon"></i>
                                &nbsp;<?php echo $status['translate_name'];
                                        ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>

            <div class="tw-pr-10 tw-w-96">
                <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-flex tw-items-center">
                    <svg style="fill: #929292" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->

                        <path d="M0 416c0 17.7 14.3 32 32 32l54.7 0c12.3 28.3 40.5 48 73.3 48s61-19.7 73.3-48L480 448c17.7 0 32-14.3 32-32s-14.3-32-32-32l-246.7 0c-12.3-28.3-40.5-48-73.3-48s-61 19.7-73.3 48L32 384c-17.7 0-32 14.3-32 32zm128 0a32 32 0 1 1 64 0 32 32 0 1 1 -64 0zM320 256a32 32 0 1 1 64 0 32 32 0 1 1 -64 0zm32-80c-32.8 0-61 19.7-73.3 48L32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l246.7 0c12.3 28.3 40.5 48 73.3 48s61-19.7 73.3-48l54.7 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-54.7 0c-12.3-28.3-40.5-48-73.3-48zM192 128a32 32 0 1 1 0-64 32 32 0 1 1 0 64zm73.3-64C253 35.7 224.8 16 192 16s-61 19.7-73.3 48L32 64C14.3 64 0 78.3 0 96s14.3 32 32 32l86.7 0c12.3 28.3 40.5 48 73.3 48s61-19.7 73.3-48L480 128c17.7 0 32-14.3 32-32s-14.3-32-32-32L265.3 64z" />
                    </svg>

                    <span>
                        &nbsp; <?php echo _l('filters_reports_certifications'); ?>
                    </span>
                </h4>
                <?php echo form_open($this->uri->uri_string(), ['id' => 'export-form']); ?>
                <ul class="nav metis-menu menu-nav-reports">
                    <li>
                        <div id="certifications-years" class="hide mbot15">
                            <label for="certifications_years"><?php echo _l('year'); ?></label><br />
                            <select class="selectpicker" id="select_certifications_years" name="certifications_years" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                <?php foreach ($certifications_years as $year) { ?>
                                    <option value="<?php echo $year['year']; ?>" <?php if ($year['year'] == date('Y')) {
                                                                                        echo 'selected';
                                                                                    } ?>>
                                        <?php echo $year['year']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div id="audits-years" class="hide mbot15">
                            <label for="select_audits_years"><?php echo _l('year'); ?></label><br />
                            <select class="selectpicker" id="select_audits_years" name="select_audits_years" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                <?php foreach ($audits_years as $year) { ?>
                                    <option value="<?php echo $year['year']; ?>" <?php if ($year['year'] == date('Y')) {
                                                                                        echo 'selected';
                                                                                    } ?>>
                                        <?php echo $year['year']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>


                    </li>
                    <li>
                        <div class="form-group" id="report-time">
                            <label for="months-report"><?php echo _l('period_datepicker'); ?></label><br />
                            <select class="selectpicker" name="months-report" id="filters" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                <option value=""><?php echo _l('report_sales_months_all_time'); ?>
                                </option>
                                <option value="this_month"><?php echo _l('this_month'); ?></option>
                                <option value="1"><?php echo _l('last_month'); ?></option>
                                <option value="this_year"><?php echo _l('this_year'); ?></option>
                                <option value="last_year"><?php echo _l('last_year'); ?></option>
                                <option value="3" data-subtext="<?php echo _d(date('Y-m-01', strtotime('-2 MONTH'))); ?> - <?php echo _d(date('Y-m-t')); ?>">
                                    <?php echo _l('report_sales_months_three_months'); ?></option>
                                <option value="6" data-subtext="<?php echo _d(date('Y-m-01', strtotime('-5 MONTH'))); ?> - <?php echo _d(date('Y-m-t')); ?>">
                                    <?php echo _l('report_sales_months_six_months'); ?></option>
                                <option value="12" data-subtext="<?php echo _d(date('Y-m-01', strtotime('-11 MONTH'))); ?> - <?php echo _d(date('Y-m-t')); ?>">
                                    <?php echo _l('report_sales_months_twelve_months'); ?></option>
                                <option value="custom"><?php echo _l('period_datepicker'); ?></option>
                            </select>
                        </div>
                    </li>
                    <li id="date-range" class="hide">
                        <div class="mbot15">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="report-from" class="control-label"><?php echo _l('report_sales_from_date'); ?></label>
                                    <div class="input-group date">
                                        <input type="text" class="form-control datepicker" id="report-from" name="report-from">
                                        <div class="input-group-addon">
                                            <i class="fa-regular fa-calendar calendar-icon"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="report-to" class="control-label"><?php echo _l('report_sales_to_date'); ?></label>
                                    <div class="input-group date">
                                        <input type="text" class="form-control datepicker" id="report-to" name="report-to">
                                        <div class="input-group-addon">
                                            <i class="fa-regular fa-calendar calendar-icon"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
                <?php echo form_close(); ?>

            </div>
        </div>

        <div class="row">
            &nbsp;
        </div>

        <div class="content_reports">

        </div>

    </div>
</div>
</div>
<?php init_tail(); ?>
<script>
    var date_validation_rule = {
        required: {
            depends: function() {
                return $('#filters').val() === 'custom';
            }
        }
    }

    var months_validation_rule = {
        required: {
            depends: function() {
                if ($("#filters").is(":visible")) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    var select_certifications_years_validation_rule = {
        required: {
            depends: function() {
                if ($("#select_certifications_years").is(":visible")) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    var select_audits_years_validation_rule = {
        required: {
            depends: function() {
                if ($("#select_audits_years").is(":visible")) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    appValidateForm('#export-form', {
        'months-report': months_validation_rule,
        'report-from': date_validation_rule,
        'report-to': date_validation_rule,
        'select_certifications_years': select_certifications_years_validation_rule,
        'select_audits_years': select_audits_years_validation_rule
    });

    var date_range = $('#date-range');
    var report_time = $('#report-time');
    var certifications_years = $('#certifications-years');
    var audits_years = $('#audits-years');


    var start_date = $('input[name="report-from"]');
    var stop_date = $('input[name="report-to"]');
    var select_certifications_years = $('#select_certifications_years');
    var select_audits_years = $('#select_audits_years');

    $('#filters').on('change', function() {
        var val = $(this).val();
        if (val == 'custom') {
            start_date.val('');
            stop_date.val('');
            date_range.addClass('fadeIn').removeClass('hide');
            return;
        } else {

            if (!date_range.hasClass('hide')) {
                date_range.removeClass('fadeIn').addClass('hide');
            }

            let reporte = $('.my_menu_reports.active').attr('id');
            generate_report(reporte);
        }
    });

    var start_val = '';
    var to_val = '';

    $(function() {

        $('.my_menu_reports').click(function() {
            let reporte = $(this).attr('id');
            $('.my_menu_reports').removeClass('active');
            $(this).addClass('active');
            generate_report(reporte);

        });

        select_certifications_years.add(select_audits_years).on('change', function() {
            let reporte = $('.my_menu_reports.active').attr('id');
            generate_report(reporte);
        });



        start_date.on('change', function() {
            var val = $(this).val();

            if (start_val != val) {

                start_val = val;

                var report_to_val = stop_date.val();
                if (val != '') {
                    stop_date.attr('disabled', false);
                    if (report_to_val != '') {
                        let reporte = $('.my_menu_reports.active').attr('id');
                        generate_report(reporte);
                    }
                } else {
                    stop_date.attr('disabled', true);
                }
            }
        });

        stop_date.on('change', function() {
            var val = $(this).val();

            if (to_val != val) {
                to_val = val;

                if (val != '') {
                    let reporte = $('.my_menu_reports.active').attr('id');
                    generate_report(reporte);
                }
            }
        });
    });

    function generate_report(reporte) {
        $('.content_reports').html('');
        let tipoFiltros = $('#filters').val();
        var start_date_val = $('input[name="report-from"]').val();
        var stop_date_val = $('input[name="report-to"]').val();
        audits_years.addClass('hide');
        certifications_years.addClass('hide');


        if (inArray(reporte, [
                'report_certification1', 'report_audit2', 'report_audit3'
            ])) {

            tipoFiltros = "custom";

            report_time.addClass('hide');
            date_range.addClass('hide');

            if (inArray(reporte, [
                    'report_audit2', 'report_audit3'
                ])) {
                start_date_val = select_audits_years.val();
                stop_date_val = select_audits_years.val();
                audits_years.removeClass('hide');
            } else {
                start_date_val = select_certifications_years.val();
                stop_date_val = select_certifications_years.val();
                certifications_years.removeClass('hide');
            }



            start_date_val = start_date_val.substr(0, 4) + '-01-01';
            stop_date_val = stop_date_val.substr(0, 4) + '-12-31';

        } else {

            if (inArray(reporte, [
                    'report_audit2', 'report_audit3'
                ]))

                audits_years.addClass('hide');
            else
                certifications_years.addClass('hide');


            report_time.removeClass('hide');

            if (tipoFiltros == "custom") {
                date_range.removeClass('hide');
            }
        }



        var $valid = $("#export-form").valid();
        if (!$valid) {
            return false;
        } else {

            init_reports(reporte, tipoFiltros, start_date_val, stop_date_val);
        }

    }

    function init_reports(reporte, tipoFiltros, start_date_val, stop_date_val) {
        $.get('<?php echo admin_url('trust_seal/reports/process_certifications'); ?>/' + reporte + '/' + tipoFiltros + '/' + start_date_val + '/' + stop_date_val, function(response) {
            $('.content_reports').html(response);
        }, 'html');
    }

    function inArray(needle, haystack) {
        var length = haystack.length;
        for (var i = 0; i < length; i++) {
            if (haystack[i] == needle) return true;
        }
        return false;
    }
</script>
</body>

</html>