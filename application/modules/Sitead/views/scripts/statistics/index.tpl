<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2011-02-16 9:40:21Z SocialEngineAddOns $
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
  var paginateSiteadListing = function(page) {
    $('table_content').innerHTML = "<center><img src='<?php echo $this->layout()->staticBaseUrl ?>application/modules/Seaocore/externals/images/core/loading.gif' style='margin:5px 0;' /></center>";
    var url = '<?php echo $this->url(array('module' => 'sitead', 'controller' => 'statistics', 'action' => 'index'), 'default', true) ?>';
    en4.core.request.send(new Request.HTML({
      'url' : url,
      'data' : {
        'format' : 'html',
        'page' : page,
				'is_ajax' : '1'
      },
      onRequest : function () { 
       }
    }), {
      'element' : $('table_content')
    });
  }

  en4.core.runonce.add(function(){$$('th.site_table_short input[type=checkbox]').addEvent('click', function(){ $$('input[type=checkbox]').set('checked', $(this).checked); })});

  var delectSelected =function(){
    var checkboxes = $$('input[type=checkbox]');
    var selecteditems = [];

    checkboxes.each(function(item, index){
      var checked = item.checked; //item.get('checked', false);
      var value =  item.value; //item.get('value', false);
      if (checked == true && value != 'on'){
        selecteditems.push(value);
      }
    });

    $('ids').value = selecteditems;
    $('delete_selected').submit();
  }

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
<?php if (empty($this->is_ajax)) : ?>
<div class="cadcomp_page">	
<div class="cadmc_list_wrapper">
	<div id="table_content">
	<?php endif; ?>

	<?php if ($this->total_count > 0) { ?>
	  <table>
	    <thead>
	      <tr> <?php if($this->can_delete):?>
			  	  <th class="site_table_short" style="width:10px;"><input type='checkbox' class='checkbox' /></th>
            <?php endif;?>
				  <th style="text-align:left;"><a href="javascript:void(0);" onclick="javascript:changeOrder('name', 'ASC');" title="<?php echo $this->translate('Campaign Name') ?>"><?php echo $this->translate('Campaign Name'); ?></a></th>
				  <th><a href="javascript:void(0);" onclick="javascript:changeOrder('ads', 'DESC');" title="<?php echo $this->translate('Number of Ads which belong to this Campaign') ?>"><?php echo $this->translate('Ads'); ?></a></th>
				  <th><a href="javascript:void(0);" onclick="javascript:changeOrder('views', 'DESC');" title="<?php echo $this->translate('Total Views') ?>"><?php echo $this->translate('Views'); ?></a></th>
				  <th><a href="javascript:void(0);" onclick="javascript:changeOrder('clicks', 'DESC');" title="<?php echo $this->translate('Total Clicks') ?>"><?php echo $this->translate('Clicks'); ?></a></th>
				  <th ><a href="javascript:void(0);" onclick="javascript:changeOrder('CTR', 'DESC');" title="<?php echo $this->translate('Click Through Rate') ?>"><?php echo $this->translate('CTR (%)'); ?></a></th>
		      <th style="text-align:left;"><?php echo $this->translate("Options") ?></th>
			   </tr>
		  </thead>
		  <tbody>
		  	<?php foreach ($this->paginator as $item) { ?>
		    	<tr>
             <?php if($this->can_delete):?>
			  		<td style="width:10px;"><input type='checkbox' class='checkbox' value="<?php echo $item->adcampaign_id ?>"/></td>
            <?php endif; ?>
		        <td style="text-align:left;width:450px;"><?php echo $this->htmlLink(array('route' => 'sitead_ads', 'adcampaign_id' => $item['adcampaign_id']), ucfirst($item->name), array('title' => ucfirst($item->name))) ?></td>
					  <td>
					  	<?php 
					      if(!empty($item->ads)) {
									echo $item->ads;
					      }
					      else echo "0";
					  	?> 
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
							<?php if(!empty($item->clicks)) {
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
						<td style="text-align:left;">
						<?php
					        echo $this->htmlLink(
					                array('route' => 'sitead_ads', 'adcampaign_id' => $item['adcampaign_id']),
					                '<i class="fa fa-eye" aria-hidden="true"></i>', 
					                array('title' => ucfirst($this->translate('Manage')))
					        )
					        ?>
                            <?php if($this->can_edit):?>  
													|
						 <?php
						  echo $this->htmlLink(array('route' => 'sitead_editcamp','id' => $item->adcampaign_id), '<i class="fa fa-edit" aria-hidden="true"></i>', array('class' => 'smoothbox', 'title' => 'Edit'));    
						     ?><?php endif; ?>
					             <?php if($this->can_delete):?>                                                       	  |
					        <?php
              				echo $this->htmlLink(array('route' => 'sitead_deletecamp','id' => $item->adcampaign_id, 'format' => 'smoothbox'), '<i class="fa fa-trash" aria-hidden="true"></i>', array('class' => 'smoothbox', 'title' => 'Delete'));  
					        ?><?php endif; ?>
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
     <?php if($this->can_delete):?>
		<button onclick="javascript:delectSelected();" type='submit'>
			<?php echo $this->translate("Delete Selected") ?>
		</button> <?php endif; ?>
		<form id='delete_selected' method='post' action='<?php echo $this->url(array(), 'sitead_deleteselectedcamp', true) ?>'>
			<input type="hidden" id="ids" name="ids" value=""/>
		</form>
	  <div style="clear:both;height:0px;"></div>  	
		<?php } else { ?>
	     <?php if ($this->viewer()->getIdentity())?>
	      <div class="tip">
	        <span>
			   <?php echo $this->translate('You have not yet created any campaigns. %1$sCreate an Ad campaign%2$s now!', '<a href="'. $this->url(array(), 'sitead_listpackage', true). '">', '</a>'); ?>
			    </span>
		  </div>
		<?php } ?>
		
	    <?php if (empty($this->is_ajax)) : ?>
		</div>
	</div>
  
   <?php if ($this->total_count > 0) { ?>
	  <div class="cadmc_statistics">
	  	<div>
		    <p>
		      <?php echo $this->translate("Use the below filter to observe various metrics of your ad campaigns over different time periods.") ?>
                <span style="font-weight: normal;">
           <?php echo $this->translate(array('(for last %s year)', '(for last %s years)', Engine_Api::_()->getApi('settings', 'core')->getSetting('ad.statistics.limit',3)), $this->locale()->toNumber(Engine_Api::_()->getApi('settings', 'core')->getSetting('ad.statistics.limit',3))) ?>
        </span>
		    </p>
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
					'ad_subject' : 'campaign',
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
                    title: 'Campaign Stats'
                     },
				};
				var chart = new google.visualization.LineChart(document.getElementById('my_chart'));
				chart.draw(data, options);
			}
		</script>
	</div>
		</div>	
	</div>
	<?php }  ?>
</div> 
<?php endif; ?>
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