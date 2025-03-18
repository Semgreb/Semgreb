<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin">
                            <?php echo $title; ?>
                        </h4>
                        <hr class="hr-panel-heading" />
                             
                        <?php echo form_open($this->uri->uri_string()); ?>
                        <div class="clearfix"></div>
                            
                        <!-- Fields Start -->
                        <?php render_yes_no_option('superadmin_system_acces','superadmin_system_acces_label'); ?>
                        <hr />
                        <?php render_yes_no_option('superadmin_customers_acces','superadmin_customers_acces_label'); ?>
                        <hr />
                        <?php echo render_input('settings[superadmin_number_users]','superadmin_number_users_label',get_option('superadmin_number_users'),'number', ['required'=>true]); ?>
                        <hr />
                        <?php echo render_input('settings[superadmin_help_link]','superadmin_help_link_label',get_option('superadmin_help_link'),'text'); ?>
                        <hr />
                        <?php echo render_input('settings[superadmin_knowledgebase_link]','superadmin_knowledgebase_link_label',get_option('superadmin_knowledgebase_link'),'text'); ?>
                        <hr />
                        
                        <div class="form-group">
                           <label for="" class="control-label"><?php echo _l('superadmin_tabs_setting_disable_label'); ?></label>
                           
                           <?php foreach($settings_tabs as $tab){ ?>
                              <div class="checkbox checkbox-primary">
                                 <input type="checkbox" name="settings[superadmin_tabs_setting_disable][]" checked  id="sat_<?php echo $tab; ?>" value="<?php echo $tab; ?>">
                                 <label for="sat_<?php echo $tab; ?>"><?php echo $tab; ?></label>
                              </div>
                           <?php } ?>

                           <?php if(is_array($settings_tabs_disable)) {
                              foreach($settings_tabs_disable as $tab){ ?>
                                 <div class="checkbox checkbox-primary">
                                    <input type="checkbox" name="settings[superadmin_tabs_setting_disable][]"  id="sat_<?php echo $tab; ?>" value="<?php echo $tab; ?>">
                                    <label for="sat_<?php echo $tab; ?>"><?php echo $tab; ?></label>
                                 </div>
                              <?php } ?>
                           <?php } ?>

                        </div>

                        <hr />
                        <?php render_yes_no_option('superadmin_system_info_acces','superadmin_sistem_info_disable_label'); ?>
                        
                        <hr />
                        <label for="" class="control-label"><?php echo _l('superadmin_menus_setting_disable_label'); ?></label>
                        <?php

                        foreach($list_menus as $key => $menus)
                        { 
                           if(in_array($key, $menus_settings)) 
                           {
                           ?>
                              <div class="form-group mleft20">
                                 
                                 <div class="checkbox checkbox-primary" style="padding-left: 5px;">
                                       <input type="checkbox" class="_header_group" name="settings[superadmin_menus_setting_disable][]"  id="<?php echo "{$key}={$key}"; ?>" value="<?php echo "{$key}={$key}"; ?>"
                                       <?php  echo in_array("{$key}={$key}", $settings_menus_disable ?? [])  ? "checked" : ""; ?>>
                                       <label for="<?php echo "{$key}={$key}"; ?>" class="control-label"><?php echo get_menu_setting()[$key]['name_menu'] ?? _l($key); ?></label>
                                 </div>
                                 <?php
                                 $list_children = $menus['children'];
                                 sort($list_children);
                                 foreach($list_children as $tab_menu){ 
                                    if($key == $tab_menu['slug'])
                                        continue;
                                 ?>
                                    <div class="checkbox checkbox-primary">
                                       <input type="checkbox" name="settings[superadmin_menus_setting_disable][]" class="child_check" id="<?php echo "{$key}=".$tab_menu['slug']; ?>" value="<?php echo "{$key}=".$tab_menu['slug']; ?>"
                                       <?php  echo in_array("{$key}=".$tab_menu['slug'], $settings_menus_disable ?? [])  ? "checked" : ""; ?>
                                       >
                                       <label for="<?php echo "{$key}=".$tab_menu['slug']; ?>"><?php echo $tab_menu['name']; ?></label>
                                    </div>
                                 <?php 
                                 } ?>
                              </div>
                           <?php 
                           }
                        } ?>

                        <!-- End Fields-->
                        <button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>
                        <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
      <?php init_tail(); ?>
   </body>
</html>
<script>
$('.child_check').click(function(){
   let group = $(this).closest('.form-group');
   let checkbox = group.find('._header_group');
   let exist = false;
   group.find('.child_check').each(function(i, obj){
      if($(this).is(':checked'))
      {
         exist = true;
      }  
   });
   checkbox.prop('checked', exist);
});

$('._header_group').click(function(){
    let group = $(this).closest('.form-group');
    let checkbox = group.find('.child_check');
    if($(this).is(':checked'))
    {
      checkbox.each(function(i, obj){
           $(obj).prop('checked', true);
      });
    }else{
      checkbox.each(function(i, obj){
           $(obj).prop('checked', false);
      });
    }
});
</script>
