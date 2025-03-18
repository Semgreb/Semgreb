<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <?php echo form_hidden('exam_id', $exam->id) ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="_buttons">
                    <div class="row">
                        <div class="col-md-7 project-heading">
                            <div class="tw-flex tw-flex-wrap tw-items-center">
                                <h3 class=" project-name">
                                    <span><?php echo (int)$exam->id . ' ' . $exam->name; ?></span>
                                    <?php if (has_permission('customers', '', 'delete') || is_admin()) { ?>
                                        <div class="btn-group">
                                            <a href="#" class="dropdown-toggle btn-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="caret"></span>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                <?php if (has_permission('certifications', '', 'delete')) { ?>
                                                    <li>
                                                        <a href="<?php echo admin_url('trust_seal/exams/delete_exam/' . $exam->id); ?>" class="text-danger delete-text _delete"><i class="fa fa-remove"></i>
                                                            <?php echo _l('delete'); ?>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    <?php } ?>
                                </h3>
                                <div class="visible-xs">
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 text-right">
                            <?php if (has_permission('exams', '', 'create')) { ?>
                                <a class="btn btn-primary" onclick="addSection()"><i class="fa-regular fa-plus tw-mr-1"></i><?php echo _l('new_section'); ?></a>
                            <?php } ?>

                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="row">
            <?php echo form_open($this->uri->uri_string(), ['id' => 'exams_sections_form']); ?>
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">

                        <div class="horizontal-scrollable-tabs panel-full-width-tabs">
                            <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
                            <div class="horizontal-tabs">
                                <ul class="nav nav-tabs nav-tabs-horizontal" role="tablist">
                                    <li role="presentation" class="tablinks" onclick="changeTab(event, 'tab_detail')" id="tab-detail">
                                        <a aria-controls="tab_detail" role="tab" data-toggle="tab">
                                            <?php echo _l('about_exam'); ?>
                                        </a>
                                    </li>
                                    <li role="presentation" class="tablinks" onclick="changeTab(event, 'tab_section')" id="tab-section">
                                        <a aria-controls="tab_section" role="tab" data-toggle="tab">
                                            <?php echo _l('sections'); ?>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane<?php if (!$this->input->get('tab')) {
                                                                echo ' active';
                                                            }; ?> tabcontent" id="tab_detail">
                            <div style="display:flex;">
                                <div style="width:70%;padding:10px;">
                                    <?php echo render_input('name', 'name_field_exam', $exam->name, 'text', ['autofocus' => true]); ?>
                                </div>
                                <div style="width:30%;padding:10px;">

                                    <?php echo render_select('status', $status, ['id', 'name'], 'audit_status', $exam->status, [], [], '', '', false); ?>

                                </div>
                            </div>
                            <div style="padding:10px;">
                                <?php echo render_textarea('description', 'description', $exam->description, [], [], ''); ?>
                            </div>
                            <div class="btn-bottom-toolbar text-right">
                                <button type="submit" name="update_exam" value="1" class="btn btn-primary" data-form="#exams_sections_form" data-loading-text="<?php echo _l('wait_text'); ?>">
                                    <?php echo _l('submit'); ?>
                                </button>
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane tabcontent" id="tab_section" style="display:none;">
                            <div class="col-12" id="list_sections">

                                <?php $i = 500;
                                if (isset($sections)) {
                                    foreach ($sections as $section) { ?>

                                        <div onload="show_hide(<?php echo $i; ?>)" style="border:1px solid #e8e8e8;border-radius:5px;margin-top:5px;" id="section_<?php echo $i; ?>">
                                            <div style="display:flex;justify-content:space-between;align-items:center;padding:15px;">
                                                <input type="text" value="<?php echo $section['name']; ?>" class="form-control" id_section="<?php echo $section['id']; ?>" onkeyup="update_section(this)" style="width:50%;border-radius:0;border:none;border-bottom:1px solid #e8e8e8;outline:none;">
                                                <div style="display:flex;justify-content:space-between;align-items:center;cursor:pointer;">
                                                    <b id="btn_ds_<?php echo $i; ?>" style="display:none; margin-right:10px;" onclick="delete_section('<?php echo $i; ?>', '<?php echo $section['id']; ?>')"><i class="fa-regular fa-trash-can fa-lg"></i> <?php echo _l('delete'); ?></b>
                                                    <button type="button" class="btn" style="" onclick="show_hide('<?php echo $i; ?>')"><span style="margin-left:0px !important;" class="caret"></span></button>
                                                </div>
                                                <input type="hidden" value="1" id="content_sections_<?php echo $i; ?>_val">
                                            </div>
                                            <div style="background:#f8fafc;padding:15px;display:none;" id="content_sections_<?php echo $i; ?>">
                                                <div style="display:flex;justify-content:space-between;align-items:center;">
                                                    <h4><?php echo _l('questions'); ?></h4>
                                                    <buttom class="btn btn-secondary" onclick="addQuestion(<?php echo $i; ?>,<?php echo $section['id']; ?>)"><?php echo _l('add_quiz'); ?></buttom>
                                                </div>
                                                <br>
                                                <div id="list_questions_<?php echo $i; ?>">

                                                    <?php foreach ($section[0]['quizs'] as $quiz) { ?>
                                                        <div style="display:flex;justify-content:space-between;align-items:center;margin-top:5px;" id="question_<?php echo $i; ?>">
                                                            <input type="text" value="<?php echo $quiz['name']; ?>" id_quiz="<?php echo $quiz['id']; ?>" onkeyup="update_quiz(this)" class="form-control" style="width:98%;">
                                                            <button type="button" class="btn" style="border-radius:5px;margin-left:10px;color:red;" onclick="delete_question('<?php echo $i; ?>','<?php echo $quiz['id']; ?>')"><span class="fa-regular fa-trash-can fa-lg"></span></button>
                                                        </div>
                                                    <?php } ?>

                                                </div>
                                            </div>
                                        </div>

                                <?php $i = $i + 1;
                                    }
                                } ?>

                            </div>

                            <div class="btn-bottom-toolbar text-right">
                                <button type="submit" name="update_exam" value="0" class="btn btn-primary" data-form="#exams_sections_form" data-loading-text="<?php echo _l('wait_text'); ?>">
                                    <?php echo _l('submit'); ?>
                                </button>
                            </div>

                        </div>

                    </div>

                </div>
                <?php echo form_close(); ?>
            </div>

        </div>
    </div>
</div>
</div>
<?php init_tail(); ?>
<script>
    $(function() {
        // init_editor('#description', {
        //     append_plugins: 'stickytoolbar'
        // });

        appValidateForm($('#exams_sections_form'), {
            name: 'required'
        });
    });

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

    let list_sections = document.getElementById('list_sections');
    // let count_sections = <?php echo $i; ?>;
    let count_sections = 1;
    let count_questions = 1;

    function addSection() {

        document.getElementById("tab-section").click();

        let template_sections = `
    <div style="border:1px solid #e8e8e8;border-radius:5px;margin-top:5px;" id="section_${count_sections}">
        <div style="display:flex;justify-content:space-between;align-items:center;padding:15px;">
            <input type="text" placeholder="<?php echo _l('section_add_edit_name'); ?>" name="sections[${count_sections}][section][]" class="form-control" style="width:50%;">
            <div style="display:flex;justify-content:space-between;align-items:center;cursor:pointer;">
                <b id="btn_ds_${count_sections}"  style="margin-right:10px;" onclick="delete_section('${count_sections}', 0)"><i class="fa-regular fa-trash-can fa-lg"></i> <?php echo _l('delete'); ?></b>
                <button type="button" class="btn" style="" onclick="show_hide('${count_sections}')"><span style="margin-left:0px !important;" class="caret"></span></button>
            </div>
            <input type="hidden" value="0" id="content_sections_${count_sections}_val">
        </div>
        <div style="background:#f8fafc;padding:15px;" id="content_sections_${count_sections}">
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <h4><?php echo _l('questions'); ?></h4>
                <buttom class="btn btn-secondary" onclick="addQuestion('${count_sections}','insert')"><?php echo _l('add_quiz'); ?></buttom>
            </div>
            <br>
            <div id="list_questions_${count_sections}">

            </div>
        </div>
    </div>
    `;

        count_sections += 1;
        list_sections.insertAdjacentHTML('beforeend', template_sections);
    }

    function delete_section(element, id) {
        document.getElementById('section_' + element).remove();
        if (id != 0) {
            $.ajax({
                url: '<?php echo admin_url('trust_seal/exams/delete_section/'); ?>' + id,
                type: 'GET',
                data: $(this).serialize(),
                success: function(res) {
                    console.log(res)
                    alert_float("success", res);
                },
                error: function(res) {
                    console.log(res)
                    alert_float("danger", res);
                }
            });
        }
    }

    function addQuestion(id, action_or_id) {
        let template_question = '';
        if (action_or_id === 'insert') {
            template_question = `
        <div style="display:flex;justify-content:space-between;align-items:center;margin-top:5px;" id="question_${count_questions}">
            <input type="text" value="" placeholder="<?php echo _l('add_quiz'); ?>" name="sections[${id}][question][${count_questions}][]" class="form-control" style="width:98%;">
            <button type="button" class="btn" style="border-radius:5px;margin-left:10px;color:red;" onclick="delete_question('${count_questions}', 0)"><span class="fa-regular fa-trash-can fa-lg"></span></button>
        </div>
    `;
        } else {
            template_question = `
        <div style="display:flex;justify-content:space-between;align-items:center;margin-top:5px;" id="question_${count_questions}">
            <input type="hidden" value="${action_or_id}" name="sections_update[${id}][section][]" class="form-control" style="width:98%;">
            <input type="text" placeholder="<?php echo _l('add_quiz'); ?>" value="" name="sections_update[${id}][question][]" class="form-control" style="width:98%;">
            <button type="button" class="btn" style="border-radius:5px;margin-left:10px;color:red;" onclick="delete_question('${count_questions}', 0)"><span class="fa-regular fa-trash-can fa-lg"></span></button>
        </div>
    `;
        }


        let list_questions = document.getElementById('list_questions_' + id);
        count_questions += 1;
        list_questions.insertAdjacentHTML('beforeend', template_question);
    }

    function delete_question(element, id) {

        if ($(`#list_questions_${element}`).find("input").length == 1) {
            alert_float("danger", "<?php echo _l('seals_secction_error_last'); ?>");
            return;
        }

        document.getElementById('question_' + element).remove();
        if (id != 0) {
            $.ajax({
                url: '<?php echo admin_url('trust_seal/exams/delete_quiz/'); ?>' + id,
                type: 'GET',
                data: $(this).serialize(),
                success: function(res) {
                    console.log(res)
                    alert_float("success", res);
                },
                error: function(res) {
                    console.log(res)
                    alert_float("danger", res);
                }
            });
        }
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

    function update_section(e) {
        if (this.timer) {
            window.clearTimeout(this.timer);
        }
        this.timer = window.setTimeout(function() {
            let id = e.getAttribute('id_section');

            $.ajax({
                url: '<?php echo admin_url('trust_seal/exams/update_section/'); ?>' + id,
                type: 'POST',
                data: {
                    'name': e.value
                }
            });

        }, 500);
    }

    function update_quiz(e) {
        if (this.timer) {
            window.clearTimeout(this.timer);
        }
        this.timer = window.setTimeout(function() {
            let id = e.getAttribute('id_quiz');

            $.ajax({
                url: '<?php echo admin_url('trust_seal/exams/update_quiz/'); ?>' + id,
                type: 'POST',
                data: {
                    'name': e.value
                }
            });

        }, 500);
    }
</script>