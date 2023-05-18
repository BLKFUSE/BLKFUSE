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
<?php $ad = $this->ad; 
  $campaign = Engine_Api::_()->getItem('sescommunityads_campaign',$ad->campaign_id);
?>
<div class="sescmads_view_main sesbasic_bxs">
  <div class="sescmads_view_breadcrumbs">
    <a href="<?php echo $this->url(array('action'=>'manage'),'sescommunityads_general',true); ?>"><?php echo $this->translate("My Campaigns") ?></a>&nbsp;&raquo;
    <a href="<?php echo $this->url(array('action'=>'manageads','campaign_id'=>$campaign->getIdentity()),'sescommunityads_general',true); ?>"><?php echo $this->translate($campaign->getTitle()) ?></a>&nbsp;&raquo;
    <?php echo $this->translate($ad->getTitle() ? $ad->getTitle() : "") ?>
  </div>
	<div class="sescmads_view">
  	<div class="sescmads_view_header sesbasic_clearfix">
  		<div class="_adtitle"><?php echo $ad->getTitle(); ?></div>
    	<div class="_right">
      	<div class="_payment">
        	<?php echo  $this->content()->renderWidget('sescommunityads.payment-status',array('sescommunityad_id'=>$ad->getIdentity())); ?>
        </div>
        <?php if(($this->can_edit && strtotime($ad->startdate) > time()) || $this->can_delete){ ?>
       	 	<div class="sesbasic_pulldown_wrapper _option">
          	<a href="javascript:void(0);" class="sesbasic_button sesbasic_pulldown_toggle"><i class="fa fa-ellipsis-h"></i></a>
            <div class="sesbasic_pulldown_options">
              <ul class="_isicon">
                <li>
                  <?php if($this->can_edit && strtotime($ad->startdate) > time()){ ?>
                  <a href="<?php echo $this->url(array('action'=>'edit-ad','sescommunityad_id'=>$ad->getIdentity()),'sescommunityads_general',true); ?>"><i class="fa fa-edit"></i><?php echo $this->translate('SESCOMMEdit'); ?></a>
                  <?php } ?>
                </li>
                <li>
                  <?php if($this->can_delete){ ?>
                  <a class="openSmoothbox" href="<?php echo $this->url(array('action'=>'delete-ad','sescommunityad_id'=>$ad->getIdentity()),'sescommunityads_general',true); ?>"><i class="fa fa-trash"></i><?php echo $this->translate('SESCOMMDelete'); ?></a>
                  <?php } ?>
                </li>
              </ul>
            </div>
        	</div>
        <?php } ?>
      </div>
    </div>
    <div class="sescmads_view_main sesbasic_clearfix">
    	<div class="sescmads_view_content">
      	<div class="_adinfo">
          <?php $date = new Zend_Date(); ?>
          <div>
            <span><?php echo $this->translate('Status:'); ?></span>
            <span class="status_count">
              <?php if($ad->status == 0){
                echo $this->translate('Approval Pending');
              }else if($ad->status == 1){
                echo $this->translate('Approved/Running'); 
              }else if($ad->status == 2){
                echo $this->translate("Paused"); 
              }else if($ad->status == 3){
                echo $this->translate("Completed"); 
              }else if($ad->status == 4){
                echo $this->translate("Declined"); 
              }
              ?>
            </span>
          </div>
          <div>
            <span><?php echo $this->translate('Approved Date:'); ?></span>
            <span class="ad_Approved"><?php echo $ad->approved_date ? $this->locale()->toDate($date->set(strtotime($ad->approved_date)), array('size' => 'long')) : $this->translate("Approval Pending"); ?></span>
          </div>
          <div>
            <span><?php echo $this->translate('Start Date:'); ?></span>
            <span class="date_count"><?php echo $ad->startdate ? $this->locale()->toDate($date->set(strtotime($ad->startdate)), array('size' => 'long')) : $this->translate("N/A"); ?></span>
          </div>
          <div>
            <span><?php echo $this->translate('End Date:'); ?></span>
            <span class="date_count"><?php echo $ad->enddate && $ad->enddate != "0000-00-00 00:00:00" ? $this->locale()->toDate($date->set(strtotime($ad->enddate)), array('size' => 'long')) : $this->translate("Never ends") ?></span>
          </div>
          <div>
            <span><?php echo $this->translate('Ad Type:'); ?></span>
            <span class="ad_type"><?php echo $ad->adType(); ?></span>
          </div>
        </div>
        <div class="_adstats sesbasic_clearfix">
          <?php 
            $EXISTS = false;
          if(($ad->type == "promote_page_cnt" || $ad->type == "promote_content_cnt" || $ad->type == "boost_post_cnt") && !empty($ad->resources_type)){
            $item = Engine_Api::_()->getItem($ad->resources_type,$ad->resources_id);
            if($item && ($item->getType() == "sesadvancedactivity_action" || $item->getType() == "activity_action")){
              $item = Engine_Api::_()->getItem($item->object_type,$item->object_id);
            }
            if($item){
              if(!empty($item->like_count)){
                $like =  $item->like_count;
              }else{
                $like = 0;
              }
              $EXISTS = true;
            }else{
              //echo $this->translate("N/A");
            }
          }else{
            
          }
          ?>

          <div class="ad_views">
            <?php
            if(($ad->type == "promote_page_cnt" || $ad->type == "promote_content_cnt" || $ad->type == "boost_post_cnt") && !empty($ad->resources_type)){
              $item = Engine_Api::_()->getItem($ad->resources_type,$ad->resources_id);
              if($item && ($item->getType() == "sesadvancedactivity_action" || $item->getType() == "activity_action")){
                $detailsTable = Engine_Api::_()->getDbTable('details','sesadvancedactivity');
                $actionDetails = $detailsTable->isRowExists($item->action_id);
              }
             } ?>
            <span class="views_count">
              <?php echo !empty($actionDetails->view_count) ? $actionDetails->view_count : $ad->views_count;  ?>
            </span>
            <span class="views_label"><?php echo $this->translate('SESCOMMViews'); ?></span>            
          </div>
          <?php if($ad->type != "boost_post_cnt"){ ?>
          <div class="ad_clicks">
            <span class="clicks_count"><?php echo $ad->click_count;  ?></span>
            <span class="clicks_label"><?php echo $this->translate('SESCOMMClicks'); ?></span>
          </div>
          <div class="ad_ctr">
            <span class="ctr_count"><?php echo $ad->views_count > 0 ? $ad->ctr(): 0;  ?></span>
            <span class="ctr_label"><?php echo $this->translate('SESCOMMCTR'); ?></span>
          </div>
          <?php } ?>
          <div class="ad_likes"> 
            
            <?php if(!empty($EXISTS)){ ?>
            <span class="like_count"><?php echo $like; ?></span>
            <span class="like_label"> <?php echo $this->translate('SESCOMMLikes'); ?> </span> 
            <?php } ?>
            </div>
        </div>
    	</div>
      <div class="sescmads_view_preview">
        <?php if($ad->type == "promote_content_cnt" || $ad->type == "promote_website_cnt"){ ?>
          <ul class="sescmads_create_preview sescmads_create_preview_image">
            <?php include('application/modules/Sescommunityads/views/scripts/widget-data/_promoteContent.php'); ?>
          </ul>
        <?php }else{ ?>
        	<div class="sescmads_create_preview">
            <div class="sesact_feed sesbasic_bxs sesbasic_clearfix">
              <ul class="feed sesbasic_clearfix sesbasic_bxs ">
                <?php
                $widgetId = Engine_Api::_()->sescommunityads()->getIdentityWidget('sesadvancedactivity.feed','widget','user_index_home');
                if($widgetId){
                  $params = Engine_Api::_()->sescommunityads()->getWidgetParams($widgetId);
                  $this->userphotoalign = !empty($params['userphotoalign']) ? $params['userphotoalign'] :'left';
                }
                $fromActivityFeed = 1;
                $_SESSION['fromActivityFeed'] = 1;
                $action = Engine_Api::_()->getItem('sesadvancedactivity_action',$ad->resources_id);
                $attachmentShowCount = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.attachment.count',5);
                include('application/modules/Sesadvancedactivity/views/scripts/_activity.tpl');
                $_SESSION['fromActivityFeed'] = 0;
                $fromActivityFeed = 0; ?>
              </ul>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>
      




  </div>
</div>
<?php 
  $baseURL = $this->layout()->staticBaseUrl;
  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/owl-carousel/jquery.js');
  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/owl-carousel/owl.carousel.js'); 
?>
<script type="application/javascript">
    en4.core.runonce.add(function() {
        displayCommunityadsCarousel();
    });
</script>
