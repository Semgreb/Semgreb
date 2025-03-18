<script>
    <?php

    $has_access = get_option('superadmin_system_info_acces');
    if ($has_access) 
    { ?>
        $(".settings-group-system-update").hide();
        $(".settings-group-system-infobold").hide();
        $(".settings-group-system-info").hide();
    <?php } ?>

    <?php /* REMOVE PURCHASE KEY INPUT */ ?>
    var inputParent = $("#settings\\[purchase_key\\]").parent(".form-group");
    
    if(inputParent !== null){
        inputParent.hide();
    }
    
</script>