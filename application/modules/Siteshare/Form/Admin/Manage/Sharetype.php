<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteshare
 * @copyright  Copyright 2017-2021 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Sharetype.php 2017-02-24 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteshare_Form_Admin_Manage_Sharetype extends Engine_Form
{
  public function init()
  {

    $this
      ->setTitle('Add New Share Type')
      ->setDescription('Use the form below to add a share type for enabling users to share their content over them.');
    $notInclude = array('activity', 'advancedactivity', 'sitelike', 'sitepageoffer', 'sitepagebadge', 'featuredcontent', 'sitepagediscussion', 'sitepagelikebox', 'mobi', 'advancedslideshow', 'birthday', 'birthdayemail', 'communityad', 'dbbackup', 'facebookse', 'facebooksefeed', 'facebooksepage', 'feedback', 'groupdocument', 'grouppoll', 'mapprofiletypelevel', 'mcard', 'poke', 'sitepageinvite', 'siteslideshow', 'seaocore', 'suggestion', 'userconnection', 'sitepageform', 'sitepageadmincontact','sitepagealbum', 'sitebusinessbadge', 'sitebusinessoffer', 'sitebusinessdiscussion', 'sitebusinesslikebox', 'sitebusinessinvite', 'sitebusinessform', 'sitebusinessadmincontact', 'classified', 'document', 'forum', 'poll', 'list', 'music', 'recipe', 'sitepagenote', 'sitepagevideo', 'sitepagepoll', 'sitepagemusic', 'sitepageevent', 'sitepagereview', 'sitepagedocument', 'sitepageurl', 'sitepageintegration', 'sitebusinessalbum', 'sitebusinessevent', 'sitebusinessreview', 'sitebusinessdocument', 'sitebusinessurl', 'sitebusinessnote', 'sitebusinessvideo', 'sitebusinesspoll', 'sitebusinessmusic', 'communityadsponsored', 'sitestore', 'sitevideoview', 'sitegroupoffer', 'sitegroupbadge', 'sitegroupdiscussion', 'sitegrouplikebox', 'sitegroupinvite', 'sitegroupform','sitegroupalbum', 'sitegroupadmincontact', 'sitegroupnote', 'sitegroupnote', 'sitegroupvideo', 'sitegrouppoll', 'sitegroupmusic', 'sitegroupevent', 'sitegroupreview', 'sitegroupdocument', 'sitegroupurl', 'sitegroupintegration', 'sitegroupmember', 'sitestoreoffer', 'sitestorebadge', 'sitestorediscussion', 'sitestorelikebox', 'sitestoreinvite', 'sitestoreform', 'sitestoreadmincontact', 'sitestorenote', 'sitestore', 'sitestorenote', 'sitestorevideo', 'sitestorepoll', 'sitestoremusic', 'sitestorealbum', 'sitestoreevent', 'sitestorereview', 'sitestoredocument', 'sitestoreurl', 'sitestoreintegration', 'sitestoremember', 'sitepagemember', 'sitemobile', 'siteusercoverphoto', 'sitemobileapp', 'sitemailtemplates', 'sitehashtag', 'sitenews', 'siteshare','siteminify','sitereviewlistingtype');


    $module_table = Engine_Api::_()->getDbTable('modules', 'core');
    $moduleName = $module_table->info('name');
    $select = $module_table->select()
      ->from($moduleName, array('name', 'title'))
      ->where($moduleName . '.type =?', 'extra')
      ->where($moduleName . '.name not in(?)', $notInclude)
      ->where($moduleName . '.enabled =?', 1);
    $contentModuloe = $select->query()->fetchAll();
    $contentModuloeArray = array();
    if( !empty($contentModuloe) ) {
      $contentModuloeArray[] = '';
      foreach( $contentModuloe as $modules ) {
        $contentModuloeArray[$modules['name']] = $modules['title'] . " ";
      }
    }

    $module_name = Zend_Controller_Front::getInstance()->getRequest()->getParam('module_name', null);
    $sharetype_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('sharetype_id', 0);

    if( !empty($contentModuloeArray) ) {
      $this->addElement('Select', 'module_name', array(
        'label' => 'Module',
        'allowEmpty' => false,
        'onchange' => 'setModuleName(this.value)',
        'multiOptions' => $contentModuloeArray,
      ));
    } else if(empty($sharetype_id)) {
      //VALUE FOR LOGO PREVIEW.
      $description = "<div class='tip'><span>" . Zend_Registry::get('Zend_Translate')->_("There are currently no new content modules that could be added for “Share Type”.") . "</span></div>";
      $this->addElement('Dummy', 'module', array(
        'description' => $description,
      ));
      $this->module->addDecorator('Description', array('placement' =>
        Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));
    }


    $contentItem = array();
    if( !empty($contentModuloeArray) && !empty($module_name) ) {
      $this->module_name->setValue($module_name);
      if( $module_name == 'sitereview' ) {
        $listTypes = Engine_Api::_()->getDbTable('listingtypes', 'sitereview')->getListingTypesArray();
        $contentItem = array_combine(
          array_map(function($k) {
            return 'sitereview_listingtype_' . $k;
          }, array_keys($listTypes)), $listTypes
        );
      } else {
        $contentItem = $this->getContentItem($module_name);
      }
      if( empty($contentItem) && empty($sharetype_id))
        $this->addElement('Dummy', 'dummy_title', array(
          'description' => 'For this module not difine any item in manifest file.',
        ));
    }
    if( !empty($contentItem) ) {
      $this->addElement('Select', 'type', array(
        'label' => 'Type',
        'description' => "This is the value of 'items' key in the manifest file of this plugin. To view this value for a desired module, go to the directory of this module, and open the file 'settings/manifest.php'. In this file, search for 'items', and view its value. [Ex in case of blog module: Open file 'application/modules/Blog/settings/manifest.php', and go to around line 62. You will see the 'items' key array with value 'blog'. Thus, the Database Table Item for blog module is: 'blog']",
        'required' => true,
        'multiOptions' => $contentItem,
      ));

      $this->addElement('Text', 'title', array(
        'label' => 'Share Option Title',
        'description' => 'Enter the title, this title will be shown to the users in Share options.',
        'required' => true
      ));
      $privacy_options = array('owner' => 'Who\'s Owner', 'admin' => 'Who\'s Admin', 'member' => 'Who\'s Members', 'all' => 'All');
      $this->addElement('Select', 'share_allow', array(
        'label' => 'Sharing Permission',
        'description' => "Who can share the content/item?",
        'required' => true,
        'multiOptions' => $privacy_options,
      ));
      $privacy_options = array('owner' => 'Owner', 'admin' => 'Admin', 'member' => 'Members','none'=>'None');
      $this->addElement('Select', 'notification_allow', array(
        'label' => 'Notifications',
        'description' => " To whom notifications will be sent when an item is shared?",
        'required' => true,
        'multiOptions' => $privacy_options,
      ));
      $this->addElement('Checkbox', 'enabled', array(
        'description' => 'Enable',
        'label' => 'Enable this share type to be part of share options for the users.',
        'value' => 1
      ));
      // Element: execute
      $this->addElement('Button', 'execute', array(
        'label' => 'Submit',
        'type' => 'submit',
        'ignore' => true,
        'decorators' => array('ViewHelper'),
      ));

      // Element: cancel
      $this->addElement('Cancel', 'cancel', array(
        'label' => 'cancel',
        'prependText' => ' or ',
        'ignore' => true,
        'link' => true,
        'href' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'index')),
        'decorators' => array('ViewHelper'),
      ));

      // DisplayGroup: buttons
      $this->addDisplayGroup(array('execute', 'cancel'), 'buttons', array(
        'decorators' => array(
          'FormElements',
          'DivDivDivWrapper',
        )
      ));
    } else if(!empty($sharetype_id)) {

      $this->addElement('Text', 'title', array(
        'label' => 'Share Option Title',
        'description' => 'Enter the title, this title will be shown to the users in Share options.',
        'required' => true
      ));
      $this->addElement('Checkbox', 'enabled', array(
        'description' => 'Enable',
        'label' => 'Enable this share type to be part of share options for the users.',
        'value' => 1
      ));
      // Element: execute
      $this->addElement('Button', 'execute', array(
        'label' => 'Submit',
        'type' => 'submit',
        'ignore' => true,
        'decorators' => array('ViewHelper'),
      ));

      // Element: cancel
      $this->addElement('Cancel', 'cancel', array(
        'label' => 'cancel',
        'prependText' => ' or ',
        'ignore' => true,
        'link' => true,
        'href' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'index')),
        'decorators' => array('ViewHelper'),
      ));

      // DisplayGroup: buttons
      $this->addDisplayGroup(array('execute', 'cancel'), 'buttons', array(
        'decorators' => array(
          'FormElements',
          'DivDivDivWrapper',
        )
      ));

    
    } else {
      // Element: cancel
      $this->addElement('Cancel', 'cancel', array(
        'label' => 'cancel',
        'ignore' => true,
        'link' => true,
        'href' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'index')),
      ));
    }
  }

  public function getContentItem($moduleName)
  {
    $file_path = APPLICATION_PATH . "/application/modules/" . ucfirst($moduleName) . "/settings/manifest.php";
    $contentItem = array();
    if( @file_exists($file_path) ) {
      $ret = include $file_path;
      if( isset($ret['items']) ) {

        foreach( $ret['items'] as $item )
          $contentItem[$item] = $item . " ";
      }
    }
    return $contentItem;
  }

}

?>
