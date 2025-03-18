<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $this->load->view('admin/includes/head'); ?>
<div id="wrapper">
    <div class="content">

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="row">
                    <div class="col-md-12">

                        <div class="panel_s">
                            <div class="panel-body">
                                <div class="col-12">

                                    <span id="text_complete"></span>
                                    <br />
                                    <br />
                                    <?php $i = 500;

                                    $evaluaciones = 0;
                                    $countEvalaciones = 0;

                                    if (isset($sections)) {
                                        foreach ($sections as $section) { ?>

                                            <div style="border:1px solid #e8e8e8;border-radius:5px;margin-top:5px;" id="section_<?php echo $i; ?>">
                                                <div style="display:flex;justify-content:space-between;align-items:center;padding:15px;">
                                                    <input type="text" value="<?php echo $section['name']; ?>" class="form-control" style="width:50%;border-radius:0;border:none;border-bottom:1px solid #e8e8e8;outline:none;">

                                                    <input type="hidden" value="1" id="content_sections_<?php echo $i; ?>_val">
                                                </div>
                                                <div style="background:#f8fafc;padding:15px;" id="content_sections_<?php echo $i; ?>">
                                                    <div id="list_questions_<?php echo $i; ?>">

                                                        <?php

                                                        foreach ($section[0]['quizs'] as $quiz) {
                                                            $countEvalaciones++;
                                                        ?>
                                                            <div style="display:flex;justify-content:space-between;align-items:center;margin-top:5px;" id="question_<?php echo $i; ?>">


                                                                <input type="text" value="<?php echo $quiz['name']; ?>" id_quiz="<?php echo $quiz['id']; ?>" class="form-control" style="width:92%;height: 40px;">


                                                                <div style="width:30px;margin-left:-120px;display:flex;">


                                                                    <?php
                                                                    if (count($quiz['approved']) > 0)
                                                                        $evaluaciones++;
                                                                    ?>

                                                                    <?php if ((count($quiz['approved']) > 0 && $quiz['approved']['approved'] == 0)) {

                                                                    ?>

                                                                        <img style="width:25px; height:25px;" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAAAXNSR0IArs4c6QAAAERlWElmTU0AKgAAAAgAAYdpAAQAAAABAAAAGgAAAAAAA6ABAAMAAAABAAEAAKACAAQAAAABAAAAIKADAAQAAAABAAAAIAAAAACshmLzAAABO1BMVEUAAAD/AAD/VVXVKyvbSSTfQCD/QEDjOTnmMzPoRi7qQCvtNzfkQzbnPTHpNyzmPjLnPTHhOy/oQDTqQDXmQDHoQDLkPzHoPjLmPTLmQDLlPzLlPjHlPTToPTTnPTTnPzPnPjDnPTLlPzHmPzHmPjPmPTLlPTLmPTPmPTLnPjLnPjLmPjPlPjHmPjLnPjHmPjLmPzLnPzLnPzLmPjLlPjLmPjLmPjHmPjLnPjLmPjLmPjPmPjLmPzPmPTLmPjLlPTLlPzLmPzLmPjLmPTLmPjLmPjLlPjLmPjLnPjLmPjLnPjLmPjLlPjLmPjLmPjPlPjLmPjLnPjLmPjLnPjLmPTLmPjLmPjLmPjLmPzPnQjfnRDnnRjroT0ToUEXoUEbrZl3tcWjtc2vvgHnzqKL1r6n1saz3wLz4yMX4y8f+6lzuAAAAV3RSTlMAAQMGBwgICQoLDA4TFRcpKissMDQ4OUJHSE1OT09UVV9gbW6DhYqNjpOUnJ2srK2vtr7AwsLEyMrLzdPU1dbY2Nja4Onr7/Hx8/P19vb3+Pj6+/v9/f70mhPXAAABUElEQVQYGX3BB0MBAQAF4HdoXEgKFRWSKySloWlXshNee6///ws6oXLd3fehRzBZJ/xSLHGUTEQln906hEGG8emV3Rp7anvLsxYDfgkjtuAB/2pkliZH0SeYZ2JlKhTXPCK6BLM7zv+qm14R30Zm4lRT3543QmawxaiutD4FQBgPlqnhRBoDTNMH1JTzANYVaiuEhzGxS23ttBP+GnXkA5Co5yyCGPU0tpCg7PL67l7h9oYdKRxRdvXw+qbw8siOLJKUXT99fCq8P7MjiwR1JRGlnosdSNRTXIWvRh35Rdj3qK116IJ1uUFNpyERQ7MZammm5gBYlopU1z6WLAAMk2tVqqpsONAx6tmsU8X5vhtdone7RKV2ZX8BfeL8+gkHNY833PhlnJJyhTZ/tE5TkgMDxjzhdP6sQdlFMX8YmrNAadgZiGylstnkzuqiS0TfF0DcD2HkpNGdAAAAAElFTkSuQmCC" />



                                                                    <?php }
                                                                    ?>


                                                                    <?php if (count($quiz['approved']) > 0 && $quiz['approved']['approved'] == 1) { ?>

                                                                        <img style="width:25px; height:25px;" src="data:image/png;base64,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" />


                                                                    <?php } ?>
                                                                    <!-- <span class="fa-regular fa fa-check-circle fa-lg"></span>
                                                                    </button> -->

                                                                </div>
                                                                <?php
                                                                $haveComment = false;

                                                                // if (if_have_comment($audit->id, $quiz['id'])) {
                                                                //     $haveComment = true;
                                                                // } 
                                                                ?>
                                                                <!-- <button type="button" class="btn" style="border-radius:5px;margin-left:5px;<?php //echo ($haveComment) ? 'color:white;background:#03a9f4;' : 'color:#8c8c8c;background:white;' 
                                                                                                                                                ?>" onclick="showComments('<?php //echo $quiz['name']; 
                                                                                                                                                                            ?>',<?php //echo $audit->id; 
                                                                                                                                                                                ?>,<?php //echo $quiz['id']; 
                                                                                                                                                                                                            ?>)"><span class="fa-regular fa fa-commenting fa-lg"></span></button> -->
                                                            </div>
                                                        <?php } ?>

                                                    </div>
                                                </div>
                                            </div>

                                    <?php $i = $i + 1;
                                        }
                                    } ?>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>


        </body>

        </html>