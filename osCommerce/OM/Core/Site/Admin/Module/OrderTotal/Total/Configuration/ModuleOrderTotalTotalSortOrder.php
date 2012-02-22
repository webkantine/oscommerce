<?php
/**
 * osCommerce Online Merchant
 * 
 * @copyright Copyright (c) 2012 osCommerce; http://www.oscommerce.com
 * @license BSD License; http://www.oscommerce.com/bsdlicense.txt
 */

  namespace osCommerce\OM\Core\Site\Admin\Module\OrderTotal\Total\Configuration;

/**
 * @since v3.0.4
 */

  class ModuleOrderTotalTotalSortOrder extends \osCommerce\OM\Core\Site\Admin\Module\ConfigurationAbstract {
    static protected $_sort = 200;
    static protected $_default = '4';
    static protected $_group_id = 6;

    public function initialize() { }
  }
?>