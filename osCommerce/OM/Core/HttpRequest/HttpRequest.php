<?php
/**
 * osCommerce Online Merchant
 * 
 * @copyright Copyright (c) 2012 osCommerce; http://www.oscommerce.com
 * @license BSD License; http://www.oscommerce.com/bsdlicense.txt
 */

  namespace osCommerce\OM\Core\HttpRequest;

  class HttpRequest {
    protected static $_methods = array('get' => HTTP_METH_GET,
                                       'post' => HTTP_METH_POST);

    public static function execute($parameters) {
      $h = new \HttpRequest($parameters['server']['scheme'] . '://' . $parameters['server']['host'] . $parameters['server']['path'] . (isset($parameters['server']['query']) ? '?' . $parameters['server']['query'] : ''), static::$_methods[$parameters['method']], array('redirect' => 5));

      if ( isset($parameters['header']) ) {
        $headers = array();

        foreach ( $parameters['header'] as $header ) {
          list($key, $value) = explode(':', $header, 2);

          $headers[$key] = trim($value);
        }

        $h->setHeaders($headers);
      }

      if ( $parameters['method'] == 'post' ) {
        $h->setBody($parameters['parameters']);
      }

      if ( $parameters['server']['scheme'] === 'https' ) {
        $h->addSslOptions(array('verifypeer' => true,
                                'verifyhost' => true));

        if ( isset($parameters['cafile']) && file_exists($parameters['cafile']) ) {
          $h->addSslOptions(array('cainfo' => $parameters['cafile']));
        }

        if ( isset($parameters['certificate']) ) {
          $h->addSslOptions(array('cert' => $parameters['certificate']));
        }
      }

      $result = '';

      try {
        $h->send();

        $result = $h->getResponseBody();
      } catch ( \Exception $e ) {
        if ( isset($e->innerException) ) {
          trigger_error($e->innerException->getMessage());
        } else {
          trigger_error($e->getMessage());
        }
      }

      return $result;
    }

    public static function canUse() {
      return class_exists('\HttpRequest');
    }
  }
?>
