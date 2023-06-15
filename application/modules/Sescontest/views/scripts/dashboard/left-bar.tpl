<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: left-bar.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/scripts/core.js'); ?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/dashboard.css'); ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/styles/styles.css'); ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/datepicker/jquery.timepicker.css'); ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/datepicker/bootstrap-datepicker.css'); ?>

<?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescontestjoinfees')): ?>
	<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescontestjoinfees/externals/styles/styles.css'); ?>
<?php endif; ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/jquery1.11.js'); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/datepicker/jquery.timepicker.js'); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/datepicker/bootstrap-datepicker.js'); ?>
<?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescontestpackage') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontestpackage.enable.package', 0) && isset($this->contest->package_id) && $this->contest->package_id ){ 
  $package = Engine_Api::_()->getItem('sescontestpackage_package', $this->contest->package_id);
  $params = json_decode($package->params,true);	
 } ?>
<?php $privacy = $this->contest->authorization();?>
<?php $viewer = $this->viewer();?>
<div class="layout_middle">
  <div class=" sescontest_dashboard_main_nav generic_layout_container ">
   <?php echo $this->content()->renderWidget('sescontest.browse-menu',array('dashboard'=>true)); ?> 
  </div>
</div>
<div class="layout_middle sescontest_dashboard_container">
<div class="sesbasic_dashboard_container sesbasic_clearfix generic_layout_container ">
  <div class="sesbasic_dashboard_top_section sesbasic_clearfix sesbm">
    <div class="sesbasic_dashboard_top_section_left">
      <div class="sesbasic_dashboard_top_section_item_photo"> <?php echo $this->htmlLink($this->contest->getHref(), $this->itemPhoto($this->contest, 'thumb.icon')) ?> </div>
      <div class="sesbasic_dashboard_top_section_item_title"> <?php echo $this->htmlLink($this->contest->getHref(),$this->contest->getTitle()); ?> </div>
    </div>
    <div class="sesbasic_dashboard_top_section_btns">
      <a href="<?php echo $this->contest->getHref(); ?>" class="sesbasic_link_btn"><?php echo $this->translate("View Contest"); ?></a>
      <?php if($privacy->isAllowed($viewer, 'delete')){ ?>
        <a href="<?php echo $this->url(array('contest_id' => $this->contest->contest_id,'action'=>'delete'), 'sescontest_general', true); ?>" class="sesbasic_link_btn smoothbox"><?php echo $this->translate("Delete Contest"); ?></a>
      <?php } ?>
    </div>
  </div>
  <div class="sesbasic_dashboard_tabs sesbasic_bxs">
    <?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescontestpackage') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontestpackage.enable.package', 0)) { ?>
    	<div class="sescontest_db_package_info sesbasic_clearfix"><?php echo $this->content()->renderWidget('sescontestpackage.contest-renew-button',array('contest'=>$this->contest)); ?></div>
    <?php } ?> 
    <ul class="sesbm">
    	<li class="sesbm">
      <?php $manage_contest = Engine_Api::_()->getDbtable('dashboards', 'sescontest')->getDashboardsItems(array('type' => 'manage_contest')); ?>
      <a href="#Manage" class="sesbasic_dashboard_nopropagate"> <i class="tab-icon db_icon_contest"></i> <i class="tab-arrow fa fa-caret-down sesbasic_text_light"></i> <span><?php echo $this->translate($manage_contest->title); ?></span> </a>
      <?php $edit_contest = Engine_Api::_()->getDbtable('dashboards', 'sescontest')->getDashboardsItems(array('type' => 'edit_contest')); ?>
      <?php $edit_photo = Engine_Api::_()->getDbtable('dashboards', 'sescontest')->getDashboardsItems(array('type' => 'edit_photo')); ?>
       <?php $upgrade = Engine_Api::_()->getDbtable('dashboards', 'sescontest')->getDashboardsItems(array('type' => 'upgrade')); ?>
      <?php $awards = Engine_Api::_()->getDbtable('dashboards', 'sescontest')->getDashboardsItems(array('type' => 'award')); ?>
      <?php $rules = Engine_Api::_()->getDbtable('dashboards', 'sescontest')->getDashboardsItems(array('type' => 'rule')); ?>
      <?php $participants = Engine_Api::_()->getDbtable('dashboards', 'sescontest')->getDashboardsItems(array('type' => 'participant')); ?>
      <?php $contact_information = Engine_Api::_()->getDbtable('dashboards', 'sescontest')->getDashboardsItems(array('type' => 'contact_information')); ?>
      <?php $seo = Engine_Api::_()->getDbtable('dashboards', 'sescontest')->getDashboardsItems(array('type' => 'seo')); ?>
      <?php $style = Engine_Api::_()->getDbtable('dashboards', 'sescontest')->getDashboardsItems(array('type' => 'style')); ?>
      <?php $overview = Engine_Api::_()->getDbtable('dashboards', 'sescontest')->getDashboardsItems(array('type' => 'overview')); ?>
      <?php $backgroundphoto = Engine_Api::_()->getDbtable('dashboards', 'sescontest')->getDashboardsItems(array('type' => 'backgroundphoto')); ?>
	  <ul class="sesbm">	
        <?php if($edit_contest->enabled): ?>
        <li><a href="<?php echo $this->url(array('contest_id' => $this->contest->custom_url), 'sescontest_dashboard', true); ?>" class="dashboard_a_link"><i class="fa fa-edit"></i><?php echo $this->translate($edit_contest->title); ?></a></li>
        <?php endif; ?>
        <?php if((!empty($upgrade) && $upgrade->enabled && isset($params))): ?>
            <li><a href="<?php echo $this->url(array('contest_id' => $this->contest->custom_url,'action'=>'upgrade'), 'sescontest_dashboard', true); ?>" class="dashboard_a_link" ><i class="fa fa-sync"></i> <?php echo $this->translate($upgrade->title); ?></a></li>
        <?php endif; ?>
        <?php if($edit_photo->enabled): ?>
          <?php if(isset($params) && $params['upload_mainphoto']):?>
            <li><a href="<?php echo $this->url(array('contest_id' => $this->contest->custom_url,'action'=>'mainphoto'), 'sescontest_dashboard', true); ?>" class="dashboard_a_link"><i class="fa fa-camera"></i><?php echo $this->translate($edit_photo->title); ?></a></li>
          <?php elseif(!isset($params) && Engine_Api::_()->authorization()->isAllowed('contest', $viewer, 'upload_mainphoto')):?>
            <li><a href="<?php echo $this->url(array('contest_id' => $this->contest->custom_url,'action'=>'mainphoto'), 'sescontest_dashboard', true); ?>" class="dashboard_a_link"><i class="fa fa-camera"></i><?php echo $this->translate($edit_photo->title); ?></a></li>
          <?php endif;?>
        <?php endif; ?>
        <?php if($awards->enabled): ?>
          <li><a href="<?php echo $this->url(array('contest_id' => $this->contest->custom_url,'action'=>'award'), 'sescontest_dashboard', true); ?>" class="dashboard_a_link"><i class="fa fa-trophy"></i><?php echo $this->translate($awards->title); ?></a></li>
        <?php endif; ?>
        <?php if($rules->enabled): ?>
          <li><a href="<?php echo $this->url(array('contest_id' => $this->contest->custom_url,'action'=>'rules'), 'sescontest_dashboard', true); ?>" class="dashboard_a_link"><i class="far fa-file-alt"></i><?php echo $this->translate($rules->title); ?></a></li>
        <?php endif; ?>
        <?php if($contact_information->enabled): ?>
          <?php if(isset($params) && $params['contest_contactinfo']):?>
            <li><a href="<?php echo $this->url(array('contest_id' => $this->contest->custom_url,'action'=>'contact-information'), 'sescontest_dashboard', true); ?>" class="sesbasic_dashboard_nopropagate_content dashboard_a_link"><i class="fa fa-envelope "></i><?php echo $this->translate($contact_information->title); ?></a></li>
          <?php elseif(!isset($params) && Engine_Api::_()->authorization()->isAllowed('contest', $viewer, 'contactinfo')):?>
            <li><a href="<?php echo $this->url(array('contest_id' => $this->contest->custom_url,'action'=>'contact-information'), 'sescontest_dashboard', true); ?>" class="sesbasic_dashboard_nopropagate_content dashboard_a_link"><i class="fa fa-envelope "></i><?php echo $this->translate($contact_information->title); ?></a></li>
          <?php endif;?>
        <?php endif; ?>
        <?php if($seo->enabled): ?>
          <?php if(isset($params) && $params['contest_seo']):?>
            <li><a href="<?php echo $this->url(array('contest_id' => $this->contest->custom_url, 'action'=>'seo'), 'sescontest_dashboard', true); ?>" class="sesbasic_dashboard_nopropagate_content dashboard_a_link"><i class="fas fa-file-alt"></i><?php echo $this->translate($seo->title); ?></a></li>
          <?php elseif(!isset($params) && Engine_Api::_()->authorization()->isAllowed('contest', $viewer, 'contest_seo')):?>
            <li><a href="<?php echo $this->url(array('contest_id' => $this->contest->custom_url, 'action'=>'seo'), 'sescontest_dashboard', true); ?>" class="sesbasic_dashboard_nopropagate_content dashboard_a_link"><i class="fas fa-file-alt"></i><?php echo $this->translate($seo->title); ?></a></li>
          <?php endif;?>
        <?php endif; ?>
        <?php if(@$style->enabled): ?>
        <li><a  href="<?php echo $this->url(array('contest_id' => $this->contest->custom_url, 'action'=>'style'), 'sescontest_dashboard', true); ?>" class="sesbasic_dashboard_nopropagate_content dashboard_a_link"><i class="fas fa-pencil-alt "></i><?php echo $this->translate($style->title); ?></a></li>
        <?php endif; ?>
        <?php if(@$overview->enabled): ?>
          <?php if(isset($params) && $params['contest_overview']):?>
            <li><a class="dashboard_a_link" href="<?php echo $this->url(array('contest_id' => $this->contest->custom_url, 'action'=>'overview'), 'sescontest_dashboard', true); ?>"><i class="fas fa-file-alt"></i><?php echo $this->translate($overview->title); ?></a></li>
          <?php elseif(!isset($params) && Engine_Api::_()->authorization()->isAllowed('contest', $viewer, 'contest_overview')):?>
            <li><a class="dashboard_a_link" href="<?php echo $this->url(array('contest_id' => $this->contest->custom_url, 'action'=>'overview'), 'sescontest_dashboard', true); ?>"><i class="fas fa-file-alt"></i><?php echo $this->translate($overview->title); ?></a></li>
          <?php endif;?>
        <?php endif; ?>
        <?php if(@$backgroundphoto->enabled): ?>
          <?php if(isset($params) && $params['contest_bgphoto']):?>
            <li><a class="dashboard_a_link" href="<?php echo $this->url(array('contest_id' => $this->contest->custom_url, 'action'=>'backgroundphoto'), 'sescontest_dashboard', true); ?>" ><i class="fas fa-image"></i><?php echo $this->translate($backgroundphoto->title); ?></a></li>
          <?php elseif(!isset($params) && Engine_Api::_()->authorization()->isAllowed('contest', $viewer, 'contest_bgphoto')):?>
            <li><a class="dashboard_a_link" href="<?php echo $this->url(array('contest_id' => $this->contest->custom_url, 'action'=>'backgroundphoto'), 'sescontest_dashboard', true); ?>" ><i class="fas fa-image"></i><?php echo $this->translate($backgroundphoto->title); ?></a></li>
          <?php endif;?>
        <?php endif; ?>
        <?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescontestjurymember') && isset($this->contest->audience_type) && $this->contest->audience_type != 1):?>
          <?php $juryMember = Engine_Api::_()->getDbtable('dashboards', 'sescontest')->getDashboardsItems(array('type' => 'jury_member')); ?>
          <?php if(isset($params) && $params['can_add_jury'] && @$juryMember->enabled): ?>
            <li><a class="dashboard_a_link" href="<?php echo $this->url(array('contest_id' => $this->contest->custom_url, 'action'=>'jury-members'), 'sescontest_dashboard', true); ?>" ><i class="fa fa-users"></i><?php echo $this->translate($juryMember->title); ?></a></li>
          <?php elseif(!isset($params) && Engine_Api::_()->authorization()->isAllowed('contest', $viewer, 'can_add_jury') && @$juryMember->enabled):?>
            <li><a class="dashboard_a_link" href="<?php echo $this->url(array('contest_id' => $this->contest->custom_url, 'action'=>'jury-members'), 'sescontest_dashboard', true); ?>" ><i class="fa fa-users"></i><?php echo $this->translate($juryMember->title); ?></a></li>
          <?php endif;?>
        <?php endif;?>
      </ul>
    </li>  
    <?php if((isset($params) && $params['contest_enable_contactparticipant']) || (!isset($params))):?>
      <li class="sesbm">  
      <?php $manage_participant = Engine_Api::_()->getDbtable('dashboards', 'sescontest')->getDashboardsItems(array('type' => 'participant')); ?>
      <a href="#Manage" class="sesbasic_dashboard_nopropagate"> <i class="tab-icon db_icon_participant"></i> <i class="tab-arrow fa fa-caret-down sesbasic_text_light"></i> <span><?php echo $this->translate($manage_participant->title); ?></span> </a>
      <?php $contact_all_participant = Engine_Api::_()->getDbtable('dashboards', 'sescontest')->getDashboardsItems(array('type' => 'contact_participants')); ?>
      <?php $contact_winners = Engine_Api::_()->getDbtable('dashboards', 'sescontest')->getDashboardsItems(array('type' => 'contact_winners')); ?>
        <ul class="sesbm">	
          <?php if(@$contact_all_participant->enabled && $this->contest->joinstarttime <= time()): ?>
              <li><a class="dashboard_a_link" href="<?php echo $this->url(array('contest_id' => $this->contest->custom_url, 'action'=>'contact-participants'), 'sescontest_dashboard', true); ?>" ><i class="fa fa-users"></i><?php echo $this->translate($contact_all_participant->title); ?></a></li>
          <?php endif;?>
          <?php if(@$contact_winners->enabled && $this->contest->process): ?>
            <li><a class="dashboard_a_link" href="<?php echo $this->url(array('contest_id' => $this->contest->custom_url, 'action'=>'contact-winners'), 'sescontest_dashboard', true); ?>" ><i class="fas fa-star"></i><?php echo $this->translate($contact_winners->title); ?></a></li>
          <?php endif;?>
        </ul>	
      </li>
    <?php endif;?>
    <?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescontestjoinfees')): ?>
    <li class="sesbm">
      <?php $tickets = Engine_Api::_()->getDbtable('dashboards', 'sescontest')->getDashboardsItems(array('type' => 'entry_fees_paid_sespaidext')); ?>
      <?php if(isset($tickets)):?>
        <a href="#Order" class="sesbasic_dashboard_nopropagate"> <i class="tab-icon db_manage_fees"></i> <i class="tab-arrow fa fa-caret-down sesbasic_text_light"></i> <span><?php echo $this->translate($tickets->title); ?></span> </a>
      <?php endif;?>
      <?php $create_fees = Engine_Api::_()->getDbtable('dashboards', 'sescontest')->getDashboardsItems(array('type' => 'create_feed_sespaidext')); ?>
      <?php $account_details = Engine_Api::_()->getDbtable('dashboards', 'sescontest')->getDashboardsItems(array('type' => 'account_details_sespaidext')); ?>
      <?php $sales_statistics = Engine_Api::_()->getDbtable('dashboards', 'sescontest')->getDashboardsItems(array('type' => 'sales_statistics_sespaidext')); ?>
      <?php $manage_orders = Engine_Api::_()->getDbtable('dashboards', 'sescontest')->getDashboardsItems(array('type' => 'manage_orders_sespaidext')); ?>
      <?php $sales_orders = Engine_Api::_()->getDbtable('dashboards', 'sescontest')->getDashboardsItems(array('type' => 'sales_orders_sespaidext')); ?>
      <?php $payment_requests = Engine_Api::_()->getDbtable('dashboards', 'sescontest')->getDashboardsItems(array('type' => 'payment_requests_sespaidext')); ?>
      <?php $payment_transactions = Engine_Api::_()->getDbtable('dashboards', 'sescontest')->getDashboardsItems(array('type' => 'payment_transactions_sespaidext')); ?>
      <ul class="sesbm">
      	<?php if($create_fees->enabled): ?>
        <li> <a class="dashboard_a_link" href="<?php echo $this->url(array('contest_id' => $this->contest->custom_url,'action'=>'create-entry-fees'), 'sescontest_dashboard', true); ?>"><i class="fa fa-plus"></i><?php echo $this->translate($create_fees->title); ?></a> </li>
        <?php endif; ?>
         <?php if($manage_orders->enabled): ?>
        <li> <a  id="sescontest_manage_order" class="sesbasic_dashboard_nopropagate_content dashboard_a_link" href="<?php echo $this->url(array('contest_id' => $this->contest->custom_url,'action'=>'manage-orders'), 'sescontest_dashboard', true); ?>"><i class="fa fa-list-alt"></i><?php echo $this->translate($manage_orders->title); ?></a> </li>
        <?php endif; ?>
        <?php if($payment_requests->enabled): ?>
        <li> <a  class="sesbasic_dashboard_nopropagate_content dashboard_a_link" href="<?php echo $this->url(array('contest_id' => $this->contest->custom_url,'action'=>'payment-requests'), 'sescontest_dashboard', true); ?>"><i class="far fa-money-bill-alt"></i><?php echo $this->translate($payment_requests->title); ?></a> </li>
        <?php endif; ?>
        <?php if($payment_transactions->enabled): ?>
        <li> <a  class="sesbasic_dashboard_nopropagate_content dashboard_a_link" href="<?php echo $this->url(array('contest_id' => $this->contest->custom_url,'action'=>'payment-transaction'), 'sescontest_dashboard', true); ?>"><i class="far fa-money-bill-alt"></i><?php echo $this->translate($payment_transactions->title); ?></a> </li>
        <?php endif; ?>
        <?php if($sales_statistics->enabled): ?>
        <li> <a  class="sesbasic_dashboard_nopropagate_content dashboard_a_link" href="<?php echo $this->url(array('contest_id' => $this->contest->custom_url,'action'=>'sales-stats'), 'sescontest_dashboard', true); ?>"><i class="fas fa-chart-bar"></i><?php echo $this->translate($sales_statistics); ?></a> </li>
        <?php endif; ?>
        <?php if($sales_orders->enabled): ?>
        <li> <a  class="dashboard_a_link" href="<?php echo $this->url(array('contest_id' => $this->contest->custom_url,'action'=>'sales-reports'), 'sescontest_dashboard', true); ?>"><i class="far fa-file-alt"></i><?php echo $this->translate($sales_orders); ?></a> </li>
        <?php endif; ?>
        <?php if($account_details->enabled): ?>
        <li> <a  class="sesbasic_dashboard_nopropagate_content dashboard_a_link" id="dashboard_account_details" href="<?php echo $this->url(array('contest_id' => $this->contest->custom_url,'action'=>'account-details'), 'sescontest_dashboard', true); ?>"><i class="fab fa-paypal"></i><?php echo $this->translate($account_details->title); ?></a> </li>
        <?php endif; ?>        
      </ul>
    </li>
    <?php endif; ?>
      
    </ul>
    <?php if(isset($this->contest->cover_photo) && $this->contest->cover_photo != 0 && $this->contest->cover_photo != ''){ 
         $contestCover =	Engine_Api::_()->storage()->get($this->contest->cover_photo, '')->getPhotoUrl(); 
   }else
      $contestCover =''; 
      ?>
    <div class="sescontest_dashboard_contest_info sesbasic_clearfix sesbm">
      <?php if(isset($this->contest->cover_photo) && $this->contest->cover_photo != 0 && $this->contest->cover_photo != ''){ ?>
        <div class="sescontest_dashboard_contest_info_cover"> 
          <img src="<?php echo $contestCover; ?>" />
          <div class="sescontest_dashboard_contest_main_photo sesbm">
            <img src="<?php echo $this->contest->getPhotoUrl(); ?>" /> 
          </div>
        </div>
      <?php } else { ?>
        <div class="sescontest_dashboard_contest_photo">
          <img src="<?php echo $this->contest->getPhotoUrl(); ?>" />
        </div>
      <?php }; ?>
      <div class="sescontest_dashboard_contest_info_content sesbasic_clearfix sesbd">
        <div class="sescontest_dashboard_contest_info_title">
          <a href="<?php echo $this->contest->getHref(); ?>"><?php echo $this->contest->getTitle(); ?></a>
        </div>
        <div class="sescontest_dashboard_contest_info_dates"> 
        	<?php echo $this->contestStartEndDates($this->contest); ?>
        </div>
        
       <?php if($this->contest->category_id){ 
        $category = Engine_Api::_()->getItem('sescontest_category', $this->contest->category_id);
       ?>
        <div class="sescontest_dashboard_contest_info_stat">
          <span>
          	<span class="sescontest_dashboard_contest_info_stat_label">Category</span> 
          	<span><a href="<?php echo $category->getHref(); ?>"><?php echo $category->getTitle(); ?></a></span> 
          </span> 
        </div>
       <?php } ?>
       <?php 
        $currentTime = time();
        if(strtotime($this->contest->starttime) > $currentTime){
          $status = 'notStarted';
        }else if(strtotime($this->contest->endtime) < $currentTime){
          $status = 'expire';
        }else{
          $status = 'onGoing';	
        }
       ?>
       	<?php if($status == 'notStarted'){ ?>
          <div class="contest_status sesbasic_clearfix pending clear floatL">
            <span class="contest_status_txt"><?php echo $this->translate('Contest not started');?></span>
          </div>
        <?php }else if($status == 'expire'){ ?>
          <div class="contest_status sesbasic_clearfix close clear floatL">
            <span class="contest_status_txt"><?php echo $this->translate('Contest Expired');?></span>
          </div>
        <?php }else{ ?>
          <div class="contest_status sesbasic_clearfix open clear floatL">
            <span class="contest_status_txt"><?php echo $this->translate('Contest ongoing');?></span>
          </div>
        <?php } ?>
        <?php if(!$this->contest->is_approved){ ?>
          <div class="contest_status sesbasic_clearfix unapproved clear floatL">
            <span class="contest_status_txt"><?php echo $this->translate('UNAPPROVED');?></b></span>
          </div>
        <?php } ?>
      	<?php echo $this->content()->renderWidget('sescontest.advance-share',array('dashboard'=>true)); ?> 
      </div>
    </div>
  </div>
<script type="application/javascript">
scriptJquery(document).on('click','#sescontest_currency_coverter',function(){
	var url = "<?php echo $this->url(array('contest_id' => $this->contest->custom_url,'action'=>'currency-converter'), 'sescontest_dashboard', true); ?>";
	openURLinSmoothBox(url);
	return false;
});
scriptJquery(document).ready(function(){
	var totalLinks = scriptJquery('.dashboard_a_link');
	for(var i =0;i < totalLinks.length ; i++){
			var data_url = scriptJquery(totalLinks[i]).attr('href');
			var linkurl = window.location.href ;
			if(linkurl.indexOf(data_url) > 0){
					 scriptJquery(totalLinks[i]).parent().addClass('active');
					// scriptJquery(totalLinks[i]).parent().parent().parent().find('a.sesbasic_dashboard_nopropagate').trigger('click');
			}
	}
});
scriptJquery(document).on('submit','#manage_order_search_form',function(event){
	var widgetName = 'manage-orders';	
	event.preventDefault();
	var searchFormData = scriptJquery(this).serialize();
	scriptJquery('#loadingimgsescontest-wrapper').show();
	scriptJquery.ajax({
			method: 'post',
			url :  en4.core.baseUrl + 'widget/index/mod/sescontestjoinfees/name/'+widgetName,
			data : {
				format : 'html',
				contest_id:'<?php echo $this->contest_id ? $this->contest_id : $this->contest->getIdentity(); ?>',
				searchParams :searchFormData, 
				is_search_ajax:true,
			},
			success: function(response) {
				scriptJquery('#loadingimgsescontest-wrapper').hide();
				scriptJquery('#sescontest_manage_order_content').html(response);
			}
	});
});
var sendParamInSearch = '';
scriptJquery(document).on('click','.sesbasic_dashboard_nopropagate, .sesbasic_dashboard_nopropagate_content',function(e){
	e.preventDefault();
	//ajax request
	if(scriptJquery(this).hasClass('sesbasic_dashboard_nopropagate_content')){
    if(!scriptJquery(this).parent().hasClass('active'))
      getDataThroughAjax(scriptJquery(this).attr('href'));
    scriptJquery(".sesbasic_dashboard_tabs > ul li").each(function() {
      scriptJquery(this).removeClass('active');
    });
    scriptJquery('.sesbasic_dashboard_tabs > ul > li ul > li').each(function() {
        scriptJquery(this).removeClass('active');
    });			
    scriptJquery(this).parent().addClass('active');
    scriptJquery(this).parent().parent().parent().addClass('active');
	}	
});
var ajaxRequest;
//get data through ajax
function getDataThroughAjax(url){
	if(!url)
		return;
	history.pushState(null, null, url);
// 	if(typeof ajaxRequest != 'undefined')
// 		ajaxRequest.cancel();
	scriptJquery('.sesbasic_dashboard_content').html('<div class="sesbasic_loading_container"></div>');
	ajaxRequest = scriptJquery.ajax({
      method: 'post',
      url : url,
      data : {
        format : 'html',
				is_ajax:true,
				dataAjax : sendParamInSearch,
				is_ajax_content:true,
      },
      success: function(response) {
				sendParamInSearch = '';
				scriptJquery('.sesbasic_dashboard_content').html(response);
				if(typeof executeAfterLoad == 'function'){
					executeAfterLoad();
				}
				if(scriptJquery('#loadingimgsescontest-wrapper').length)
					scriptJquery('#loadingimgsescontest-wrapper').hide();
			}
    });
}
scriptJquery(".sesbasic_dashboard_tabs > ul li a").each(function() {
	var c = scriptJquery(this).attr("href");
	scriptJquery(this).click(function() {
		if(scriptJquery(this).hasClass('sesbasic_dashboard_nopropagate')){
			if(scriptJquery(this).parent().find('ul').is(":visible")){
				scriptJquery(this).parent().find('ul').slideUp()
			}else{
					scriptJquery(".sesbasic_dashboard_tabs ul ul").each(function() {
							scriptJquery(this).slideUp();
					});
					scriptJquery(this).parent().find('ul').slideDown()
			}
					return false
			}	
	})
});
var error = false;
var objectError ;
var counter = 0;
var customAlert;
function validateForm(){
		var errorPresent = false;
		if(scriptJquery('#sescontest_ajax_form_submit').length>0)
			var submitFormVal= 'sescontest_ajax_form_submit';
		else if(scriptJquery('#sescontest_ticket_submit_form').length>0)
			var submitFormVal= 'sescontest_ticket_submit_form';
		else
			return false;
		objectError;
		scriptJquery('#'+submitFormVal+' input, #'+submitFormVal+' select,#'+submitFormVal+' checkbox,#'+submitFormVal+' textarea,#'+submitFormVal+' radio').each(
				function(index){
						customAlert = false;
						var input = scriptJquery(this);
						if(scriptJquery(this).closest('div').parent().css('display') != 'none' && scriptJquery(this).closest('div').parent().find('.form-label').find('label').first().hasClass('required') && scriptJquery(this).prop('type') != 'hidden' && scriptJquery(this).closest('div').parent().attr('class') != 'form-elements'){	
						  if(scriptJquery(this).prop('type') == 'checkbox'){
								value = '';
								if(scriptJquery('input[name="'+scriptJquery(this).attr('name')+'"]:checked').length > 0) { 
										value = 1;
								};
								if(value == '')
									error = true;
								else
									error = false;
							}else if(scriptJquery(this).prop('type') == 'select-multiple'){
								if(scriptJquery(this).val() === '' || scriptJquery(this).val() == null)
									error = true;
								else
									error = false;
							}else if(scriptJquery(this).prop('type') == 'select-one' || scriptJquery(this).prop('type') == 'select' ){
								if(scriptJquery(this).val() === '')
									error = true;
								else
									error = false;
							}else if(scriptJquery(this).prop('type') == 'radio'){
								if(scriptJquery("input[name='"+scriptJquery(this).attr('name').replace('[]','')+"']:checked").val() === '')
									error = true;
								else
									error = false;
							}else if(scriptJquery(this).prop('type') == 'textarea'){
								if(scriptJquery(this).val() === '' || scriptJquery(this).val() == null)
									error = true;
								else
									error = false;
							}else{
								if(scriptJquery(this).val() === '' || scriptJquery(this).val() == null)
									error = true;
								else
									error = false;
							}
							if(error){
							 if(counter == 0){
							 	objectError = this;
							 }
								counter++
							}else{
							}
							if(error)
								errorPresent = true;
							error = false;
						}
				}
			);	
			if(!errorPresent){
				if(scriptJquery('#price').length && scriptJquery('#price').val() != '' &&  !scriptJquery.isNumeric(scriptJquery('#price').val() || scriptJquery('#price').val() < 0 )){
						errorPresent = true;
						objectError = scriptJquery('#price');
						alert("<?php echo $this->translate('Please Enter valid amount'); ?>");
				}else if(scriptJquery('#total').length && scriptJquery('#total').val() != '' && (!scriptJquery.isNumeric(scriptJquery('#total').val()) || scriptJquery('#total').val() < 0)){
						errorPresent = true;
						objectError = scriptJquery('#total');
						customAlert = true;
						alert("<?php echo $this->translate('Please Enter valid total quantity'); ?>");
				}else if(scriptJquery('#min_quantity').length && scriptJquery('#min_quantity').val() != '' && (!scriptJquery.isNumeric(scriptJquery('#min_quantity').val()) || scriptJquery('#min_quantity').val() < 0)){
						errorPresent = true;
						objectError = scriptJquery('#min_quantity');
						customAlert = true;
						alert("<?php echo $this->translate('Please Enter valid min quantity'); ?>");
				}else if(scriptJquery('#max_quantity').length && scriptJquery('#max_quantity').val() != '' && (!scriptJquery.isNumeric(scriptJquery('#max_quantity').val()) || scriptJquery('#max_quantity').val() < 0)){
						errorPresent = true;
						objectError = scriptJquery('#max_quantity');
						customAlert = true;
						alert("<?php echo $this->translate('Please Enter valid max quantity'); ?>");
				}else if(scriptJquery('#max_quantity').length && scriptJquery('#max_quantity').val() != '' && (parseInt(scriptJquery('#max_quantity').val()) < parseInt(scriptJquery('#min_quantity').val()) || parseInt(scriptJquery('#min_quantity').val()) > parseInt(scriptJquery('#total').val()) || parseInt(scriptJquery('#max_quantity').val()) > parseInt(scriptJquery('#total').val()))){
						errorPresent = true;
						objectError = scriptJquery('#total');
						customAlert = true;
						alert("<?php echo $this->translate('Please Enter valid min & max quantity'); ?>");
				}else	if(scriptJquery('#starttime-date').length && scriptJquery('#starttime-date').val() == ''){
						errorPresent = true;
						objectError = scriptJquery('#starttime-date');
				}else if(scriptJquery('#endtime-date').length && scriptJquery('#endtime-date').val() == ''){
						errorPresent = true;
						objectError = scriptJquery('#endtime-date');
				}else if(scriptJquery('#starttime-date').length && scriptJquery('#endtime-date').length){
					var startDate = new Date(scriptJquery('#starttime-date').val()+" "+scriptJquery('#starttime-hour').val()+":"+scriptJquery('#starttime-minute').val()+":00 "+scriptJquery('#starttime-ampm').val());	
					var endDate = new Date(scriptJquery('#endtime-date').val()+" "+scriptJquery('#endtime-hour').val()+":"+scriptJquery('#endtime-minute').val()+":00 "+scriptJquery('#endtime-ampm').val());
					if(startDate.getTime() > endDate.getTime()){
							errorPresent = true;
							objectError = scriptJquery('#starttime-date');
					}
				}
			}
			return errorPresent ;
}
var ajaxDeleteRequest;
scriptJquery(document).on('click','.sescontest_ajax_delete',function(e){
	e.preventDefault();
	var object = scriptJquery(this);
	var url = object.attr('href');
	var value = object.attr('data-value');
	if(!value)
		value = "Are you sure want to delete?";
// 	if(typeof ajaxDeleteRequest != 'undefined')
// 			ajaxDeleteRequest.cancel();
	if(confirm(value)){
		 scriptJquery.ajax({
      method: 'post',
      url : url,
      data : {
        format : 'html',
				is_ajax:true,
      },
      success: function(response) {
				if(response)
					scriptJquery(object).parent().parent().remove();
				else
					alert('Something went wrong,please try again later');
			}
    });
	}
});
var submitFormAjax;
scriptJquery(document).on('submit','#sescontest_ajax_form_submit',function(e){
	e.preventDefault();
	//validate form
	var validation = validateForm();
	//if error comes show alert message and exit.
		if(validation)
		{
			if(!customAlert){
				if(scriptJquery(objectError).hasClass('contest_calendar')){
					alert('<?php echo $this->string()->escapeJavascript("Start date must be less than end date."); ?>');
				}else{
					alert('<?php echo $this->string()->escapeJavascript("Please complete the red mark fields"); ?>');
				}
			}
			if(typeof objectError != 'undefined'){
			 var errorFirstObject = scriptJquery(objectError).parent().parent();
			 scriptJquery('html, body').animate({
        scrollTop: errorFirstObject.offset().top
    	 }, 2000);
			}
			return false;	
		}else{
			if(!scriptJquery('#sesdashboard_overlay_content').length)
				scriptJquery('#sescontest_ajax_form_submit').before('<div class="sesbasic_loading_cont_overlay" id="sesdashboard_overlay_content" style="display:block;"></div>');
			else
				scriptJquery('#sesdashboard_overlay_content').show();
			//submit form 
			var form = scriptJquery('#sescontest_ajax_form_submit');
			var formData = new FormData(this);
			formData.append('is_ajax', 1);
			submitFormAjax = scriptJquery.ajax({
            type:'POST',
            url: scriptJquery(this).attr('action'),
            data:formData,
            cache:false,
            contentType: false,
            processData: false,
            success:function(data){
							scriptJquery('#sesdashboard_overlay_content').hide();
							var dataJson = data;
						try{
							var dataJson = JSON.parse(data);
						}catch(err){
							//silence
						}
							if(dataJson.redirect){
								scriptJquery('#'+dataJson.redirect).trigger('click');
								return;
							}else{
								if(data){
										scriptJquery('.sesbasic_dashboard_content').html(data);
								}else{
									alert('Something went wrong,please try again later');	
								}
							}
						},
            error: function(data){
            	//silence
						}
        });
		}
});
//validate email
function checkEmail(){
	var email = scriptJquery('input[name="contest_contact_email"]').val(),
	 emailReg = "/^([w-.]+@([w-]+.)+[w-]{2,4})?$/";
	if(!emailReg.test(email) || email == '')
	{
			 return false;
	}
	return true;
}
//validate phone number
function checkPhone(){
	var phone = $('input[name="contest_contact_phone"]').val(),
		intRegex = "/[0-9 -()+]+$/";
	if((phone.length < 6) || (!intRegex.test(phone)))
	{
		 return false;
	}
	return true;
}
</script>
