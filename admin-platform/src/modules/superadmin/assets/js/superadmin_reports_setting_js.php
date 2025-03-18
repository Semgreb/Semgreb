<script>
window.onload = function()
{
    let slug = '', event_string = '', name_report = '', lits;

    <?php 
        $settings_menus_disable = json_decode(get_option('superadmin_menus_setting_disable'), true);
    
        if(is_array($settings_menus_disable)) 
        {
            foreach($settings_menus_disable as $tab) 
            {    
                $menus = explode('=', $tab);
    ?>
                slug ='<?php echo str_replace("_","-",$menus[1]); ?>';
                lits = document.getElementsByClassName("group");
                for (let i = 0; i < lits.length; i++) 
                {
                        event_string = lits.item(i).getAttribute('onclick'); 
                        name_report = String(event_string.match(/\'+[a-zA-Z0-9\-_]+\'/));
                        name_report = name_report.replace(/'/g, "");

                        if(name_report.indexOf(slug) !== -1)
                        {
                            lits.item(i).style.display = 'none';
                        }
                        
                }
    <?php
                      
            }
        }

    ?>
}
</script>