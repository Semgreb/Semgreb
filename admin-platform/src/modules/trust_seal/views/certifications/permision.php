<?php
$permision_certificaciont = 0;
$permision_audit = 0;
if (isset($contact)) {
    $result = get_certificacion_notification($contact->id);
    if ($result != null) {
        $permision_certificaciont = $result->notifications_certifications_emails;
        $permision_audit = $result->permision_audit;
    }
}
?>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-6 row">
            <div class="row">
                <div class="col-md-6 mtop10 border-right">
                    <span><?php echo _l('certification'); ?></span>
                </div>
                <div class="col-md-6 mtop10">
                    <div class="onoffswitch">
                        <input type="checkbox" id="notifications_certifications_emails" data-perm-id="-1" class="onoffswitch-checkbox" <?php if ($permision_certificaciont == '1') {
                                                                                                                                            echo 'checked';
                                                                                                                                        } ?> value="notifications_certifications_emails" name="notifications_certifications_emails">
                        <label class="onoffswitch-label" for="notifications_certifications_emails"></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 row">
            <div class="row">
                <div class="col-md-6 mtop10 border-right">
                    <span><?php echo _l('audit'); ?></span>
                </div>
                <div class="col-md-6 mtop10">
                    <div class="onoffswitch">
                        <input type="checkbox" id="permision_audit" data-perm-id="-1" class="onoffswitch-checkbox" <?php if ($permision_audit == '1') {
                                                                                                                        echo 'checked';
                                                                                                                    } ?> value="permision_audit" name="permision_audit">
                        <label class="onoffswitch-label" for="permision_audit"></label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>