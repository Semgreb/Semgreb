<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="tw-flex tw-items-center tw-justify-between tw-mb-3">
    <h4 class="tw-my-0 tw-font-semibold tw-text-lg tw-text-neutral-700 section-heading section-heading-tickets">
        <?php echo _l('support_certifications'); ?>
    </h4>
</div>
<div class="panel_s">
    <div class="panel-body">


        <div class="horizontal-scrollable-tabs panel-full-width-tabs">
            <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
            <div class="horizontal-tabs">
                <ul class="nav nav-tabs nav-tabs-horizontal" role="tablist">
                    <li role="presentation" class="tablinks" onclick="changeTab(event, 'tab_detail')" id="tab-detail">
                        <a aria-controls="tab_detail" role="tab" data-toggle="tab">
                            <?php echo _l('my_certifications'); ?>
                        </a>
                    </li>
                    <li role="presentation" class="tablinks" onclick="changeTab(event, 'tab_section')" id="tab-section">
                        <a aria-controls="tab_section" role="tab" data-toggle="tab">
                            <?php echo _l('seals'); ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div role="tabpanel" class="tab-pane active tabcontent" id="tab_detail">

            <div class="mbot15">

                <div class="tw-grid tw-grid-cols-2 md:tw-grid-cols-3 lg:tw-grid-cols-6 tw-gap-2">

                    <a href="javascript:void(0);" data-status="-1" class="tw-text-neutral-600 hover:tw-opacity-70 filterMyView">
                        <div class="md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center">
                            <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                                <?php echo total_rows(db_prefix() . 'certifications', ['id_customer' => $clientId]); ?>
                            </span>
                            <span class="text-dark tw-truncate sm:tw-text-clip"><?php echo _l('total_certifications'); ?></span>
                        </div>
                    </a>

                    <?php
                    foreach (get_clients_area_certifications_summary(get_status_certifications()) as $status) {
                        if (in_array($status['status'], $list_statuses)) {
                            continue;
                        }

                    ?>
                        <a href="javascript:void(0);" data-status="<?php echo substr($status['translate_name'], 0, -1); ?>" class="tw-text-neutral-600 hover:tw-opacity-70 filterMyView">
                            <div class="md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center">
                                <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                                    <?php echo $status['total_certifications']; ?></span>
                                <span class="tw-truncate sm:tw-text-clip" style="color:<?php echo $status['status_color']; ?>">
                                    <?php echo $status['translate_name']; ?></span>
                            </div>
                        </a>

                    <?php } ?>


                    <!-- <div class="md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center">
                                    <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                                        <?php // echo total_rows(db_prefix() . 'certifications', 'status=1'); 
                                        ?></span>
                                    <span class="text-primary tw-truncate sm:tw-text-clip"><?php echo _l('certification_active'); ?></span>
                                </div>
                                <div class="md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center">
                                    <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                                        <?php //echo total_rows(db_prefix() . 'certifications', 'status=2'); 
                                        ?></span>
                                    <span class="text-danger tw-truncate sm:tw-text-clip"><?php echo _l('certification_expired'); ?></span>
                                </div> -->
                </div>
            </div>



            <table class="table dt-table table-certifications" data-order-col="1" data-order-type="desc">
                <thead>
                    <th width="10%" class="th-ticket-number"><?php echo _l('seal_nui'); ?>
                        &nbsp;&nbsp;&nbsp;&nbsp;<i data-toggle="tooltip" data-title="<?php echo _l('tooltip_text_nui_seals'); ?>" style="float: right;" class="fa fa-circle-info tw-mr-1"></i>
                    </th>
                    <th class="th-ticket-subject"><?php echo _l('certification'); ?></th>


                    <th class="th-ticket-priority"><?php echo _l('certification_date_release'); ?></th>
                    <th class="th-ticket-status"><?php echo _l('certification_date_expired'); ?></th>
                    <th class="th-ticket-last-reply"><?php echo _l('audit_status'); ?></th>
                    <th class="not-sort">
                        <?php echo _l('you_seals_digital'); ?>
                        &nbsp;&nbsp;&nbsp;&nbsp;<i data-toggle="tooltip" data-title="<?php echo _l('tooltip_text_seals'); ?>" style="float: right;" class="fa fa-circle-info tw-mr-1"></i>
                    </th>

                </thead>
                <tbody>
                    <?php foreach ($certifications as $ticket) { ?>
                        <tr>
                            <td data-order="<?php echo $ticket['id']; ?>">

                                <?php echo $ticket['certificationkey']; ?>

                            </td>
                            <td>

                                <?php
                                $nameSeal = $this->certifications_model->get_seal($ticket['id_seal'])[0]['title'];
                                echo  $nameSeal;
                                ?>

                            </td>


                            <td>
                                <?php
                                echo  date_format(date_create($ticket['date']), 'd-m-Y');
                                ?>
                            </td>

                            <td>
                                <?php
                                echo  date_format(date_create($ticket['date_expiration']), 'd-m-Y');
                                ?>
                            </td>
                            <td>
                                <?php
                                foreach (get_status_certifications() as $qualification) {
                                    if ($qualification['status'] == $ticket['status']) {
                                        $qualification['translate_name'] =  substr($qualification['translate_name'], 0, -1);
                                        echo get_status_audits_format($qualification);
                                        break;
                                    }
                                }
                                ?>
                            </td>

                            <td data-order="false">
                                <?php

                                $urlActive = "";
                                $urlInactive = "";


                                if ($ticket['logo_active'] != null)
                                    $urlActive =  base_url(PATH_SEALS . '' . $ticket['id_seal'] . '/' . $ticket['logo_active']);

                                if ($ticket['logo_inactive'] != null)
                                    $urlInactive =  base_url(PATH_SEALS . '' . $ticket['id_seal'] . '/' . $ticket['logo_inactive']);

                                $status = '<a href="javascript:void(0);"  data-nameseal="' . $nameSeal . '"  data-seal="' . $urlActive . '"  data-sealinactive="' . $urlInactive  . '"  class="btn btn-primary btn-sm btn_file_enable">'
                                    . _l('btn_view_seal') .
                                    '</a>';

                                echo $status;
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <code id="sourceCode" style="display: none;">

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

        <div role="tabpanel" class="tab-pane tabcontent" id="tab_section" style="display:none;">
            <div class="col-12" id="list_sections">
                <table class="table dt-table table-seals-request" data-order-col="1" data-order-type="desc">
                    <thead>
                        <tr width="10%" class="th-ticket-number" style="background-color: none;">&nbsp;</tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sealsList as $seal) {
                        ?>
                            <tr>
                                <td data-order="">
                                    <div class="media">
                                        <div class="media-left media-top ">



                                            <?php

                                            $logoPath = base_url(PATH_SEALS . $seal['id'] . '/' . $seal['logo_active']);

                                            // echo  $logoPath;

                                            if (file_exists(PATH_SEALS . $seal['id'] . '/' . $seal['logo_active'])) {
                                            ?>
                                                <a href="#">
                                                    <img class="img-rounded" src="<?php echo $logoPath; ?>" alt=" <?php echo $seal['title']; ?>" style="width: 64px;height: 64px;">
                                                </a>
                                            <?php } else {
                                            ?>

                                                <div style="width:64px; height:64px; ">


                                                    <svg xmlns="http://www.w3.org/2000/svg" style="display:block;margin: auto" width="55" height="55" viewBox="0 0 384 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                                        <path d="M173.8 5.5c11-7.3 25.4-7.3 36.4 0L228 17.2c6 3.9 13 5.8 20.1 5.4l21.3-1.3c13.2-.8 25.6 6.4 31.5 18.2l9.6 19.1c3.2 6.4 8.4 11.5 14.7 14.7L344.5 83c11.8 5.9 19 18.3 18.2 31.5l-1.3 21.3c-.4 7.1 1.5 14.2 5.4 20.1l11.8 17.8c7.3 11 7.3 25.4 0 36.4L366.8 228c-3.9 6-5.8 13-5.4 20.1l1.3 21.3c.8 13.2-6.4 25.6-18.2 31.5l-19.1 9.6c-6.4 3.2-11.5 8.4-14.7 14.7L301 344.5c-5.9 11.8-18.3 19-31.5 18.2l-21.3-1.3c-7.1-.4-14.2 1.5-20.1 5.4l-17.8 11.8c-11 7.3-25.4 7.3-36.4 0L156 366.8c-6-3.9-13-5.8-20.1-5.4l-21.3 1.3c-13.2 .8-25.6-6.4-31.5-18.2l-9.6-19.1c-3.2-6.4-8.4-11.5-14.7-14.7L39.5 301c-11.8-5.9-19-18.3-18.2-31.5l1.3-21.3c.4-7.1-1.5-14.2-5.4-20.1L5.5 210.2c-7.3-11-7.3-25.4 0-36.4L17.2 156c3.9-6 5.8-13 5.4-20.1l-1.3-21.3c-.8-13.2 6.4-25.6 18.2-31.5l19.1-9.6C65 70.2 70.2 65 73.4 58.6L83 39.5c5.9-11.8 18.3-19 31.5-18.2l21.3 1.3c7.1 .4 14.2-1.5 20.1-5.4L173.8 5.5zM272 192a80 80 0 1 0 -160 0 80 80 0 1 0 160 0zM1.3 441.8L44.4 339.3c.2 .1 .3 .2 .4 .4l9.6 19.1c11.7 23.2 36 37.3 62 35.8l21.3-1.3c.2 0 .5 0 .7 .2l17.8 11.8c5.1 3.3 10.5 5.9 16.1 7.7l-37.6 89.3c-2.3 5.5-7.4 9.2-13.3 9.7s-11.6-2.2-14.8-7.2L74.4 455.5l-56.1 8.3c-5.7 .8-11.4-1.5-15-6s-4.3-10.7-2.1-16zm248 60.4L211.7 413c5.6-1.8 11-4.3 16.1-7.7l17.8-11.8c.2-.1 .4-.2 .7-.2l21.3 1.3c26 1.5 50.3-12.6 62-35.8l9.6-19.1c.1-.2 .2-.3 .4-.4l43.2 102.5c2.2 5.3 1.4 11.4-2.1 16s-9.3 6.9-15 6l-56.1-8.3-32.2 49.2c-3.2 5-8.9 7.7-14.8 7.2s-11-4.3-13.3-9.7z" />
                                                    </svg>
                                                </div>


                                            <?php } ?>

                                        </div>
                                        <div class="media-body">
                                            <h4 class="media-heading" style="color:#0038b4;"><?php echo $seal['title']; ?></h4>

                                            <div class="row">
                                                <div class="col-md-10">
                                                    <?php echo $seal['description']; ?>
                                                </div>
                                                <div class="col-md-2">
                                                    <a href="<?php echo site_url('trust_seal/clients/open_ticket/' . $seal['id']);

                                                                //site_url('clients/open_ticket'); 
                                                                ?>" class="btn btn-primary new-ticket">
                                                        <?php echo _l('request_seals_f'); ?>

                                                        <!-- <i class="fa-regular fa-arrow-right tw-mr-1"></i> -->
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php }
                        ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
<?php $this->load->view('trust_seal/certifications/download.php'); ?>
<?php
$this->load->view('trust_seal/utility.php');
?>
<script>
    <?php if ($this->input->get('tab') == 'detail' || !$this->input->get('tab')) { ?>
        document.getElementById("tab-detail").click();
    <?php } ?>
    <?php if ($this->input->get('tab') == 'section') { ?>
        document.getElementById("tab-section").click();
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

    $(function() {
        $(".table-certifications tbody").on("click", ".btn_file_enable", function(e) {

            e.preventDefault();
            let seal = $(this).data("seal");
            let sealinactive = $(this).data("sealinactive");
            let nameseal = $(this).data("nameseal");

            if (seal != "" && sealinactive != "") {

                showLogoDownload(nameseal, seal, sealinactive);

            } else {

                alert_float('danger', "<?php echo _l('file_no_found_seal'); ?>");
            }

        });

        $(".filterMyView").on("click", function() {
            let val = $(this).data("status");

            if (val != '-1') {
                $('.table-certifications').DataTable().column(4).search(val).draw();
            } else {
                $('.table-certifications').DataTable().columns().search("").draw();
            }
        });
    });
</script>