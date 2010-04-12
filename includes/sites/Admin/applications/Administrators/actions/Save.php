<?php
/*
  osCommerce Online Merchant $osCommerce-SIG$
  Copyright (c) 2010 osCommerce (http://www.oscommerce.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class OSCOM_Site_Admin_Application_Administrators_Action_Save {
    public function execute(OSCOM_ApplicationAbstract $application) {
      if ( isset($_GET['id']) && is_numeric($_GET['id']) ) {
        $application->setPageContent('edit.php');
      } else {
        $application->setPageContent('new.php');
      }

      if ( isset($_POST['subaction']) && ($_POST['subaction'] == 'confirm') ) {
        $data = array('username' => $_POST['user_name'],
                      'password' => $_POST['user_password']);

        switch ( OSCOM_Site_Admin_Application_Administrators_Administrators::save((isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : null), $data, (isset($_POST['modules']) ? $_POST['modules'] : null)) ) {
          case 1:
            if ( isset($_GET['id']) && is_numeric($_GET['id']) && ($_GET['id'] == $_SESSION['admin']['id']) ) {
              $_SESSION['admin']['access'] = osC_Access::getUserLevels($_GET['id']);
            }

            OSCOM_Registry::get('MessageStack')->add(null, __('ms_success_action_performed'), 'success');

            osc_redirect_admin(OSCOM::getLink());

            break;

          case -1:
            OSCOM_Registry::get('MessageStack')->add(null, __('ms_error_action_not_performed'), 'error');

            osc_redirect_admin(OSCOM::getLink());

            break;

          case -2:
            OSCOM_Registry::get('MessageStack')->add(null, __('ms_error_username_already_exists'), 'error');

            break;
        }
      }
    }
  }
?>