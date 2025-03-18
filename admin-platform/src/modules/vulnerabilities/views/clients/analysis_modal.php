<!-- Modal -->
<?php echo form_open(admin_url('vulnerabilities/vulnerabilities/store?client=' . $client->userid), ['id' => 'analisy_form', 'name' => 'analisy_form']); ?>
<div class="modal fade" data-backdrop="static" id="analysisModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo _l('new_analysis'); ?></h4>
            </div>
            <input type="hidden" name="clientid" value="<?php echo $client->userid; ?>">
            <div class="modal-body">
                <?php
                $webSites =  $this->vulnerabilities_model->getClientWebSites($client->userid);
                $urls = [];
                $listUrl = "";
                if ($webSites != null) {
                    foreach ($webSites as $value) {
                        $urls[] = $value->web_site;
                    }
                    $listUrl = implode(',', $urls);
                }
                ?>
                <div class="form-group" app-field-wrapper="vat">
                    <label for="web_site" class="control-label">Website</label>
                    <!-- <input type="text" id="web_site" name="web_site" class="form-control" value="<?php // echo $listUrl; 
                                                                                                        ?>"> -->

                    <textarea id="web_site" name="web_site" class="form-control" rows="8"><?php echo $listUrl; ?></textarea>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="button" id="save_analysis_button" data-loading-text="<?php echo _l('wait_text'); ?>" class="btn btn-primary"><?php echo _l('save_changes_init_analisys'); ?></button>
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>