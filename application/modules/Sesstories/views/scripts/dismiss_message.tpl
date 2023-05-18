<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesstories
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: dismiss_message.tpl 2018-11-05 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>

<h2><?php echo $this->translate("Stories Feature") ?></h2>
<?php if(!Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('sesbasic')) { ?>
    <div class="tip"><span><?php echo $this->translate("This plugin requires \"<a href='https://socialnetworking.solutions/social-engine/socialenginesolutions-basic-required-plugin/' target='_blank'>SocialNetworking.Solutions (SNS) Basic Required Plugin </a>\" to be installed and enabled on your website for Location and various other features to work. Please get the plugin from <a href='https://socialnetworking.solutions/social-engine/socialenginesolutions-basic-required-plugin/' target='_blank'>here</a> to install and enable on your site."); ?></span></div>
  <?php } ?>

<?php if( engine_count($this->navigation) ):?>
<div class='tabs'>
  <ul class="navigation">
    <?php foreach( $this->navigation as $link ): ?>
    <li class="<?php echo $link->get('active') ? 'active' : '' ?>">
      <?php if(!empty($link->plateform)) { ?>
      <a href='<?php echo $link->getHref() . "?plateform=".$link->plateform ?>' class="<?php echo $link->getClass() ? ' ' . $link->getClass() : ''  ?>"><?php echo $this->translate($link->getlabel()) ?></a>
      <?php } else { ?>
      <a href='<?php echo $link->getHref() ?>' class="<?php echo $link->getClass() ? ' ' . $link->getClass() : ''  ?>"><?php echo $this->translate($link->getlabel()) ?></a>
      <?php } ?>
    </li>
    <?php endforeach; ?>
  </ul>
</div>
<?php endif; ?>
