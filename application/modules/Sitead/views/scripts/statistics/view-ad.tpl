<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: view-ad.tpl  2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php
$isAdvActivity = Engine_Api::_()->sitead()->isModuleEnabled('advancedactivity');
$baseUrl = $this->layout()->staticBaseUrl;
$this->headLink()
  ->prependStylesheet($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/styles/style_carousel.css') 
  ->prependStylesheet($baseUrl . 'application/modules/Sitead/externals/styles/owl.carousel.min.css')
  ->prependStylesheet($baseUrl . 'application/modules/Sitead/externals/styles/owl.carousel.css')
  ->prependStylesheet($baseUrl . 'application/modules/Sitead/externals/styles/owl.theme.default.css')
  ->prependStylesheet($baseUrl . 'application/modules/Sitead/externals/styles/style.css');
 if($isAdvActivity)
 	$this->headLink()->prependStylesheet($baseUrl . 'application/modules/Advancedactivity/externals/styles/style_advancedactivity.css');
$this->headScript()
  ->appendFile($baseUrl . 'application/modules/Sitead/externals/scripts/jquery.min.js')
  ->appendFile($baseUrl . 'application/modules/Sitead/externals/scripts/owl.carousel.js')
  ->appendFile($baseUrl . 'application/modules/Sitead/externals/scripts/core.js'); 
?>
<?php $this->carouselClass = 'categorizedAdCarousel'; ?>

<script>
  en4.core.runonce.add(function() {
  var j_q = jq.noConflict();
  j_q(document).ready(function () {
    j_q('.categorizedAdCarousel').owlCarousel({
      loop: false,
      autoplay: false,
      touchdrag: true,
      responsiveClass: true,
      responsive: {
        0: {
          items: 1,
          nav: true
        },
        600: {
          items: 1,
          nav: false
        },
        1000: {
          items: 1,
          nav: true,
          loop: false,
          margin: 20,
        }
      },
      slideBy: 1,
      dots: true,
      navigation: true,
    })
  }
  );
});
</script>

<?php if (empty($this->is_ajax) && empty($this->ajax_filter)) : ?>
	<a id="classified_review_anchor" style="position:absolute;"></a>
    <?php
	$this->headScript()
					->appendFile($this->layout()->staticBaseUrl . 'externals/calendar/calendar.compat.js');
	$this->headLink()
					->appendStylesheet($this->layout()->staticBaseUrl . 'externals/calendar/styles.css');
	?>
	<style type="text/css">
	.global_form div.form-element
	{
		min-width:0px;
	}

     .cmaddis_sample img{
        max-width:40px;
    }
	</style>
	<script type="text/javascript">
var showMarkerInDate="<?php echo $this->showMarkerInDate ?>";
		en4.core.runonce.add(function()
		{    
			en4.core.runonce.add(function init()
			{
				monthList = [];
				myCal = new Calendar({ 'start_cal[date]': 'M d Y', 'end_cal[date]' : 'M d Y' }, {
					classes: ['event_calendar'],
					pad: 0,
					direction: 0
				});
			}); 
		});

			var cal_start_cal_onHideStart = function(){        
       if(showMarkerInDate == 0) return;
			// check end date and make it the same date if it's too
			cal_end_cal.calendars[0].start = new Date( $('start_cal-date').value );
			// redraw calendar
			cal_end_cal.navigate(cal_end_cal.calendars[0], 'm', 1);
			cal_end_cal.navigate(cal_end_cal.calendars[0], 'm', -1);
		}
		var cal_end_cal_onHideStart = function(){
       if(showMarkerInDate == 0) return;
			// check start date and make it the same date if it's too
			cal_start_cal.calendars[0].end = new Date( $('end_cal-date').value );
			// redraw calendar
			cal_start_cal.navigate(cal_start_cal.calendars[0], 'm', 1);
			cal_start_cal.navigate(cal_start_cal.calendars[0], 'm', -1);
		}

		en4.core.runonce.add(function(){

			cal_start_cal_onHideStart();
			cal_end_cal_onHideStart();
				if($('start_cal-minute'))
			$('start_cal-minute').style.display= 'none';	
			if($('start_cal-hour'))
			$('start_cal-hour').style.display= 'none';
			if($('end_cal-minute'))
			$('end_cal-minute').style.display= 'none';
			if($('end_cal-hour'))		
			$('end_cal-hour').style.display= 'none';
			if($('start_cal-ampm'))
			$('start_cal-ampm').style.display= 'none';
			if($('end_cal-ampm'))
			$('end_cal-ampm').style.display= 'none';
		});

	</script>
<?php endif; ?>

<script type="text/javascript">
 
var siteadPage = <?php echo sprintf('%d', $this->paginator->getCurrentPageNumber()) ?>;
var ad_id = <?php echo sprintf('%d', $this->ad_id) ?>;
  function paginateSiteadListing(page) {

    $('table_content').innerHTML = "<center><img src='<?php echo $this->layout()->staticBaseUrl ?>application/modules/Seaocore/externals/images/core/loading.gif' style='margin:10px 0;' /></center>";
    var url = '<?php echo $this->url(array('module' => 'sitead', 'controller' => 'statistics', 'action' => 'view-ad'), 'default', true) ?>';

    en4.core.request.send(new Request.HTML({
      'url' : url,
      'method' : 'post',
      'data' : {
        'format' : 'html',
        'ad_subject' : 'ad',
				'ad_id' : ad_id,
        'page' : page,
				'is_ajax' : '1',
        'start_cal':$("start_cal-date").value,
        'end_cal':$('end_cal-date').value
      }
    }), {
       'element' : $('table_content')
    });
	}
 
  window.addEvent('domready', function(){

  $$('.global_form').addEvent('submit', function(e) {
		  $('table_content').innerHTML = "<center><img src='<?php echo $this->layout()->staticBaseUrl ?>application/modules/Seaocore/externals/images/core/loading.gif' style='margin:10px 0;' /></center>";
		  //Prevents the default submit event from loading a new page.
		  e.stop();
		  this.set('send', {'format':'html',onComplete: function(response) { 
			  $('table_content').set('html', response);
		  }});
		  //Send the form.
		  this.send();
	  });
  });

 function filterDropdown(element) {
    var optn1 = document.createElement("OPTION");
		optn1.text = '<?php echo $this->translate("By Week") ?>';
		optn1.value = '<?php echo Zend_Date::WEEK; ?>';
    var optn2 = document.createElement("OPTION");
		optn2.text = '<?php echo $this->translate("By Month") ?>';
		optn2.value = '<?php echo Zend_Date::MONTH; ?>';

    switch(element.value) {
      case 'ww':
			removeOption('ww');
			removeOption('MM');
      break;

      case 'MM':
			addOption(optn1,'ww' );
			removeOption('MM');
      break;

      case 'y':
			addOption(optn1,'ww' );
			addOption(optn2,'MM' );
      break;
    }
  }

  function addOption(option,value )
  {
    var addoption = false;
		for (var i = ($('chunk').options.length-1); i >= 0; i--) {
			var val = $('chunk').options[ i ].value; 
			if (val == value) {
				addoption = true;
				break; 
			}
		}
		if(!addoption) {
			$('chunk').options.add(option);
		}
  }

   function removeOption(value) 
  {
    for (var i = ($('chunk').options.length-1); i >= 0; i--) 
    { 
      var val = $('chunk').options[ i ].value; 
      if (val == value) {
				$('chunk').options[i] = null;
				break; 
      }
    } 
  }
</script>

<?php if (empty($this->is_ajax) && empty($this->ajax_filter)) : ?>
<div class="cadcomp_page">
	<?php if(!Engine_Api::_()->seaocore()->checkModuleNameAndNavigation()):?>
					<?php if (count($this->navigation)): ?>
				 <?php $this->navigation()->menu()->setContainer($this->navigation)->render();?>
				<?php endif; ?>
			<?php endif; ?> 
	<div class="breadcrumb">
		<a href='<?php echo $this->url(array(), 'sitead_campaigns', true) ?>'><?php echo $this->translate('My Campaigns') ?></a> &nbsp; &raquo; &nbsp; <a href='<?php echo $this->url(array('adcampaign_id' => $this->siteads_array['campaign_id']), 'sitead_ads', true) ?>'><?php echo $this->translate("Campaign:"). "\t". ucfirst($this->siteads_array['name']) ?></a> &nbsp; &raquo; &nbsp; <b><?php echo ucfirst($this->siteads_array['web_name']) ?></b>
	</div>
  <?php if(!empty($this->saved) && $this->saved== "saved"):?>
  <ul class="form-notices" style="clear:both;margin-bottom:0px;">
    <li style="margin:0px;">
    	<b style="text-transform:none;">
      	<?php echo $this->translate("SITEAD_CREATE_SUSSEC_HEADING"); ?>
      </b>
      <div style="text-transform:none;">
      	<?php echo $this->translate("SITEAD_CREATE_SUSSEC_MESSAGE"); ?>
      </div>
    </li>
  </ul>
  <?php endif; ?>

  <?php if(!empty($this->saved) && $this->saved== "edit"):?>
  <ul class="form-notices" style="clear:both;margin-bottom:0px;">
    <li style="margin:0px;">
    	<b style="text-transform:none;">
      	<?php echo $this->translate("SITEAD_EDIT_SUSSEC_HEADING"); ?>
      </b>
    </li>
  </ul>
  <?php endif; ?>

  <?php
if(empty($this->siteads_array['declined']) && $this->siteads_array['status'] !=4):
	$renewFlage=0;$renewFlageValue=0;
	switch ($this->siteads_array['price_model']):

		case "Pay/view":
			if ($this->siteads_array['limit_view'] != -1) {

				$renewFlageValue=$this->siteads_array['limit_view'];
				$renewFlage=1;
			}
		break;

		case "Pay/click":
			if ($this->siteads_array['limit_click'] != -1){

				$renewFlageValue=$this->siteads_array['limit_click'];
				$renewFlage=1;
			}
		break;
		case "Pay/period":
			if (!empty($this->siteads_array['expiry_date'])) {
					if ($this->siteads_array['expiry_date'] !== '2250-01-01'){
					$diff_days = round((strtotime($this->siteads_array['expiry_date']) - strtotime(date('Y-m-d'))) / 86400);
          if($diff_days <=0)
            $diff_days=0;
					$renewFlageValue=$diff_days;


					$renewFlage=1;
				}
			}
		break;
		endswitch;

		if(($this->siteads_array['payment_status'] !='active' && $this->siteads_array['payment_status'] !='pending') && $this->siteads_array['price'] != 0 &&  empty($this->siteads_array['approve_date'])):
				?> <div class="tip"><span>
		<?php echo $this->translate('You have not completed the payment for this ad. %1$sMake your payment%2$s for this ad.', '<a href="javascript:void(0);" title="'. $this->translate('Make your payment'). '" onclick="setSession('. $this->siteads_array['userad_id']. ')" >', '</a>') ?>
						</span></div>
		<?php
					endif;
			?>
		<?php if(!empty($this->siteads_array['renew']) && !empty($this->siteads_array['approve_date']) && !empty($renewFlage) && $renewFlageValue <= $this->siteads_array['renew_before'] && $renewFlageValue > 0):?>
			<?php if($this->siteads_array['price'] != 0):?> <div class="tip"><span>
			<?php echo $this->translate('Your ad is about to expire. %1$sRenew your ad%2$s now.', '<a href="javascript:void(0);"  title="'. $this->translate('Renew your ad'). '" onclick="setSession('. $this->siteads_array['userad_id']. ')" >', '</a>'); ?>
				</span></div>
			<?php else:?>
			<div class="tip"><span>
			<?php echo $this->translate('Your ad is about to expire. %1$sRenew your ad%2$s now.', '<a href="'. $this->url(array('id' =>  $this->siteads_array['userad_id']),  'siteade_renew', true). '" class = "smoothbox" title = "'. $this->translate('Renew your ad'). '" >', '</a>') ?> </span></div>
			<?php  endif; ?>                                       
		<?php  endif; ?>

		<?php if(!empty($this->siteads_array['renew']) && !empty($this->siteads_array['approve_date']) && !empty($renewFlage) && $renewFlageValue <= $this->siteads_array['renew_before'] && $renewFlageValue <= 0):?>
				<?php if($this->siteads_array['price'] !=0 ):?> <div class="tip"><span>
				<?php echo $this->translate('Your ad has expired. %1$sRenew your ad%2$s now.', '<a href="javascript:void(0);"  title="'. $this->translate('Renew your ad'). '" onclick="setSession('. $this->siteads_array['userad_id']. ')" >', '</a>'); ?>
					</span></div>
				<?php else:?>
				<div class="tip"><span>
			<?php echo $this->translate('Your ad has expired. %1$sRenew your ad%2$s now.', '<a href="'. $this->url(array('id' =>  $this->siteads_array['userad_id']),  'siteade_renew', true). '" class = "smoothbox" title = "'. $this->translate('Renew your ad'). '" >', '</a>') ?> </span></div>
			<?php  endif; ?>                                       
		<?php  endif; ?>
<?php  endif; ?>


	<div class="cadcomp_vad_header">
		<h3>
			<?php if( !empty($this->siteads_array['cads_title']) ){ echo "<span>". $this->translate('Ad:') . "</span> ". ucfirst($this->translate($this->siteads_array['cads_title'])); } ?>
      <?php if( !empty($this->siteads_array['ad_type']) ){ echo "<span style='margin-right:10px;'>". $this->translate('Ad Type:') . "</span> ". $this->translate($this->list->getAdTypeTitle($this->list->cmd_ad_type)); } ?>
		</h3>
		<div class="cmad_hr_link">
			<?php if($this->can_create):?>
			<a href='<?php echo $this->url(array(), 'sitead_listpackage', true) ?>' style="margin-left:5px;">
				<?php echo $this->translate('Create an Ad'); ?><i style="margin-left:4px;" class="fa fa-angle-right" aria-hidden="true"></i>
			</a> <?php endif; ?>
		</div>
	</div>  
 <div class="statistics-wrp">
 	<div class=" ads-detail-page">
		<div class="ads-detail">
			<?php $totalSpend= Engine_Api::_()->sitead()->paymentSpend(array('source_type'=>'userads','source_id'=> $this->siteads_array['userad_id'])); ?>
			<ul class="details-heading">
				<li>Campaign Name</li>
				<li>Package Name</li>
				<li>Start Date</li>
				<li>End Date</li>
				<?php if(!empty($this->enableTarget)):?>
					<li>Targeting</li>
				<?php endif; ?>	
				<?php if(!empty($this->enableLocation)):?>
					<li>Location Targeting</li>
				<?php endif; ?>	
				<li>Remaining</li>
				<li>Status</li>
				<li>Payment </li>
				<?php if(!empty($totalSpend)):?>
					<li><?php echo $this->translate('Total Spend') ?></li>
				<?php endif; ?>
			</ul>
			
			<ul class="details-content">
				<li><?php echo $this->htmlLink(array('route' => 'sitead_ads', 'adcampaign_id' => $this->siteads_array['adcampaign_id'] ), ucfirst(Engine_Api::_()->sitead()->truncation($this->siteads_array['name'], 25)), array('title' => ucfirst($this->siteads_array['name']))) ?></li>
				<li><?php echo $this->htmlLink(
				      array('route' => 'default', 'module' => 'sitead', 'controller' => 'index', 'action' => 'packge-detail', 'id' => $this->siteads_array['package_id']),
				      ucfirst($this->translate(Engine_Api::_()->sitead()->truncation($this->siteads_array['package_name']))), array('class' => 'smoothbox', 'title' => ucfirst($this->siteads_array['package_name'])))
					?></li>
				<li><?php 	$labelDate = new Zend_Date();	
           $startDate = strtotime($this->siteads_array['cads_start_date']);
             $oldTz = date_default_timezone_get();
              date_default_timezone_set($this->viewer()->timezone);   
							echo $this->locale()->toDate($labelDate->set($startDate), array('size' => 'long'));
              date_default_timezone_set($oldTz);
						?></li>
				<li><?php if(!empty($this->siteads_array['cads_end_date'])) {
						$labelDate = new Zend_Date();
             $endDate = strtotime($this->siteads_array['cads_end_date']);
             $oldTz = date_default_timezone_get();
              date_default_timezone_set($this->viewer()->timezone);   
							echo $this->locale()->toDate($labelDate->set($endDate), array('size' => 'long'));
              date_default_timezone_set($oldTz);
					
					  } else {
						  echo $this->translate('Never ends');
					  } ?> </li>
				<?php if(!empty($this->enableTarget)):?>
				<li><?php if(empty($this->linkTarget)):?>
         <?php echo $this->translate("No") ?>
          <?php else:?>
         <?php	echo $this->htmlLink(
					               array('route' => 'sitead_targetdetails', 'id' => $this->siteads_array['userad_id']),
					                $this->translate(ucfirst('yes')), array('class' => 'smoothbox', 'title'=> $this->translate(ucfirst('View targeting parameters'))));
           ?>
          <?php endif; ?></li>	
				<?php endif; ?>
				<?php if(!empty($this->enableLocation)):?>
				<li><?php if(empty($this->linkLocation)):?>
         <?php echo $this->translate("No") ?>
          <?php else:?>
         <?php	echo $this->translate("Yes")
           ?>
          <?php endif; ?></li>	
				<?php endif; ?>	 	  
				<li><?php
					   if(!empty($this->siteads_array['approve_date'])):
					    switch ($this->siteads_array['price_model']):

					    case "Pay/view":

					      $limit_view = $this->siteads_array['limit_view'];
					      if ($limit_view == -1) {
									echo  $this->translate('UNLIMITED Views');
					      } else{
									$renewFlageValue = $limit_view;
									$renewFlage = 1;
									echo  $this->translate(array('%s View', '%s Views', $limit_view), $this->locale()->toNumber($limit_view));
					      }
					    break;

					    case "Pay/click":
					      $limit_click = $this->siteads_array['limit_click'];
					      if ($limit_click == -1){
									echo  $this->translate('UNLIMITED Clicks');
					      }else{
									$renewFlageValue = $limit_click;
									$renewFlage = 1;
									echo  $this->translate(array('%s Click', '%s Clicks', $limit_click), $this->locale()->toNumber($limit_click));
					      }
					    break;
					    case "Pay/period": 
					      if (!empty($this->siteads_array['expiry_date'])) {
									if ($this->siteads_array['expiry_date'] !== '2250-01-01'){
									$diff_days = round((strtotime($this->siteads_array['expiry_date']) - strtotime(date('Y-m-d'))) / 86400);
									if($diff_days<=0)
                    $diff_days=0;
                  $renewFlageValue=$diff_days;
									$renewFlage=1;
									echo  $this->translate(array('%s Day', '%s Days', $diff_days), $this->locale()->toNumber($diff_days));
								}
								else {
									echo  $this->translate('UNLIMITED Days');
								}
					      }else {
									echo  $this->translate('-');
					      }
					    break;
					    endswitch;
						else:
							echo  $this->translate('-');
						endif;
				      ?>  </li>
				<li><?php if($this->siteads_array['approved'] == 1 && $this->siteads_array['status'] <=3 && $this->siteads_array['declined'] !=1):?>
					<?php switch($this->siteads_array['status']) {
							    case 0:
							    echo $this->translate("Approval Pending");
							    break;
					      
							    case 1:
							    echo $this->translate("Running");
							    break;
							
							    case 2:
							    echo $this->translate("Paused");
							    break;
					      
							    case 3:
							    echo $this->translate("Completed");
							    break;
						      
							    case 4:
							    echo "<span style='color:red;'>".  $this->translate('Deleted'). "</span>";
							    break;

							    case 5:
							    echo $this->translate("Declined");
							    break;
						}?>
             <?php elseif($this->siteads_array['status']==4): ?>
                <span style='color:red;'><?php echo $this->translate("Deleted"); ?></span>
           <?php elseif($this->siteads_array['status']==3): ?>
                <?php echo $this->translate("Completed"); ?>
           <?php elseif($this->siteads_array['declined']==1): ?>
                <span style='color:red;'><?php echo $this->translate("Declined"); ?></span>
                <?php else: ?>
               <?php if(empty($this->siteads_array['approve_date'])): ?>
                 <?php echo $this->translate("Approval Pending"); ?>
                <?php else:?>
                <?php echo $this->translate("Dis-Approved"); ?>
                <?php endif;?>
               <?php endif; ?> </li>
				<li><?php if($this->siteads_array['payment_status'] == 'active' && $this->siteads_array['price']!=0) : ?>
						<?php echo $this->translate('Yes') ?>
					<?php elseif($this->siteads_array['payment_status'] == 'initial' && $this->siteads_array['price']!=0): ?>
						<?php echo $this->translate('No') ?>
					<?php elseif($this->siteads_array['payment_status'] == 'pending' && $this->siteads_array['price']!=0): ?>
						<?php echo $this->translate('Pending') ?>
					<?php elseif($this->siteads_array['payment_status'] == 'overdue' && $this->siteads_array['price']!=0): ?>
						<?php echo $this->translate('Overdue') ?>
					<?php elseif($this->siteads_array['payment_status']  == 'refunded' && $this->siteads_array['price']!=0): ?>
						<?php echo $this->translate('Refunded') ?>
					<?php elseif($this->siteads_array['payment_status'] == 'cancelled' && $this->siteads_array['price']!=0): ?>
						<?php echo $this->translate('Cancelled') ?>
					<?php elseif($this->siteads_array['payment_status']  == 'expired' && $this->siteads_array['price']!=0): ?>
						<?php echo $this->translate('Expired') ?>
					<?php elseif( $this->siteads_array['price']==0): ?>
						<?php  echo $this->translate('FREE') ?>
					<?php endif; ?> </li>
				<?php if(!empty($totalSpend)):?>
				<li>
					<?php echo Engine_Api::_()->sitead()->getPriceWithCurrency($totalSpend);  ?>
				</li>
        <?php endif; ?>
			</ul>
		</div>	
	</div>

	<div id="ad_preview" class="ad-preview">
		<div class="ad_preview_wpr">
			<div class="saad-preview">
	    <h3><?php echo $this->translate('Preview');
	     ?></h3>
	      <?php if($this->siteads_array['cmd_ad_type'] != 'boost') { ?>
	     <span class="cadva_buttons">
	     	<?php if($this->siteads_array['status'] != 4 && $this->siteads_array['declined'] != 1 ) {
	     		if($this->can_edit):
	     			echo $this->htmlLink(
	     				array('route' => 'sitead_edit', 'id' => $this->siteads_array['userad_id']),
	     				$this->translate('Edit Ad')
	     			);
	     		endif;
	     	}
	     	?>
	     </span>
	 </div>
	     
	  <div class="cmd-weapper">
      <div class="cmd-info">
        <span class="add-oner">
        	<div class="ad-icon">
         <a href="<?php $this->siteads_array['web_url'] ?>"  <?php echo $set_target ?>><?php echo $this->itemPhoto($this->list, '', '') ?></a>
     </div>
        </span>
        <span class="add-title"> <?php
          // Title if has existence on site then "_blank" not work else work.
        if (!empty($siteads_array['resource_type']) && !empty($siteads_array['resource_id'])) {
          $set_target = '';
        } else {
          $set_target = 'target="_blank"';
        }
        echo '<a href="' . $this->siteads_array['web_url'] . '" ' . $set_target . ' >' . ucfirst(Engine_String::strlen($this->siteads_array['web_name']) > 25 ? Engine_String::substr($this->siteads_array['web_name'], 0, 25) . '..' : $this->siteads_array['web_name'] ) . "</a>";
        ?>
        <span class="sponsored-tag">
         <?php if($this->siteads_array['sponsored']) {
             echo 'Sponsored';
           }
             elseif ($this->siteads_array['featured']) {
               echo 'Featured';
             } 
      ?>
      </span>
      </span>
  </div>

      <?php  print_r($siteads_array['cmd_ad_format']); ?>

   <?php if($this->siteads_array['cmd_ad_format'] == 'image') {
    foreach ($this->siteadsinfo_array as $siteadsinfo) { ?>
        <a href="<?php echo $siteadsinfo['cads_url'] ?>"  <?php echo $set_target ?>><?php echo $this->htmlImage($siteadsinfo->getIconUrl()); ?></a>
       <div class="discription">
       	 <div class="dis_bottom_wrp">
        <div class="cmd-info">
          <span class="add-title"><?php
          // Title if has existence on site then "_blank" not work else work.
          if (!empty($siteads_array['resource_type']) && !empty($siteads_array['resource_id'])) {
            $set_target = '';
          } else {
            $set_target = 'target="_blank"';
          }
          echo '<a href="' . $siteadsinfo['cads_url'] . '" ' . $set_target . ' >' . ucfirst(Engine_String::strlen($siteadsinfo['cads_title']) > 25 ? Engine_String::substr($siteadsinfo['cads_title'], 0, 25) . '..' : $siteadsinfo['cads_title'] ) . "</a>";
          ?></span>
           <p><a href="<?php echo $siteadsinfo['cads_url'] ?>" <?php echo $set_target ?>><?php echo Engine_String::strlen($siteadsinfo['cads_body']) > 80 ? Engine_String::substr($siteadsinfo['cads_body'], 0, 80) . '..' : $siteadsinfo['cads_body'] ?></a></p>
        </div>
        <div class="call-to-action">
         <?php if($siteadsinfo['cta_button'] != 0 || $siteadsinfo['cta_button'] != '0') { ?>
            <a href="<?php echo $siteadsinfo['cads_url'] ?>" <?php echo $set_target ?>> <?php echo $siteadsinfo['cta_button']; ?> </a>
        <?php } ?>
      </div>
  </div>
  <a href="<?php echo $siteadsinfo['cads_url'] ?>" <?php echo $set_target ?>> </a>
        </div>
        <?php }
      } ?>	


      <?php if($this->siteads_array['cmd_ad_format'] ==  'carousel') { ?>
      <div class="owl-carousel owl-theme <?php echo $this->carouselClass ?>">
        <?php foreach ($this->siteadsinfo_array as $siteadsinfo) { ?>
            <div class="sitead_owl">
            	 <?php if($siteadsinfo['overlay'] != 0 || !empty($siteadsinfo['overlay'])) { ?>
            <span class="ad_overlay"> <?php echo $siteadsinfo['overlay']; ?> </span>
        <?php } ?>
              <a href="<?php echo $siteadsinfo['cads_url'] ?>" <?php echo $set_target ?>><?php echo $this->htmlImage($siteadsinfo->getIconUrl()); ?></a>
       <div class="discription">
       <div class="dis_bottom_wrp"> 
        <div class="cmd-info">
          <span class="add-title"><?php
          // Title if has existence on site then "_blank" not work else work.
          if (!empty($siteads_array['resource_type']) && !empty($siteads_array['resource_id'])) {
            $set_target = '';
          } else {
            $set_target = 'target="_blank"';
          }
          echo '<a href="' . $siteadsinfo['cads_url'] . '" ' . $set_target . ' >' . ucfirst(Engine_String::strlen($siteadsinfo['cads_title']) > 25 ? Engine_String::substr($siteadsinfo['cads_title'], 0, 25) . '..' : $siteadsinfo['cads_title'] ) . "</a>";
          ?></span>
          <p><a href="<?php echo $siteadsinfo['cads_url'] ?>" <?php echo $set_target ?>><?php echo Engine_String::strlen($siteadsinfo['cads_body']) > 80 ? Engine_String::substr($siteadsinfo['cads_body'], 0, 80) . '..' : $siteadsinfo['cads_body'] ?></a></p>
        </div>
        <div class="call-to-action">
          <?php if($siteadsinfo['cta_button'] != 0 || !empty($siteadsinfo['cta_button'])) { ?>
            <a href="<?php echo $siteadsinfo['cads_url'] ?>" <?php echo $set_target ?>> <?php echo $siteadsinfo['cta_button']; ?> </a>
        <?php } ?>
      </div>
  </div>
         <a href="<?php echo $siteadsinfo['cads_url'] ?>" <?php echo $set_target ?>> </a>
        </div>
              </div>
          <?php } ?>
        </div>
      <?php } ?>

      <?php if($this->siteads_array['cmd_ad_format'] ==  'video') {
        foreach ($this->siteadsinfo_array as $siteadsinfo) { ?>
        <div class="video-ad-wrapper">
            <video id="video" controls  preload="auto">
                    <source type='video/mp4;' src="<?php echo $this->video_location ?>">
            </video>
        </div>
            <div class="discription"> 
           <div class="dis_bottom_wrp"> 	
        <div class="cmd-info">
          <span class="add-title"><?php
          // Title if has existence on site then "_blank" not work else work.
          if (!empty($siteads_array['resource_type']) && !empty($siteads_array['resource_id'])) {
            $set_target = '';
          } else {
            $set_target = 'target="_blank"';
          }
          echo '<a href="' . $siteadsinfo['cads_url'] . '" ' . $set_target . ' >' . ucfirst(Engine_String::strlen($siteadsinfo['cads_title']) > 25 ? Engine_String::substr($siteadsinfo['cads_title'], 0, 25) . '..' : $siteadsinfo['cads_title'] ) . "</a>";
          ?></span>
          <p><a href="<?php echo $siteadsinfo['cads_url'] ?>" <?php echo $set_target ?>><?php echo Engine_String::strlen($siteadsinfo['cads_body']) > 80 ? Engine_String::substr($siteadsinfo['cads_body'], 0, 80) . '..' : $siteadsinfo['cads_body'] ?></a></p>
        </div>
        <div class="call-to-action">
           <?php if($siteadsinfo['cta_button'] != 0 || $siteadsinfo['cta_button'] != '0') { ?>
            <a href="<?php echo $siteadsinfo['cads_url'] ?>" <?php echo $set_target ?>> <?php echo $siteadsinfo['cta_button']; ?> </a>
        <?php } ?>
      </div>
  </div>
  <a href="<?php echo $siteadsinfo['cads_url'] ?>" <?php echo $set_target ?>> </a>
        </div>
            <?php }
        }?>
    </div> 
		</div>
	 
</div> 	
 </div>
</div>
<?php  } else { ?>
</div>
   	<div id="ad_preview_in_wrp" class="ad_preview_in_wrp boost">
         <?php $action = Engine_Api::_()->getDbtable('actions', 'advancedactivity')->getActionById($this->action);
     echo $this->advancedActivity($action, array('onlyPreview' => 1, 'feedSettings' => array('memberPhotoStyle' => 'left'))); ?>
 </div>
     </div>
 </div>
</div>
<?php } ?> 
 
<div class="cadva_dsform">
	<?php echo $this->filter_form->render($this) ?>
	<div id="table_content" class="cadva_table">
<?php endif; ?>
		<table border='0'>
			<thead>
			  <tr>
			    <th><?php echo $this->translate("Date") ?></th>
			    <th><?php echo $this->translate("Views") ?></th>
			    <th><?php echo $this->translate("Clicks") ?></th>
			    <th><?php echo $this->translate("CTR (%)") ?></th>
		      </tr>
		    </thead>
				<tbody>
					<?php if ($this->total_count > 0) { ?>
					<?php foreach ($this->paginator as $item) { ?>
				  	<tr>
							<td><?php 
										$response_time = strtotime($item->response_date);
										$labelDate = new Zend_Date();
										$labelDate->set($response_time);
										$date_value = $this->locale()->toDate($labelDate, array('size' => 'long')); 
										echo $date_value; 
									?></td>
							<td>
								<?php 
									if(!empty($item->views)) {
										echo number_format($item->views);
									}
									else echo "0";
								?> 
							</td>
							<td>
								<?php  
									if(!empty($item->clicks)) {
										echo number_format($item->clicks);
									}
					      else echo "0";
					    	?> 
					   	</td>
							<td>
								<?php 
					      if($item->views != 0) {
									echo number_format(round(($item->clicks/$item->views)*100, 4), 4);
					      }
					      else echo number_format("0", 4); 
					    	?> 
					    </td>
						</tr>
				  <?php } ?>
		  	</tbody>
			</table>
  		<br/>
 <?php if ($this->paginator->count() > 1): ?>
   <div>
    <?php if ($this->paginator->getCurrentPageNumber() > 1): ?>
      <div id="user_group_members_previous" class="paginator_previous">
      <?php
                      echo $this->htmlLink('javascript:void(0);', $this->translate('Previous'), array(
                              'onclick' => 'paginateSiteadListing(siteadPage - 1)',
                              'class' => 'buttonlink icon_previous'
                      )); ?>
			</div>
    <?php endif; ?>
    <?php if ($this->paginator->getCurrentPageNumber() < $this->paginator->count()): ?>
       <div id="user_group_members_next" class="paginator_next">
      <?php  echo $this->htmlLink('javascript:void(0);', $this->translate('Next'), array(
                                'onclick' => 'paginateSiteadListing(siteadPage + 1)',
                                'class' => 'buttonlink_right icon_next'
                        ));?>
				</div>
    <?php endif; ?>
  </div>
<?php endif; ?>
<?php
    } else {
?>
     <?php if ($this->viewer()->getIdentity())?>
    <tr>
	    <td><?php echo $this->translate('Lifetime') ?></td>
	    <td>0</td>
	    <td>0</td>
	    <td>0.0000</td>
    </tr>
  </tbody>
</table>
<?php } ?>

<?php if (empty($this->is_ajax) && empty($this->ajax_filter)) : ?>
	</div>
</div>

<div class="cadmc_statistics">
	<div>
		<p>
			<?php echo $this->translate("Use the below filter to observe various metrics of your ad over different time periods.") ?>
			<span style="font-weight: normal;">
				<?php echo $this->translate(array('(for last %s year)', '(for last %s years)', Engine_Api::_()->getApi('settings', 'core')->getSetting('ad.statistics.limit',3)), $this->locale()->toNumber(Engine_Api::_()->getApi('settings', 'core')->getSetting('ad.statistics.limit',3))) ?>
			</span>
		</p>
		<br>
		<div class="cadmc_statistics_search">
			<?php echo $this->filterForm->render($this) ?>
		</div>
        <div class="cmad_statistics" id ="cmad_statistics">
        	<div class="cadmc_statistics_nav">
			<a id="admin_stats_offset_previous"  class='buttonlink icon_previous' onclick="processStatisticsPage(-1);" href="javascript:void(0);" style="float:left;"><?php echo $this->translate("Previous") ?></a>
			<a id="admin_stats_offset_next" class='buttonlink buttonlink_right icon_next' onclick="processStatisticsPage(1);" href="javascript:void(0);" style="display:none;float:right;"><?php echo $this->translate("Next") ?></a>
		</div>
		<div id="my_chart" class="my_chart"></div>
		<div id="loading" style="display: none"></div>

		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>


		<script type="text/javascript">
			var prev = '<?php echo $this->prev_link ?>';
			var currentArgs = {};
			var processStatisticsFilter = function(formElement) {
				var vals = formElement.toQueryString().parseQueryString();
				vals.offset = 0;
				buildChart(vals);
				return false;
			}						    
			var processStatisticsPage = function(count) {							   
				var args = $merge(currentArgs);						      
				args.offset += count;					      
				buildChart(args);
				window.console.log(currentArgs);					      
			}

			var buildChart = function(args) {
				currentArgs = args;
				$('admin_stats_offset_next').setStyle('display', (args.offset < 0 ? '' : 'none'));
				var url = new URI('<?php echo $this->url(array('action' => 'chart-data')) ?>');
				url.setData(args);
				while ($('my_chart').firstChild) {
					$('my_chart').removeChild($('my_chart').firstChild);
				}
				$('loading').setStyle('display', '').inject($('my_chart'));
				var req = new Request.JSON({
					url : url.toString(),
					data : {
						format : 'json',
					},
					onComplete : function(responseJSON) {
						$('loading').setStyle('display', 'none').inject($('cmad_statistics'));
						google.charts.setOnLoadCallback(drawChart(responseJSON));
					}
				});
				(function() {
					req.send();
				}).delay(250);
			}	    

			window.addEvent('load', function() {
				buildChart({
					'type' : 'all',
					'mode' : 'normal',
					'chunk' : 'dd',
					'period' : 'ww',
					'start' : 0,
					'offset' : 0,
					'ad_subject' : 'ad',
					'prev_link' : prev
				});
			});

			google.charts.load('current', {'packages':['corechart']});
			function drawChart(response) {
				if(response.case == "all") {
					var data = google.visualization.arrayToDataTable(response.data);
				}
				else {
					var data = [];
					for (var key in response.data) {
						if (response.data.hasOwnProperty(key)) {
							data.push([key, response.data[key]]);
						}
					}
					var data = google.visualization.arrayToDataTable(data);
				}
				switch($('type').value) {
					  case 'all':
					    series = {
								0: { color: '<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sitead.graphview.color', '#0211f9') ?>',
			                         lineWidth: '<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sitead.graphview.width', '3') ?>',
								 },
								1: { color: '<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sitead.graphclick.color', '#18591e') ?>',
			                        lineWidth: '<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sitead.graphclick.width', '3') ?>',
								 },
								2: { color: '<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sitead.graphctr.color', '#c11313') ?>',
			                        lineWidth: '<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sitead.graphctr.width', '3') ?>',
								 },
							} 
					    break;
					  case 'view':
					    series = {
					    	0: { color: '<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sitead.graphview.color', '#0211f9') ?>',
                         		lineWidth: '<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sitead.graphview.width', '3') ?>',
					 		},
					    }
					    break;
				      case 'CTR':
				      series = {
						        0: { color: '<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sitead.graphctr.color', '#c11313') ?>',
		                        		lineWidth: '<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sitead.graphctr.width', '3') ?>',
									 },
						 }			 
					   break;
					  case 'click':
					  series = {
						    0: { color: '<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sitead.graphclick.color', '#18591e') ?>',
	                        	lineWidth: '<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sitead.graphclick.width', '3') ?>',
						 		},
						 }		
					   break; 
					  default:
					    series = {
								0: { color: '<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sitead.graphview.color', '#0211f9') ?>',
			                         lineWidth: '<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sitead.graphview.width', '3') ?>',
								 },
								1: { color: '<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sitead.graphclick.color', '#18591e') ?>',
			                        lineWidth: '<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sitead.graphclick.width', '3') ?>',
								 },
								2: { color: '<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sitead.graphctr.color', '#c11313') ?>',
			                        lineWidth: '<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sitead.graphctr.width', '3') ?>',
								 },
							} 
					}

				var options = {
					title: response.title,
					legend: { position: 'bottom' },
					series: series,
				backgroundColor: '<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sitead.graph.bgcolor', '#ffffff') ?>',
				vAxis: {
                    title: 'Ad Stats'
                     }
				};
				var chart = new google.visualization.LineChart(document.getElementById('my_chart'));
				chart.draw(data, options);
			}
		</script>
	</div>
	</div>	
</div>
</div>
	
</div>	
<?php endif; ?>
<div>
		  <form name="setSession_form" method="post" id="setSession_form" action="<?php echo $this->url(array('module' => 'sitead', 'controller' => 'index', 'action' => 'set-session'), 'default', true) ?>">
		    <input type="hidden" name="ad_ids_session" id="ad_ids_session">
		  </form>
		</div>
		<script type="text/javascript">
		function setSession(id){

		    document.getElementById("ad_ids_session").value=id;
		    document.getElementById("setSession_form").submit();
		}
		</script>

<style>
  .my_chart {
    height: 450px;
    margin: 20px;
  }
  #loading {
    width: inherit;
    height: inherit;
    background-position: center 10%;
    background-repeat: no-repeat;
    background-image: url(application/modules/Core/externals/images/large-loading.gif);
  }
</style>