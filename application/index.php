<?php
/**
 * @package     Engine_Core
 * @version     $Id: index.php 10228 2014-05-16 22:43:39Z andres $
 * @copyright   Copyright (c) 2006-2020 Webligo Developments
 * @license     http://www.socialengine.com/license/
 */

// Start trace
if (!empty($_SERVER['_ENGINE_TRACE_ALLOW']) && extension_loaded('xdebug')) {
    xdebug_start_trace();
} elseif (!empty($_SERVER['_ENGINE_XHPROF_ALLOW']) && extension_loaded('xhprof')) {
    xhprof_enable();
}

if(file_exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..'. DIRECTORY_SEPARATOR.'engineFunctions.php'))
  include dirname(__FILE__) . DIRECTORY_SEPARATOR . '..'. DIRECTORY_SEPARATOR . 'engineFunctions.php';

// Rewrite detection
if (!defined('_ENGINE_R_REWRITE') && 'cli' !== PHP_SAPI) {
    $target = null;
    if (empty($_GET['rewrite']) && 0 !== strpos($_SERVER['REQUEST_URI'], $_SERVER['PHP_SELF'])) {
        // Redirect to index if rewrite not enabled
        $target = $_SERVER['PHP_SELF'];
        $params = $_GET;
        unset($params['rewrite']);
        if (!empty($params)) {
            $target .= '?' . http_build_query($params);
        }
    } elseif (isset($_GET['rewrite']) && $_GET['rewrite'] == 2) {
        // Redirect to virtual index if rewrite enabled
        $target = str_replace($_SERVER['PHP_SELF'], dirname($_SERVER['PHP_SELF']), $_SERVER['REQUEST_URI']);
    }
    if (null !== $target) {
        header('Location: ' . $target);
        exit();
    }
}

$cookieParams = session_get_cookie_params();
session_set_cookie_params($cookieParams['lifetime'], $cookieParams["path"].';SameSite=Lax', $cookieParams["domain"],$cookieParams["secure"], $cookieParams["httponly"]);

// Basic setup
error_reporting(E_ALL);

defined('DS') || define('DS', DIRECTORY_SEPARATOR);
defined('PS') || define('PS', PATH_SEPARATOR);
defined('_ENGINE') || define('_ENGINE', true);
defined('_ENGINE_REQUEST_START') ||
    define('_ENGINE_REQUEST_START', microtime(true));

defined('APPLICATION_PATH') ||
    define('APPLICATION_PATH', realpath(dirname(dirname(__FILE__))));
defined('APPLICATION_PATH_COR') ||
    define('APPLICATION_PATH_COR', realpath(dirname(__FILE__)));
defined('APPLICATION_PATH_EXT') ||
    define('APPLICATION_PATH_EXT', APPLICATION_PATH . DS . 'externals');
defined('APPLICATION_PATH_PUB') ||
    define('APPLICATION_PATH_PUB', APPLICATION_PATH . DS . 'public');
defined('APPLICATION_PATH_TMP') ||
    define('APPLICATION_PATH_TMP', APPLICATION_PATH . DS . 'temporary');

defined('APPLICATION_PATH_BTS') ||
    define('APPLICATION_PATH_BTS', APPLICATION_PATH_COR . DS . 'bootstraps');
defined('APPLICATION_PATH_LIB') ||
    define('APPLICATION_PATH_LIB', APPLICATION_PATH_COR . DS . 'libraries');
defined('APPLICATION_PATH_MOD') ||
    define('APPLICATION_PATH_MOD', APPLICATION_PATH_COR . DS . 'modules');
defined('APPLICATION_PATH_PLU') ||
    define('APPLICATION_PATH_PLU', APPLICATION_PATH_COR . DS . 'plugins');
defined('APPLICATION_PATH_SET') ||
    define('APPLICATION_PATH_SET', APPLICATION_PATH_COR . DS . 'settings');
defined('APPLICATION_PATH_WID') ||
    define('APPLICATION_PATH_WID', APPLICATION_PATH_COR . DS . 'widgets');

// Setup required include paths; optimized for Zend usage. Most other includes
// will use an absolute path
set_include_path(
  APPLICATION_PATH_LIB . PS .
  APPLICATION_PATH_LIB . DS . 'PEAR' . PS .
  '.' // get_include_path()
);

defined('APPLICATION_NAME') || define('APPLICATION_NAME', 'Core');
defined('_ENGINE_ADMIN_NEUTER_MODE') || define('_ENGINE_ADMIN_NEUTER_MODE', false);
defined('_ENGINE_NO_AUTH') || define('_ENGINE_NO_AUTH', false);
defined('_ENGINE_SSL') || define('_ENGINE_SSL', ((isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') || (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on')));

//site address
$siteurl = (_ENGINE_SSL ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'];
$PHP_SELF = explode('/', $_SERVER['PHP_SELF']);
if($PHP_SELF[1] != 'index.php') {
	$PHP_SELF = $PHP_SELF[1];
	$siteurl = $siteurl . '/' . $PHP_SELF;
}
defined('_ENGINE_SITE_URL') || define('_ENGINE_SITE_URL', $siteurl);

if (version_compare(PHP_VERSION, '5.3.2', '>') && file_exists(APPLICATION_PATH . '/vendor/autoload.php')) {
    define('_ENGINE_HAS_VENDOR', true);
    require(APPLICATION_PATH . '/vendor/autoload.php');
}

// get general config
if (file_exists(APPLICATION_PATH_SET . DS . 'general.php')) {
    if(in_array('Zend OPcache', get_loaded_extensions()))
      opcache_invalidate(APPLICATION_PATH_SET . DS . 'general.php');//Reset file cache
    $generalConfig = include APPLICATION_PATH_SET . DS . 'general.php';
} else {
    $generalConfig = array('environment_mode' => 'production');
}

  // maintenance mode
if (!defined('_ENGINE_R_MAINTENANCE') || _ENGINE_R_MAINTENANCE) {
    if (!empty($generalConfig['maintenance']['enabled']) && !empty($generalConfig['maintenance']['code'])) {
        $code = $generalConfig['maintenance']['code'];
        if (@$_REQUEST['en4_maint_code'] == $code || @$_COOKIE['en4_maint_code'] == $code) {
            if (@$_COOKIE['en4_maint_code'] !== $code) {
                setcookie('en4_maint_code', $code, time() + (86400 * 7), '/');
            }
        } else {
            header('HTTP/1.1 503 Service Temporarily Unavailable');
            header('Status: 503 Service Temporarily Unavailable');
            header('Retry-After: 300');//300 seconds
						$body = file_get_contents(dirname(__FILE__) . DS . 'maintenance.html');
						echo str_replace('%_BASE_URL_%', _ENGINE_SITE_URL, $body);
            exit();
        }
    }
}

// Get Gzipping Option
if (file_exists(APPLICATION_PATH_SET . DS . 'cache.php')) {
    $cache_array = include APPLICATION_PATH_SET . DS . 'cache.php';
    if (isset($cache_array['frontend']['core']['gzip'])) {
        if ($cache_array['frontend']['core']['gzip'] == true) {
            if (!empty($_SERVER['HTTP_ACCEPT_ENCODING']) && substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
                ob_start("ob_gzhandler");
            } else {
                ob_start();
            }
        }
    }
}


// development mode
$application_env = @$generalConfig['environment_mode'];
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (
  !empty($_SERVER['_ENGINE_ENVIRONMENT']) ? $_SERVER['_ENGINE_ENVIRONMENT'] : (
  $application_env ? $application_env :
  'production'
)));

$appMode = isset($generalConfig['application_mode']) ? $generalConfig['application_mode'] : 'production';
defined('APPLICATION_DEV_MODE') || define('APPLICATION_DEV_MODE', $appMode);

// production mode
if (APPLICATION_ENV === 'production' && version_compare(phpversion(), '5.3.0', '>=')) {
    error_reporting(E_ALL & ~E_STRICT & ~E_DEPRECATED);
} elseif (APPLICATION_ENV === 'production') {
    error_reporting(E_ALL & ~E_STRICT);
}
// Check for uninstalled state
if (!file_exists(APPLICATION_PATH_SET . DS . 'database.php')) {
    if ('cli' !== PHP_SAPI) {
        header('Location: ' . rtrim((string)constant('_ENGINE_R_BASE'), '/') . '/install/index.php');
    } else {
        echo 'Not installed' . PHP_EOL;
    }
    exit();
}

// Check tasks
if (!empty($_REQUEST['notrigger'])) {
    define('ENGINE_TASK_NOTRIGGER', true);
}

// Sub apps
if (!defined('_ENGINE_R_MAIN') && !defined('_ENGINE_R_INIT')) {
    if (@$_GET['m'] == 'css') {
        define('_ENGINE_R_MAIN', 'css.php');
        define('_ENGINE_R_INIT', false);
    } elseif (@$_GET['m'] == 'lite') {
        define('_ENGINE_R_MAIN', 'lite.php');
        define('_ENGINE_R_INIT', true);
    } else {
        define('_ENGINE_R_MAIN', false);
        define('_ENGINE_R_INIT', true);
    }
}

// Boot
if (_ENGINE_R_INIT) {
    // Application
    require_once 'Engine/Loader.php';
    require_once 'Engine/Application.php';

    // Create application, bootstrap, and run
    $application = new Engine_Application(
        array(
            'environment' => APPLICATION_ENV,
            'bootstrap' => array(
                'path' => APPLICATION_PATH_COR . DS . 'modules' . DS . APPLICATION_NAME . DS . 'Bootstrap.php',
                'class' => ucfirst(APPLICATION_NAME) . '_Bootstrap',
            ),
            'autoloaderNamespaces' => array(
                'Zend'      => APPLICATION_PATH_LIB . DS . 'Zend',
                'Engine'    => APPLICATION_PATH_LIB . DS . 'Engine',
                'Facebook'  => APPLICATION_PATH_LIB . DS . 'Facebook',

                'Bootstrap' => APPLICATION_PATH_BTS,
                'Plugin'    => APPLICATION_PATH_PLU,
                'Widget'    => APPLICATION_PATH_WID,
            ),
        )
    );

    Engine_Application::setInstance($application);
    Engine_Api::getInstance()->setApplication($application);
}

// config mode
if (defined('_ENGINE_R_CONF') && _ENGINE_R_CONF) {
    return;
}


// Sub apps
if (_ENGINE_R_MAIN) {
    require dirname(__FILE__) . DS . _ENGINE_R_MAIN;
    exit();
}

// Main app
else {
    $application->bootstrap();
    $application->run();
}



// Start trace
if (!empty($_SERVER['_ENGINE_TRACE_ALLOW']) && extension_loaded('xdebug')) {
    xdebug_stop_trace();
} elseif (!empty($_SERVER['_ENGINE_XHPROF_ALLOW']) && extension_loaded('xhprof')) {
    $xhprof_data = xhprof_disable();
  //
  // Saving the XHProf run
  // using the default implementation of iXHProfRuns.
  //
    include_once APPLICATION_PATH . "/development/xhprof_lib/utils/xhprof_lib.php";
    include_once APPLICATION_PATH . "/development/xhprof_lib/utils/xhprof_runs.php";

    $xhprof_runs = new XHProfRuns_Default();

  // Save the run under a namespace "xhprof_foo".
  //
  // **NOTE**:
  // By default save_run() will automatically generate a unique
  // run id for you. [You can override that behavior by passing
  // a run id (optional arg) to the save_run() method instead.]
  //
    $run_id = $xhprof_runs->save_run($xhprof_data, "engine4");

    $link = "http://" . $_SERVER['HTTP_HOST'] . _ENGINE_R_BASE
      . "/development/xhprof_html/?run=$run_id&source=engine4";
    echo "<hr />\n".
       "<div>".
       "Assuming you have set up the http based UI for <br />\n".
       "XHProf at some address, you can view run at <br />\n".
       "<a target='_blank' href='" . $link . "'>" . $link . "</a><br />\n".
       "</div>";
}
