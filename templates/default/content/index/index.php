<?php
/*
  $Id: $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 osCommerce

  Released under the GNU General Public License
*/
?>

<?php echo osc_image(DIR_WS_IMAGES . 'table_background_default.gif', $osC_Template->getPageTitle(), null, null, 'id="pageIcon"'); ?>

<h1><?php echo $osC_Template->getPageTitle(); ?></h1>

<?php
  if ($osC_Customer->isLoggedOn()) {
    echo '<p>' . sprintf($osC_Language->get('greeting_customer'), osc_output_string_protected($osC_Customer->getFirstName()), osc_href_link(FILENAME_PRODUCTS, 'new')) . '</p>';
  } else {
    echo '<p>' . sprintf($osC_Language->get('greeting_guest'), osc_href_link(FILENAME_ACCOUNT, 'login', 'SSL'), osc_href_link(FILENAME_PRODUCTS, 'new')) . '</p>';
  }
?>

<p><?php echo $osC_Language->get('index_text'); ?></p>