<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating	
 * @package    Sesdating
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2018-09-21 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesdating/externals/styles/style_login.css'); ?>
<div class="sesdating_login_main sesbasic_bxs">
  <?php if($this->showlogo): ?>
	<div class="sesdating_site_logo">    
    	<?php echo $this->content()->renderWidget('sesdating.menu-logo'); ?>
    </div>
    <?php endif; ?>
    <div class="sesdating_login_form">
	<div class="sesdating_login_tittle">
    	<h3>Sign In</h3>
    </div>
  <?php $settings = Engine_Api::_()->getApi('settings', 'core'); ?>
	<?php if(Engine_Api::_()->getDbTable('modules','core')->isModuleEnabled('sessociallogin')):?>
        <div class="sesdating_social_login_btns">
        <?php  echo $this->partial('_socialLoginIcons.tpl','sessociallogin',array()); ?>
      </div>
      <?php else: ?>
  <div class="sesdating_ligin_with_social">
    <?php
          if( 'none' != $settings->getSetting('core_facebook_enable', 'none')
              && $settings->core_facebook_secret ) {
    ?>
      <div id="facebook_login_btn_href" style="display:none;"><?php echo User_Model_DbTable_Facebook::loginButton(); ?></div>
    	<div class="facebook_btn">
        <a href="" id="fb_href_lnk">
        	<p class="facebook_icon"><i class="fab fa-facebook-f"></i></p>
        	<p class="facebook"><?php echo $this->translate("LOG IN WITH FACEBOOK");?></p>
            </a>
        </div>
   <?php } ?>
    <?php
          if( 'none' != $settings->getSetting('core_twitter_enable', 'none')
              && $settings->core_twitter_secret ) {
    ?>
        <div id="twitter_login_btn_href" style="display:none;"><?php echo User_Model_DbTable_Twitter::loginButton(); ?></div>
        <div class="twitter_btn">
        <a href="" id="twitter_href_lnk">
        	<p class="twitter_icon"><i class="fab fa-twitter"></i></p>
        	<p class="twitter"><?php echo $this->translate("LOG IN WITH TWITTER");?></p>
            </a>
        </div>
    <?php } ?>
    </div>
      <?php endif;?>
     
      <div class="sesdating_info_text">
    	<p><?php echo $this->translate("Log in with your credentials");?></p>
    </div>
<?php echo $this->form->render($this) ?>
</div>
</div>
<script type="application/javascript">
scriptJquery('#fb_href_lnk').attr('href',scriptJquery('#facebook_login_btn_href').find('a').attr('href'));
scriptJquery('#facebook_login_btn_href').remove();
scriptJquery('#twitter_href_lnk').attr('href',scriptJquery('#twitter_login_btn_href').find('a').attr('href'));
scriptJquery('#twitter_login_btn_href').remove();
if(!scriptJquery('#twitter_href_lnk').length && !scriptJquery('#fb_href_lnk').length)
  scriptJquery('.sesdating_ligin_with_social').remove();
scriptJquery('#user_form_login').find('input[type="email"]').attr('placeholder','<?php echo $this->translate("Email Address"); ?>');
scriptJquery('#user_form_login').find('input[type="password"]').attr('placeholder','<?php echo $this->translate("Password"); ?>');
</script>
