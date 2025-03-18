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
    <br/>
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

                            <div class="col-12">
                               
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
                                         } ?>
                                         <span class="tw-text-lg"><?php echo $exam->name; ?></span>
                                         <br />
                                         <br />
                                        <?php 
                                         $exam_count++;

                               $i = 500;
                                if (isset($sections[$exam->id])) {
                                    foreach ($sections[$exam->id] as $section) { 
                                        
                                        ?>

                                        <div onload="show_hide(<?php echo $exam->id.'_'.$i; ?>)" style="border:1px solid #e8e8e8;border-radius:5px;margin-top:5px;" id="section_<?php echo $exam->id.'_'.$i; ?>">
                                            <div style="display:flex;justify-content:space-between;align-items:center;padding:15px;">
                                                <input type="text" value="<?php echo $section['name']; ?>" class="form-control" style="width:50%;border-radius:0;border:none;border-bottom:1px solid #e8e8e8;outline:none;" readonly>
                                                <div style="display:flex;justify-content:space-between;align-items:center;cursor:pointer;">
                                                    <b id="btn_ds_<?php echo $exam->id.'_'.$i; ?>"><?php echo get_count_quiz_complete($section[0]['quizs']); ?>/<?php echo count($section[0]['quizs']); ?></b>
                                                    <!-- <button type="button" class="btn" style="border-radius:5px;margin-left:10px;padding:5px !important;" onclick="show_hide('<?php //echo $i; 
                                                                                                                                                                                    ?>')"><span class="caret"></span></button> -->
                                                </div>
                                                <input type="hidden" value="1" id="content_sections_<?php echo $exam->id.'_'.$i; ?>_val">
                                            </div>
                                            <div style="background:#f8fafc;padding:15px;" id="content_sections_<?php echo $exam->id.'_'.$i; ?>">
                                                <div id="list_questions_<?php echo $exam->id.'_'.$i; ?>">

                                                    <?php foreach ($section[0]['quizs'] as $quiz) { ?>
                                                        <div style="display:flex;justify-content:space-between;align-items:center;margin-top:5px;" id="question_<?php echo $exam->id.'_'.$i; ?>">
                                                            <input type="text" value="<?php echo $quiz['name']; ?>" id_quiz="<?php echo $quiz['id']; ?>" class="form-control" style="width:93%;height: 40px;" readonly>


                                                            <div style="width:30px;margin-left:-120px;display:flex;">


                                                                <button type="button" class="btn" id="btnn_<?php echo $quiz['id']; ?>" disabled style="height:30px; padding:5px; border-radius:5px;margin-left:4px;<?php
                                                                                                                                                                                                                    echo (count($quiz['approved']) > 0 && $quiz['approved']['approved'] == 0) ? 'color:white;background:red;' : 'color:#8c8c8c;background:white;' ?>" onclick="audit_exam('<?php echo $audit->id; ?>', '<?php echo $audit->id_customer; ?>', '<?php echo $quiz['id']; ?>', 0, 'n')">
                                                                    <span class="fa-regular fa fa-times-circle fa-lg"></span>
                                                                </button>


                                                                <button type="button" class="btn" id="btns_<?php echo $quiz['id']; ?>" disabled style="height:30px; padding:5px; border-radius:5px;margin-left:4px;<?php echo (count($quiz['approved']) > 0 && $quiz['approved']['approved'] == 1) ? 'color:white;background:#22c55e;' : 'color:#8c8c8c;background:white;' ?>" onclick="audit_exam('<?php echo $audit->id; ?>', '<?php echo $audit->id_customer; ?>', '<?php echo $quiz['id']; ?>', 1, 's')">
                                                                    <span class="fa-regular fa fa-check-circle fa-lg"></span>
                                                                </button>

                                                            </div>
                                                            <?php
                                                            $haveComment = false;
                                                            $disableShowComment = 'disabled';
                                                            if (if_have_comment($audit->id, $quiz['id'])) {
                                                                $haveComment = true;
                                                                $disableShowComment = '';
                                                            } ?>
                                                            <button type="button" class="btn" style="height:30px;border-radius:5px;margin-left:5px;<?php echo ($haveComment) ? 'color:white;background:#03a9f4;' : 'color:#8c8c8c;background:white;' ?>" onclick="showComments('<?php echo $quiz['name']; ?>',<?php echo (int)$audit->id; ?>,<?php echo $quiz['id']; ?>)" <?php echo $disableShowComment; ?>><span class="fa-regular fa fa-commenting fa-lg"></span></button>


                                                        </div>
                                                    <?php } ?>

                                                </div>
                                            </div>
                                        </div>

                                <?php $i = $i + 1;
                                    }
                                } 
                                
                                
                            } ?>
                            </div>

                            <?php $this->load->view('audits/modals/comments_clients.php');
                            ?>

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
        //init_editor('#description');
        // appValidateForm($('#audit-form'), {
        //     description: 'required',
        // });
    });

    function go_audit(id_audit, go_to)
    {
        switch(go_to)
        {
            case "audit":
                window.location.href = '<?php echo site_url('trust_seal/clients/audits'); ?>';
                break;
            case "s_audit":
                window.location.href = '<?php echo site_url('trust_seal/clients/audidetails/'); ?>/'+id_audit;
                break;
            case "exams":
                window.location.href = '<?php echo site_url('trust_seal/clients/audidetails/'); ?>/'+id_audit+'?tab=exams';
                break;
        }           
    }

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