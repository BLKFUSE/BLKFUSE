<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: browse-ad.tpl  2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<a id="classified_review_anchor" style="position:absolute;"></a>
<script type="text/javascript">

  var currentOrder = '<?php echo $this->order ?>';
  var currentOrderDirection = '<?php echo $this->order_direction ?>';
  var changeOrder = function(order, default_direction){  

    if( order == currentOrder ) { 
      $('order_direction').value = ( currentOrderDirection == 'ASC' ? 'DESC' : 'ASC' );
    } else { 
      $('order').value = order;
      $('order_direction').value = default_direction;
    }
    $('filter_form').submit();
  }
 
  var siteadPage = <?php echo sprintf('%d', $this->paginator->getCurrentPageNumber()) ?>;
  var camp_id = <?php echo sprintf('%d', $this->adcampaign_id) ?>;
    function paginateSiteadListing(page) {

      $('table_content').innerHTML = "<center><img src='<?php echo $this->layout()->staticBaseUrl ?>application/modules/Seaocore/externals/images/core/loading.gif' style='margin:5px 0;' /></center>";
      var url = '<?php echo $this->url(array('module' => 'sitead', 'controller' => 'statistics', 'action' => 'browse-ad'), 'default', true) ?>';
      en4.core.request.send(new Request.HTML({
				'url' : url,
				'data' : {
					'format' : 'html',
					'ad_subject' : 'campaign',
					'adcampaign_id' : camp_id,
					'page' : page,
					'is_ajax' : '1'
				}
      }), {
					'element' : $('table_content')
      });
    }

    en4.core.runonce.add(function(){$$('th.site_table_short input[type=checkbox]').addEvent('click', function(){ $$('input[type=checkbox]').set('checked', $(this).get('checked', false)); })});

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

<div>
	<?php if (empty($this->is_ajax)) : ?>
		<div class="cadcomp_page">
				<?php if(!Engine_Api::_()->seaocore()->checkModuleNameAndNavigation()):?>
					<?php if (count($this->navigation)): ?>
				 <?php $this->navigation()->menu()->setContainer($this->navigation)->render();?>
				<?php endif; ?>
			<?php endif; ?> 
		
	     <div class="breadcrumb">
		<a href='<?php echo $this->url(array(), 'sitead_campaigns', true) ?>'><?php echo $this->translate('My Campaigns') ?></a> &nbsp; &raquo; &nbsp;
		<b><?php echo ucfirst($this->camp_title) ?></b>
	</div>
	
	<div class="cadcomp_vad_header">
		<h3>
			<?php echo "<span>". $this->translate('Campaign:'). "</span> ". ucfirst($this->camp_title) ?>
		</h3>
		<div style="display:none;">
			<?php echo $this->formFilter->render($this) ?>
		</div>
	</div>	
	
	<div class="cadmc_list_wrapper">
		<div id="table_content">
<?php endif; ?>
     
			<?php if ($this->total_count > 0) { ?>
	    	<table>
	        <thead>
	          <tr>
						 <!-- <th class='site_table_short'><input type='checkbox' class='checkbox' /></th>-->
						  <th style="text-align:left;"><a href="javascript:void(0);" onclick="javascript:changeOrder('web_name', 'ASC');"  title="<?php echo $this->translate('Ad Name') ?>"><?php echo $this->translate('Ad Name'); ?></a></th>						  
						  <th><a href="javascript:void(0);" onclick="javascript:changeOrder('cmd_ad_type', 'DESC');" title="<?php echo $this->translate('Type') ?>"><?php echo $this->translate('Type'); ?></a></th>
						  <th><a href="javascript:void(0);" onclick="javascript:changeOrder('views', 'DESC');" title="<?php echo $this->translate('Total Views') ?>"><?php echo $this->translate('Views'); ?></a></th>
						  <th><a href="javascript:void(0);" onclick="javascript:changeOrder('clicks', 'DESC');" title="<?php echo $this->translate('Total Clicks') ?>"><?php echo $this->translate('Clicks'); ?></a></th>
						  <th><a href="javascript:void(0);" onclick="javascript:changeOrder('CTR', 'DESC');" title="<?php echo $this->translate('Click Through Rate') ?>"><?php echo $this->translate('CTR (%)'); ?></a></th>
						  <th><?php echo $this->translate("Status") ?></th>
						  <th><a href="javascript:void(0);" onclick="javascript:changeOrder('payment', 'DESC');" title="<?php echo $this->translate('Payment Status'); ?>"><?php echo $this->translate('Payment'); ?></a></th>
						  <th><a href="javascript:void(0);" onclick="javascript:changeOrder('approve_date', 'DESC');" ><?php echo $this->translate('Approved Date'); ?></a></th>
						  <th><a href="javascript:void(0);" onclick="javascript:changeOrder('cads_start_date', 'DESC');" ><?php echo $this->translate('Start Date'); ?></a></th>
						  <th><a href="javascript:void(0);" onclick="javascript:changeOrder('cads_end_date', 'DESC');" ><?php echo $this->translate('End Date'); ?></a></th>
						  <th><?php echo $this->translate("Remaining") ?></th>
              <th style="text-align:left;"><?php echo $this->translate("Options") ?></th>
		    		</tr>
	        </thead>
	        <tbody>
	        
	    			<?php foreach ($this->paginator as $item) {	    					
	    				$renewFlageValue=0; $renewFlage=0; ?>
	        		<tr>
	         			<td style="text-align:left;">
					    <?php 

					    	echo $this->htmlLink(array('route' => 'sitead_userad', 'ad_id' => $item['userad_id']),ucfirst($this->translate(Engine_Api::_()->sitead()->truncation($item->web_name, 20))),
					    array('title' => ucfirst($item->web_name)));
					    ?>
					</td>
					
          <td title="<?php echo $item->getAdTypeTitle($item->cmd_ad_type)?>">					
          <?php  echo  $this->htmlImage($this->layout()->staticBaseUrl . "application/modules/Sitead/externals/images/$item->cmd_ad_type-icon-image.png"); ?>						
					</td>
								<td>
											<?php 
								      if(!empty($item->views) && $item->views >= 0) {
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
								      if(!empty($item->CTR)) {
												echo number_format(round(($item->CTR)*100, 4), 4);
								      }
								      else echo number_format("0", 4); 
								    ?> 
								</td>
								<td>
									 <?php if($item->approved == 1 && $item->status <=2 && $item->declined!=1):?>
										<?php switch($item->status) {
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
										}
										?>
									<?php elseif($item->status==4): ?>
										<?php echo $this->translate("<span style='color:red;'>Deleted</span>"); ?>
                  	<?php elseif($item->status==3): ?>
										<?php echo $this->translate("Completed"); ?>
                   <?php elseif($item->declined==1): ?>
                    <?php echo $this->translate("<span style='color:red;'>Declined</span>"); ?>
									<?php else: ?>
										<?php if(empty($item->approve_date)): ?>
                     <?php echo $this->translate("Approval Pending"); ?>
                     <?php else:?>
                     <?php echo $this->translate("Dis-Approved"); ?>
                    <?php endif;?>
									<?php endif; ?>
								</td>

                <td align="center" class="admin_table_centered">
									<?php if($item->payment == 'active' && $item->price!=0) : ?>
										<?php echo $this->translate('Yes') ?>
									<?php elseif($item->payment == 'initial' && $item->price!=0): ?>
										<?php echo $this->translate('No') ?>
									<?php elseif($item->payment == 'pending' && $item->price!=0): ?>
										<?php echo $this->translate('Pending') ?>
									<?php elseif($item->payment == 'overdue' && $item->price!=0): ?>
										<?php echo $this->translate('Overdue') ?>
									<?php elseif($item->payment == 'refunded' && $item->price!=0): ?>
										<?php echo $this->translate('Refunded') ?>
                  <?php elseif($item->payment == 'cancelled' && $item->price!=0): ?>
										<?php echo $this->translate('Cancelled') ?>
									<?php elseif($item->payment == 'expired' && $item->price!=0): ?>
										<?php echo $this->translate('Expired') ?>
									<?php elseif( $item->price==0): ?>
										<?php  echo $this->translate('FREE') ?>
                  <?php endif; ?>
                </td>

								<td><?php if(!empty($item->approve_date)): $labelDate = new Zend_Date();
											echo $this->locale()->toDate($labelDate->set(strtotime($item->approve_date)), array('size' => 'long'));
											 else: echo "-"; endif; ?> </td>
								<td><?php $labelDate = new Zend_Date();
						//if( !empty($item['story_type ']) ) {
										echo $this->locale()->toDate($labelDate->set(strtotime($item->cads_start_date)), array('size' => 'long')); //} ?> </td>
								<td>	<?php 
										if(!empty($item->cads_end_date)) {
											$labelDate = new Zend_Date();
											echo $this->locale()->toDate($labelDate->set(strtotime($item->cads_end_date)), array('size' => 'long'));
										} else {
											echo $this->translate('Never ends');
										}
									?> </td>
                <td><?php
                   if(!empty($item->approve_date)):
                    switch ($item->price_model):

                    case "Pay/view":


                      if ($item->limit_view == -1) {
                      echo  $this->translate('UNLIMITED Views');
                      } else{
                        $renewFlageValue=$item->limit_view;
                        $renewFlage=1;
                      echo  $this->translate(array('%s View', '%s Views', $item->limit_view), $this->locale()->toNumber($item->limit_view));
                      }

                    break;

                    case "Pay/click":
                      if ($item->limit_click == -1){
                      echo  $this->translate('UNLIMITED Clicks');
                      }else{

                       $renewFlageValue=$item->limit_click;
                       $renewFlage=1;
                      echo  $this->translate(array('%s Click', '%s Clicks', $item->limit_click), $this->locale()->toNumber($item->limit_click));
                      }
                    break;
                    case "Pay/period": 
                      if (!empty($item->expiry_date)) {

                         if ($item->expiry_date !== '2250-01-01'){
                        $diff_days = round((strtotime($item->expiry_date) - strtotime(date('Y-m-d'))) / 86400);
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
              ?> </td>
		<td style="text-align:left;">
          <?php $needToaddPipe = false; ?>
			<?php if($item->status!=4 &&  $item->declined!=1):
				if($item->cmd_ad_type != 'boost'):
             if($this->can_edit):
			echo $this->htmlLink(
				array('route' => 'sitead_edit', 'id' => $item->userad_id),
				'<i class="fa fa-pencil" aria-hidden="true"></i>', 
				array('title' => $this->translate('Edit'))
			);
             $needToaddPipe = true;
			endif;
		endif;
			?>
			<?php  if(!empty($item->approved) && $item->status<=2 && $this->can_edit) {	?>
			  <span  id="status_<?php echo $item->userad_id ?>">
              <?php if($needToaddPipe):?>  | <?php endif; $needToaddPipe =true;?>
					<?php	    if($item->enable==1): ?>									
						<a  href='<?php echo $this->url(array('module' => 'sitead', 'controller' => 'index', 'action' => 'enabled',"id"=>$item->userad_id), 'default', true) ?>'  title="<?php echo $this->translate('Pause')?>"  ><i class="fa fa-toggle-on" aria-hidden="true"></i></a>
					<?php else: ?>											 
						<a  href='<?php echo $this->url(array('module' => 'sitead', 'controller' => 'index', 'action' => 'enabled',"id"=>$item->userad_id), 'default', true) ?>'  title="<?php echo $this->translate('Activate')?>"  ><i class="fa fa-toggle-off" aria-hidden="true"></i></a>

					<?php  endif; ?>
				</span> 
			<?php } ?>
		<?php 
			if($item->payment !='active' && $item->payment !='pending' && $item->payment != 'free' && !empty($item->price) &&  empty($item->approve_date)):
		?>
			<?php if($needToaddPipe):?>  | <?php endif; $needToaddPipe =true;?>   <a href='javascript:void(0);' style="color: red;" title="<?php echo $this->translate('Make Payment')?>" onclick="setSession(<?php echo $item->userad_id?>)" ><i class="fa fa-money" aria-hidden="true"></i></a>
		<?php
		    endif;
		?>
		<?php if(!empty($item->renew) && !empty($item->approve_date) && !empty($renewFlage) && $renewFlageValue <= $item->renew_before):?>
            <?php if($needToaddPipe):?>  | <?php endif; $needToaddPipe =true;?>
			<?php if(!empty($item->price)):?>
				 <a href='javascript:void(0);' style="color: red;" title="<?php echo $this->translate('Renew')?>" onclick="setSession(<?php echo $item->userad_id?>)" ><i class="fa fa-refresh" aria-hidden="true"></i></a>
			<?php else:?>
				 <?php echo $this->htmlLink(array('route' => 'siteade_renew','id' =>  $item->userad_id), '<i class="fa fa-refresh" aria-hidden="true"></i>', array(
					  'class' => 'smoothbox','title' => $this->translate('Renew') ,'style'=>"color: red;"
					)) ?>
			<?php  endif; ?>                                       	  
		<?php  endif; ?>
		<?php if($this->can_delete):?>    <?php if($needToaddPipe):?>  | <?php endif; $needToaddPipe =true;?>
			<?php
		       echo $this->htmlLink(array('route' => 'sitead_deletead','id' => $item->userad_id), '<i class="fa fa-trash" aria-hidden="true"></i>', array('class' => 'smoothbox', 'title' => $this->translate('Delete'))); 
		endif;         
		endif;  ?>
        <?php if(!$needToaddPipe):?>
           -
         <?php endif;?>
		</td>
	</tr>
	<?php } ?>
		</tbody>
	</table>
	<?php if ($this->paginator->count() > 1): ?>
		<div style="margin-top:10px;">
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
	<div style="clear:both;height:0px;"></div>  
			<?php } else { ?>
	    <?php if ($this->viewer()->getIdentity())?>
	      <div class="tip">
	        <span>
	    			<?php echo $this->translate("There are no ads in this campaign yet.") ?>
	    		</span>
	  		</div>
			<?php } ?>
<?php if (empty($this->is_ajax)) : ?>
		</div>
	</div>

	<div class="cadmc_statistics">
		<div>
	    <p>
	      <?php echo $this->translate("Use the below filter to observe various metrics of your ad campaign over different time periods.") ?>
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
                    title: 'Ads Stats'
                     }
				};
				var chart = new google.visualization.LineChart(document.getElementById('my_chart'));
				chart.draw(data, options);
			}
		</script>
	</div>
			</div>
		</div>
		<?php endif; ?>
		<form name="setSession_form" method="post" id="setSession_form" action="<?php echo $this->url(array('module' => 'sitead', 'controller' => 'index', 'action' => 'set-session'), 'default', true) ?>">
			<input type="hidden" name="ad_ids_session" id="ad_ids_session" />
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
