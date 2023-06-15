<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: _subjectfeedtabs.tpl 2017-01-12 00:00:00 socialnetworking.solutions $
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

    var url = '<?php echo $this->url(array('module' => 'core', 'controller' => 'widget', 'action' => 'index', 'content_id' => $this->identity), 'default', true) ?>';
    var hashTag = scriptJquery('#hashtagtextsesadv').val();  
    var adsIds = scriptJquery('.sescmads_ads_listing_item');
    var adsIdString = "";
    if(adsIds.length > 0){
       scriptJquery('.sescmads_ads_listing_item').each(function(index){
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
          'action_id':sesAdvancedActivityGetAction_id,
          'getUpdates':1,
        'nolayout' : true,
        'ads_ids': adsIdString,
        'subject' : en4.core.subject.guid,
      },
      evalScripts : true,
      success : function( responseHTML) {

          if(!sesAdvancedActivityGetFeeds){
              scriptJquery('#activity-feed').append(responseHTML);
          }else{
              scriptJquery('#activity-feed').html(responseHTML);
          }
        if(scriptJquery('#activity-feed').find('li').length > 0)
         scriptJquery('.sesadv_noresult_tip').hide();
        else
         scriptJquery('.sesadv_noresult_tip').show();
        //initialize feed autoload counter
        counterLoadTime = 0;
        sesadvtooltip();
        initSesadvAnimation();
        Smoothbox.bind(document.getElementById('activity-feed'));
        scriptJquery('.sesadvancedactivity_filter_img').hide();
          activateFunctionalityOnFirstLoad();
      }
    });
 });
</script>
<?php 
  $lists = $this->lists;
 ?>
<div class="sesact_feed_filters mprofile_filter_tabs sesbasic_clearfix sesbasic_bxs sesbm" style="display: none;">
  <ul class="sesadvancedactivity_filter_tabs sesbasic_clearfix">
    <li style="display:none;" class="sesadvancedactivity_filter_img"><i class='fas fa-circle-notch fa-spin'></i></li>
    
    <li class="sesadvancedactivity_filter_tabsli"><a href="javascript:;" data-src="<?php echo 'own'; ?>"><?php echo strlen($this->subject()->getTitle()) > 20 ? $this->string()->truncate($this->subject()->getTitle(),20).'...' : $this->subject()->getTitle(); ?></a></li>
    
    <?php 
     $counter = 1;
     foreach($lists as $activeList){ 
       if(@$activeList['filtertype'] == 'all' || @$activeList['filtertype'] == 'post_self_buysell' || @$activeList['filtertype'] == 'post_self_file')
        {
     ?>
      <li class="sesadvancedactivity_filter_tabsli">
        <a href="javascript:;" data-src="<?php echo @$activeList['filtertype']; ?>"><?php echo $this->translate(@$activeList['title']); ?>
          <i class="fa <?php echo $activeList['icon']; ?>"></i>
        </a></li>
     <?php 
      }
     } ?>
   <?php if($this->subject() && method_exists($this->subject(),'approveAllowed') && method_exists($this->subject(),'canApproveActivity') && $this->subject()->canApproveActivity($this->subject()) ){
    $approveAllowed = $this->subject()->approveAllowed();
    if($approveAllowed){
   ?>
   <li class="sesadvancedactivity_filter_tabsli"><a href="javascript:;" data-src="<?php echo 'unapprovedfeed'; ?>"><?php echo $this->translate("Un-Approved Feeds"); ?></a></li>
   <?php }
    }
    ?>
   
   <?php if($this->viewer()->getIdentity() && $this->subject()->getGuid() == $this->viewer()->getGuid()){ ?>
     <li class="sesadvancedactivity_filter_tabsli"><a href="javascript:;" data-src="hiddenpost"><?php echo $this->translate("Posts You've Hidden"); ?></a></li>
     <li class="sesadvancedactivity_filter_tabsli"><a href="javascript:;" data-src="taggedinpost"><?php echo $this->translate("Posts You're Tagged In"); ?></a></li>
   <?php } ?>
  </ul>
</div>
<script type="application/javascript">
scriptJquery(document).ready(function(e){
  var elem = scriptJquery('.sesadvancedactivity_filter_tabs').children();
  if(elem.length == 2){
      scriptJquery('.sesact_feed_filters').hide();
  }else{
    scriptJquery(elem).eq(1).addClass('active');  
  }
});
</script>
