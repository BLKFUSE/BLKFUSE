<?php
/**
 * SocialEngine
 *
 * @category   Engine
 * @package    Engine_Api
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Api.php 9747 2012-07-26 02:08:08Z john $
 * @author     John Boehr <j@webligo.com>
 */

/**
 * @category   Engine
 * @package    Engine_Api
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 *
 * @method User_Api_Core user()
 */
class Engine_Api
{
    /**
     * The singleton Api object
     *
     * @var Engine_Api
     */
    protected static $_instance;

    /**
     * The internal error reporting level mask
     *
     * @var integer
     */
    protected static $_errorReporting = E_ALL;

    /**
     * The current application instance
     *
     * @var Engine_Application
     */
    protected $_application;

    /**
     * An array of module api objects
     *
     * @var array
     */
    protected $_modules = [];

    /**
     * Contains the current set module name
     * @var string
     */
    protected $_currentModuleName;

    /**
     * @var array assoc map of item type => module
     */
    protected $_itemTypes;

    /**
     * @var error code to be included with an error or exception
     */
    protected static $_errorCode = null;

    /**
     * Get or create the current api instance
     *
     * @return Engine_Api
     */
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Shorthand for getInstance
     *
     * @return Engine_Api
     */
    public static function _()
    {
        return self::getInstance();
    }

    /**
     * Set or unset the current api instance
     *
     * @param Engine_Api $api
     * @return Engine_Api
     */
    public static function setInstance(Engine_Api $api = null)
    {
        return self::$_instance = $api;
    }

    public function getAutoloader()
    {
        if (null === $this->_autoloader) {
            throw new Exception('Autoloader not set');
        }

        return $this->_autoloader;
    }

    public function setAutoloader(Engine_Application_Autoloader $autoloader)
    {
        $this->_autoloader = $autoloader;
        return $this;
    }



    // Application

    /**
     * Sets the current application instance
     *
     * @param Engine_Application $application
     * @return Engine_Api
     */
    public function setApplication(Engine_Application $application)
    {
        $this->_application = $application;
        return $this;
    }

    /**
     * Gets the current application object
     *
     * @return Engine_Application
     * @throws Engine_Api_Exception If application is not set
     */
    public function getApplication()
    {
        if (null === $this->_application) {
            throw new Engine_Api_Exception('Application instance not set');
        }

        return $this->_application;
    }



    // Bootstraps

    /**
     * Checks if the specfied module has been bootstrapped
     *
     * @param string $name The module name
     * @return bool
     */
    public function hasModuleBootstrap($name)
    {
        return isset($this->_modules[$name]);
    }

    /**
     * Sets the local copy of a module bootstrap
     *
     * @param Zend_Application_Module_Bootstrap $bootstrap
     * @return Engine_Api
     */
    public function setModuleBootstrap(Engine_Application_Bootstrap_Abstract $bootstrap)
    {
        $name = strtolower($bootstrap->getModuleName());
        $this->_modules[$name] = $bootstrap;
        return $this;
    }

    /**
     * Gets a module bootstrap
     *
     * @param string $name The module name
     * @return Zend_Application_Module_Bootstrap|Zend_Application_Bootstrap_Bootstrap
     * @throws Engine_Api_Exception If module not found
     */
    public function getModuleBootstrap($name = null)
    {
        if (!$name) {
            $name = Zend_Controller_Front::getInstance()->getDefaultModule();
        }

        if (!isset($this->_modules[$name])) {
            // Special case, default module can be detected and set
            if ($name == Zend_Controller_Front::getInstance()->getDefaultModule()) {
                $this->_modules[$name] = $this->getApplication()->getBootstrap();
            }

            // Normal modules must be registered manually
            else {
                throw new Engine_Api_Exception(sprintf('Module "%s" not found', $name));
            }
        }

        return $this->_modules[$name];
    }



    // Loading

    /**
     * Shorthand for loadModuleApi
     *
     * @return Engine_Application_Module_Api
     * @throws Engine_Api_Exception If given improper arguments or module is missing
     */
    public function __call($method, $args)
    {
        if ('get' == substr($method, 0, 3)) {
            $type = strtolower(substr($method, 3));
            if (empty($args)) {
                throw new Engine_Api_Exception("Cannot load resources; no resource specified");
            }
            $resource = array_shift($args);
            $module = array_shift($args);
            if ($module === null) {
                if ($this->_currentModuleName === null) {
                    throw new Engine_Api_Exception("Cannot load resources; no module specified");
                } else {
                    $module = $this->_currentModuleName;
                    $this->_currentModuleName = null;
                }
            }

            return $this->load($module, $type, $resource);
        }

        // Backwards
        if (isset($this->_modules[$method])) {
            return $this->load($method, 'api', 'core');
            //return $this->load($method, 'model', 'api');
        }

        // Boo
        throw new Engine_Exception("Method '$method' is not supported");
    }

    /**
     * Loads a singleton instance of a module resource
     *
     * @param string $module The module name
     * @param string $type The resource type
     * @param string $resource The resource name
     * @return mixed The requested singleton object
     */
    public function load($module, $type, $resource)
    {
        if (strtolower($type) == 'dbtable') {
            $type = 'Model_DbTable';
        }
        return Engine_Loader::getInstance()->load(ucfirst($module) . '_' . ucfirst($type) . '_' . ucfirst($resource));
    }

    /**
     * Loads a singleton instance of a module resource using a full class name
     *
     * @param string $class The class name
     * @return mixed The requested singleton object
     */
    public function loadClass($class)
    {
        return Engine_Loader::getInstance()->load($class);
    }



    // Item handling stuff

    /**
     * Checks if the item of $type has been registered
     *
     * @param string $type
     * @return bool
     */
    public function hasItemType($type)
    {
        $this->_loadItemInfo();
        return isset($this->_itemTypes[$type]);
    }

    /**
     * Gets an item given a type and identity
     *
     * @param string $type
     * @param int $identity
     * @return Core_Model_Item_Abstract
     */
    public function getItem($type, $identity)
    {
        $this->_loadItemInfo();

        $api = $this->getItemApi($type);

        $method = 'get'.ucfirst($type);
        if (method_exists($api, $method)) {
            return $api->$method($identity);
        } elseif (method_exists($api, 'getItem')) {
            return $api->getItem($type, $identity);
        }

        return $this->getItemTable($type)->find($identity)->current();
    }

    /**
     * Gets multiple items of a type from an array of ids
     *
     * @param string $type
     * @param array $identities
     * @return Engine_Db_Table_Rowset
     */
    public function getItemMulti($type, array $identities)
    {
        $this->_loadItemInfo();

        $api = $this->getItemApi($type);

        $method = 'get'.ucfirst($type).'Multi';
        if (method_exists($api, $method)) {
            return $api->$method($identities);
        } elseif (method_exists($api, 'getItemMulti')) {
            return $api->getItemMulti($type, $identities);
        }

        return $this->getItemTable($type)->find($identities);
    }

    /**
     * Gets an item using a guid array or string
     *
     * @param array|string $guid
     * @return Core_Model_Item_Abstract
     * @throws Engine_Api_Exception If given improper arguments
     */
    public function getItemByGuid($guid)
    {
        $this->_loadItemInfo();

        if (is_string($guid)) {
            $guid = explode('_', $guid);
            if (count($guid) > 2) {
                $id = array_pop($guid);
                $guid = [join('_', $guid), $id];
            }
        }
        if (!is_array($guid) || count($guid) !== 2 || !is_string($guid[0]) || !is_numeric($guid[1])) {
            throw new Engine_Api_Exception(sprintf('Malformed guid passed to getItemByGuid(): %s', join('_', $guid)));
        }
        return $this->getItem($guid[0], $guid[1]);
    }

    /**
     * Gets the name of the module that an item type belongs to
     *
     * @param string $type The item type
     * @return string The module name
     * @throws Engine_Api_Exception If item type isn't registered
     */
    public function getItemModule($type)
    {
        $this->_loadItemInfo();

        return $this->getItemInfo($type, 'module');
    }

    /**
     * Gets info about an item
     *
     * @param string $type The item type
     * @param string (OPTIONAL) $key The info key
     * @return mixed
     */
    public function getItemInfo($type, $key = null)
    {
        $this->_loadItemInfo();

        if (empty($this->_itemTypes[$type])) {
            throw new Engine_Api_Exception(sprintf("Unknown item type: %s", $type));
        }

        if (null === $key) {
            return $this->_itemTypes[$type];
        } elseif (array_key_exists($key, $this->_itemTypes[$type])) {
            return $this->_itemTypes[$type][$key];
        }

        return null;
    }

    /**
     * Get all item types
     *
     * @return array
     */
    public function getItemTypes()
    {
        return array_keys($this->_itemTypes);
    }

    /**
     * Gets the class of an item
     *
     * @param string $type The item type
     * @return string The class name
     */
    public function getItemClass($type)
    {
        $this->_loadItemInfo();

        // Check api for overriding method
        $api = $this->getItemApi($type);
        if (method_exists($api, 'getItemClass')) {
            return $api->getItemClass($type);
        }

        // Generate item class manually
        $module = $this->getItemModule($type);
        return ucfirst($module) . '_Model_' . self::typeToClassSuffix($type, $module);
    }

    /**
     * Gets the class of the dbtable that an item type belongs to
     *
     * @param string $type The item type
     * @return string The table class name
     */
    public function getItemTableClass($type)
    {
        $this->_loadItemInfo();

        // Check api for overriding method
        $api = $this->getItemApi($type);
        if (method_exists($api, 'getItemTableClass')) {
            return $api->getItemTableClass($type);
        }

        // Generate item table class manually
        $module = $this->getItemInfo($type, 'moduleInflected');
        $class = $module . '_Model_DbTable_' . self::typeToClassSuffix($type, $module);
        if (substr($class, -1, 1) === 'y' && substr($class, -3) !== 'way') {
            $class = substr($class, 0, -1) . 'ies';
        } elseif (substr($class, -1, 1) !== 's') {
            $class .= 's';
        }
        return $class;
    }

    /**
     * Gets a singleton instance of the dbtable an item type belongs to
     *
     * @param string $type The item type
     * @return Engine_Db_Table The table object
     */
    public function getItemTable($type)
    {
        $this->_loadItemInfo();

        // Check api for overriding method
        $api = $this->getItemApi($type);
        if (method_exists($api, 'getItemTable')) {
            return $api->getItemTable($type);
        }

        $class = $this->getItemTableClass($type);
        return $this->loadClass($class);
    }

    /**
     * Gets the item api object that an item type belongs to
     *
     * @param string $type The item type
     * @return Core_Api_Abstract
     */
    public function getItemApi($type)
    {
        $this->_loadItemInfo();

        $module = $this->getItemInfo($type, 'moduleInflected');
        return $this->load($module, 'api', 'core');
    }

    /**
     * Load item info from manifest
     */
    protected function _loadItemInfo()
    {
        if (null === $this->_itemTypes) {
            $manifest = Zend_Registry::get('Engine_Manifest');
            if (null === $manifest) {
                throw new Engine_Api_Exception('Manifest data not loaded!');
            }
            $this->_itemTypes = [];
            foreach ($manifest as $module => $config) {
                if (!isset($config['items'])) {
                    continue;
                }
                foreach ($config['items'] as $key => $value) {
                    if (is_numeric($key)) {
                        $this->_itemTypes[$value] = [
                            'module' => $module,
                            'moduleInflected' => self::inflect($module),
                        ];
                    } else {
                        $this->_itemTypes[$key] = $value;
                        $this->_itemTypes[$key]['module'] = $module;
                        $this->_itemTypes[$key]['moduleInflected'] = self::inflect($module);
                    }
                }
            }
        }
    }



    // Utility

    public static function inflect($string)
    {
        return str_replace(' ', '', ucwords(str_replace(['.', '-'], ' ', $string)));
    }

    public static function deflect($string)
    {
        return strtolower(trim(preg_replace('/([a-z0-9])([A-Z])/', '\1-\2', $string), '-. '));
        //return strtolower(trim(preg_replace('/([a-z0-9])([A-Z])/', '\1-\2', preg_replace('/[^A-Za-z0-9-]/', '', $string)), '-. '));
    }

    /**
     * Used to inflect item types to class suffix.
     *
     * @param string $type
     * @param string $module
     * @return string
     */
    public static function typeToClassSuffix($type, $module)
    {
        $parts = explode('_', $type);
        if (count($parts) > 1 && $parts[0] === strtolower($module)) {
            array_shift($parts);
        }
        $partial = str_replace(' ', '', ucwords(join(' ', $parts)));
        return $partial;
    }

    /**
     * Used to inflect item class to type.
     *
     * @param string $class
     * @param string $module
     * @return string
     * @throws Engine_Api_Exception If given improper arguments
     */
    public static function classToType($class, $module)
    {
        list($classModule, $resourceType, $resourceName)
            = explode('_', $class, 3);

        // Throw stuff
        if (strtolower($classModule) != strtolower($module)) {
            throw new Engine_Api_Exception('class and module do not match');
        } elseif ($resourceType != 'Model') {
            throw new Engine_Api_Exception('resource type must be a model');
        }

        // Parse camel case
        preg_match_all('/([A-Z][a-z]+)/', $resourceName, $matches);
        if (empty($matches[0])) {
            throw new Engine_Exception('resource name not useable');
        }
        $matches = $matches[0];

        // Append module name if first not equal
        if (strtolower($matches[0]) != strtolower($module)) {
            array_unshift($matches, $module);
        }
        $type = strtolower(join('_', $matches));
        return $type;
    }

    /**
     * Inflects a type to the class name suffix
     * @todo Not used?
     *
     * @param string $type
     * @param string $module
     * @return string
     */
    public static function typeToShort($type, $module)
    {
        $parts = explode('_', $type);
        if (count($parts) > 1 && strtolower($parts[0]) == strtolower($module)) {
            array_shift($parts);
        }
        return strtolower(join('_', $parts));
    }

    /**
     * Register error handlers
     */
    public static function registerErrorHandlers()
    {
        // Compat
        defined('E_RECOVERABLE_ERROR') || define('E_RECOVERABLE_ERROR', 4096);
        defined('E_DEPRECATED') || define('E_DEPRECATED', 8192);
        defined('E_USER_DEPRECATED') || define('E_USER_DEPRECATED', 16384);

        // Set mask
        self::$_errorReporting = E_ERROR | E_COMPILE_ERROR |
            E_COMPILE_WARNING | E_CORE_ERROR | E_CORE_WARNING | E_ERROR |
            E_PARSE | E_RECOVERABLE_ERROR | E_USER_ERROR;

        // Register
        set_error_handler(['Engine_Api', 'handleError']);
        set_exception_handler(['Engine_Api', 'handleException']);

        if (defined('APPLICATION_ENV')
            && APPLICATION_ENV == 'development'
            && version_compare(PHP_VERSION, '5.5.9', '>=')
            && class_exists('Whoops\Handler\PrettyPageHandler')) {
            self::$_errorReporting = E_ALL;
            $classNamePrettyPageHandler = '\Whoops\Handler\PrettyPageHandler';
            $handler = new $classNamePrettyPageHandler;
            $handler->setEditor('phpstorm');
            $classNameWhoopsRun = '\Whoops\Run';
            $whoops = new $classNameWhoopsRun;
            $whoops->pushHandler($handler);
            $whoops->handleShutdown();
            $whoops->register();
        }
    }

    /**
     * Error handler
     *
     * @param integer $errno
     * @param string $errstr
     * @param string $errfile
     * @param integer $errline
     * @param array $errcontext
     */
    public static function handleError($errno, $errstr, $errfile = null, $errline = null, array $errcontext = null)
    {
        // Check log
        if (Zend_Registry::isRegistered('Zend_Log')) {

            // Force fatal errors to get reported
            $reporting = error_reporting() | self::$_errorReporting;
            $fatal = false;

            // Log
            if ($reporting & $errno) {
                $log = Zend_Registry::get('Zend_Log');
                switch ($errno) {
                    case E_USER_NOTICE: case E_NOTICE:
                    $level = Zend_Log::NOTICE;
                    break;
                    default:
                    case E_USER_WARNING: case E_WARNING: case E_COMPILE_WARNING: case E_CORE_WARNING:
                    case E_DEPRECATED: case E_STRICT: case E_USER_DEPRECATED:
                    $level = Zend_Log::WARN;
                    break;
                    case E_COMPILE_ERROR: case E_CORE_ERROR: case E_ERROR: case E_PARSE:
                    case E_RECOVERABLE_ERROR: case E_USER_ERROR:
                    $level = Zend_Log::ERR;
                    $fatal = true;
                    break;
                }
                //$backtrace = debug_backtrace();
                //ob_start();
                //debug_print_backtrace();
                //$backtrace = ob_get_clean();
                self::getErrorCode(true);
                $backtrace = self::formatBacktrace(array_slice(debug_backtrace(), 1));
                $message = sprintf(
                    '[%1$d] %2$s (%3$s) [%4$d]' . PHP_EOL . '%5$s',
                    $errno,
                    $errstr,
                    $errfile,
                    $errline,
                    $backtrace
                );
                try {
                    $log->log($message, $level);
                } catch (Exception $e) {
                    $logFile = APPLICATION_PATH . '/temporary/log/exception.log';
                    if (is_writable(dirname($logFile))) {
                        if (strpos($message, 'session')) {
                            return null;
                        }
                        file_put_contents($logFile, $message . "\n" . $e->getTraceAsString() . "\n\n", FILE_APPEND);
                    }
                }
            }

            // Handle fatal with nice response for user
            if ($fatal) {
                self::_sendFatalResponse();
            }
        }

        return !defined('_ENGINE_ERROR_SILENCE') || _ENGINE_ERROR_SILENCE;
    }

    /**
     * Exception handler
     *
     * @param Exception $exception
     */
    public static function handleException($exception)
    {
        // If no log, just do normally?
        if (Zend_Registry::isRegistered('Zend_Log')) {

            // Ignore Engine_Exception since it has built-in logging
            if (!($exception instanceof Engine_Exception)) {
                $log = Zend_Registry::get('Zend_Log');
                $output = 'Error Code: ' . self::getErrorCode(true) . PHP_EOL .
                    $exception->__toString();

                $log->log($output, Zend_Log::ERR);
            }
        }

        // Uncaught exceptions are always fatal
        // Send nice response for user
        self::_sendFatalResponse();

        return !defined('_ENGINE_ERROR_SILENCE') || _ENGINE_ERROR_SILENCE;
    }

    public static function formatBacktrace($backtrace)
    {
        $output = '';
        $output .= 'Error Code: ' . self::getErrorCode() . PHP_EOL;
        $output .= 'Stack trace:' . PHP_EOL;
        $index = 0;
        $index = 0;
        foreach ($backtrace as $index => $stack) {
            // Process args
            $args = [];
            if (!empty($stack['args'])) {
                foreach ($stack['args'] as $argIndex => $argValue) {
                    if (is_object($argValue)) {
                        $args[$argIndex] = get_class($argValue);
                    } elseif (is_array($argValue)) {
                        $args[$argIndex] = 'Array'; //substr(print_r($argValue, true), 0, 32);
                    } elseif (is_string($argValue)) {
                        $args[$argIndex] = "'" . substr($argValue, 0, 32) . (strlen($argValue) > 32 ? '...' : '') . "'";
                    } else {
                        $args[$argIndex] = print_r($argValue, true);
                    }
                }
            }
            // Process message
            $output .= sprintf(
                '#%1$d %2$s(%3$d): %4$s%5$s%6$s(%7$s)',
                $index,
                (!empty($stack['file']) ? $stack['file'] : '(unknown file)'),
                (!empty($stack['line']) ? $stack['line'] : '(unknown line)'),
                (!empty($stack['class']) ? $stack['class'] : ''),
                (!empty($stack['type']) ? $stack['type'] : ''),
                $stack['function'],
                join(', ', $args)
            );
            $output .= PHP_EOL;
        }

        // Throw main in there for the hell of it
        $output .= sprintf('#%1$d {main}', $index + 1);

        return $output . PHP_EOL;
    }

    protected static function _sendFatalResponse()
    {
        // Clean any previous output from buffer
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        // Make body
        $body = file_get_contents(APPLICATION_PATH . '/application/offline.html');
        $body = str_replace('%__ERROR_CODE__%', 'Error code: ' .  self::getErrorCode(), $body);
        if(defined(_ENGINE_SITE_URL))
          $body = str_replace('%_BASE_URL_%', _ENGINE_SITE_URL, $body);

        // Send firephp headers
        if (APPLICATION_ENV == 'development') {
            try {
                $request = new Zend_Controller_Request_Http();
                $response = new Zend_Controller_Response_Http();

                $headers = Zend_Wildfire_Channel_HttpHeaders::getInstance();
                $headers->setResponse($response);
                $headers->setRequest($request);
                $headers->flush();

                $response->setBody($body);
                $response->sendHeaders();
                $response->sendResponse();

                die(1);
            } catch (Exception $e) {
                // Will fall through to stuff below
            }
        }

        // Send normal error message
        echo $body;
        die(1);
    }

    /**
     * Creates a random error code
     *
     * @param bool $reset
     */
    public static function getErrorCode($reset = false)
    {
        if ($reset === true || self::$_errorCode == null) {
            $code = md5(uniqid('', true));
            self::$_errorCode = substr($code, 0, 2)
                . substr($code, 15, 2)
                . substr($code, 30, 2);
        }
        return self::$_errorCode;
    }
    
		/**
     * Check mobile view
     *
		*/
		public function isMobile()
		{
			// No UA defined?
			if( !isset($_SERVER['HTTP_USER_AGENT']) ) {
				return false;
			}

			// Windows is (generally) not a mobile OS
			if( false !== stripos($_SERVER['HTTP_USER_AGENT'], 'windows') &&
					false === stripos($_SERVER['HTTP_USER_AGENT'], 'windows phone os')) {
				return false;
			}

			// Sends a WAP profile header
			if( isset($_SERVER['HTTP_PROFILE']) ||
					isset($_SERVER['HTTP_X_WAP_PROFILE']) ) {
				return true;
			}

			// Accepts WAP as a valid type
			if( isset($_SERVER['HTTP_ACCEPT']) &&
					false !== stripos($_SERVER['HTTP_ACCEPT'], 'application/vnd.wap.xhtml+xml') ) {
				return true;
			}

			// Is Opera Mini
			if( isset($_SERVER['ALL_HTTP']) &&
					false !== stripos($_SERVER['ALL_HTTP'], 'OperaMini') ) {
				return true;
			}
			
			if (strstr($_SERVER['HTTP_USER_AGENT'], 'iPad')) {
				return true;
			}

			if( preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android)/i', $_SERVER['HTTP_USER_AGENT']) ) {
				return true;
			}

			$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
			$mobile_agents = array(
				'w3c ', 'acs-', 'alav', 'alca', 'amoi', 'audi', 'avan', 'benq', 'bird',
				'blac', 'blaz', 'brew', 'cell', 'cldc', 'cmd-', 'dang', 'doco', 'eric',
				'hipt', 'inno', 'ipaq', 'java', 'jigs', 'kddi', 'keji', 'leno', 'lg-c',
				'lg-d', 'lg-g', 'lge-', 'maui', 'maxo', 'midp', 'mits', 'mmef', 'mobi',
				'mot-', 'moto', 'mwbp', 'nec-', 'newt', 'noki', 'oper', 'palm', 'pana',
				'pant', 'phil', 'play', 'port', 'prox', 'qwap', 'sage', 'sams', 'sany',
				'sch-', 'sec-', 'send', 'seri', 'sgh-', 'shar', 'sie-', 'siem', 'smal',
				'smar', 'sony', 'sph-', 'symb', 't-mo', 'teli', 'tim-', 'tosh', 'tsm-',
				'upg1', 'upsi', 'vk-v', 'voda', 'wap-', 'wapa', 'wapi', 'wapp', 'wapr',
				'webc', 'winw', 'winw', 'xda ', 'xda-'
			);

			if( engine_in_array($mobile_ua, $mobile_agents) ) {
				return true;
			}
			
			return false;
		}
}
