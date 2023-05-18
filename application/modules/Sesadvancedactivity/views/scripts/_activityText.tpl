<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: _activityText.tpl 2017-01-12 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php 
  if(empty($this->enabledModuleNames)){
    $this->enabledModuleNames = Engine_Api::_()->getDbTable('modules', 'core')->getEnabledModuleNames();
  }
?>
<?php 

$settings = Engine_Api::_()->getApi('settings', 'core');
$pintotop = $settings->getSetting('sesadvancedactivity.pintotop',1);
$sesAdvancedactivitytextlimit = $settings->getSetting('sesadvancedactivity.textlimit',120);
if( empty($this->actions) ) {
  $actions = array();
} else {
   $actions = $this->actions;
} 
$attachmentShowCount = $settings->getSetting('sesadvancedactivity.attachment.count',5);
?>

<?php

if( !$this->getUpdate && ($this->ulInclude)):
  $date = '';
 ?>
<div class="sesact_feed sesbasic_bxs sesbasic_clearfix">
  <ul class='feed sesbasic_clearfix sesbasic_bxs <?php echo $this->feeddesign == 2 ? "pinfeed prelative" : "";?>' id="activity-feed">
  <?php endif ?>
  <?php
    //google key
    $googleKey = $settings->getSetting('ses.mapApiKey', '');
    $languageTranslate = $settings->getSetting('sesadvancedactivity.language', 'en');
    $islanguageTranslate = 0;
    if($this->isMemberHomePage){
      $adsEnable = $settings->getSetting('sesadvancedactivity.adsenable', 0);
      $peopleymkEnable = $settings->getSetting('sesadvancedactivity.peopleymk', 1);
      $adsRepeat = $settings->getSetting('sesadvancedactivity.adsrepeatenable', 0);
      $pymkrepeatenable = $settings->getSetting('sesadvancedactivity.pymkrepeatenable', 0);
      $adsRepeatTime = $settings->getSetting('sesadvancedactivity.adsrepeattimes', 15);
      $peopleymkrepeattimes = $settings->getSetting('sesadvancedactivity.peopleymkrepeattimes', 5);
      $islanguageTranslate = $settings->getSetting('sesadvancedactivity.translate', 0);
      $contentCount = $this->contentCount;
    }
    
    if(defined('SESCOMMUNITYADS')){
      $communityAdsEnable = $settings->getSetting('sescommunityads_advertisement_enable', 1);
      $communityAdsDisplay = $settings->getSetting('sescommunityads_advertisement_display', 3);
      $communityAdsDisplayFeed = $settings->getSetting('sescommunityads_advertisement_displayfeed', 1);
      if(!$this->isMemberHomePage && !$communityAdsDisplayFeed)
        $communityAdsEnable = 0;
      $communityAdsDisplayAds = $settings->getSetting('sescommunityads_advertisement_displayads', 5);
    }
    
    foreach( $actions as $action ): //(goes to the end of the file)
      //ses communityads
      if(@$communityAdsEnable && ($contentCount && $contentCount%$communityAdsDisplayAds == 0)){ ?>
        <li class="sesbasic_clearfix sesact_community_ads sesact_pinfeed_hidden _photo<?php echo $this->userphotoalign; ?>">
           <?php
              $valueAds['communityAdsDisplay'] = $communityAdsDisplay;
              $valueAds['communityadsIds'] = $this->communityadsIds;
              include('application/modules/Sescommunityads/views/scripts/_activityAds.tpl'); 
           ?>
        </li>
      <?php
      }
      
      
     //google ads code start here
     if($this->isMemberHomePage && $adsEnable && ($contentCount && $contentCount%$adsRepeatTime == 0) && ($adsRepeat || (!$adsRepeat && $contentCount/$adsRepeatTime == 1))){
     ?>
     <li class="sesbasic_clearfix sesact_pinfeed_hidden sesact_ads_camp">
     <?php    
       $content =  $this->content()->renderWidget('sesadvancedactivity.ad-campaign');
       echo preg_replace('#<script(.*?)>(.*?)</script>#is', '', $content)
     ?>
     <script type="application/javascript">
     en4.core.runonce.add(function() {
        var url = '<?php echo $this->url(array('module' => 'core', 'controller' => 'utility', 'action' => 'advertisement'), 'default', true) ?>';
        var processClick = window.processClick = function(adcampaign_id, ad_id) {
          (scriptJquery.ajax({
            'format': 'json',
            'url' : url,
            'data' : {
              'format' : 'json',
              'adcampaign_id' : adcampaign_id,
              'ad_id' : ad_id
            }
          }))
        }
      });
     </script>
    </li>
    <?php
    }

    //People You may know plugin widget
    if($this->isMemberHomePage && engine_in_array('sespymk',$this->enabledModuleNames) && $peopleymkEnable && ($contentCount && $contentCount%$peopleymkrepeattimes == 0) && ($pymkrepeatenable || (!$pymkrepeatenable && $contentCount/$peopleymkrepeattimes == 1))){
    ?>
    <li class="sesbasic_clearfix sesact_ads_pymk sesact_pinfeed_hidden _photo<?php echo $this->userphotoalign; ?>">
    <?php
      echo $this->content()->renderWidget('sespymk.suggestion-carousel', array('showdetails' => array('friends', 'mutualfriends'), 'viewType' => 'horizontal', 'height' => '220', 'heightphoto' => '150', 'width' => '150', 'itemCount' => '15', 'anfheader' => 1, 'anffeed' => 1, 'page' => 1));
    ?>
    </li>
    <?php } 
    
    //google ads code end here
      try { // prevents a bad feed item from destroying the entire page
        // Moved to controller, but the items are kept in memory, so it should not hurt to double-check
        if( !$action->getTypeInfo()->enabled ) continue;
        if( !$action->getSubject() || !$action->getSubject()->getIdentity() ) continue;
        if( !$action->getObject() || !$action->getObject()->getIdentity() ) continue;
        
        ob_start();
      ?>
    <?php if($this->isOnThisDayPage){ ?>
     <?php if($date != $action->date){ ?>
      <li class="onthisday">
        <?php
          $date1=date_create(date('Y-m-d',strtotime($action->date)));
          $date2=date_create(date('Y-m-d'));
          $date_diff = date_diff($date1,$date2);
          if($date_diff == 1)
            $year = 'YEAR';
          else 
            $year = 'YEARS';
          echo $date_diff->y." ".$year." AGO TODAY";
        ?>
      </li>
    <?php } ?>
    <?php $date = $action->date; ?>
    <?php } ?>
    	<?php include('application/modules/Sesadvancedactivity/views/scripts/_activity.tpl'); ?>
  
  <?php
        @$contentCount++;
        ob_end_flush();
      } catch (Exception $e) {
        ob_end_clean();
        if( APPLICATION_ENV === 'development' ) {
          echo $e->__toString();
        }
      };
    endforeach;
  ?>
  
  <?php if( !$this->getUpdate  && ($this->ulInclude)): ?>
  </ul>
</div>
<?php endif ?>
