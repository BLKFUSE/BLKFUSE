<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: AdminMailController.php 9798 2012-10-12 19:11:49Z matthew $
 * @author     John
 */

/**
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Core_AdminMailController extends Core_Controller_Action_Admin
{

  public function settingsAction()
  {
    // Get mail config
    $mailConfigFile = APPLICATION_PATH . '/application/settings/mail.php';
    $mailConfig = array();
    if( file_exists($mailConfigFile) ) {
      $mailConfig = include $mailConfigFile;
    }

    // Get form
    $this->view->form = $form = new Core_Form_Admin_Mail_Settings();

    // Populate form
    $form->populate((array) Engine_Api::_()->getApi('settings', 'core')->core_mail);

    if( 'Zend_Mail_Transport_Smtp' === @$mailConfig['class'] && !_ENGINE_ADMIN_NEUTER) {
      $form->populate(array_filter(array(
        'mail_smtp' => ( 'Zend_Mail_Transport_Smtp' == @$mailConfig['class'] ),
        'mail_smtp_server' => @$mailConfig['args'][0],
        'mail_smtp_ssl' => @$mailConfig['args'][1]['ssl'],
        'mail_smtp_authentication' => !empty($mailConfig['args'][1]['username']),
        'mail_smtp_port' => @$mailConfig['args'][1]['port'],
        'mail_smtp_username' => @$mailConfig['args'][1]['username'],
        'mail_smtp_password' => @$mailConfig['args'][1]['password'],
      )));
    }
    
    // Check method/valid
    if( !$this->getRequest()->isPost() ) {
      return;
    }
    if( !$form->isValid($this->getRequest()->getPost()) ) {
      return;
    }

    // Process
    $values = $form->getValues();



    // Special case for auth
    if( $values['mail_smtp'] ){
      // re-assign existing password if form password is left blank
      if( empty($values['mail_smtp_password']) ) {
        if( !empty($mailConfig['args'][1]['password']) ){
          $values['mail_smtp_password'] = $mailConfig['args'][1]['password'];
        }
      }
    }


    // Save smtp settings
    if( $values['mail_smtp'] ) {
      $args = array();

      $args['port'] = (int) $values['mail_smtp_port'];

      if( !empty($values['mail_smtp_ssl']) ) {
        $args['ssl'] = $values['mail_smtp_ssl'];
      }

      if( !empty($values['mail_smtp_authentication']) ) {
        $args['auth'] = 'login';
        $args['username'] = $values['mail_smtp_username'];
        $args['password'] = $values['mail_smtp_password'];
      }

      $mailConfig = array(
        'class' => 'Zend_Mail_Transport_Smtp',
        'args' => array(
          $values['mail_smtp_server'],
          $args,
        )
      );

    } else {
      $mailConfig = array(
        'class' => 'Zend_Mail_Transport_Sendmail',
        'args' => array(),
      );
    }

    // Write contents to file
    if( (is_file($mailConfigFile) && is_writable($mailConfigFile)) ||
        (is_dir(dirname($mailConfigFile)) && is_writable(dirname($mailConfigFile))) ) {
      $contents = "<?php defined('_ENGINE') or die('Access Denied'); return ";
      $contents .= var_export($mailConfig, true);
      $contents .= "; ?>";

      file_put_contents($mailConfigFile, $contents);
    } else {
      return $form->addError('Unable to change mail settings due to the file ' .
        '/application/settings/mail.php not having the correct permissions.' .
        'Please CHMOD (change the permissions of) that file to 666, then try again.');
    }

    // save the name and email address
    Engine_Api::_()->getApi('settings', 'core')->core_mail = array(
      'from' => $values['from'],
      'name' => $values['name'],
      'queueing' => $values['queueing'],
      'contact' => $values['contact'],
      'count' => $values['count'],
    );
    
    
    
    $form->addNotice('Your changes have been saved.');
  }

  public function templatesAction()
  {
    $this->view->form = $form = new Core_Form_Admin_Mail_Templates();

    // Get language
    $this->view->language = $language = preg_replace('/[^a-zA-Z_-]/', '', $this->_getParam('language', 'en'));
    if( !Zend_Locale::isLocale($language) ) {
      $form->removeElement('submit');
      return $form->addError('Please select a valid language.');
    }

    // Check dir for exist/write
    $languageDir = APPLICATION_PATH . '/application/languages/' . $language;
    $languageFile = $languageDir . '/custom.csv';
    if( !is_dir($languageDir) ) {
      $form->removeElement('submit');
      return $form->addError('The language does not exist, please create it first');
    }
    if( !is_writable($languageDir) ) {
      $form->removeElement('submit');
      return $form->addError('The language directory is not writable. Please set CHMOD -R 0777 on the application/languages folder.');
    }
    if( is_file($languageFile) && !is_writable($languageFile) ) {
      $form->removeElement('submit');
      return $form->addError('The custom language file exists, but is not writable. Please set CHMOD -R 0777 on the application/languages folder.');
    }


    
    // Get template
    $this->view->template = $template = $this->_getParam('template', '1');
    $this->view->templateObject = $templateObject = Engine_Api::_()->getItem('core_mail_template', $template);
    if( !$templateObject ) {
      $templateObject = Engine_Api::_()->getDbtable('MailTemplates', 'core')->fetchRow();
      $template = $templateObject->mailtemplate_id;
    }

    // Populate form
    $description = $this->view->translate(strtoupper("_email_".$templateObject->type."_description"));
    $description .= '<br /><br />';
    $description .= $this->view->translate('Available Placeholders:');
    $description .= '<br />';
    $description .= join(', ', explode(',', $templateObject->vars));

    $form->getElement('template')
      ->setDescription($description)
      ->getDecorator('Description')
        ->setOption('escape', false)
        ;

    // Get translate
    $translate = Zend_Registry::get('Zend_Translate');


    // Get stuff
    $subjectKey = strtoupper("_email_".$templateObject->type."_subject");
    $subject = $translate->_($subjectKey, $language);
    if( $subject == $subjectKey ) {
      $subject = $translate->_($subjectKey, 'en');
    }

    $bodyHTMLKey = strtoupper("_email_".$templateObject->type."_bodyhtml");
    $bodyHTML = $translate->_($bodyHTMLKey, $language);
    if( $bodyHTML == $bodyHTMLKey ) {
      $bodyHTML = $translate->_($bodyHTMLKey, 'en');
    }

    // get body from email body key if not found by bodyhtml key
    if( $bodyHTML == $bodyHTMLKey ) {
        $bodyKey = strtoupper("_email_".$templateObject->type."_body");
        $body = $translate->_($bodyKey, $language);
        if( $body == $bodyKey ) {
          $body = $translate->_($bodyKey, 'en');
        }
        $bodyHTML = nl2br($body);
    }
   $form->populate(array(
      'language' => $language,
      'template' => $template,
      'subject' => $subject,
      'bodyhtml' => $bodyHTML,
      'default' => $templateObject->default,
    ));
   
    $enabledMemberLevel = false;
    if(!engine_in_array($templateObject->module, array("core","user","payment","invite"))){
      if(!is_null($templateObject->member_level)){
        $memberLevel = @explode(",",$templateObject->member_level);
        $form->member_level->setValue($memberLevel);
      }
      $enabledMemberLevel = true;
    } else {
      $form->removeElement("member_level");
    }
    // Check method/valid
    if( !$this->getRequest()->isPost() ) {
      return;
    }
    if( !$form->isValid($this->getRequest()->getPost()) ) {
      return;
    }

    // Process
    $values = $form->getValues();
    
    /*Feature Improvement - Add Member Level Settings for Emailed Notifications #971 */
    if($enabledMemberLevel){
      $templateObject->member_level = implode(",",(array)$values['member_level']);
      $templateObject->save();
    }
    $templateObject->default = $values['default'];
		$templateObject->save();

    $writer = new Engine_Translate_Writer_Csv();
    // Try to write to a file
    $targetFile = APPLICATION_PATH . '/application/languages/' . $language . '/custom.csv';
    if( !file_exists($targetFile) ) {
      touch($targetFile);
      chmod($targetFile, 0777);
    }

    // set the local folder depending on the language_id
    $writer->read(APPLICATION_PATH . '/application/languages/' . $language . '/custom.csv');

    // write new subject
    $writer->removeTranslation(strtoupper("_email_" . $templateObject->type . "_subject"));
    $writer->setTranslation(strtoupper("_email_" . $templateObject->type . "_subject"), $values['subject']);

    // write new body
    $writer->removeTranslation(strtoupper("_email_" . $templateObject->type . "_bodyhtml"));
    $writer->setTranslation(strtoupper("_email_" . $templateObject->type . "_bodyhtml"), $values['bodyhtml']);

    $writer->write();


    // Clear cache?
    $translate->clearCache();

    
    $form->addNotice('Your changes have been saved.');

    // Check which Translation Adapter has been selected
    $db = Engine_Db_Table::getDefaultAdapter();
    $translationAdapter = $db->select()
      ->from('engine4_core_settings', 'value')
      ->where('`name` = ?', 'core.translate.adapter')
      ->query()
      ->fetchColumn();
    if ($translationAdapter == 'array') {
        $form->addNotice('You have enabled the "Translation Performance" setting from \"<a href="admin/core/settings/performance">Performance and Caching</a>\" section of administration. For your email template changes to be effective by re-generation of updated translation PHP array, please click on "Save Changes" button of \"<a href="admin/core/settings/performance">Performance and Caching</a>\" section, with "Flush cache?" enabled, after completing all your changes in email templates.');
    }
  }

}
