<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 osCommerce

  Released under the GNU General Public License
*/

  class osC_Boxes_manufacturer_info extends osC_Modules {
    var $_title,
        $_code = 'manufacturer_info',
        $_author_name = 'osCommerce',
        $_author_www = 'http://www.oscommerce.com',
        $_group = 'boxes';

    function osC_Boxes_manufacturer_info() {
      global $osC_Language;

      $this->_title = $osC_Language->get('box_manufacturer_info_heading');
    }

    function initialize() {
      global $osC_Database, $osC_Language, $osC_Product;

      if (isset($osC_Product) && is_a($osC_Product, 'osC_Product')) {
        $Qmanufacturer = $osC_Database->query('select m.manufacturers_id, m.manufacturers_name, m.manufacturers_image, mi.manufacturers_url from :table_manufacturers m left join :table_manufacturers_info mi on (m.manufacturers_id = mi.manufacturers_id and mi.languages_id = :languages_id), :table_products p  where p.products_id = :products_id and p.manufacturers_id = m.manufacturers_id');
        $Qmanufacturer->bindTable(':table_manufacturers', TABLE_MANUFACTURERS);
        $Qmanufacturer->bindTable(':table_manufacturers_info', TABLE_MANUFACTURERS_INFO);
        $Qmanufacturer->bindTable(':table_products', TABLE_PRODUCTS);
        $Qmanufacturer->bindInt(':languages_id', $osC_Language->getID());
        $Qmanufacturer->bindInt(':products_id', $osC_Product->getID());
        $Qmanufacturer->execute();

        if ($Qmanufacturer->numberOfRows()) {
          $this->_content = '';

          if (tep_not_null($Qmanufacturer->value('manufacturers_image'))) {
            $this->_content .= '<div style="text-align: center;">' .
                               osc_link_object(osc_href_link(FILENAME_DEFAULT, 'manufacturers=' . $Qmanufacturer->valueInt('manufacturers_id')), osc_image(DIR_WS_IMAGES . $Qmanufacturer->value('manufacturers_image'), $Qmanufacturer->value('manufacturers_name'))) .
                               '</div>';
          }

          $this->_content .= '<ol style="list-style: none; margin: 0; padding: 0;">';

          if (tep_not_null($Qmanufacturer->value('manufacturers_url'))) {
            $this->_content .= '<li>' . osc_link_object(osc_href_link(FILENAME_REDIRECT, 'action=manufacturer&manufacturers_id=' . $Qmanufacturer->valueInt('manufacturers_id')), sprintf($osC_Language->get('box_manufacturer_info_website'), $Qmanufacturer->value('manufacturers_name')), 'target="_blank"') . '</li>';
          }

          $this->_content .= '<li>' . osc_link_object(osc_href_link(FILENAME_DEFAULT, 'manufacturers=' . $Qmanufacturer->valueInt('manufacturers_id')), $osC_Language->get('box_manufacturer_info_products')) . '</li>';

          $this->_content .= '</ol>';
        }
      }
    }
  }
?>
