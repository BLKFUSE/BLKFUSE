<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: _homefeedtabs.tpl 2017-01-12 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<script type="application/javascript">
var filterResultrequest;
 scriptJquery(document).on('click','ul.sesadvancedactivity_filter_tabs li a',function(e){

   if(scriptJquery(this).hasClass('viewmore'))
    return false;
   scriptJquery('.sesadvancedactivity_filter_img').show();
   scriptJquery('.sesadvancedactivity_filter_tabsli').removeClass('active sesadv_active_tabs');
   scriptJquery(this).parent().addClass('active sesadv_active_tabs');
   var filterFeed = scriptJquery(this).attr('data-src');
   //if(typeof filterResultrequest != 'undefined')
    //filterResultrequest.remove();
    var url = '<?php echo $this->url(array('module' => 'core', 'controller' => 'widget', 'action' => 'index', 'content_id' => $this->identity), 'default', true) ?>';
    var hashTag = scriptJquery('#hashtagtextsesadv').val();
    
    var adsIds = scriptJquery('.sescmads_ads_listing_item');
    var adsIdString = "";
    if(adsIds.length > 0){
       scriptJquery('.sescmads_ads_listing_item').each(function(index){
         var dataFeedItem = scriptJquery(this).attr('data-activity-feed-item');
         if(typeof dataFeedItem == "undefined")
          adsIdString = scriptJquery(this).attr('rel')+ "," + adsIdString ;
       });
    }
      
    filterResultrequest = scriptJquery.ajax({
      url : url+"?hashtag="+hashTag+'&isOnThisDayPage='+isOnThisDayPage+'&isMemberHomePage='+isMemberHomePage,
      type: "POST",
      data : {
        format : 'html',
        'filterFeed' : filterFeed,
        'feedOnly' : true,
        'ads_ids': adsIdString,
          'getUpdates':1,
        'nolayout' : true,
        'subject' : '<?php echo !empty($this->subjectGuid) ? $this->subjectGuid : "" ?>',
      },
      evalScripts : true,
      success : function( responseHTML) {
        if(!sesAdvancedActivityGetFeeds){
            scriptJquery('#activity-feed').append(responseHTML);
        }else{
            scriptJquery('#activity-feed').html(responseHTML);
        }
        if(scriptJquery('#activity-feed').find('li').length > 0){
          scriptJquery('.sesadv_noresult_tip').hide();
          if(scriptJquery('#feed_viewmore').css('display') == 'none' && scriptJquery('#feed_loading').css('display') == 'none')
            scriptJquery('#feed_no_more_feed').show();
        }else{
          scriptJquery('#feed_no_more_feed').hide();
          scriptJquery('.sesadv_noresult_tip').css('display','block');
        }
        //initialize feed autoload counter
        counterLoadTime = 0;
        sesadvtooltip();
        Smoothbox.bind(document.getElementById('activity-feed'));
        scriptJquery('.sesadvancedactivity_filter_img').hide();
        initSesadvAnimation();
        feedUpdateFunction();
          activateFunctionalityOnFirstLoad();
      }
    });
 });

</script>
<?php 
  $filterViewMoreCount = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.visiblesearchfilter',5);
  $lists = $this->lists;
 ?>
<div class="sesact_feed_filters sesbasic_clearfix sesbasic_bxs sesbm" style="display: none;">
  <ul class="sesadvancedactivity_filter_tabs sesbasic_clearfix">
    <li style="display:none;" class="sesadvancedactivity_filter_img"><i class='fas fa-circle-notch fa-spin'></i></li>
   <?php 
   $counter = 1;
   $netwrokStarted = false;
   $listStarted = false;
   $listsCount = engine_count($lists);
   foreach($lists as $activeList){
    if($counter > $filterViewMoreCount)
      break;
    if(isset($activeList['network_id'])){
      if(!$netwrokStarted){  $netwrokStarted = true; ?>
        <li class="_sep sesbm"></li>
     <?php
      } ?>
    <li class="sesadvancedactivity_filter_tabsli <?php echo $counter == 1 ? 'active sesadv_active_tabs' : ''; ?>"><a href="javascript:;" class="sesadv_tooltip" data-src="<?php echo 'network_filter_'.$activeList['network_id']; ?>" title="<?php echo $this->translate($activeList['title']); ?>">
      <i class="fa <?php echo $activeList['icon']; ?>"></i>
      <span><?php echo $this->translate($activeList['title']); ?></span>
    </a></li>
   <?php   
    }else if(isset($activeList['list_id'])){
    
      if(!$listStarted){  $listStarted = true; ?>
        <li class="_sep sesbm"></li>
     <?php
      } ?>
      <li class="sesadvancedactivity_filter_tabsli <?php echo $counter == 1 ? 'active sesadv_active_tabs' : ''; ?>"><a href="javascript:;" class="sesadv_tooltip" data-src="<?php echo 'member_list_'.$activeList['list_id']; ?>" title="<?php echo $this->translate($activeList['title']); ?>">
        <i class="fa <?php echo $activeList['icon']; ?>"></i>
        <span><?php echo $this->translate($activeList['title']); ?></span>
    </a></li>
   <?php   
    }else{
    ?>
   
    <li class="sesadvancedactivity_filter_tabsli <?php echo $counter == 1 ? 'active sesadv_active_tabs' : ''; ?>"><a href="javascript:;" class="sesadv_tooltip" data-src="<?php echo $activeList['filtertype']; ?>" title="<?php echo $this->translate($activeList['title']); ?>">
      <i class="fa <?php echo $activeList['icon']; ?>"></i>
      <span><?php echo $this->translate($activeList['title']); ?></span></a></li>
   
   <?php 
   }
    ++$counter;
   } ?>
   <?php if($listsCount > $filterViewMoreCount){ ?>
    <li class="sesact_feed_filter_more sesact_pulldown_wrapper">
    	<a href="javascript:;" class="viewmore"><?php echo $this->translate("More"); ?>&nbsp;<i class="fa fa-angle-down"></i></a>
    	<div class="sesact_pulldown">
				<div class="sesact_pulldown_cont isicon">
        	<ul>
          <?php 
           $counter = 1;
           foreach($lists as $activeList){
            if($counter <= $filterViewMoreCount){
              ++$counter;
              continue;
             }
             if(isset($activeList['network_id'])){
                if(!$netwrokStarted){ $netwrokStarted = true; ?>
                  <li class="_sep sesbm"></li>
               <?php
                } ?>
              <li class="sesadvancedactivity_filter_tabsli"><a href="javascript:;" data-src="<?php echo 'network_filter_'.$activeList['network_id']; ?>">
                <i class="fa <?php echo $activeList['icon'] ?  $activeList['icon'] : 'fas fa-network-wired'; ?>"></i>
                <?php echo $this->translate($activeList['title']); ?></a></li>
             <?php   
              }else if(isset($activeList['list_id'])){
                if(!$listStarted){ $listStarted = true; ?>
                  <li class="_sep sesbm"></li>
               <?php
                } ?>
              <li class="sesadvancedactivity_filter_tabsli"><a href="javascript:;" data-src="<?php echo 'member_list_'.$activeList['list_id']; ?>">            <i class="fa <?php echo $activeList['icon']; ?>"></i>
                <?php echo $this->translate($activeList['title']); ?></a></li>
             <?php   
              }else{
            ?>
            <li class="sesadvancedactivity_filter_tabsli"><a href="javascript:;" data-src="<?php echo $activeList['filtertype']; ?>">
              <i class="fa <?php echo $activeList['icon']; ?>"></i>
              <?php echo $this->translate($activeList['title']); ?></a></li>
           <?php 
              }
           } ?>
           <!-- <li class="_sep sesbm"></li>-->
        	</ul>
        </div>													
      </div>
    </li>
    <?php if($this->viewer()->getIdentity()){ ?>
    <li class="sesadvancedactivity_filter_tabsli sesact_feed_filter_setting"><a href="javascript:;" class="sessmoothbox viewmore sesadv_tooltip " title="<?php echo $this->translate('Settings');?>" data-url="sesadvancedactivity/ajax/settings/"><i class="fa fa-cog" aria-hidden="true"></i></a></li> 
    <?php } ?>
  <?php } ?>  
    
  </ul>
</div>
