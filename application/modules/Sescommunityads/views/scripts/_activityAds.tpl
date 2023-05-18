<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _activityAds.tpl  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>

<?php
  $view = Engine_Api::_()->authorization()->isAllowed('sescommunityads', null, 'view');
    if(!$view)
      return '';
  $valueAds['fetchAll'] = true;
  $valueAds['limit'] = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescommunityads.ads.count', 1);
  $valueAds["fromActivityFeed"] = true;
  $select = Engine_Api::_()->getDbTable('sescommunityads','sescommunityads')->getAds($valueAds);
  $paginator =  $select;
?>
<?php 
  $baseURL = $this->layout()->staticBaseUrl;
  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/owl-carousel/jquery.js');
  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/owl-carousel/owl.carousel.js'); 
  $timeAds = "sesads_comm_".time().md5(RAND(1,100000088));
  $actionOld = $action;
  if(engine_count($paginator)>0){
    $feedType = $paginator[0]['type'] == "boost_post_cnt" ? '' : 'class';
  }
?>
<ul id="<?php echo $timeAds; ?>" <?php if($feedType == "class") { ?> class="sesact_show_ads sesact_show_ads_<?php echo engine_count($paginator); ?>" <?php } ?>>
  <?php if(engine_count($paginator) > 0){ ?>
   <?php foreach($paginator as $ad){ 
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
    <?php if($ad->type == "promote_content_cnt" || $ad->type == "promote_website_cnt"){ ?>
    	<?php include('application/modules/Sescommunityads/views/scripts/widget-data/_promoteContent.php'); ?>
    <?php }else{ 
            $fromActivityFeed = 1;
            $_SESSION['fromActivityFeed'] = 1;
            $action = Engine_Api::_()->getItem('sesadvancedactivity_action',$ad->resources_id);
            include('application/modules/Sesadvancedactivity/views/scripts/_activity.tpl');
            $_SESSION['fromActivityFeed'] = 0;
            $fromActivityFeed = 0;
          } ?>
<?php    } ?>
<?php   }else{ ?>
  <script type="application/javascript">
    en4.core.runonce.add(function() {
      scriptJquery('#<?php echo $timeAds; ?>').parent().hide();
    }); 
  </script>
<?php   }
  $action = $actionOld;
 ?>
</ul>
