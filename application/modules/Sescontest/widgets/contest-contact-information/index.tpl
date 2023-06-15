<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/scripts/core.js'); ?>

<div class='sescontest_owner_contact_details sesbasic_clearfix sesbasic_bxs'>
  <ul>
    <?php if( engine_in_array('name',$this->info) && $this->subject->contest_contact_name): ?>
      <li class="sesbasic_clearfix sescontest_owner_contact_name">
      	<i class="far fa-user-circle sesbasic_text_light"></i>  
        <span><?php echo nl2br($this->subject->contest_contact_name) ?></span>
      </li>
    <?php endif ?>
    <?php if( engine_in_array('email',$this->info) && $this->subject->contest_contact_email): ?>
      <li class="sesbasic_clearfix" title='<?php echo $this->translate("Contact Email"); ?>'>
        <i class="far fa-envelope sesbasic_text_light"></i>  
        <span><a href='mailto:<?php echo $this->subject->contest_contact_email ?>' target="_blank" class="sesbasic_linkinherit"><?php echo $this->subject->contest_contact_email ?></a></span>
      </li>
    <?php endif ?>
    <?php if( engine_in_array('phone',$this->info) && $this->subject->contest_contact_phone): ?>
      <li class="sesbasic_clearfix" title='<?php echo $this->translate("Contact Phone Number"); ?>'>
        <i class="fa fa-mobile sesbasic_text_light"></i>
        <span><?php echo ($this->subject->contest_contact_phone) ?></span>
      </li>
    <?php endif ?>
    <li class="sescontest_owner_contact_social">
      <?php if( engine_in_array('facebook',$this->info) && $this->subject->contest_contact_facebook): ?>
        <a class="sesbasic_animation _facebook" target="_blank" href='<?php echo parse_url($this->subject->contest_contact_facebook, PHP_URL_SCHEME) === null ? 'https://' . $this->subject->contest_contact_facebook : $this->subject->contest_contact_facebook; ?>' title='<?php echo $this->translate("Facebook URL"); ?>'><i class="fab fa-facebook"></i></a>
      <?php endif ?>
      <?php if( engine_in_array('twitter',$this->info) && $this->subject->contest_contact_twitter): ?>
        <a class="sesbasic_animation _twitter" target="_blank" href='<?php echo parse_url($this->subject->contest_contact_twitter, PHP_URL_SCHEME) === null ? 'https://' . $this->subject->contest_contact_twitter : $this->subject->contest_contact_twitter; ?>' title='<?php echo $this->translate("Twitter URL"); ?>'><i class="fab fa-twitter"></i></a>
      <?php endif;?>
      <?php if( engine_in_array('linkedin',$this->info) && $this->subject->contest_contact_linkedin): ?>
        <a class="sesbasic_animation _linkedin" target="_blank" href='<?php echo parse_url($this->subject->contest_contact_linkedin, PHP_URL_SCHEME) === null ? 'https://' . $this->subject->contest_contact_linkedin : $this->subject->contest_contact_linkedin; ?>' title='<?php echo $this->translate("Linkedin URL"); ?>'><i class="fab fa-linkedin"></i></a>
      <?php endif;?>
      <?php if( engine_in_array('website',$this->info) && $this->subject->contest_contact_website): ?>
        <a class="sesbasic_animation" target="_blank" href='<?php echo parse_url($this->subject->contest_contact_website, PHP_URL_SCHEME) === null ? 'http://' . $this->subject->contest_contact_website : $this->subject->contest_contact_website; ?>' title='<?php echo $this->translate("Website URL"); ?>'><i class="fa fa-globe"></i></a>
      <?php endif ?>
    </li>
  </ul>
</div>
