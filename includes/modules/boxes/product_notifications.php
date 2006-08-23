<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 osCommerce

  Released under the GNU General Public License
*/

  class osC_Boxes_product_notifications extends osC_Modules {
    var $_title,
        $_code = 'product_notifications',
        $_author_name = 'osCommerce',
        $_author_www = 'http://www.oscommerce.com',
        $_group = 'boxes';


    function osC_Boxes_product_notifications() {
      global $osC_Language;

      $this->_title = $osC_Language->get('box_product_notifications_heading');
      $this->_title_link = osc_href_link(FILENAME_ACCOUNT, 'notifications', 'SSL');
    }

    function initialize() {
      global $osC_Database, $osC_Language, $osC_Product, $osC_Customer;

      if (isset($osC_Product) && is_a($osC_Product, 'osC_Product')) {
        if ($osC_Customer->isLoggedOn()) {
          $Qcheck = $osC_Database->query('select global_product_notifications from :table_customers_info where customers_info_id = :customers_info_id');
          $Qcheck->bindTable(':table_customers_info', TABLE_CUSTOMERS_INFO);
          $Qcheck->bindInt(':customers_info_id', $osC_Customer->getID());
          $Qcheck->execute();

          if ($Qcheck->valueInt('global_product_notifications') === 0) {
            $Qcheck = $osC_Database->query('select products_id from :table_products_notifications where products_id = :products_id and customers_id = :customers_id limit 1');
            $Qcheck->bindTable(':table_products_notifications', TABLE_PRODUCTS_NOTIFICATIONS);
            $Qcheck->bindInt(':products_id', $osC_Product->getID());
            $Qcheck->bindInt(':customers_id', $osC_Customer->getID());
            $Qcheck->execute();

            if ($Qcheck->numberOfRows() > 0) {
              $this->_content = '<div style="float: left; width: 55px;">' . osc_link_object(osc_href_link(basename($_SERVER['PHP_SELF']), tep_get_all_get_params(array('action')) . 'action=notify_remove', 'AUTO'), osc_image(DIR_WS_IMAGES . 'box_products_notifications_remove.gif', sprintf($osC_Language->get('box_product_notifications_remove'), $osC_Product->getTitle()))) . '</div>' .
                                osc_link_object(osc_href_link(basename($_SERVER['PHP_SELF']), tep_get_all_get_params(array('action')) . 'action=notify_remove', 'AUTO'), sprintf($osC_Language->get('box_product_notifications_remove'), $osC_Product->getTitle()));
            } else {
              $this->_content = '<div style="float: left; width: 55px;">' . osc_link_object(osc_href_link(basename($_SERVER['PHP_SELF']), tep_get_all_get_params(array('action')) . 'action=notify', 'AUTO'), osc_image(DIR_WS_IMAGES . 'box_products_notifications.gif', sprintf($osC_Language->get('box_product_notifications_add'), $osC_Product->getTitle()))) . '</div>' .
                                osc_link_object(osc_href_link(basename($_SERVER['PHP_SELF']), tep_get_all_get_params(array('action')) . 'action=notify', 'AUTO'), sprintf($osC_Language->get('box_product_notifications_add'), $osC_Product->getTitle()));
            }

            $this->_content .= '<div style="clear: both;"></div>';
          }
        }
      }
    }
  }
?>
