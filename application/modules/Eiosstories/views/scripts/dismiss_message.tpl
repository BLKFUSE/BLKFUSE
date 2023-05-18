<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eiosstories
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: dismiss_message.tpl 2019-11-07 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>

<h2><?php echo $this->translate("Stories Feature") ?></h2>
<?php if(!Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('sesbasic')) { ?>
  <div class="tip"><span><?php echo $this->translate("This plugin requires \"<a href='https://socialnetworking.solutions/social-engine/socialenginesolutions-basic-required-plugin/' target='_blank'>SocialNetworking.Solutions (SNS) Basic Required Plugin</a>\" to be installed and enabled on your website for Location and various other features to work. Please get the plugin from <a href='https://socialnetworking.solutions/social-engine/socialenginesolutions-basic-required-plugin/' target='_blank'>here</a> to install and enable on your site."); ?></span></div>
<?php } ?>
<?php //$eiosstories_adminmenu = Zend_Registry::isRegistered('eiosstories_adminmenu') ? Zend_Registry::get('eiosstories_adminmenu') : null; ?>

<?php //if(!empty($eiosstories_adminmenu)) { ?>
  <?php if( engine_count($this->navigation) ):?>
    <div class='tabs'>
      <ul class="navigation">
        <?php foreach( $this->navigation as $link ): ?>
          <li class="<?php echo $link->get('active') ? 'active' : '' ?>">
            <a href='<?php echo $link->getHref() ?>' class="<?php echo $link->getClass() ? ' ' . $link->getClass() : ''  ?>"><?php echo $this->translate($link->getlabel()) ?></a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>
<?php //} ?>
