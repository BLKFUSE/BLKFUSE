<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Install
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: index.php 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */

// Sanity check
if( version_compare(PHP_VERSION, '8.0', '<') ) {
  echo 'SocialEngine requires at least PHP 8.0';
  exit();
}

// Redirect to index.php if rewrite not enabled
$target = null;
if( empty($_GET['rewrite']) && 0 !== strpos($_SERVER['REQUEST_URI'], $_SERVER['PHP_SELF']) ) {
  // Redirect to index if rewrite not enabled
  $target = $_SERVER['PHP_SELF'];
  $params = $_GET;
  unset($params['rewrite']);
  if( !empty($params) ) {
    $target .= '?' . http_build_query($params);
  }
} else if( isset($_GET['rewrite']) && $_GET['rewrite'] == 2 ) {
  // Redirect to virtual index if rewrite enabled
  $target = str_replace($_SERVER['PHP_SELF'], dirname($_SERVER['PHP_SELF']), $_SERVER['REQUEST_URI']);
}
if( null !== $target ) {
  header('Location: ' . $target);
  exit();
}

error_reporting(E_ALL);
define('_ENGINE', TRUE);
define('DS', DIRECTORY_SEPARATOR);
define('PS', PATH_SEPARATOR);

define('_ENGINE_REQUEST_START', microtime(true));

defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(dirname(__FILE__))));

defined('APPLICATION_ENV') || (
  !empty($_SERVER['_ENGINE_ENVIRONMENT']) && in_array($_SERVER['_ENGINE_ENVIRONMENT'], array('development', 'staging', 'production')) ?
  define('APPLICATION_ENV', $_SERVER['_ENGINE_ENVIRONMENT']) :
  define('APPLICATION_ENV', 'production')
);

defined('_ENGINE_NO_AUTH') || (
  !empty($_SERVER['_ENGINE_NOAUTH']) && $_SERVER['_ENGINE_NOAUTH'] == '1' ?
  define('_ENGINE_NO_AUTH', true) :
  define('_ENGINE_NO_AUTH', false)
);

defined('_ENGINE_SSL') || define('_ENGINE_SSL', (
  (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') ||
  (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on')
));

//site address
$siteurl = (_ENGINE_SSL ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'];
// $PHP_SELF = explode('/', $_SERVER['PHP_SELF']);
// if($PHP_SELF[1] != 'index.php') {
// 	$PHP_SELF = $PHP_SELF[1];
// 	$siteurl = $siteurl . '/' . $PHP_SELF;
// }
defined('_ENGINE_SITE_URL') || define('_ENGINE_SITE_URL', $siteurl);

set_include_path(
  APPLICATION_PATH . DS . 'application' . DS . 'libraries' . PS .
  APPLICATION_PATH . DS . 'application' . DS . 'libraries' . DS . 'PEAR' . PS .
  '.' // get_include_path()
);

require_once "Zend/Loader.php";
require_once "Zend/Loader/Autoloader.php";
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace('Engine');

$application = new Zend_Application(APPLICATION_ENV, array(
  'bootstrap' => array(
    'class' => 'Install_Bootstrap',
    'path' => APPLICATION_PATH . '/install/Bootstrap.php',
  ),
));


// Debug
if( !empty($_SERVER['_ENGINE_TRACE_ALLOW']) && extension_loaded('xdebug') ) {
  xdebug_start_trace();
}


// Run
try {
  $application->bootstrap();
  $application->run();
} catch( Exception $e ) {

  // Render custom error page
  $error = $e;
  $base = dirname($_SERVER['PHP_SELF']);
  include_once './views/scripts/_rawError.tpl';
}

// Debug
if( !empty($_SERVER['_ENGINE_TRACE_ALLOW']) && extension_loaded('xdebug') ) {
  xdebug_stop_trace();
}
