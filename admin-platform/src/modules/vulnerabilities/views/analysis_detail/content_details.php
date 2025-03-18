<?php

// echo "<pre>";
// print_r($info);

?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">
        <?php
        printf("%s # %s - %s", _l('table_warning_vulnerabilities_once'), $info->id, $info->alert);
        ?>
    </h4>
</div>
<div class="modal-body">
    <div class="row">

        <div class="col-md-12">


            <div class="row">

                <?php if (!empty($info->confidence)) { ?>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label"><?php echo _l('table_trust_label_vulnerabilities'); ?></label>
                            <p>
                                <?php

                                $confidence = '';
                                foreach (get_trust_scan() as $qualification) {
                                    if ($qualification['status'] == strtoupper($info->confidence)) {
                                        $confidence = get_risk_or_confidence_format($qualification);
                                        break;
                                    }
                                }

                                echo $confidence;
                                ?>
                            <p>
                        </div>
                    </div>

                <?php  }  ?>

                <?php if (!empty($info->risk)) { ?>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label"><?php echo _l('table_trust_vulnerabilities'); ?></label>
                            <p>

                                <?php

                                $risk = '';
                                foreach (get_risk_scan() as $qualification) {
                                    if ($qualification['status'] == strtoupper($info->risk)) {
                                        $risk = get_risk_or_confidence_format($qualification);
                                        break;
                                    }
                                }

                                echo $risk;
                                ?>


                            </p>
                        </div>
                    </div>

                <?php } ?>

                <?php if (!empty($info->method)) { ?>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label"><?php echo _l('table_vulnerabilities_method'); ?></label>
                            <p><?php echo $info->method; ?></p>
                        </div>
                    </div>
                <?php } ?>

                <?php if (!empty($info->web_site)) { ?>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label"><?php echo _l('table_resource_vulnerabilities'); ?></label>
                            <p><?php echo $info->web_site; ?></p>
                        </div>
                    </div>
                <?php } ?>
            </div>




        </div>
    </div>

    <div class="row">

        <div class="col-md-12">


            <div class="row">

                <?php if (!empty($info->param)) { ?>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label"><?php echo _l('table_vulnerabilities_params'); ?></label>
                            <p><?php echo $info->param; ?></p>
                        </div>
                    </div>

                <?php } ?>

                <?php if (!empty($info->attack)) { ?>

                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="control-label"><?php echo _l('table_vulnerabilities_attacks'); ?></label>
                            <p><?php echo $info->attack; ?></p>
                        </div>
                    </div>

                <?php } ?>
            </div>
        </div>
    </div>

    <hr>
    <?php if (!empty($info->description)) { ?>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label"><?php echo _l('table_vulnerabilities_description'); ?></label>
                            <p><?php echo $info->description; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <div class="row">

        <div class="col-md-12">


            <div class="row">

                <?php if (!empty($info->evidence)) { ?>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label"><?php echo _l('table_vulnerabilities_evidence'); ?></label>
                            <p><?php echo $info->evidence; ?></p>
                        </div>
                    </div>

                <?php } ?>

                <?php if (!empty($info->reference)) {
                    preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $info->reference, $match);
                ?>



                    <div class="col-md-8">
                        <div class="form-group">

                            <label class="control-label"><?php echo _l('table_vulnerabilities_reference'); ?></label>

                            <?php foreach ($match[0] as $value) { ?>

                                <div class="task-info task-billable-amount">
                                    <h5 class="tw-inline-flex tw-items-center mr-10">
                                        &nbsp;&nbsp;<i class="fa fa-regular fa-link fa-fw pull-left task-info-icon"></i>
                                        <a href="<?php echo $value; ?>" target="_blank"><?php echo $value; ?></a>
                                    </h5>
                                </div>

                            <?php  } ?>


                        </div>
                    </div>

                <?php } ?>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-12">

            <?php
            $listTags = json_decode($info->tags);
            ?>

            <div class="row">
                <?php
                $i = 0;
                foreach ($listTags as  $key => $value) { ?>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label"><?php echo  $i == 0 ? _l('table_vulnerabilities_tags') : "&nbsp;"; ?></label>
                            <p>
                                <a href="<?php echo $value; ?>" target="_blank">
                                    <span class="label ticket-status-<?php echo $key; ?>" style="border:0px solid  #a8c1f7; color: #2563eb; background:#f6f9fe;">
                                        <?php
                                        echo $key;
                                        ?>

                                        &nbsp;<i class="fas fa-up-right-from-square"></i>
                                    </span>
                                </a>
                            </p>
                        </div>
                    </div>
                <?php $i++;
                } ?>

            </div>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <?php if (!empty($info->solution)) { ?>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label"><?php echo _l('table_vulnerabilities_solution'); ?></label>
                            <p><?php echo $info->solution; ?></p>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

</div>
<div class="modal-footer">
    <div>
        <button type="button" class="btn btn-default close_btn" data-dismiss="modal"><?php echo _l('close'); ?></button>
    </div>
</div>