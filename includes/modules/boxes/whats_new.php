<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 osCommerce

  Released under the GNU General Public License
*/

  class osC_Boxes_whats_new extends osC_Modules {
    var $_title,
        $_code = 'whats_new',
        $_author_name = 'osCommerce',
        $_author_www = 'http://www.oscommerce.com',
        $_group = 'boxes';

    function osC_Boxes_whats_new() {
      global $osC_Language;

      $this->_title = $osC_Language->get('box_whats_new_heading');
      $this->_title_link = osc_href_link(FILENAME_PRODUCTS, 'new');
    }

    function initialize() {
      global $osC_Cache, $osC_Database, $osC_Services, $osC_Currencies, $osC_Specials, $osC_Language, $osC_Image;

      if ((BOX_WHATS_NEW_CACHE > 0) && $osC_Cache->read('box-whats_new-' . $osC_Language->getCode() . '-' . $osC_Currencies->getCode(), BOX_WHATS_NEW_CACHE)) {
        $data = $osC_Cache->getCache();
      } else {
        $data = array();

        $Qnew = $osC_Database->query('select p.products_id, p.products_tax_class_id, p.products_price, pd.products_name, pd.products_keyword, i.image from :table_products p left join :table_products_images i on (p.products_id = i.products_id and i.default_flag = :default_flag), :table_products_description pd where p.products_status = 1 and p.products_id = pd.products_id and pd.language_id = :language_id order by p.products_date_added desc limit :max_random_select_new');
        $Qnew->bindTable(':table_products', TABLE_PRODUCTS);
        $Qnew->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
        $Qnew->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
        $Qnew->bindInt(':default_flag', 1);
        $Qnew->bindInt(':language_id', $osC_Language->getID());
        $Qnew->bindInt(':max_random_select_new', BOX_WHATS_NEW_RANDOM_SELECT);
        $Qnew->executeRandomMulti();

        if ($Qnew->numberOfRows()) {
          $data = $Qnew->toArray();

          $products_price = $osC_Currencies->displayPrice($Qnew->valueDecimal('products_price'), $Qnew->valueInt('products_tax_class_id'));

          if ($osC_Services->isStarted('specials') && $osC_Specials->isActive($Qnew->valueInt('products_id'))) {
            $products_price = '<s>' . $products_price . '</s>&nbsp;<span class="productSpecialPrice">' . $osC_Currencies->displayPrice($osC_Specials->getPrice($Qnew->valueInt('products_id')), $Qnew->valueInt('products_tax_class_id')) . '</span>';
          }

          $data['products_price'] = $products_price;
        }

        $osC_Cache->writeBuffer($data);
      }

      if (empty($data) === false) {
        $this->_content = '';

        if (empty($data['image']) === false) {
          $this->_content .= osc_link_object(osc_href_link(FILENAME_PRODUCTS, $data['products_keyword']), $osC_Image->show($data['image'], $data['products_name'])) . '<br />';
        }

        $this->_content .= osc_link_object(osc_href_link(FILENAME_PRODUCTS, $data['products_keyword']), $data['products_name']) . '<br />' . $data['products_price'];
      }
    }

    function install() {
      global $osC_Database;

      parent::install();

      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Random New Product Selection', 'BOX_WHATS_NEW_RANDOM_SELECT', '10', 'Select a random new product from this amount of the newest products available', '6', '0', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Cache Contents', 'BOX_WHATS_NEW_CACHE', '1', 'Number of minutes to keep the contents cached (0 = no cache)', '6', '0', now())");
    }

    function getKeys() {
      if (!isset($this->_keys)) {
        $this->_keys = array('BOX_WHATS_NEW_RANDOM_SELECT', 'BOX_WHATS_NEW_CACHE');
      }

      return $this->_keys;
    }
  }
?>
