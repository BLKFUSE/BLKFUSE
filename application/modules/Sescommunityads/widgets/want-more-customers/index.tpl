<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescommunityads/externals/styles/styles.css'); ?>

<div class="sescmads_advertise_box sesbasic_bxs">
  <div class="_sponsored_header"> <?php echo $this->translate("Want More Customers?"); ?> </div>
  <div class="_customers_main">
    <label><?php echo $this->translate("Create Advertisement"); ?></label>
    <div class="_user_img"> <?php echo $this->itemPhoto($this->viewer(), 'thumb.icon') ?> </div>
    <div class="_user_content"> <span><?php echo $this->translate("Get started by creating your advertisement here on our community and increase your reach."); ?></span>
      <button type="submit" onClick="submitSescommCreate()"><?php echo $this->translate("Create an Ad"); ?></button>
    </div>
  </div>
</div>
<script type="application/javascript">
function submitSescommCreate(){
  window.location.href = "<?php echo $this->url(array('action'=>'create'),'sescommunityads_general',true); ?>";  
}
</script>