<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating	
 * @package    Sesdating
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: general-setting.tpl  2018-09-21 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php $viewer = Engine_Api::_()->user()->getViewer();?>
<ul>
	<li><a href="<?php echo $viewer->getHref(); ?>"><?php echo $this->translate("My Profile");?></a></li>
</ul>    
<?php echo $this->navigation()->menu()->setContainer($this->settingNavigation)->render();?>
<ul>
  <?php if($viewer->level_id == 1 || $viewer->level_id == 2):?>
    <li>
      <a href="<?php echo $this->url(array(), 'admin_default', true)?>"><?php echo $this->translate('Administrator');?></a>
    </li>
  <?php endif;?>
  <li>
    <a href="<?php echo $this->url(array(), 'user_logout', true)?>"><?php echo $this->translate('Logout');?></a>
  </li>
</ul>
