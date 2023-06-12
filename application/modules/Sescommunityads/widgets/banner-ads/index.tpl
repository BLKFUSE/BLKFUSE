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
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescommunityads/externals/scripts/core.js'); ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescommunityads/externals/styles/styles.css'); ?>
<?php if(empty($this->rented)) { ?>
<?php 
  $baseURL = $this->layout()->staticBaseUrl;
$this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/owl-carousel/jquery.js');
$this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/owl-carousel/owl.carousel.js'); 
?>
<div class="sescmads_browse_ads_listing sesbasic_bxs sescmads_banner_ads">
	<ul class="sescmads_ads_listing" id="sescomm_widget_<?php echo $randonNumber; ?>">
<?php foreach($this->paginator as $ad){ ?>
  <?php if($ad->type == "promote_content_cnt" || $ad->type == "promote_website_cnt"){ 
          if($ad->user_id != $this->viewer()->getIdentity()){
          
            $adsItem = Engine_Api::_()->getItem('sescommunityads',$ad->getIdentity());
            $adsItem->views_count++;
            $adsItem->save();
            
            $campaign = Engine_Api::_()->getItem('sescommunityads_campaign',$adsItem->campaign_id);
            $campaign->views_count++;
            $campaign->save();
            
            //insert in view table
            Engine_Api::_()->getDbTable('viewstats','sescommunityads')->insertrow($adsItem,$this->viewer());
            //insert campaign stats
            Engine_Api::_()->getDbTable('campaignstats','sescommunityads')->insertrow($adsItem,$this->viewer(),'view');
          } 
  ?>
    <?php include('application/modules/Sescommunityads/views/scripts/widget-data/_promoteContent.php'); ?>
  <?php } ?>
<?php } ?>
	</ul>
</div>
<script type="text/javascript">
  displayCommunityadsCarousel();
</script>
<?php } elseif(!empty($this->rented) && !empty($this->package_id) && $this->paginator->getTotalItemCount() == 0 && empty($this->widgetIdAds)) { ?>
  <div class="sesbasic_bxs sescomadbanr_rentedad_space">
  	<div class="sescomadbanr_rentedad_space_inner">
      <div class="sescomadbanr_rentedad_space_item" style="height:<?php echo $this->banner->height; ?>px;width:<?php echo $this->banner->width; ?>px;">
      	<img src="<?php echo Engine_Api::_()->sescomadbanr()->getFileUrl($this->defaultbanner); ?>" alt="" />
        <a href="<?php echo $this->url(array('action' => 'create', 'package_id' => $this->package_id, 'widgetid' => $this->identity),'sescommunityads_general',true); ?>" class="sesbasic_animation"><?php echo $this->translate("Create An Ad"); ?></a>
      </div>
    </div>
  </div>
<?php } elseif(!empty($this->rented) && !empty($this->package_id) && $this->paginator->getTotalItemCount() > 0) { ?>
<?php 
  $baseURL = $this->layout()->staticBaseUrl;
$this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/owl-carousel/jquery.js');
$this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/owl-carousel/owl.carousel.js'); 
?>
<div class="sescmads_browse_ads_listing sesbasic_bxs sescmads_banner_ads">
	<ul class="sescmads_ads_listing" id="sescomm_widget_<?php echo $randonNumber; ?>">
<?php foreach($this->paginator as $ad){ ?>
  <?php if($ad->type == "promote_content_cnt" || $ad->type == "promote_website_cnt"){ 
          if($ad->user_id != $this->viewer()->getIdentity()){
          
            $adsItem = Engine_Api::_()->getItem('sescommunityads',$ad->getIdentity());
            $adsItem->views_count++;
            $adsItem->save();
            
            $campaign = Engine_Api::_()->getItem('sescommunityads_campaign',$adsItem->campaign_id);
            $campaign->views_count++;
            $campaign->save();
            
            //insert in view table
            Engine_Api::_()->getDbTable('viewstats','sescommunityads')->insertrow($adsItem,$this->viewer());
            //insert campaign stats
            Engine_Api::_()->getDbTable('campaignstats','sescommunityads')->insertrow($adsItem,$this->viewer(),'view');
          } 
  ?>
    <?php include('application/modules/Sescommunityads/views/scripts/widget-data/_promoteContent.php'); ?>
  <?php } ?>
<?php } ?>
	</ul>
</div>
<script type="text/javascript">
  displayCommunityadsCarousel();
</script>
<?php } ?>
