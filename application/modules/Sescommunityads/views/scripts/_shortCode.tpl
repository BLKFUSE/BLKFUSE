<?php
$ad = $this->ad;
if($ad->type == "promote_content_cnt" || $ad->type == "promote_website_cnt"){
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
<div class="sescmads_shortcode_ads_display">
  <ul class="sescmads_ads_listing">
  	<?php include('application/modules/Sescommunityads/views/scripts/widget-data/_promoteContent.php'); ?>
  </ul>
</div>
<?php } ?>