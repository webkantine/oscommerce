<?php
/*
  $Id: password.php 64 2005-03-12 16:36:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2005 osCommerce

  Released under the GNU General Public License
*/

  class osC_Index_Index extends osC_Template {

/* Private variables */

    var $_module = 'index',
        $_group = 'index',
        $_page_title = HEADING_TITLE_INDEX,
        $_page_contents = 'index.php',
        $_page_image = 'table_background_list.gif';

/* Class constructor */

    function osC_Index_Index() {
      global $osC_Database, $osC_Services, $breadcrumb, $cPath, $cPath_array, $current_category_id, $osC_Category;

      if (isset($cPath) && (empty($cPath) === false)) {
        if ($osC_Services->isStarted('breadcrumb')) {
          $Qcategories = $osC_Database->query('select categories_id, categories_name from :table_categories_description where categories_id in (:categories_id) and language_id = :language_id');
          $Qcategories->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
          $Qcategories->bindTable(':categories_id', implode(',', $cPath_array));
          $Qcategories->bindInt(':language_id', $_SESSION['languages_id']);
          $Qcategories->execute();

          $categories = array();
          while ($Qcategories->next()) {
            $categories[$Qcategories->value('categories_id')] = $Qcategories->valueProtected('categories_name');
          }

          $Qcategories->freeResult();

          for ($i=0, $n=sizeof($cPath_array); $i<$n; $i++) {
            $breadcrumb->add($categories[$cPath_array[$i]], tep_href_link(FILENAME_DEFAULT, 'cPath=' . implode('_', array_slice($cPath_array, 0, ($i+1)))));
          }
        }

        $osC_Category = new osC_Category($current_category_id);

        $this->_page_title = $osC_Category->getTitle();
        $this->_page_image = $osC_Category->getImage();

        $Qproducts = $osC_Database->query('select products_id from :table_products_to_categories where categories_id = :categories_id limit 1');
        $Qproducts->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
        $Qproducts->bindInt(':categories_id', $current_category_id);
        $Qproducts->execute();

        if ($Qproducts->numberOfRows() > 0) {
          $this->_page_contents = 'product_listing.php';

          $this->_process();
        } else {
          $Qparent = $osC_Database->query('select categories_id from :table_categories where parent_id = :parent_id limit 1');
          $Qparent->bindTable(':table_categories', TABLE_CATEGORIES);
          $Qparent->bindInt(':parent_id', $current_category_id);
          $Qparent->execute();

          if ($Qparent->numberOfRows() > 0) {
            $this->_page_contents = 'category_listing.php';
          } else {
            $this->_page_contents = 'product_listing.php';

            $this->_process();
          }
        }
      }
    }

/* Private methods */

    function _process() {
      global $current_category_id, $osC_Products;

      include('includes/classes/products.php');
      $osC_Products = new osC_Products($current_category_id);

      if (isset($_GET['filter']) && is_numeric($_GET['filter']) && ($_GET['filter'] > 0)) {
        $osC_Products->setManufacturer($_GET['filter']);
      }

      if (isset($_GET['sort']) && !empty($_GET['sort'])) {
        if (strpos($_GET['sort'], '|d') !== false) {
          $osC_Products->setSortBy(substr($_GET['sort'], 0, -2), '-');
        } else {
          $osC_Products->setSortBy($_GET['sort']);
        }
      }
    }
  }
?>