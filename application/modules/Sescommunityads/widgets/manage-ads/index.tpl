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
<?php 
$randonNumber = $this->identity;
$this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescommunityads/externals/styles/styles.css'); ?>
<?php if(empty($this->is_ajax)){ ?>
	<div class="sescmads_campaign_view sesbasic_bxs">
<?php } ?>
<?php if($this->paginator->getTotalItemCount() > 0){ ?>
	<?php if(empty($this->is_ajax)){ ?>
  	<form method="post" id="sescommunityads_campaign_frm" onSubmit="return multiDelete()">
      <div id="sescommunityads_campaigns" class="sescmads_campaigns_details">
  <?php } ?>
      <?php foreach($this->paginator as $ad){ 
      	$date = new Zend_Date();
      ?>
        <div id="sescommunityads_tr_<?php echo $ad->getIdentity(); ?>" class="sescmads_camp_inner">
          <div id="title_sescomm_<?php echo $ad->getIdentity(); ?>" class="ad_information">
          	<span><input type="checkbox" name="camapign_delete_<?php echo $ad->getIdentity(); ?>"></span>
           	<div class="ad_status">
              <div class="_title"><a href="<?php echo $this->url(array('action' => 'view', 'ad_id' => $ad->getIdentity()),'sescommunityads_general',false); ?>"><?php echo $ad->getTitle(); ?></a></div>
              <div class="_info">
              <span>
                <span class="_label"><?php echo $this->translate('SESCOMMStatus:'); ?></span>
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
                </span>
                <span>
                  <span class="_label"><?php echo $this->translate('Approved Date:'); ?></span>
                  <span class="ad_Approved"><?php echo $ad->approved_date ? $this->locale()->toDate($date->set(strtotime($ad->approved_date)), array('size' => 'long')) : $this->translate("Approval Pending"); ?></span>
                </span>
                <span>
                  <span class="_label"><?php echo $this->translate('Start Date:'); ?></span>
                  <span class="date_count">: <?php echo $ad->startdate ? $date->set(strtotime($ad->startdate), 'long') : $this->translate("N/A"); ?></span>
                </span>
                 <span>
                    <span class="_label"><?php echo $this->translate('End Date:'); ?></span>
                    <span class="date_count"><?php echo $ad->enddate && $ad->enddate != "0000-00-00 00:00:00" ? $this->locale()->toDate($date->set(strtotime($ad->enddate)), array('size' => 'long')) : $this->translate("Never ends") ?></span>
                 </span>
                 <span>
                 <span class="_label"><?php echo $this->translate('Ad Type:'); ?></span>
                 		<span class="ad_type"><?php echo $ad->adType();  ?></span>
              	</span>
            	</div>
          	</div>
         	</div>
					<div class="ad_stats">
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
					<div class="make-payment">
						<?php echo $this->content()->renderWidget('sescommunityads.payment-status',array('sescommunityad_id'=>$ad->getIdentity())); ?>
        	</div>
          <div class="sesbasic_pulldown_wrapper _option">
          	<a href="javascript:void(0);" class="sesbasic_button sesbasic_pulldown_toggle"><i class="fa fa-ellipsis-h"></i></a>  
          	<div class="sesbasic_pulldown_options">
              <ul class="_isicon">
                <li><a href="<?php echo $this->url(array('action'=>'view','ad_id'=>$ad->getIdentity()),'sescommunityads_general',true); ?>"><i class="fa fa-eye"></i><?php echo $this->translate('SESCOMMView'); ?></a></li>
                <?php if($this->can_edit && strtotime($ad->startdate) > time()){ ?>
                	<li><a href="<?php echo $this->url(array('action'=>'edit-ad','sescommunityad_id'=>$ad->getIdentity()),'sescommunityads_general',true); ?>"><i class="fa fa-edit"></i><?php echo $this->translate('SESCOMMEdit'); ?></a></li>
                <?php } ?>
                <?php if($this->can_delete){ ?>
                	<li><a class="openSmoothbox" href="<?php echo $this->url(array('action'=>'delete','sescommunityad_id'=>$ad->getIdentity()),'sescommunityads_general',true); ?>"><i class="fa fa-trash"></i><?php echo $this->translate('SESCOMMDelete'); ?></a></li>
                <?php } ?>                                                                   
              </ul>
          	</div>
          </div>
      	</div>   
      <?php } ?>
  <?php if(empty($this->is_ajax)){ ?>
   </div>
  <?php } ?>
  <?php }else{ ?>
    <div class="tip">
      <span><?php echo $this->translate('No ads created in this campaign by you yet.'); ?></span>
    </div>
  <?php } ?>
 <?php if(empty($this->is_ajax) && $this->paginator->getTotalItemCount() > 0){ ?>
    <div class="sescmads_campaign_view_dlt_btn">
      <button type="submit" class="sescomm_campaign_del"><?php echo $this->translate('Delete Selected'); ?></button>
    </div>
  </form>
 <?php } ?>
 <?php if(empty($this->is_ajax)){ ?>
  <?php if($this->loadType == 'pagging'): ?>
    <div>
      <?php echo $this->paginationControl($this->paginator, null, array("_pagging.tpl", "sescommunityads"),array('identityWidget'=>$this->identity)); ?>
    </div>
  <?php endif;?>
  <?php if($this->loadType != 'pagging'): ?>
    <div class="sesbasic_load_btn" style="display:none;" id="view_more_<?php echo $this->identity;?>" onclick="viewMore_<?php echo $randonNumber; ?>();" >
      <a href="javascript:void(0);" class="sesbasic_animation sesbasic_link_btn" id="feed_viewmore_link_<?php echo $randonNumber; ?>"><i class="fa fa-sync"></i><span><?php echo $this->translate('View More');?></span></a>
    </div>  
    <div class="sesbasic_load_btn sesbasic_view_more_loading_<?php echo $randonNumber;?>" id="loading_image_<?php echo $randonNumber; ?>" style="display: none;"><span class="sesbasic_link_btn"><i class="fa fa-spinner fa-spin"></i></span></div>
  <?php endif;?>
</div>  
<script type="application/javascript">
function multiDelete(){
  return confirm("<?php echo $this->translate('Are you sure you want to delete selected Ads?');?>");
}
  <?php if($this->loadType == 'auto_load'){ ?>    
    scriptJquery( window ).load(function() {
      scriptJquery(window).scroll( function() {
				var containerId = '#sescommunityads_campaign_frm';
				if(typeof scriptJquery(containerId).offset() != 'undefined' && scriptJquery('#view_more_<?php echo $randonNumber; ?>').length > 0) {
					var hT = scriptJquery('#view_more_<?php echo $randonNumber; ?>').offset().top,
					hH = scriptJquery('#view_more_<?php echo $randonNumber; ?>').outerHeight(),
					wH = scriptJquery(window).height(),
					wS = scriptJquery(this).scrollTop();
					if ((wS + 30) > (hT + hH - wH) && scriptJquery('#view_more_<?php echo $randonNumber; ?>').css('display') == 'block') {
						document.getElementById('feed_viewmore_link_<?php echo $randonNumber; ?>').click();
					}
				}      
      });
    });
  <?php } ?>
</script>
<?php } ?>

<script type="application/javascript">
<?php if($this->loadType != 'pagging'){ ?>
    function viewMoreHide_<?php echo $randonNumber; ?>() {

      if (document.getElementById('view_more_<?php echo $randonNumber; ?>'))
      document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = "<?php echo ($this->paginator->count() == 0 ? 'none' : ($this->paginator->count() == $this->paginator->getCurrentPageNumber() ? 'none' : '' )) ?>";
    }
	 viewMoreHide_<?php echo $randonNumber; ?>();
    function viewMore_<?php echo $randonNumber; ?> (){
      scriptJquery('#view_more_<?php echo $randonNumber; ?>').hide();
      scriptJquery('#loading_image_<?php echo $randonNumber; ?>').show(); 
       scriptJquery.ajax({
        dataType: 'html',
        method: 'post',
        'url': en4.core.baseUrl + "widget/index/mod/sescommunityads/name/manage-ads",
        'data': {
          format: 'html',
          page: <?php echo $this->page + 1; ?>,    
          is_ajax : 1,
					campaign_id :'<?php echo $this->campaign_id; ?>',
          identity:'<?php echo isset($this->identity) ? $this->identity : "" ?>',
        },
        success: function(responseHTML) {
          scriptJquery('#sescommunityads_campaigns').append(responseHTML);
          scriptJquery('#loading_image_<?php echo $randonNumber; ?>').hide();
        }
      });
      return false;
    }
  <?php }else{ ?>
    function paggingNumber<?php echo $randonNumber; ?>(pageNum){
      scriptJquery('.sesbasic_loading_cont_overlay').css('display','block');
       (scriptJquery.ajax({
        dataType: 'html',
        method: 'post',
        'url': en4.core.baseUrl + "widget/index/mod/sescommunityads/name/manage-campaign",
        'data': {
          format: 'html',
					campaign_id :'<?php echo $this->campaign_id; ?>',
          page: pageNum,    
          is_ajax : 1,
          identity:'<?php echo isset($this->identity) ? $this->identity : "" ?>',
        },
        success: function(responseHTML) {
          scriptJquery('.sesbasic_loading_cont_overlay').hide();
          document.getElementById('sescommunityads_campaigns').innerHTML =  responseHTML;          
        }
      }));
      return false;
    }
  <?php } ?>
</script>
