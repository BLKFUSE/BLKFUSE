<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Add.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescommunityads_Form_Admin_Module_Add extends Engine_Form {
  public function init() {
    $this->setTitle('Integrate New Plugin')
            ->setDescription('Here, you can configure the required details for the plugin to be integrated.');
    $integrateothermoduleId = Zend_Controller_Front::getInstance()->getRequest()->getParam('integrateothermodule_id', 0);
      $integrateothermodule = false;
    if (!$integrateothermoduleId) {
      $integrateothermoduleItem = array();
      $integrateothermoduleArray = array();
      $integrateothermoduleArray[] = '';
			//get all enabled modules
			$notincludepluginarray = array('chat',  'sesadvancedactivity', 'sesadvancedcomment', 'sesfeedbg', 'sesfeelingactivity', 'sesfeedgif', 'sesbasic', 'sesadvsitenotification', 'sesariana', 'sesbrowserpush', 'seschristmas', 'sescleanwide', 'sescontentcoverphoto', 'sescontestjoinfees', 'sescontestjurymember', 'sescontestpackage', 'sesdemouser', 'seselegant', 'sesemailverification', 'sesemoji', 'seserror', 'seseventticket', 'sesexpose', 'sesfaq', 'sesfbstyle', 'sesfooter', 'seshtmlbackground', 'seslangtranslator', 'sesletteravatar', 'sesmediaimporter', 'sesmember', 'sesmembershorturl', 'sesmetatag', 'sesmultipleform', 'sespagebuilder', 'sespoke', 'sesprofilelock', 'sespymk', 'sessiteiframe', 'sessociallogin', 'sessocialshare', 'sessoundcloudsearch', 'sesspectromedia', 'sesteam', 'sestour', 'sestweet', 'sesusercoverphoto', 'sesvideoimporter','sesapi','sesandroidapp','sesiosapp');
      $coreTable = Engine_Api::_()->getDbTable('modules', 'core');
      $select = $coreTable->select()
              ->from($coreTable->info('name'), array('name', 'title'))
              ->where('enabled =?', 1)
              ->where('name NOT IN (?)', $notincludepluginarray)
              ->where('type =?', 'extra');
      $resultsArray = $select->query()->fetchAll();
      if (!empty($resultsArray)) {
        foreach ($resultsArray as $result) {
          $integrateothermoduleArray[$result['name']] = $result['title'];
        }
      }
      if (!empty($integrateothermoduleArray)) {
        $this->addElement('Select', 'module_name', array(
            'label' => 'Choose Plugin',
            'description' => 'Below, you can choose the plugin to be integrated.',
            'allowEmpty' => false,
            'onchange' => 'changemodule(this.value)',
            'multiOptions' => $integrateothermoduleArray,
        ));
      } else {
        $description = "<div class='tip'><span>" . Zend_Registry::get('Zend_Translate')->_("Here are no module to configure with our plugin.") . "</span></div>";
        $this->addElement('Dummy', 'module', array(
            'description' => $description,
        ));
        $this->module->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));
      }
      $module = Zend_Controller_Front::getInstance()->getRequest()->getParam('module_name', null);
      if (!empty($module)) {
        $this->module_name->setValue($module);
				//get manifest item for given module
        $integrateothermodule = Engine_Api::_()->sescommunityads()->getPluginItem($module);
        if (empty($integrateothermodule))
          $this->addElement('Dummy', 'dummy_title', array(
              'description' => 'No item type define for this plugin.',
          ));
      }
    }
		$param = false;
    if ($integrateothermoduleId)
      $param = true;
    elseif ($integrateothermodule)
      $param = true;
    if ($param) {
      if (!$integrateothermoduleId) {
         $this->addElement('Text', 'title', array(
            'label' => 'Title',
            'description' => 'Below, you can write the item title.',
            'allowEmpty' => false,
            'required' => true,
        ));
        $this->addElement('Select', 'content_type', array(
            'label' => 'Item Type of Plugin',
            'description' => 'Select the item type for ads of the above chosen plugin which is defined in its manifest.php file. [This item type is the parent to which ads are associated]',
            'multiOptions' => $integrateothermodule,
        ));
      }
      $this->addElement('Checkbox', 'enabled', array(
          'description' => 'Enable This Plugin?',
          'label' => 'Yes, enable this plugin now.',
          'value' => 1,
      ));
      $this->addElement('Button', 'execute', array(
          'label' => 'Add Plugin',
          'type' => 'submit',
          'ignore' => true,
          'decorators' => array('ViewHelper'),
      ));
      $this->addElement('Cancel', 'cancel', array(
          'label' => 'Cancel',
          'prependText' => ' or ',
          'ignore' => true,
          'link' => true,
          'href' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'modules'),false),
          'decorators' => array('ViewHelper'),
      ));
    }
  }
}