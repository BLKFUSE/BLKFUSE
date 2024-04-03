<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sestutorial
 * @package    Sestutorial
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2017-10-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
<?php $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl'). 'application/modules/Sestutorial/externals/scripts/core.js'); ?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sestutorial/externals/styles/styles.css'); ?>

<div class="sestutorial_category_banner sestutorial_bxs">

  <?php 
  if($this->subject->colored_icon) {
    $banner = Engine_Api::_()->storage()->get($this->subject->colored_icon);
    if($banner) {
      $banner = $banner->getPhotoUrl();
  ?>
  <?php } } ?>
	<span class="sestutorial_category_banner_img" <?php if($banner) { ?> style="background-image:url(<?php echo $banner; ?>);" <?php } ?>></span>
  <div class="sestutorial_category_banner_cont">
  	<div>
      <div class="sestutorial_category_banner_cont_inner">
        <div class="sestutorial_category_banner_title"><?php echo $this->subject->title; ?></div>
        <div class="sestutorial_category_banner_des"><?php echo $this->subject->description; ?></div>
      </div>
    </div>
  </div>
</div>
