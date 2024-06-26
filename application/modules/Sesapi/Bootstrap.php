<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesapi
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Bootstrap.php 2018-08-14 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

if (defined('_SESAPI_R_TARG')) {
	class Sesapi_ModulesBootstrap extends Engine_Application_Bootstrap_Abstract
	{

		public function __construct($application)
		{
			parent::__construct($application);
		}
	}
	class Sesapi_Bootstrap extends Engine_Application_Bootstrap_Abstract
	{
		static $_autoloader;
		static $_bootstrap;
		public function __construct($application)
		{
      
			parent::__construct($application);
		
		}

		public function run()
		{
			// Ensure frontcontroller and router are bootstrapped
			$this->bootstrap('frontcontroller');
			$this->bootstrap('router');
			$front = $this->getContainer()->frontcontroller; //print_r($front);die;
			//echo $default = $front->getDefaultModule();die;
			// Start main
			$default = 'sesapi';
			//$front->setDefaultModule($default);
			//$front->setControllerDirectory($default,'sesapi');
			//$front->setControllerDirectory($default);
			//echo "<pre>";var_dump($front);die;
			if (null === $front->getControllerDirectory($default)) {
				throw new Zend_Application_Bootstrap_Exception(
					'No default controller directory registered with front controller'
				);
			}
			// End main


			// Start main 2
			$front->setParam('bootstrap', $this);
			$front->dispatch();
			// End main 2
		}
		public static function getAutoloader()
		{
			if (null === self::$_autoloader) {
				self::$_autoloader = Engine_Loader::getInstance();
			}
			return self::$_autoloader;
		}
		protected function _initDb()
		{
			$file = APPLICATION_PATH . '/application/settings/database.php';
			$options = include $file;

			$db = Zend_Db::factory($options['adapter'], $options['params']);
			Engine_Db_Table::setDefaultAdapter($db);
			Engine_Db_Table::setTablePrefix($options['tablePrefix']);
			// set DB to UTC timezone for this session
			switch ($options['adapter']) {
				case 'mysqli':
				case 'mysql':
				case 'pdo_mysql': {
						$db->query("SET time_zone = '+0:00'");
						break;
					}

				case 'postgresql': {
						$db->query("SET time_zone = '+0:00'");
						break;
					}

				default: {
						// do nothing
					}
			}

			// attempt to disable strict mode
			try {
				$db->query("SET SQL_MODE = ''");
			} catch (Exception $e) { }

			return $db;
		}

		protected function _initFrontController()
		{

			Zend_Controller_Action_HelperBroker::addPath("Engine/Controller/Action/Helper/", 'Engine_Controller_Action_Helper');
			Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'application'
				. DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . "Authorization/Controller/Action/Helper/", 'Authorization_Controller_Action_Helper');
			Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'application'
				. DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . "Core/Controller/Action/Helper/", 'Core_Controller_Action_Helper');
			Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'application'
				. DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . "Sesapi/Controller/Action/Helper/", 'Sesapi_Controller_Action_Helper');
			$enabledModuleNames = Engine_Api::_()->getDbtable('modules', 'core')->getEnabledModuleNames();

			if (engine_in_array("sespage", $enabledModuleNames)) {
				Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'application'
					. DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . "Sespage/Controller/Action/Helper/", 'Sespage_Controller_Action_Helper');
			}
			if (engine_in_array("sesgroup", $enabledModuleNames)) {
				Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'application'
					. DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . "Sesgroup/Controller/Action/Helper/", 'Sesgroup_Controller_Action_Helper');
			}
			if (engine_in_array("sesbusiness", $enabledModuleNames)) {
				Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'application'
					. DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . "Sesbusiness/Controller/Action/Helper/", 'Sesbusiness_Controller_Action_Helper');
			}

			//Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'application'
			//      . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . "Sesapi/Model/Helper/", 'Sesapi_Model_Helper');
			$frontController = Zend_Controller_Front::getInstance();
			$frontController
				//->addModuleDirectory(APPLICATION_PATH . "/application/modules/")
				->setDefaultModule('core')
				->setParam('viewSuffix', 'tpl')
				->setParam('prefixDefaultModule', 'true');

			// Add our special path for action helpers
			$this->initActionHelperPath();

			// Our virtual index hack confuses the request class, this other hack will
			// make it think it's in the root folder
			$request = new Zend_Controller_Request_Http();
			$script = $_SERVER['SCRIPT_NAME'];
			$_SERVER['SCRIPT_NAME'] = str_replace('/application/', '/', $script);
			$frontController->setBaseUrl($request->getBaseUrl());
			$_SERVER['SCRIPT_NAME'] = $script;

			// Save to registy and local container
			Zend_Registry::set('Zend_Controller_Front', $frontController);
			Zend_Controller_Action_HelperBroker::addHelper(new Sesapi_Controller_Action_Helper_Dispatcher());
			if (engine_in_array("sespage", $enabledModuleNames)) {
				Zend_Controller_Action_HelperBroker::addHelper(new Sespage_Controller_Action_Helper_ShowDetails());
			}
			if (engine_in_array("sesgroup", $enabledModuleNames)) {
				Zend_Controller_Action_HelperBroker::addHelper(new Sesgroup_Controller_Action_Helper_ShowDetailsSesgroup());
			}
			if (engine_in_array("sesbusiness", $enabledModuleNames)) {
				Zend_Controller_Action_HelperBroker::addHelper(new Sesbusiness_Controller_Action_Helper_ShowDetailsSesbusiness());
			}
			
			//Emailtemplates Plugin work for emails
			if (engine_in_array("emailtemplates", $enabledModuleNames)) {
				include APPLICATION_PATH . '/application/modules/Emailtemplates/Plugin/Loader.php';
				$frontController->registerPlugin( new Emailtemplates_Plugin_Loader() );
			}
			return $frontController;
		}

		protected function _initCache()
		{
			// Get configurations
			$file = APPLICATION_PATH . '/application/settings/cache.php';

			// @todo cache config in database

			if (file_exists($file)) {
				// Manual config
				$options = include $file;
			} else if (is_writable(APPLICATION_PATH . '/temporary/cache') || (!@is_dir(APPLICATION_PATH . '/temporary/cache') &&
				@mkdir(APPLICATION_PATH . '/temporary/cache', 0777, true))) {
				// Auto default config
				$options = array(
					'default_backend' => 'File',
					'frontend' => array(
						'core' => array(
							'automatic_serialization' => true,
							'cache_id_prefix' => 'Engine4_',
							'lifetime' => '86400',
							'caching' => true,
						),
					),
					'backend' => array(
						'File' => array(
							'cache_dir' => APPLICATION_PATH . '/temporary/cache',
						),
					),
				);
			} else {
				// Failure
				return null;
			}

			// Create cache
			$frontend = key($options['frontend']);
			$backend = key($options['backend']);
			Engine_Cache::setConfig($options);
			$cache = Engine_Cache::factory($frontend, $backend, array(), array(), false, true);

			// Disable caching in development mode
			if (APPLICATION_ENV == 'development') {
				$cache->setOption('caching', false);
			}

			// Save in registry
			Zend_Registry::set('Zend_Cache', $cache);

			// Use cache helper?
			Zend_Controller_Action_HelperBroker::getStack()->offsetSet(-1, new Engine_Controller_Action_Helper_Cache());

			// Add cache to database
			Zend_Db_Table_Abstract::setDefaultMetadataCache($cache);

			// Save in bootstrap
			return $cache;
		}

		protected function _initLog()
		{
			$log = new Zend_Log();
			$log->setEventItem('domain', 'error');

			// Non-production
			if (APPLICATION_ENV !== 'production') {
				$log->addWriter(new Zend_Log_Writer_Firebug());
			}

			// Get log config
			$db = Engine_Db_Table::getDefaultAdapter();
			$logAdapter = $db->select()
				->from('engine4_core_settings', 'value')
				->where('`name` = ?', 'core.log.adapter')
				->query()
				->fetchColumn();

			// Set up log
			switch ($logAdapter) {
				case 'database': {
						try {
							$log->addWriter(new Zend_Log_Writer_Db($db, 'engine4_core_log'));
						} catch (Exception $e) {
							// Make sure logging doesn't cause exceptions
							$log->addWriter(new Zend_Log_Writer_Null());
						}
						break;
					}
				default:
				case 'file': {
						try {
							$log->addWriter(new Zend_Log_Writer_Stream(APPLICATION_PATH . '/temporary/log/main.log'));
						} catch (Exception $e) {
							// Check directory
							if (
								!@is_dir(APPLICATION_PATH . '/temporary/log') &&
								@mkdir(APPLICATION_PATH . '/temporary/log', 0777, true)
							) {
								$log->addWriter(new Zend_Log_Writer_Stream(APPLICATION_PATH . '/temporary/log/main.log'));
							} else {
								// Silence ...
								if (APPLICATION_ENV !== 'production') {
									$log->log($e->__toString(), Zend_Log::CRIT);
								} else {
									// Make sure logging doesn't cause exceptions
									$log->addWriter(new Zend_Log_Writer_Null());
								}
							}
						}
						break;
					}
				case 'none': {
						$log->addWriter(new Zend_Log_Writer_Null());
						break;
					}
			}

			// Save to registry
			Zend_Registry::set('Zend_Log', $log);

			// Register error handlers
			Engine_Api::registerErrorHandlers();

			if ('production' != APPLICATION_ENV) {
				Engine_Exception::setLog($log);
			}

			return $log;
		}

		protected function _initFrontControllerModules()
		{
			$frontController = Zend_Controller_Front::getInstance();
			$path = APPLICATION_PATH . DIRECTORY_SEPARATOR . "application" . DIRECTORY_SEPARATOR . "modules";

			$enabledModuleNames = Engine_Api::_()->getDbtable('modules', 'core')->getEnabledModuleNames();
			$changeModuleNames = array('sesmember' => 'user');
			foreach ($enabledModuleNames as $module) {
				$orgModule = $module;
				if (!empty($changeModuleNames[$module]))
					$module = $changeModuleNames[$module];
				$moduleInflected = Engine_Api::inflect($module);
				$moduleDir = $path . DIRECTORY_SEPARATOR . ucfirst($orgModule);
				if (is_dir($moduleDir)) {
					if (is_dir($path . DIRECTORY_SEPARATOR . 'Sesapi' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . ucfirst($moduleInflected))) {
						$moduleDir = $path . DIRECTORY_SEPARATOR . 'Sesapi' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . ucfirst($moduleInflected);
					} else
						$moduleDir .= DIRECTORY_SEPARATOR . $frontController->getModuleControllerDirectoryName();
					$frontController->addControllerDirectory($moduleDir, $module);
				} else {
					// Maybe we should log modules that fail to load?
					if (APPLICATION_ENV == 'development') {
						//throw new Engine_Exception('failed to load module "' . $module . '"');
					}
				}
			}
			$frontController
				->setDefaultModule('sesapi');
		}

		protected function _initManifest()
		{
			// Load from cache
			$cached = false;

			if (isset($this->getContainer()->manifestcache)) {
				$data = $this->getContainer()->manifestcache->load('Engine_Manifest');
				if (is_array($data)) {
					$manifest = $data;
					//$manifest = new Zend_Config($data);
					$cached = true;
				}
			}

			// Load manually
			if (!$cached) {
				$data = array();
				$path = APPLICATION_PATH . DIRECTORY_SEPARATOR . "application" . DIRECTORY_SEPARATOR . "modules";
				$enabledModuleNames = Engine_Api::_()->getDbtable('modules', 'core')->getEnabledModuleNames();
				foreach ($enabledModuleNames as $name => $module) {
					$file = $path . DIRECTORY_SEPARATOR . Engine_Api::inflect($module) . DIRECTORY_SEPARATOR . 'settings' . DIRECTORY_SEPARATOR . 'manifest.php';
					$moduleName = $module;
					if (file_exists($file)) {
						$data[$moduleName] = include($file);
					} else {
						$data[$moduleName] = array();
					}
				}
				$file = $path . DIRECTORY_SEPARATOR . "Sesapi" . DIRECTORY_SEPARATOR . 'settings' . DIRECTORY_SEPARATOR . 'customroutes.php';
				if (file_exists($file)) {
					$routesData = include($file);
					//override default plugin route with custom routes
					foreach ($routesData as $key => $route) {
						foreach ($route as $routeKey => $routeValue) {
							$data[$key]["routes"][$routeKey] = $routeValue;
						}
					}
				}
				$manifest = $data;
			}
			Zend_Registry::set('Engine_Manifest', $manifest);

			// Save to cache
			if (!$cached && isset($this->getContainer()->manifestcache)) {
				$this->getContainer()->manifestcache->save(serialize($manifest), 'Engine_Manifest');
				//$this->getContainer()->manifestcache->save(serialize($manifest->toArray()), 'Engine_Manifest');
			}
			return $data;
		}

		protected function _initSession()
		{
			// Get session configuration
			$file = APPLICATION_PATH . '/application/settings/session.php';
			$config = array();
			if (file_exists($file)) {
				$config = include $file;
			}

			// Get default session configuration
			if (empty($config)) {
				$config = array(
					'options' => array(
						'save_path' => 'session',
						'use_only_cookies' => true,
						'remember_me_seconds' => 864000,
						'gc_maxlifetime' => 86400,
						'cookie_httponly' => false,
					),
					'saveHandler' => array(
						'class' => 'Core_Model_DbTable_Session',
						'params' => array(
							'lifetime' => 86400,
						),
					),
				);
			}

			// Remove httponly unless forced in config
			if (!isset($config['options']['cookie_httponly'])) {
				$config['options']['cookie_httponly'] = false;
			}

			// Set session options
			Zend_Session::setOptions($config['options']);

			$saveHandler = $config['saveHandler']['class'];
			Zend_Session::setSaveHandler(new $saveHandler($config['saveHandler']['params']));

			// Session hack for fancy upload
			//if( !isset($_COOKIE[session_name()]) )
			//{
			$sessionName = Zend_Session::getOptions('name');
			if (isset($_POST[$sessionName])) {
				Zend_Session::setId($_POST[$sessionName]);
			} else if (isset($_POST['PHPSESSID'])) {
				Zend_Session::setId($_POST['PHPSESSID']);
			}
			//}

			//Zend_Session::start();
		}

		protected function _initRouter()
		{
			$router = $this->getContainer()->frontcontroller->getRouter();
			$defaultAdminRoute = Engine_Controller_Router_Route_ControllerPrefix::getInstance(new Zend_Config(array()));
			$router->addRoute('admin_default', $defaultAdminRoute);

			include APPLICATION_PATH . '/application/modules/Sesapi/controllers/Checklicense.php';
			$sesapi_restapi = Zend_Registry::isRegistered('sesapi_restapi') ? Zend_Registry::get('sesapi_restapi') : null;

			if (empty($sesapi_restapi)) {
				// exit();
			}

			// Add module-configured routes
			$manifest = Zend_Registry::get('Engine_Manifest');
			foreach ($manifest as $module => $config) {
				if (!isset($config['routes'])) {
					continue;
				}
				$router->addConfig(new Zend_Config($config['routes']));
			}

			// Add user-defined routes
			$routesTable = Engine_Api::_()->getDbtable('routes', 'core');
			$userConfig = array();
			$stmt = $routesTable->select()
				->from($routesTable, array('name', 'config'))
				->order('order ASC')
				->query();
			foreach ($stmt->fetchAll() as $row) {
				if (!empty($row['config'])) {
					$data = Zend_Json::decode($row['config']);
					if (is_array($data)) {
						$userConfig[$row['name']] = $data;
					}
				}
			}

			$router->addConfig(new Zend_Config($userConfig));

			// Add default routes
			$router->addDefaultRoutes();

			return $router;
		}

		protected function _initView()
		{
			// Create view
			$view = new Zend_View();

			// Set encoding (@todo maybe use configuration?)
			$view->setEncoding('utf-8');

			$view->addScriptPath(APPLICATION_PATH);

			// Setup and register viewRenderer
			// @todo we may not need to override zend's
			$viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer($view);
			//$viewRenderer = new Engine_Controller_Action_Helper_ViewRenderer($view);
			$viewRenderer->setViewSuffix('tpl');
			Zend_Controller_Action_HelperBroker::getStack()->offsetSet(-80, $viewRenderer);

			// Initialize contextSwitch helper
			Zend_Controller_Action_HelperBroker::addHelper(new Core_Controller_Action_Helper_ContextSwitch());

			// Add default helper paths
			$view->addHelperPath('Engine/View/Helper/', 'Engine_View_Helper_');
			/*Include User View Helpers*/
			$view->addHelperPath(APPLICATION_PATH . "/application/modules/User/View/Helper", 'User_View_Helper');
			$view->addHelperPath(APPLICATION_PATH . "/application/modules/Fields/View/Helper", 'Fields_View_Helper');
			if (file_exists(APPLICATION_PATH . "/application/modules/Sesevent/View/Helper"))
				$view->addHelperPath(APPLICATION_PATH . "/application/modules/Sesevent/View/Helper", 'Sesevent_View_Helper');
			if (file_exists(APPLICATION_PATH . "/application/modules/Sesqa/View/Helper"))
				$view->addHelperPath(APPLICATION_PATH . "/application/modules/Sesqa/View/Helper", 'Sesqa_View_Helper');
			if (file_exists(APPLICATION_PATH . "/application/modules/Sespagepoll/View/Helper"))
				$view->addHelperPath(APPLICATION_PATH . "/application/modules/Sespagepoll/View/Helper", 'Sespagepoll_View_Helper');
			if (file_exists(APPLICATION_PATH . "/application/modules/Sesbusinesspoll/View/Helper"))
				$view->addHelperPath(APPLICATION_PATH . "/application/modules/Sesbusinesspoll/View/Helper", 'Sesbusinesspoll_View_Helper');
			if (file_exists(APPLICATION_PATH . "/application/modules/Sesgrouppoll/View/Helper"))
				$view->addHelperPath(APPLICATION_PATH . "/application/modules/Sesgrouppoll/View/Helper", 'Sesgrouppoll_View_Helper');
			if (file_exists(APPLICATION_PATH . "/application/modules/Sescontest/View/Helper"))
				$view->addHelperPath(APPLICATION_PATH . "/application/modules/Sescontest/View/Helper", 'Sescontest_View_Helper');
			$this->initViewHelperPath();

			// Set doctype
			Engine_Loader::loadClass('Zend_View_Helper_Doctype');
			$doctypeHelper = new Zend_View_Helper_Doctype();
			$doctypeHelper->doctype(Engine_Api::_()->getApi('settings', 'core')->getSetting('core.doctype', 'HTML4_LOOSE'));

			// Add to local container and registry
			Zend_Registry::set('Zend_View', $view);
			return $view;
		}

		protected function _initLayout()
		{
			// Create layout
			$layout = Zend_Layout::startMvc();

			// Set options
			$layout->setViewBasePath(APPLICATION_PATH . "/application/modules/Sesapi/layouts", 'Sesapi_Layout_View')
				->setViewSuffix('tpl')
				->setLayout(null);

			// Add themes
			$theme = null;
			$themes = array();
			$themesInfo = array();

			$themeTable = Engine_Api::_()->getDbtable('themes', 'core');
			if (!empty($_COOKIE['theme']) && is_numeric($_COOKIE['theme'])) {
				$theme = $themeTable->find((int) $_COOKIE['theme'])->current();
			} else if (!empty($_SESSION['theme']) && is_numeric($_SESSION['theme'])) {
				$theme = $themeTable->find((int) $_SESSION['theme'])->current();
			}
			if (!$theme) {
				$themeSelect = $themeTable->select()
					->where('active = ?', 1)
					->limit(1);
				$theme = $themeTable->fetchRow($themeSelect);
			}

			if ($theme) {
				$themes[] = $theme->name;
				$themesInfo[$theme->name] = include APPLICATION_PATH_COR . DS
					. 'themes' . DS . $theme->name . DS . 'manifest.php';
			}

			$layout->themes = $themes;
			$layout->themesInfo = $themesInfo;
			Zend_Registry::set('Themes', $themesInfo);

			// Add global site title etc
			$siteinfo = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.general.site', array());
			$siteinfo = array_filter($siteinfo);
			$siteinfo = array_merge(array(
				'title' => 'Social Network',
				'description' => '',
				'keywords' => '',
			), $siteinfo);
			$layout->siteinfo = $siteinfo;

			// Get global site revision counter
			$settings = Engine_Api::_()->getApi('settings', 'core');
			$counter = $settings->core_site_counter;
			if (!$counter) {
				$settings->core_site_counter = $counter = 1;
			}
			$layout->counter = $counter;

			// Set the value for secret salt
			$salt = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.secret', 'staticSalt');
			Engine_Form::setSecretSalt($salt);

			// Get baseUrl for static content
			$view = Zend_Registry::get('Zend_View');
			$staticBaseUrl = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.static.baseurl');
			if (!$staticBaseUrl) {
				$staticBaseUrl = $view->baseUrl();
			}
			$staticBaseUrl = rtrim($staticBaseUrl, '/') . '/';
			$layout->staticBaseUrl = $staticBaseUrl;
			Zend_Registry::set('StaticBaseUrl', $staticBaseUrl);

			// Get includes
			$front = $this->getContainer()->frontcontroller;
			$request = $front->getRequest();

			if ($request === null) {
				$request = new Zend_Controller_Request_Http();
			}
			if ($request->getParam('format') !== 'smoothbox') {
				$layout->headIncludes = (string) Engine_Api::_()->getApi('settings', 'core')
					->getSetting('core.general.includes', '');
			}

			return $layout;
		}
		/**
		 * Initializes translator
		 *
		 * @return Zend_Translate_Adapter
		 */
		public function _initTranslate()
		{
			// Set cache
			if (isset($this->getContainer()->cache)) {
				Zend_Translate::setCache($this->getContainer()->cache);
			}

			// Get list of supported languages
			/*
			$languages = array();
			$it = new DirectoryIterator(APPLICATION_PATH_COR . DIRECTORY_SEPARATOR . 'languages');
			foreach( $it as $item ) {
				if( $item->isDot() || !$item->isDir() ) {
					continue;
				}
				$name = $item->getBasename();
				if( !Zend_Locale::isLocale($name) ) {
					continue;
				}
				$languages[] = $name;
			}
			*/

			// If in development, log untranslated messages
			$params = array(
				'scan' => Zend_Translate_Adapter::LOCALE_DIRECTORY,
				'logUntranslated' => true
			);

			$log = new Zend_Log();
			if (APPLICATION_ENV == 'development') {
				$log = new Zend_Log();
				$log->addWriter(new Zend_Log_Writer_Firebug());
				$log->addWriter(new Zend_Log_Writer_Stream(APPLICATION_PATH . '/temporary/log/translate.log'));
			} else {
				$log->addWriter(new Zend_Log_Writer_Null());
			}
			$params['log'] = $log;

			// Check Locale
			$locale = Zend_Locale::findLocale('hi');
			// Make Sure Language Folder Exist
			$languageFolder = is_dir(APPLICATION_PATH . '/application/languages/' . $locale);
			if ($languageFolder === false) {
				$locale = substr($locale, 0, 2);
				$languageFolder = is_dir(APPLICATION_PATH . '/application/languages/' . $locale);
				if ($languageFolder == false) {
					$locale = 'en';
				}
			}
			// Check which Translation Adapter has been selected
			$db = Engine_Db_Table::getDefaultAdapter();
			$translationAdapter = $db->select()
				->from('engine4_core_settings', 'value')
				->where('`name` = ?', 'core.translate.adapter')
				->query()
				->fetchColumn();

			// If adapter is 'array', Make sure array files exist
			/*
			if( $translationAdapter == 'array'){
				// Check if Language File Exists
				if( !file_exists(APPLICATION_PATH . '/application/languages/' . $locale . '/' . $locale . '.php')){
					//echo 'Locale does not exist ' . APPLICATION_PATH . '/application/languages/' . $locale . '/' . $locale . '_array.php<br />';
					// Try looking elsewhere
					$newLocale = substr($locale, 0, 2);
					//echo 'Attempting to Look for ' . $newLocale . '<br />';
					if( file_exists(APPLICATION_PATH . '/application/languages/' . $newLocale . '/' . $newLocale . '.php')){
						$locale = $newLocale;
						//echo 'New Locale Found ' . APPLICATION_PATH . '/application/languages/' . $newLocale . '/' . $newLocale . '_array.php<br />';
					} else { $translationAdapter = 'csv'; $locale = 'en'; }
				}
			}
			*/

			// Use Array Translation Adapter, Loop through all Availible Translations
			if ($translationAdapter == 'array') {
				// Find all Valid Language Arrays
				// Check For Array Files
				$languagePath = APPLICATION_PATH . '/application/languages';
				// Get List of Folders
				$languageFolders = array_filter(glob($languagePath . DIRECTORY_SEPARATOR . '*'), 'is_dir');
				// Look inside Folders for PHP array
				$locale_array = array();
				foreach ($languageFolders as $folder) {
					// Get Locale code
					$locale_code = str_replace($languagePath . DIRECTORY_SEPARATOR, "", $folder);
					$locale_array[] = $locale_code;
					if (!file_exists($folder . DIRECTORY_SEPARATOR . $locale_code . '.php')) {
						// If Array files do not exist, switch to CSV
						$translationAdapter = 'csv';
					}
				}

				$language_count = engine_count($locale_array);

				// Add the First One
				$translate = new Zend_Translate(
					array(
						'adapter' => 'array',
						'content' => $languagePath . DIRECTORY_SEPARATOR . $locale_array[0] . DIRECTORY_SEPARATOR . $locale_array[0] . '.php',
						'locale'  => $locale_array[0]
					)
				);
				if ($language_count > 1) {
					for ($i = 1; $i < $language_count; $i++) {
						$translate->addTranslation(
							array(
								'content' => $languagePath . DIRECTORY_SEPARATOR . $locale_array[$i] . DIRECTORY_SEPARATOR . $locale_array[$i] . '.php',
								'locale' => $locale_array[$i]
							)
						);
					}
				}

				/*
				if( $language_count > 1) {
					for( $i = 1; $i < $language_count; $i++ ) {
						$translate->addTranslation(
										array(
												'content' => $languageFolders[$i] . DIRECTORY_SEPARATOR . $locale_array[$i] . '.php',
												'locale' => $locale_array[$i] )
																	);
							echo $locale_array[$i] . ' Translation Added<br />';
						}

					}
				 * */
			}

			// Use CSV Translation Adapter
			else {
				$translate = new Zend_Translate(
					'Engine_Translate_Adapter_Csv',
					APPLICATION_PATH . '/application/languages',
					null,
					$params
				);
			}

			//echo "<pre>";var_dump($translate);die;
			Zend_Registry::set('Zend_Translate', $translate);

			Zend_Validate_Abstract::setDefaultTranslator($translate);
			Zend_Form::setDefaultTranslator($translate);
			Zend_Controller_Router_Route::setDefaultTranslator($translate);

			return $translate;
		}

		protected function _initContent()
		{
			$content = Engine_Content::getInstance();

			// Set storage
			$contentTable = Engine_Api::_()->getDbtable('pages', 'core');
			$content->setStorage($contentTable);

			// Load content helper
			$contentRenderer = new Engine_Content_Controller_Action_Helper_Content();
			$contentRenderer->setContent($content);
			Zend_Controller_Action_HelperBroker::getStack()->offsetSet(-85, $contentRenderer);

			// Set cache object
			if (isset($this->getContainer()->cache)) {
				$content->setCache($this->getContainer()->cache);
			}

			// Set translator
			if (isset($this->getContainer()->translate)) {
				$content->setTranslator($this->getContainer()->translate);
			}

			// Save to registry
			Zend_Registry::set('Engine_Content', $content);

			return $content;
		}

		protected function _initPaginator()
		{
			// Set up default paginator options
			Zend_Paginator::setDefaultScrollingStyle('Sliding');
			Zend_View_Helper_PaginationControl::setDefaultViewPartial(array(
				'pagination/search.tpl',
				'core'
			));
		}

		protected function _initHooks()
		{
			$hooks = Engine_Hooks_Dispatcher::getInstance();

			// Add module-configured routes
			$manifest = Zend_Registry::get('Engine_Manifest');
			foreach ($manifest as $module => $config) {
				if (!isset($config['hooks'])) {
					continue;
				}
				$hooks->addEvents($config['hooks']);
			}

			return $hooks;
		}

		protected function _initApi()
		{
			return Engine_Api::_();
		}

		protected function _initModules()
		{
			//$front = null;
			//$default = 'core';
			//if( isset($this->getContainer()->frontcontroller) ) {
			$front = $this->getContainer()->frontcontroller;
			$default = 'sesapi';
			//}
			$bootstraps = new ArrayObject();

			// Prepare data
			$enabledModuleNames = Engine_Api::_()->getDbtable('modules', 'core')->getEnabledModuleNames();
			$baseDir = APPLICATION_PATH;
			//$baseUrl = preg_replace('/[\/]*index\.php[\/]*/', '/', $front->getBaseUrl());
			$counter = 1;
			foreach ($enabledModuleNames as $module) {
				$moduleInflected = Engine_Api::inflect($module);
				$moduleDir = $baseDir . DIRECTORY_SEPARATOR . 'application'
					. DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $moduleInflected;

				// Default module is already bootstrapped, but bootstrap others
				if (strtolower($module) === strtolower($default)) {
					continue;
				}

				$bootstrapClass =   'Sesapi_Bootstrap';
				Engine_Loader::getInstance()->register(ucfirst($module), $moduleDir);
				if (self::$_bootstrap === null) {
					$moduleBootstrap = new $bootstrapClass($this);
					self::$_bootstrap = $moduleBootstrap;
				} else {
					$moduleBootstrap = self::$_bootstrap;
				}
				$moduleBootstrap->setModuleName($module);
				Engine_Api::getInstance()->setModuleBootstrap($moduleBootstrap);
			}
			//			return $bootstraps;
		}

		protected function _initAdminNeuter()
		{
			if (defined('_ENGINE_ADMIN_NEUTER')) {
				return;
			}

			$adminNeuter = _ENGINE_ADMIN_NEUTER_MODE;
			if ($adminNeuter) {
				$viewer = Engine_Api::_()->user()->getViewer();
				$adminNeuter = !$viewer->getIdentity() || $viewer->level_id != 1;
			}
			define('_ENGINE_ADMIN_NEUTER', $adminNeuter);
		}

		protected function _initLocale()
		{
			// Translate needs to be initialized before Modules, so _initTranslate() could
			// not load the "User" couldn't be initialized then.  Thus, we must assign
			// the language over here if it is a user.

			// Try to pull from various sources
			$viewer   = Engine_Api::_()->user()->getViewer();
			$timezone = Engine_Api::_()->getApi('settings', 'core')->core_locale_timezone;
			if ($viewer->getIdentity()) {
				$locale = $viewer->locale;
				$language = $viewer->language;
				$timezone = $viewer->timezone;
			}else if (!empty($_GET['language'])) {
			

				$locale = null;
				$language = (!empty($_REQUEST['language'])) ? $_REQUEST['language'] : "";
				if($language){
				if( !empty($language) ) {
					try {
					$language = Zend_Locale::findLocale($language);
					} catch( Exception $e ) {
					$language = null;
					}
				}
			
				if(  $language && !$locale ) $locale = $language;
				if( !$language &&  $locale ) $language = $locale;
				

			}
				} 
			if (empty($locale) &&  !empty($_COOKIE['en4_language']) && !empty($_COOKIE['en4_locale'])) {
				$locale = $_COOKIE['en4_locale'];
				$language = $_COOKIE['en4_language'];
			} else if (empty($locale) &&  !empty($_SERVER["HTTP_ACCEPT_LANGUAGE"])) {
				$l = new Zend_Locale(Zend_Locale::BROWSER);
				$locale = $l->toString();
				$language = $l->getLanguage();
			} else if(empty($locale)) {
				$locale = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.locale.locale', 'auto');
				$language = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.locale.locale', 'auto');
			}
			Zend_Registry::set('timezone', $timezone);

			// Make sure it's valid
			try {
				$locale = Zend_Locale::findLocale($locale);
			} catch (Exception $e) {
				$locale = 'en_US';
			}

			$localeObject = new Zend_Locale($locale);
			Zend_Registry::set('Locale', $localeObject);

			// Set in locale and language
			$translate = $this->getContainer()->translate;
			$defaultLanguage  = Engine_Api::_()->getApi('settings', 'core')->core_locale_locale;
			$localeLanguage = $localeObject->getLanguage();

			$ls = array($locale, $language, $localeLanguage, $defaultLanguage, 'en');
			foreach ($ls as $l)
				if ($translate->isAvailable($l)) {
					$translate->setLocale($l);
					break;
				}

			if (!$viewer->getIdentity()) {
				if (empty($_COOKIE['en4_language'])) {
					setcookie('en4_language', $translate->getLocale(), time() + (86400 * 365), '/');
				}

				if (empty($_COOKIE['en4_locale'])) {
					setcookie('en4_locale', $locale, time() + (86400 * 365), '/');
				}
			}

			// Set cache
			Zend_Locale_Data::setCache($this->getContainer()->cache);

			// Get orientation
			$localeData = Zend_Locale_Data::getList($localeObject->__toString(), 'layout');
			$this->getContainer()->layout->orientation = @$localeData['characters'];

			return $localeObject;
		}


		protected function _initCensor()
		{
			$bannedWords = null;

			// caching
			$cache = $this->getContainer()->cache;
			if (
				$cache instanceof Zend_Cache_Core && ($data = $cache->load('bannedwords')) &&
				is_string($data)
			) {
				$bannedWords = $data;
			} else {
				$bannedWords = Engine_Api::_()->getApi('settings', 'core')->core_spam_censor;

				$db = $this->getContainer()->db;
				if ($db instanceof Zend_Db_Adapter_Abstract) {
					$dbBannedWords = $db->select()
						->from('engine4_core_bannedwords', 'word')
						->query()
						->fetchAll(Zend_Db::FETCH_COLUMN);
					$bannedWords .= ',' . join(',', $dbBannedWords);
				}

				$bannedWords = trim($bannedWords, ' ,');

				// save
				$cache->save($bannedWords, 'bannedwords');
			}

			Engine_Filter_Censor::setDefaultForbiddenWords($bannedWords);
		}

		protected function _initBannedIps()
		{
			// No CLI
			if ('cli' === PHP_SAPI) {
				return;
			}

			// Check if visitor is banned by IP
			$addressObject = new Engine_IP();
			$addressBinary = $addressObject->toBinary();

			// Load banned IPs
			$db = $this->getContainer()->db;
			$bannedIps = $db->select()
				->from('engine4_core_bannedips')
				->query()
				->fetchAll();

			$isBanned = false;
			foreach ($bannedIps as $bannedIp) {
				// @todo ipv4->ipv6 transformations
				if (strlen($addressBinary) == strlen($bannedIp['start'])) {
					if (
						strcmp($addressBinary, $bannedIp['start']) >= 0 &&
						strcmp($addressBinary, $bannedIp['stop']) <= 0
					) {
						$isBanned = true;
						break;
					}
				}
			}

			// tell them they're banned
			if ($isBanned) {
				//@todo give appropriate forbidden page
				if (!headers_sent()) {
					header('HTTP/1.0 403 Forbidden');
				}
				die('banned');
			}
		}
	}
} else {
	class Sesapi_Bootstrap extends Engine_Application_Bootstrap_Abstract
	{
		protected function _initLayout()
		{
			if (isset($_GET['sesapiPaymentModel']) || !empty($_SESSION['sesapiPaymentModel'])) {
				$_SESSION['sesapiPaymentModel'] = true;
			}
		}
		protected function _initFrontController()
		{
			//$this->initActionHelperPath();
			Zend_Controller_Action_HelperBroker::addHelper(new Sesapi_Controller_Action_Helper_Dispatcher());
		}
	}
}
