<script>
window.onload = function()
{
    let slug = '', get_id_widget, total_slug, total_hidden, li_selected, next_list, next_panel, li_panel;
   
    <?php 
        
        $widget_stast_id = [
             'invoices' => 1
             ,'estimates' => 2
             ,'proposals' => 3
        ];

        $settings_menus_disable = json_decode(get_option('superadmin_menus_setting_disable'), true);    
        if(is_array($settings_menus_disable)) 
        {
            foreach($settings_menus_disable as $tab) 
            {    
                $menus = explode('=', $tab);
    ?>
                
                slug ='<?php echo $menus[1]; ?>';       
                get_id_widget = '<?php echo $widget_stast_id[$menus[1]] ?? 0; ?>';
                
                $(`.quick-stats-${slug}`).hide();
                $(`#${slug}_total`).hide();

                $('#widget-finance_overview .home-summary > div').each(function(i, obj){
                    if(parseInt(get_id_widget) === parseInt((i + 1)))
                    {
                        $(obj).hide();
                    }
                });

                get_tab_list_widget = '<?php echo get_menu_setting()[$menus[0]]['user_data_widget_tab'] ?? ''; ?>';
              
                if(get_tab_list_widget != '')
                {
                    $('#widget-user_data ul li').each(function(i,obj)
                    {
                        if(get_tab_list_widget == $('a', obj).attr('href'))
                        {
                            li_selected = obj;
                            return false
                        }
                    });

                    li_selected.remove();
                }

                $('a', '#widget-user_data ul li:first').trigger('click');
    <?php       
            }
        }
    ?>
}
</script>