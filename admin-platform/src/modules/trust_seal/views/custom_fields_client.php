<?php

$client_description = "";
$client_email = "";
$client_logo = "";
$client_razon_social = "";
$slug = "";

if (isset($client)) {
    $result = get_extra_data_customer($client->userid);
    if ($result != null) {
        $client_description = $result->descriptions;
        $client_email = $result->email;
        $client_logo = $result->logo;
        // $client_razon_social = $result->client_razon_social;
    }
}

echo render_input('client_email', 'clients_email', $client_email);
// echo render_input('client_razon_social', 'clients_razon_social',  $client_razon_social);
echo render_textarea('client_description', 'clients_description', $client_description);
?>

<div class="form-group">
    <label for="website">Logo</label>
    <div class="input-group">
        <?php if ($client_logo != "") { ?>
            <div style="width: 100%;display:flex;justify-content:space-between;align-items: center;">
                <img src="<?php echo base_url(PATH_SEALS . '/logo_cliente_base/' . $client->userid . '/' . $client_logo);
                            ?>" alt="Logo active" style="width:200px;border-radius:5px 5px;">
                <!-- <a href="<?php // echo admin_url('trust_seal/seals/attachment_inactive/' . $seal->id . '?logo=' . $seal->logo_inactive);
                                ?>" style="color:red;"><b>
                        <h4>X</h4>
                    </b></a> -->
            </div>
            <hr>
        <?php }
        if (true) { ?>
            <input type="file" name="file_client_logo" id="file_client_logo" />
        <?php } ?>

        <input type="hidden" name="client_logo" id="client_logo" value="">
    </div>
</div>