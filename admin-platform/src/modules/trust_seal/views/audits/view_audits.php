<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); 

if (isset($audit->id)) {
    $audit->id = (int) $audit->id;
}


$displayAlert = "display='none'";
if ($exist_audit) {
    $displayAlert = "";
}
?>
<div id="wrapper">

    <div class="content">

    <div class="row">
            <div class="col-md-8 col-md-offset-2">
                            <a href="#"
                                onclick="go_audit('0','audit'); return false;">
                                <?php echo _l('audits'); ?>
                            </a> / 
                            
                            <a href="#"
                                onclick="go_audit(<?php echo $audit->id; ?>, 's_audit'); return false;">
                                <?php echo $current_seal->title; ?>
                            </a>
                             
                            / <a href="#"
                                onclick="go_audit(<?php echo $audit->id; ?>, 'exams'); return false;">
                                <?php echo _l('exams'); ?>
                            </a>
            </div>
    </div>
    <br/>
        <div class="alert alert-warning alert_seal_exist" <?php echo $displayAlert; ?>>
            <?php echo _l('audit_certification_exist'); ?>
        </div>

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="tw-flex tw-justify-between tw-mb-2">
                    <h4 class="tw-mt-0 tw-font-semibold tw-text-neutral-700">
                    <span class="tw-text-lg"><?php echo $title; ?></span>

                        <?php
                        if (has_permission('audits', '', 'delete') || is_admin()) {
                            $idAudit = isset($audit->id) ? $audit->id : 0;

                            if ($idAudit > 0) {

                        ?>
                                <div class="btn-group">
                                    <a href="#" class="dropdown-toggle btn-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <?php

                                        if (has_permission('audits', '', 'delete')) {

                                        ?>
                                            <li>
                                                <a href="<?php echo admin_url('trust_seal/audits/delete/' . $idAudit); ?>" class="text-danger delete-text _delete"><i class="fa fa-remove"></i>
                                                    <?php echo _l('delete'); ?>
                                                </a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                        <?php
                            }
                        } ?>
                    </h4>
                    <div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">

                        <div class="panel_s">
                            <div class="panel-body">

                                <?php
                                $attrsAll = [];
                                $autoAsignar = "";
                                $attrsAllString = "";

                                if (isset($audit) == true) {
                                    $autoAsignar = $audit->auto_asignar;
                                ?>

                                <?php } ?>
                               <?php  
                               $exam_count = 0;                   
                               foreach($exams_groups as $exam){ ?>
                                    <?php
                                   if($exam_count > 0)
                                   {
                                    ?>
                                    <br />
                                    <br />
                                    <?php
                                   }
                                   
                                   $exam_count++;
                                    ?>
                                    <span class="tw-text-lg"><?php echo $exam->name; ?></span>
                                    <br />
                                    <br />
                                    <div class="col-12">

                                        <span class="text_complete"></span>
                                        <br />
                                        <br />
                                        <?php 

                                        $i = 500;

                                        $evaluaciones = 0;
                                        $countEvalaciones = 0;

                                        if (isset($sections[$exam->id])) {
                                            foreach ($sections[$exam->id] as $section) { ?>

                                                <div onload="show_hide('<?php echo $exam->id.'_'.$i; ?>')" style="border:1px solid #e8e8e8;border-radius:5px;margin-top:5px;" id="section_<?php echo $exam->id.'_'.$i; ?>">
                                                    <div style="display:flex;justify-content:space-between;align-items:center;padding:15px;">
                                                        <input type="text" value="<?php echo $section['name']; ?>" class="form-control" style="width:50%;border-radius:0;border:none;border-bottom:1px solid #e8e8e8;outline:none;">

                                                        <div style="display:flex;justify-content:space-between;align-items:center;cursor:pointer;">
                                                            <b id="btn_ds_<?php echo $exam->id.'_'.$i; ?>"><?php echo get_count_quiz_complete($section[0]['quizs']); ?>/<?php echo count($section[0]['quizs']); ?>&nbsp;&nbsp;&nbsp;</b>
                                                            <button type="button" class="btn" style="border-radius:5px; background:#dfe4ed" onclick="show_hide('<?php echo $exam->id.'_'.$i; ?>')"><span class="caret" style="margin-left:0px !important;"></span></button>
                                                        </div>
                                                        <input type="hidden" value="0" id="content_sections_<?php echo $exam->id.'_'.$i; ?>_val">
                                                    </div>
                                                    <div style="background:#f8fafc;padding:15px;" id="content_sections_<?php echo $exam->id.'_'.$i; ?>">
                                                        <div id="list_questions_<?php echo $i; ?>">

                                                            <?php


                                                            foreach ($section[0]['quizs'] as $quiz) {
                                                                $countEvalaciones++;
                                                            ?>
                                                                <div style="display:flex;justify-content:space-between;align-items:center;margin-top:5px;" id="question_<?php echo $exam->id.'_'.$i; ?>">


                                                                    <input type="text" <?php echo   $attrsAllString; ?> value="<?php echo $quiz['name']; ?>" id_quiz="<?php echo $quiz['id']; ?>" class="form-control" style="width:92%;height: 40px;">


                                                                    <div style="width:30px;margin-left:-120px;display:flex;">
                                                                        <button type="button" class="btn btnNone" <?php echo   $attrsAllString; ?> id="btnn_<?php echo $quiz['id']; ?>" style="padding:5px; border-radius:5px;margin-left:4px;
                                                                        
                                                                        <?php

                                                                        if (count($quiz['approved']) > 0)
                                                                            $evaluaciones++;

                                                                        echo (count($quiz['approved']) > 0 && $quiz['approved']['approved'] == 0) ? 'color:white;background:red;' : 'color:#8c8c8c;background:white;' ?>" data-cod='<?php echo $audit->id; ?>' data-customer='<?php echo $audit->id_customer; ?>' data-pregunta='<?php echo $quiz['id']; ?>' data-status='0'>
                                                                            <span class="fa-regular fa fa-times-circle fa-lg"></span>
                                                                        </button>
                                                                        <button type="button" <?php echo   $attrsAllString; ?> class="btn btnCheck" id="btns_<?php echo $quiz['id']; ?>" style="padding:5px; border-radius:5px;margin-left:4px;
                                                                        <?php echo (count($quiz['approved']) > 0 && $quiz['approved']['approved'] == 1) ? 'color:white;background:#22c55e;' : 'color:#8c8c8c;background:white;' ?>" data-cod='<?php echo $audit->id; ?>' data-customer='<?php echo $audit->id_customer; ?>' data-pregunta='<?php echo $quiz['id']; ?>' data-status='1'>
                                                                            <span class="fa-regular fa fa-check-circle fa-lg"></span>
                                                                        </button>

                                                                    </div>
                                                                    <?php
                                                                    $haveComment = false;
                                                                    $disabledComment =  $attrsAllString;

                                                                    if (if_have_comment($audit->id, $quiz['id'])) {
                                                                        $haveComment = true;
                                                                        $disabledComment = "";
                                                                    } ?>
                                                                    <button type="button" <?php echo  $disabledComment; ?> class="btn" style="border-radius:5px;margin-left:5px;<?php echo ($haveComment) ? 'color:white;background:#03a9f4;' : 'color:#8c8c8c;background:white;' ?>" onclick="showComments('<?php echo $quiz['name']; ?>',<?php echo $audit->id; ?>,<?php echo $quiz['id']; ?>)"><span class="fa-regular fa fa-commenting fa-lg"></span></button>


                                                                </div>
                                                            <?php } ?>

                                                        </div>
                                                    </div>
                                                </div>

                                        <?php $i = $i + 1;
                                            }
                                        } ?>

                                        <input type="hidden" class="progress_evaluations"  value="<?php echo "$evaluaciones/$countEvalaciones " . _l('assessments_audit'); ?>"/>
                                    </div>

                                    <?php } ?>

                                    <?php 
                                 
                                    $this->load->view('audits/modals/comments.php'); ?>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
        <?php init_tail(); ?>
        <script>

            $('.progress_evaluations').each(function(){
                let textEvaluation = $(this).closest('div').find('.text_complete');
                let evaluation = $(this).val();
                textEvaluation.html(evaluation);
            });

            function init_seal() {
                window.location.href = '<?php echo admin_url('trust_seal/audits/manage'); ?>';
            }

            function go_audit(id_audit, go_to)
            {
                switch(go_to)
                {
                    case "audit":
                        window.location.href = '<?php echo admin_url('trust_seal/audits/manage'); ?>';
                        break;
                    case "s_audit":
                        window.location.href = '<?php echo admin_url('trust_seal/audits/audit'); ?>/'+id_audit;
                        break;
                    case "exams":
                        window.location.href = '<?php echo admin_url('trust_seal/audits/audit'); ?>/'+id_audit+'?tab=exams';
                        break;
                }           
            }

            $(function() {
                //http://perfex.crm.indotel/admin/trust_seal/audits/audit/1
                $("#id_seal, #id_customer").change(function() {
                    let id_seal = $("#id_seal").val();
                    let id_customer = $("#id_customer").val();

                    $.get('<?php echo   admin_url('trust_seal/audits/if_exist_certifications_with_this_seal_and_client/'); ?>' + id_seal + '/' + id_customer, function(response) {
                        if (response.exist_audit) {
                            $('.alert_seal_exist').show();
                        } else {
                            $('.alert_seal_exist').hide();
                        }
                    }, 'json');
                });

                // init_editor('#description');

                $("#status, #qualification").change(function() {
                    let qualification = $('#qualification').val();
                    let status = $('#status').val();
                    // &&  $audit->status == 2
                    //                     &&  $audit->qualification == 2
                    //if (auts != 1) {
                    if (qualification == 2 && status == 2) {
                        $(".row_auto_certifications").show();
                    } else {
                        $("#auto_asignar, #reminder, #notification").prop('checked', false);
                        $(".row_auto_certifications").hide();
                    }
                    //}
                });

                $("#status, #qualification").trigger('change');
            });

            <?php if ($this->input->get('tab') == 'detail' || !$this->input->get('tab')) { ?>
                //document.getElementById("tab-detail").click();
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
                    // document.getElementById('btn_ds_' + id).style.display = "none";
                } else {
                    val.value = 0;
                    document.getElementById('content_sections_' + id).style.display = "block";
                    //document.getElementById('btn_ds_' + id).style.display = "block";
                }
            }


            $('.btnCheck, .btnNone').click(function() {


                let id_audit = $(this).data('cod');
                let id_customer = $(this).data('customer');
                let id_question = $(this).data('pregunta');
                let approved = $(this).data('status');

                if (approved == 1) {

                    $(this).css({
                        "color": "white",
                        "background-color": "#22c55e"
                    });

                    let btnNone = $(this).closest('div').find('.btnNone');

                    btnNone.css({
                        "color": "#8c8c8c ",
                        "background-color": "white"
                    });

                } else {
                    $(this).css({
                        "color": "white",
                        "background-color": "red"
                    });

                    let btnCheck = $(this).closest('div').find('.btnCheck');

                    btnCheck.css({
                        "color": "#8c8c8c ",
                        "background-color": "white"
                    });
                }

                audit_exam(id_audit, id_customer, id_question, approved);
            });



            function audit_exam(id_audit, id_customer, id_question, approved) {

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
                $('#comments_list').html("");
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
        </body>

        </html>