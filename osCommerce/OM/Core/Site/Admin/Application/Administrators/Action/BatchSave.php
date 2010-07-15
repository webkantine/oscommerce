<?php
/*
  osCommerce Online Merchant $osCommerce-SIG$
  Copyright (c) 2010 osCommerce (http://www.oscommerce.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  namespace osCommerce\OM\Site\Admin\Application\Administrators\Action;

  use osCommerce\OM\ApplicationAbstract;
  use osCommerce\OM\Site\Admin\Application\Administrators\Administrators;
  use osCommerce\OM\Registry;
  use osCommerce\OM\OSCOM;
  use osCommerce\OM\Site\Admin\Access;

  class BatchSave {
    public static function execute(ApplicationAbstract $application) {
      if ( isset($_POST['batch']) && is_array($_POST['batch']) && !empty($_POST['batch']) ) {
        $application->setPageContent('batch_edit.php');

        if ( isset($_POST['subaction']) && ($_POST['subaction'] == 'confirm') ) {
          $error = false;

          foreach ( $_POST['batch'] as $id ) {
            if ( !Administrators::setAccessLevels($id, $_POST['modules'], $_POST['mode']) ) {
              $error = true;
              break;
            }
          }

          if ( $error === false ) {
            Registry::get('MessageStack')->add(null, OSCOM::getDef('ms_success_action_performed'), 'success');

            if ( in_array($_SESSION[OSCOM::getSite()]['id'], $_POST['batch']) ) {
              $_SESSION[OSCOM::getSite()]['access'] = Access::getUserLevels($_SESSION[OSCOM::getSite()]['id']);
            }
          } else {
            Registry::get('MessageStack')->add(null, OSCOM::getDef('ms_error_action_not_performed'), 'error');
          }

          osc_redirect_admin(OSCOM::getLink());
        }
      }
    }
  }
?>