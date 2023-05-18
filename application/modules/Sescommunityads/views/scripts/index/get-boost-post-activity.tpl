<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: get-boost-post-activity.tpl  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesadvancedactivity/externals/styles/styles.css'); ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesadvancedcomment/externals/styles/styles.css'); ?>
<?php 
if(engine_count($this->data['activity'])){
foreach($this->data['activity'] as $action){ 
  $href = "";
      if(!Engine_Api::_()->sescommunityads()->getAllowedActivityType($action->type))
        continue;
?>
<?php 
    $attachment = $action->getFirstAttachment();
    if($attachment){
      $item = $attachment->item;
      $href = $item->getPhotoUrl();
    }
    if(empty($href)){
      //default boost post photo
      $href = Engine_Api::_()->sescommunityads()->getFileUrl(Engine_Api::_()->getApi('settings', 'core')->getSetting('sescommunityads_boost_default_adult'));
    }
?>

<li>
  <div class="_postitem_main">
    <div class="_postitem_cont">
      <div class="_thumb"> <img src="<?php echo $href; ?>" alt="" align="middle"> </div>
    </div>
    <div class="_options">
      <div class="_shortdes"> <?php echo strip_tags($action->getDescription()); ?> </div>
      <div class="_publish_date sesbasic_text_light"><?php echo $this->translate('Published by on %s',date('Y-m-d',strtotime($action->date))); ?></div>
    </div>
    <div class="_other_options">
      <div class="_reaction_count">
        <?php if($this->advComment){
                $like_count = 0; 
                $likesGroup = Engine_Api::_()->sesadvancedcomment()->likesGroup($action);   
                $commentCount = Engine_Api::_()->sesadvancedcomment()->commentCount($action);
                if($commentCount || engine_count($likesGroup['data'])){
                  ?>
          <?php  foreach($likesGroup['data'] as $type){ 
                            $like_count = $like_count + $type['total'];
                      ?>
          <span><i style="background-image:url(<?php echo Engine_Api::_()->sesadvancedcomment()->likeImage($type['type']);?>);"></i></span>
          <?php  } ?>
          <?php }else{ ?>
          <span><i style="background-image:url(<?php echo Engine_Api::_()->sesadvancedcomment()->likeImage(1);?>);"></i></span>
          <?php }
              } ?>
        <span><?php echo !empty($like_count) ? $like_count : $action->like_count; ?></span> </div>
      	<div class="_comment_count"> <i class="far fa-comments"></i><span><?php echo !empty($this->advComment) ? Engine_Api::_()->sesadvancedcomment()->commentCount($action) : $action->comment_count; ?></span> </div>
      <div class="_boost_btn">
      <?php 
        $post = "";
        $selected = !empty($this->selected) && $this->selected > 0 && $this->selected == $action->getIdentity() ;  
        if($selected)
          $post = 'Selected ';      
      ?>
        <button type="button" selected-rel="<?php echo $this->translate("Selected Boost Post"); ?>" unselected-rel="<?php echo $this->translate("Boost Post"); ?>" class="boost_post_sescomm <?php echo $selected ? 'sesboost_post_active' : ''; ?>" data-rel="<?php echo $action->getIdentity(); ?>"><?php echo $this->translate($post.'Boost Post'); ?></button>
      </div>
    </div>
  </div>
</li>
<?php }
  }else{
  ?>
    <div  class="tip"><span><?php echo $this->translate("You have not posted anything yet, Please post one."); ?></span></div>
  <?php }

 ?>
<script type="application/javascript">
 var activity_count = <?php echo sprintf('%d', $this->data['activityCount']) ?>;
var next_id = <?php echo sprintf('%d', $this->data['nextid']) ?>;
var subject_guid = '<?php echo $this->viewer()->getGuid(); ?>';
var endOfFeed = <?php echo ( $this->data['endOfFeed'] ? 'true' : 'false' ) ?>;
if( next_id > 0 && !endOfFeed ) {
  scriptJquery('#sescommunityads_boost_feed_viewmore').show();
  scriptJquery('#sescommunityads_boost_feed_loading').hide();
  if(scriptJquery('#sescommunityads_boost_feed_viewmore_link').length){
    scriptJquery('#sescommunityads_boost_feed_viewmore_link').off('click').on('click', function(event){
      event.preventDefault();
      activityViewMore(next_id, subject_guid);
    });
  }
} else {
  scriptJquery('#sescommunityads_boost_feed_viewmore').hide();
  scriptJquery('#sescommunityads_boost_feed_loading').hide();
}
 var activityViewMore = window.activityViewMore = function(next_id, subject_guid) {
  if( en4.core.request.isRequestActive() ) return;
  var hashTag = "";
  var url = '<?php echo $this->url(array('module' => 'sescommunityads', 'controller' => 'index', 'action' => 'get-boost-post-activity'), 'default', true) ?>';         
  document.getElementById('sescommunityads_boost_feed_viewmore').style.display = 'none';
  document.getElementById('sescommunityads_boost_feed_loading').style.display = '';
    var request = scriptJquery.ajax({
    dataType: 'html',
    url : url+"?hashtag="+hashTag+'&isOnThisDayPage=0&isMemberHomePage=0&subjectPage=<?php echo $this->viewer()->getGuid(); ?>',
    data : {
      format : 'html',
      'maxid' : next_id,
      'feedOnly' : true,
      'nolayout' : true,
      'subject' : subject_guid,
      'contentCount':scriptJquery('.sescmads_select_post').find("li").length,
      'filterFeed':'all',
    },
    evalScripts : true,
    success : function(responseHTML) {
      scriptJquery('#sescmads_select_post_overlay').remove();
      scriptJquery('.sescmads_select_post').append(responseHTML);
    }
  });
 
}
</script>
