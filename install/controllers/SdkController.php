<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Install
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: SdkController.php 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */

/**
 * @category   Application_Core
 * @package    Install
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class SdkController extends Zend_Controller_Action
{
    protected $_outputPath;

    protected $_skeletonPath;

    protected $_includeNative = false;

    public function init()
    {
        // Check if already logged in
        if (!Zend_Registry::get('Zend_Auth')->getIdentity()) {
            return $this->_helper->redirector->gotoRoute(array(), 'default', true);
        }

        // Set up output path
        $this->_outputPath = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary'
            . DIRECTORY_SEPARATOR . 'package' . DIRECTORY_SEPARATOR . 'sdk';

        if (!is_writable($this->_outputPath)) {
            throw new Engine_Exception(sprintf('Package output path "%s" is not writable. Please create and chmod 777.', $this->_outputPath));
        }

        // Set up skeleton path
        $this->_skeletonPath = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'install'
            . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'skeletons';
    }

    public function indexAction()
    {
    }

    public function createAction()
    {
        // Require in advance
        require_once 'PEAR.php';
        require_once 'Archive/Tar.php';

        // Form
        $this->view->form = $form = new Install_Form_Sdk_Create();

        if (!$this->getRequest()->isPost()) {
            return;
        }

        if (!$form->isValid($this->getRequest()->getPost())) {
            return;
        }

        // Process
        $values = $form->getValues();

        if (false == ($structureData = $this->_getStructure($values['type']))) {
            return $form->addError('Invalid type');
        }

        // Build manifest
        $manifestData = array();

        // General
        $manifestData['type'] = $structureData['singular'];
        $manifestData['name'] = $values['name'];
        $manifestData['version'] = $values['version'];
//         $manifestData['sku'] = $values['sku'];
//         if (empty($manifestData['sku'])) {
//           unset($manifestData['sku']);
//         }

        // Path
        if ($structureData['inflect']) {
            $manifestData['path'] = $structureData['path'] . '/' . $this->_inflect($values['name']);
        } else {
            $manifestData['path'] = $structureData['path'] . '/' . $values['name'];
        }

        // Meta
        $manifestData['title'] = $values['title'];
        $manifestData['description'] = $values['description'];
        $manifestData['author'] = $values['author'];

        // Callback
        if (@$structureData['defaultCallback']) {
            $manifestData['callback'] = $structureData['defaultCallback'];
        }

        // Actions
        $manifestData['actions'] = $structureData['defaultActions'];

        // Directories
        if ($structureData['inflect']) {
            $manifestData['directories'] = array(
                $structureData['path'] . '/' . $this->_inflect($values['name'])
            );
        } else {
            $manifestData['directories'] = array(
                $structureData['path'] . '/' . $values['name']
            );
        }


        // Language
        if ($manifestData['type'] == 'module') {
            $manifestData['files'] = array(
                'application/languages/en/' . $values['name'] . '.csv',
            );
        } elseif ($manifestData['type'] == 'language') {
            $manifestData['files'] = array(
                // 'application/languages/' . $values['name'] . '/' . $values['name'] . '.csv',
            );
        } elseif ($manifestData['type'] == 'theme') {
            $manifestData['files'] = array(
              'application/themes/' . $values['name'] . '/theme.css',
              'application/themes/' . $values['name'] . '/constants.css',
            );
        }

        // Now let's build it
        $archiveDirectory = $this->_outputPath . DIRECTORY_SEPARATOR .
            $manifestData['type'] . '-' . $manifestData['name'] . '-' . $manifestData['version'];

        if (file_exists($archiveDirectory)) {
            Engine_Package_Utilities::fsRmdirRecursive($archiveDirectory, true);
        }

        $archiveFilename = $this->_outputPath . DIRECTORY_SEPARATOR .
            $manifestData['type'] . '-' . $manifestData['name'] . '-' . $manifestData['version'] . '.tar';

        if (file_exists($archiveFilename)) {
            if (!@unlink($archiveFilename)) {
                throw new Engine_Exception(sprintf('Unable to remove file: %s', $archiveFilename));
            }
        }

        // Construct archive
        $archive = new Archive_Tar($archiveFilename);

        // Prepare search and replace
        $searchAndReplace = array(
            '%%type%%' => $structureData['singular'],
            '%%typePlural%%' => $values['type'],
            '%%name%%' => $values['name'],
            '%%nameInflected%%' => $this->_inflect($values['name']),
            '%%version%%' => $manifestData['version'],
        );
        $search = array_keys($searchAndReplace);
        $replace = array_values($searchAndReplace);

        // Build skeleton directory
        $path = $manifestData['path'];
        $skeletonPath = $this->_skeletonPath . '/' . $manifestData['type'];
        if (file_exists($skeletonPath)) {
            $it = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($skeletonPath),
                RecursiveIteratorIterator::CHILD_FIRST
            );
            foreach ($it as $file) {
                if (!$file->isFile()) {
                    continue;
                }

                $filename = $file->getPathname();

                $targetFilename = ltrim(str_replace($skeletonPath, '', $filename), '/\\');
                $targetFilename = str_replace('.template', '', $targetFilename);
                $targetFilename = str_replace($search, $replace, $targetFilename);

                $targetData = file_get_contents($filename);
                $targetData = str_replace($search, $replace, $targetData);

                $targetPath = $archiveDirectory . '/' . $path . '/' . $targetFilename;

                if (!is_dir(dirname($targetPath))) {
                    if (!mkdir(dirname($targetPath), 0777, true)) {
                        throw new Engine_Exception(sprintf('Unable to create folder: %s', dirname($targetPath)));
                    }
                }

                if (false === file_put_contents($targetPath, $targetData)) {
                    throw new Engine_Exception(sprintf('Unable to put data to file: %s', $targetPath));
                }
            }
        }
        
        $fullManifestData = array(
            'package' => $manifestData,
        );
        if ($manifestData['type'] == 'module') { // add SQL to insert into modules table

            $sql  = 'INSERT IGNORE INTO `engine4_core_modules` (`name`, `title`, `description`, `version`, `enabled`, `type`) VALUES ';
            $sql .= sprintf(" ('%s', '%s', '%s', '%s', 1, 'extra') ",
                $manifestData['name'],
                $manifestData['title'],
                $manifestData['description'],
                $manifestData['version']);
            $targetPath = $archiveDirectory . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . 'settings' . DIRECTORY_SEPARATOR . 'my-install.sql';
            if (false === file_put_contents($targetPath, "$sql;")) {
                throw new Engine_Exception(sprintf('Unable to put SQL data to file: %s', $targetPath));
            }
        }
        if ($manifestData['type'] == 'widget') { //b/c
            $fullManifestData['type'] = @$manifestData['type'];
            $fullManifestData['name'] = @$manifestData['name'];
            $fullManifestData['version'] = @$manifestData['version'];
            $fullManifestData['title'] = @$manifestData['title'];
            $fullManifestData['description'] = @$manifestData['description'];
            $fullManifestData['category'] = 'Widgets';
        }
        $targetPath = $archiveDirectory . '/' . $path . '/' . $structureData['manifest'];
        
        $targetData = '<?php return ' . var_export($fullManifestData, true) . '; ?>';
        if (!is_dir(dirname($targetPath))) {
            if (!mkdir(dirname($targetPath), 0777, true)) {
                throw new Engine_Exception(sprintf('Unable to create folder: %s', dirname($targetPath)));
            }
        }
        if (false === file_put_contents($targetPath, $targetData)) {
            throw new Engine_Exception(sprintf('Unable to put data to file: %s', $targetPath));
        }
        $manifestPath = $targetPath;

        if ($manifestData['type'] == 'language'  || $manifestData['type'] == 'module') {
            $name = $manifestData['type'] == 'language' ? $values['name'] : 'en';
            $targetPath = $archiveDirectory . '/' . 'application/languages/' . $name . '/' . $values['name'] . '.csv';
            $targetData = '';
            if (!is_dir(dirname($targetPath))) {
                if (!mkdir(dirname($targetPath), 0777, true)) {
                    throw new Engine_Exception(sprintf('Unable to create folder: %s', dirname($targetPath)));
                }
            }
            if (false === file_put_contents($targetPath, $targetData)) {
                throw new Engine_Exception(sprintf('Unable to put data to file: %s', $targetPath));
            }
        }

        $manifestDataCopy = array();
        $manifestDataCopy['basePath'] = $archiveDirectory;
        $manifestDataCopy = array_merge($manifestDataCopy, $manifestData);
        $archiveFilename = $this->_buildPackage($manifestDataCopy);

        // Output the archive
        header('content-type: application/x-tar');
        header('content-disposition: attachment; filename=' . urlencode(basename($archiveFilename)));
        echo file_get_contents($archiveFilename);
        @unlink($archiveFilename);
        try {
            Engine_Package_Utilities::fsRmdirRecursive($archiveDirectory, true);
        } catch (Exception $e) {
        }
        exit();
    }

    public function buildAction()
    {
        $availablePackages = array();

        // Iterate over types
        foreach ($this->_getStructure() as $type => $info) {
            // Check if action set to be run
            //if( in_array($type, $actions) )
            //{
            // Get current path and list of dirs
            $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, trim($info['path'], '/\\'));

            $dirs = scandir($path);
            foreach ($dirs as $dir) {
                $dirPath = $path . DIRECTORY_SEPARATOR . $dir;
                if ($dir[0] == '.' || !is_dir($dirPath)) {
                    continue;
                }

                // Get manifest data
                $manifestPath = $dirPath . DIRECTORY_SEPARATOR . str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, trim($info['manifest']));
                $manifestData = include $manifestPath;
                $availablePackages[] = array(
                    'type' => $manifestData['package']['type'],
                    'name' => $manifestData['package']['name'],
                    'guid' => $manifestData['package']['type'] . '-' . $manifestData['package']['name'],
                    'key'  => $manifestData['package']['type'] . '-' . $manifestData['package']['name'] . '-' . $manifestData['package']['version'],
                    'manifestPath' => $manifestPath,
                    'manifest' => $manifestData,
                );
            }
            //} // END IF ACTION
        } // END STRUCTURE

        // Fiter out ours
        $buildPackages = array();
        foreach ($availablePackages as $availablePackage) {
            if (empty($availablePackage['manifest']['package']['author']) ||
                $availablePackage['manifest']['package']['author'] != 'SocialEngine Core' ||
                $this->_includeNative) {
                $buildPackages[] = $availablePackage;
            }
        }
        $this->view->buildPackages = $buildPackages;


        // return if not post
        if (!$this->getRequest()->isPost()) {
            return;
        }

        // BUILD!
        $selected = $this->_getParam('build');
        if (empty($selected)) {
            $this->view->error = 'No package selected.';
            return;
        }

        // Check if selected exist
        $toBuildPackages = array();
        foreach ($buildPackages as $buildPackage) {
            if (in_array($buildPackage['key'], $selected)) {
                $toBuildPackages[] = $buildPackage;
            }
        }
        if (empty($toBuildPackages)) {
            $this->view->error = 'No valid package selected.';
            return;
        }

        // Build 'em
        foreach ($toBuildPackages as $toBuildPackage) {
            try {
                $this->_buildPackage($toBuildPackage['manifestPath']);
            } catch (Exception $e) {
                $this->view->error = $e->getMessage();
                return;
            }
        }

        $this->view->status = true;

        return $this->_helper->redirector->gotoRoute(array('action' => 'manage'));
    }

    public function manageAction()
    {
        require_once 'PEAR.php';
        require_once 'Archive/Tar.php';

        // Get built packages
        $builtPackages = array();
        $builtPackageFiles = array();
        foreach (scandir($this->_outputPath) as $file) {
            $path = $this->_outputPath . '/' . $file;
            if (!is_file($path)) {
                continue;
            }
            if (substr($file, -4) !== '.tar') {
                continue;
            }

            // Read package.json
            $archive = new Archive_Tar($path);
            $string = $archive->extractInString('package.json');

            if ($string) {
                $package = new Engine_Package_Manifest();
                $parser = Engine_Package_Manifest_Parser::factory('package.json');
                $package->fromArray($parser->fromString($string));
            } else {
                $package = null;
            }

            $builtPackages[] = $package;
            $builtPackageFiles[] = $path;
        }
        $this->view->packages = $builtPackages;
        $this->view->packageFiles = $builtPackageFiles;
    }

    public function downloadAction()
    {
        $file = $this->_getParam('file');
        $path = $this->_outputPath . '/' . $file;
        if (!file_exists($path) || !is_file($path)) {
            echo 'No file';
            exit();
            return;
        }

        // Close output buffering
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        // Send headers
        header('content-disposition: attachment; filename=' . urlencode($file));
        header('content-length: ' . filesize($path));

        // Open file
        $handle = fopen($this->_outputPath . '/' . $file, 'r');
        while ('' !== ($str = fread($handle, 256))) {
            echo $str;
        }
        exit();
    }

    public function deleteAction()
    {
        $this->view->form = $form = new Install_Form_Confirm(array(
            'action' => $_SERVER['REQUEST_URI'],
            'title' => 'Delete Packages?',
            'description' => 'Are you sure you want to delete these packages?',
            'submitLabel' => 'Delete Packages',
            'cancelHref' => $this->view->url(array('action' => 'manage')),
            'useToken' => true,
        ));

        if (!$this->getRequest()->isPost()) {
            return;
        }

        if (!$form->isValid($this->getRequest()->getPost())) {
            return;
        }

        $actions = (array) $this->_getParam('actions');
        foreach ($actions as $action) {
            $path = $this->_outputPath . '/' . $action;
            if (file_exists($path) && is_file($path)) {
                @unlink($path);
            }
        }

        return $this->_helper->redirector->gotoRoute(array('action' => 'manage'));
    }

    public function combineAction()
    {
        require_once 'PEAR.php';
        require_once 'Archive/Tar.php';

        $this->view->form = $form = new Install_Form_Sdk_Combine();
        $form->name->setValue('combined_' . time() . '.tar');

        $actions = (array) $this->_getParam('actions');
        foreach ($actions as $index => $action) {
            $path = $this->_outputPath . '/' . $action;
            if (!file_exists($path) || !is_file($path)) {
                unset($actions[$index]);
            }
        }
        if (empty($actions) || !is_array($actions)) {
            return $form->addError('No packages selected.');
        } elseif (count($actions) == 1) {
            return $form->addError('Cannot combine only one package.');
        }

        if (!$this->getRequest()->isPost()) {
            return;
        }

        if (!$form->isValid($this->getRequest()->getPost())) {
            return;
        }

        $archiveFilename = $form->getValue('name');
        $archiveFilename = preg_replace('/[^a-zA-Z0-9_.-]/', '', $archiveFilename);
        if (strtolower(substr($archiveFilename, -4)) != '.tar') {
            $archiveFilename .= '.tar';
        }

        $archive = new Archive_Tar($this->_outputPath . '/' . $archiveFilename);

        foreach ($actions as $action) {
            $path = $this->_outputPath . '/' . $action;
            $archive->addModify($path, null, $this->_outputPath);
        }

        return $this->_helper->redirector->gotoRoute(array('action' => 'manage'));
    }


    protected function _inflect($string)
    {
        $string = preg_replace('/[^a-z0-9]+/', ' ', $string);
        $string = ucwords($string);
        $string = str_replace(' ', '', $string);
        return $string;
    }

    protected function _deflect($string)
    {
        $string = preg_replace('/([A-Z])/', '-\1', $string);
        $string = strtolower($string);
        $string = trim($string, '- ');
        return $string;
    }

    protected function _buildPackage($manifest, $date = null)
    {
        if (null === $date) {
            $date = time();
        }

        // Get manifest data
        if (is_string($manifest)) {
            $manifestData = require $manifest;
            if (empty($manifestData['package'])) {
                throw new Exception(sprintf('Missing package data for package in path: %s', $manifestPath));
            }
            $manifestData = $manifestData['package'];
        } elseif (is_array($manifest)) {
            $manifestData = $manifest;
        } else {
            throw new Exception('Invalid manifest data type');
        }

        // Override date (for now at least)
        $manifestData['date'] = $date;

        // Build package file
        $package = new Engine_Package_Manifest($manifestData);

        // Build archive
        $archiveFile = Engine_Package_Archive::deflate($package, $this->_outputPath);

        // Verify archive for integrity
        $extractedPath = Engine_Package_Archive::inflate($archiveFile, $this->_outputPath);
        $loaded = new Engine_Package_Manifest($extractedPath);

        // Remove verified archive
        Engine_Package_Utilities::fsRmdirRecursive($extractedPath, true);

        return $archiveFile;
    }

    protected function _getStructure($type = null)
    {
        // Generate structure
        $structure = array(
            'externals' => array(
                'path' => 'externals',
                'manifest' => 'manifest.php',
                'array' => true,
                'singular' => 'external',
                'inflect' => false,
                'defaultActions' => array(
                    'install',
                    'upgrade',
                    'refresh',
                    //'enable',
                    //'disable',
                ),
            ),
            'languages' => array(
                'path' => 'application/languages',
                'manifest' => 'manifest.php',
                'array' => true,
                'singular' => 'language',
                'inflect' => false,
                'defaultActions' => array(
                    'install',
                    'upgrade',
                    'refresh',
                    'remove',
                ),
            ),
            'libraries' => array(
                'path' => 'application/libraries',
                'manifest' => 'manifest.php',
                'array' => true,
                'singular' => 'library',
                'inflect' => true,
                'defaultActions' => array(
                    'install',
                    'upgrade',
                    'refresh',
                    'remove',
                ),
            ),
            'modules' => array(
                'path' => 'application/modules',
                'manifest' => 'settings/manifest.php',
                'array' => true,
                'singular' => 'module',
                'inflect' => true,
                'language' => true,
                'defaultActions' => array(
                    'install',
                    'upgrade',
                    'refresh',
                    'enable',
                    'disable',
                ),
                'defaultCallback' => array(
                    'class' => 'Engine_Package_Installer_Module',
                ),
            ),
            'plugins' => array(
                'path' => 'application/plugins',
                'manifest' => 'manifest.php',
                'array' => true,
                'singular' => 'plugin',
                'inflect' => false,
                'defaultActions' => array(
                    'install',
                    'upgrade',
                    'refresh',
                    'remove',
                ),
            ),
            'themes' => array(
                'path' => 'application/themes',
                'manifest' => 'manifest.php',
                'array' => true,
                'singular' => 'theme',
                'inflect' => false,
                'defaultActions' => array(
                    'install',
                    'upgrade',
                    'refresh',
                    'remove',
                ),
                'defaultCallback' => array(
                    'class' => 'Engine_Package_Installer_Theme',
                ),
            ),
            'widgets' => array(
                'path' => 'application/widgets',
                'manifest' => 'manifest.php',
                'array' => true,
                'singular' => 'widget',
                'inflect' => false,
                'defaultActions' => array(
                    'install',
                    'upgrade',
                    'refresh',
                    'remove',
                ),
            ),
        );

        if (func_num_args() <= 0) {
            return $structure;
        } elseif (isset($structure[$type])) {
            return $structure[$type];
        } else {
            return false;
        }
    }

    protected function _sys_get_temp_dir()
    {
        if (!function_exists('sys_get_temp_dir')) {
            function sys_get_temp_dir()
            {
                if (false != ($temp = getenv('TMP'))) {
                    return $temp;
                }
                if (false != ($temp = getenv('TEMP'))) {
                    return $temp;
                }
                if (false != ($temp = getenv('TMPDIR'))) {
                    return $temp;
                }
                $temp = tempnam(__FILE__, '');
                if (file_exists($temp)) {
                    unlink($temp);
                    return dirname($temp);
                }
                return null;
            }
        }

        return sys_get_temp_dir();
    }
}
