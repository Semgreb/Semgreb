<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style>
    .form-control[disabled],
    .form-control[readonly],
    fieldset[disabled] .form-control {
        background-color: #ffffff !important;
        border-color: #ffffff !important;
        color: #344156 !important;

    }
</style>
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
                                    <li role="presentation" class="tablinks active" onclick="changeTab(event, 'tab_detail')" id="tab-detail">
                                        <a aria-controls="tab_detail" role="tab" data-toggle="tab">
                                            <?php echo _l('about_audit'); ?>
                                        </a>
                                    </li>
                                    <?php //if (isset($audit) == true) { 
                                    ?>
                                    <li role="presentation" class="tablinks" onclick="changeTab(event, 'tab_exams')" id="tab-exams">
                                        <a aria-controls="tab_exams" role="tab" data-toggle="tab">
                                            <?php echo _l('exams');
                                            ?>
                                        </a>
                                    </li>
                                    <?php //} 
                                    ?>
                                </ul>
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane<?php if (!$this->input->get('tab')) {
                                                                echo ' active';
                                                            }; ?> tabcontent" id="tab_detail">


                            <div style="margin-bottom:27px;">

                                <?php if (isset($audit) == true) {

                                    foreach (get_qualification_audits() as $qualification) {
                                        if ($qualification['qualification'] == $audit->qualification) {
                                            echo  _l('audit_qualification') . " " . get_qualification_format($qualification);
                                            break;
                                        }
                                    }
                                } ?>

                            </div>


                            <?php echo render_textarea('description', _('description'), (isset($audit) ? strip_tags($audit->description) : ''), ['disabled' => true], [], '', 'tinymce tinymce-manual'); ?>
                            <?php
                            // echo render_input('name', 'customers', $audit->id_customer);
                            ?>

                            <?php
                            echo render_input('nameIseal', 'seals', $this->audits_model->get_seal($audit->id_seal)[0]['title'], 'text', ['disabled' => true]);
                            ?>





                            <?php //echo form_close(); 
                            ?>
                        </div>

                        <div role="tabpanel" class="tab-pane tabcontent" id="tab_exams" style="display:none;">
                            <div class="col-12">

                                <table class="table dt-table table-exams" data-order-col="1" data-order-type="desc">
                                    <thead>
                                        <tr>
                                            <th class="toggleable">#</th>
                                            <th class="toggleable"><?php echo  _l('exams'); ?></th>
                                            <th class="toggleable"><?php echo "% "._l('trust_seal_audit_compliance'); ?></th>
                                            <th class="toggleable"><?php echo _l('trust_seal_audit_progress'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                      <?php foreach($exams_group as $exam){ ?>
                                            <tr>
                                                <td><?php echo $exam['id']; ?></td>
                                                <td>
                                                  <a href="<?php echo site_url('trust_seal/clients/view_audits/' . $audit->id); ?>">
                                                     <?php echo $exam['name']; ?>
                                                  </a>
                                                </td>
                                                <td>
                                                    <?php 
                                                        $result = $this->exams_model->get_exams_details_groups($audit->id, $exam['id_customer'], $exam['id'], $audit->id_seal)[0];
                                                        $total = $this->exams_model->get_count_quiz($audit->id_seal,$exam['id']);
                                                        $percentage = (($result['COUNT_APPROBED']/$total)*100);
                                                        echo get_progress_bar((int)$percentage);
                                                    ?>
                                                </td>
                                                <td>
                                                  <?php 
                                                        $result = $this->exams_model->get_exams_details_groups($audit->id, $exam['id_customer'], $exam['id'], $audit->id_seal)[0];
                                                        $total = $this->exams_model->get_count_quiz($audit->id_seal,$exam['id']);
                                                        $completado = ($result['COUNT_APPROBED'] + $result['COUNT_FAILURE']);
                                                        $completado = $completado <= $total ? $completado : $total;
                                                        $percentage = (($completado/$total)*100);
                                                        echo get_progress_bar((int)$percentage);
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
<?php //init_tail(); 
?>
<script>
    $(function() {
        // initDataTable('.table-exams', '<?php //echo admin_url('trust_seal/audits/table_exams_group/').$audit->id; ?>', [], [], "", [0, "desc"]);

        //init_editor('#description');
        // appValidateForm($('#audit-form'), {
        //     description: 'required',
        // });
    });

    <?php if ($this->input->get('tab') == 'detail' || !$this->input->get('tab')) { ?>
        document.getElementById("tab-detail").click();
    <?php } ?>
    <?php if ($this->input->get('tab') == 'exams') { ?>
        document.getElementById("tab-exams").click();
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

    function show_hide(id) {
        let val = document.getElementById('content_sections_' + id + '_val');
        if (val.value == 0) {
            val.value = 1;
            document.getElementById('content_sections_' + id).style.display = "none";
            document.getElementById('btn_ds_' + id).style.display = "none";
        } else {
            val.value = 0;
            document.getElementById('content_sections_' + id).style.display = "block";
            document.getElementById('btn_ds_' + id).style.display = "block";
        }
    }

    function audit_exam(id_audit, id_customer, id_question, approved, id_btn) {

        if (id_btn == "n") {
            let btn = document.getElementById('btnn_' + id_question);
            btn.style.color = 'white';
            btn.style.background = 'red';
        } else if (id_btn == "s") {
            let btn = document.getElementById('btns_' + id_question);
            btn.style.color = 'white';
            btn.style.background = 'green';
        }

        let datos = {
            "id_audit": id_audit,
            "id_customer": id_customer,
            "id_question": id_question,
            "approved": approved
        };
        $.ajax({
            url: '<?php echo admin_url('trust_seal/audits/add_audit_exam/'); ?>',
            type: 'POST',
            data: datos
        });
        // alert_float("success", "");
    }

    function showComments(name, id_audit, id_question) {

        $.ajax({
            type: "GET",
            url: "<?php echo admin_url('trust_seal/audits/get_comment/'); ?>" + id_audit + "/" + id_question,
            success: function(res) {
                res = JSON.parse(res);
                console.log(res);

                let template = '<ul>';

                if (res.length >= 1) {
                    res.map(function(item) {

                        template += `<li style="margin-bottom:30px;"><h4>${item}</h4></li>`;
                    });
                    template += '</ul>';

                    $('#comments_list').html(template);
                }

            }
        });

        $('.id_audit_modal').val(id_audit)
        $('.id_question_modal').val(id_question)
        $('.modal-title').text(id_question + '. ' + name)
        $('#audit_commet').modal('show')
    }
</script>