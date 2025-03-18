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
                                <div class="clearfix"></div>
                                
                                <h5>
                                    <?php echo _l('menu_builder'); ?>
                                </h5>
                                <ul class="nav nav-stacked nav-tabs navbar-pills navbar-pills-flat">
                                    <li>
                                        <a href="<?php echo admin_url('menu_setup/main_menu'); ?>"><?php echo _l('main_menu'); ?></a>
                                    </li>
                                    <li>
                                        <a href="<?php echo admin_url('menu_setup/setup_menu'); ?>"><?php echo _l('setup_menu'); ?></a>
                                    </li>
                                </ul>

                                <h5>
                                    <?php echo _l('modules'); ?>
                                </h5>
                                <ul class="nav nav-stacked nav-tabs navbar-pills navbar-pills-flat">
                                    <li>
                                        <a href="<?php echo admin_url('modules'); ?>"><?php echo _l('modules'); ?></a>
                                    </li>
                                </ul>

                                <h5>System/Server Info</h5>
                                <ul class="nav nav-stacked nav-tabs navbar-pills navbar-pills-flat">
                                    <li>
                                        <a href="<?php echo admin_url('settings?group=update'); ?>"><?php echo _l('settings_update'); ?></a>
                                    </li>
                                    <li>
                                        <a href="<?php echo admin_url('settings?group=info'); ?>">System/Server Info</a>
                                    </li>
                                </ul>
                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php init_tail(); ?>
   </body>
</html>