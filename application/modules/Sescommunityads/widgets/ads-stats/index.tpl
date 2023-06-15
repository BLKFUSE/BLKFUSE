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
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/datepicker/bootstrap-datepicker.css'); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/jquery1.11.js'); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/datepicker/bootstrap-datepicker.js'); ?>
<?php if(!$this->is_ajax):?>
<style>
.dispayF {
  display: block !important;
}
</style>
<div class="sescmads_stats_graph sesbasic_bxs">
  <div class="sesbasic_filter_tabs sesbasic_clearfix">
    <div>
      <input type="text" class="dispayF datepicker" value="<?php echo $this->startdate ?>" name="startdate" id="startdate">
      <input type="text" class="dispayF datepicker" value="<?php echo $this->enddate ?>" name="enddate" id="enddate">
      <button type="button" class="submitstats"><?php echo $this->translate('SESCOMMSearch'); ?></button>
    </div>
    <ul id="statsul">
      <?php if(isset($this->hourlyActive)):?>
      <li><a href="javascript:;" rel="hourly" onclick="showIntervalData('hourly', this)" id="vote-hourly" class="<?php if($this->view_type == 'hourly'):?>active<?php endif;?>"><?php echo $this->translate('SESCOMMHourly');?></a></li>
      <?php endif;?>
      <?php if(isset($this->dailyActive)):?>
      <li><a href="javascript:;" rel="daily" onclick="showIntervalData('daily', this)" id="vote-daily" class="<?php if($this->view_type == 'daily'):?>active<?php endif;?>"><?php echo $this->translate('SESCOMMDaily');?></a></li>
      <?php endif;?>
      <?php if(isset($this->weeklyActive)):?>
      <li><a href="javascript:;" rel="weekly" onclick="showIntervalData('weekly', this)" id="vote-weekly" class="<?php if($this->view_type == 'weekly'):?>active<?php endif;?>"><?php echo $this->translate('SESCOMMWeekly');?></a></li>
      <?php endif;?>
      <?php if(isset($this->monthlyActive)):?>
      <li><a href="javascript:;" rel="monthly" onclick="showIntervalData('monthly', this)" id="vote-monthly" class="<?php if($this->view_type == 'monthly'):?>active<?php endif;?>"><?php echo $this->translate('SESCOMMMonthly');?></a></li>
      <?php endif;?>
    </ul>
  </div>
  <div id="chartxcontainer" class="clear sescmads_stats_graph_box">
    <div class="sesbasic_loading_container"></div>
  </div>
  <div id="sescommunityads-graph-loading" class="sesbasic_loading_container sescommunityads_view_graph_box clear" style="display: none;"></div>
</div>
<script src="https://code.highcharts.com/highcharts.js"></script>
<?php endif;?>
<script type='text/javascript'>//<![CDATA[

scriptJquery(document).on('click','.submitstats',function(e){
  showIntervalData(scriptJquery('#statsul').children().find('.active').attr('rel'),scriptJquery('#statsul').children().find('.active'),'1');  
})
function setDateData(containerId,date,ctlCount,clickCount,viewCount,headingTitle,baseTitle,clickHeadingTitle,clickBaseTitle, ctlHeadingTitle, ctlBaseTitle,viewHeadingTitle,viewBaseTitle) {
  var jsarrya =date;
    if(typeof date == 'undefined')  {
      jsarrya = [<?php echo implode(',',$this->date)?>];
    }
    
    if(typeof clickCount == 'undefined')  {
      clickCount = [<?php echo implode(',',$this->clickCount)?>];
    }
    if(typeof ctlCount == 'undefined')  {
      ctlCount = [<?php echo implode(',',$this->ctlCount)?>];
    }
    
    if(typeof viewCount == 'undefined')  {
      viewCount = [<?php echo implode(',',$this->viewCount)?>];
    }
    if(typeof headingTitle == 'undefined')  {
      headingTitle = '<?php echo $this->headingTitle?>';
    }
    if(typeof baseTitle == 'undefined')  {
      baseTitle = '<?php echo $this->XAxisTitle?>';
    }
    
    if(typeof clickHeadingTitle == 'undefined')  {
      clickHeadingTitle = '<?php echo $this->clickHeadingTitle?>';
    }
    
    if(typeof ctlHeadingTitle == 'undefined')  {
      ctlHeadingTitle = '<?php echo $this->ctlHeadingTitle?>';
    }
    
    if(typeof ctlBaseTitle == 'undefined')  {
      ctlBaseTitle = '<?php echo $this->ctlBaseTitle?>';
    }
    if(typeof clickBaseTitle == 'undefined')  {
      clickBaseTitle = '<?php echo $this->clickBaseTitle?>';
    }
    
    
    if(typeof viewHeadingTitle == 'undefined')  {
      viewHeadingTitle = '<?php echo $this->viewHeadingTitle?>';
    }
    if(typeof viewBaseTitle == 'undefined')  {
      viewBaseTitle = '<?php echo $this->viewXAxisTitle?>';
    }
    Highcharts.chart(containerId, {
         	chart: {
            type: 'spline',
            animation: Highcharts.svg,
            marginRight: 10 ,
			},
			title: {text: headingTitle,},    
	  xAxis: { 
	       categories: jsarrya,
     			title: {text: '<?php echo $this->translate("SESCOMMDate"); ?>'},    
     		}, 
			 yAxis: {
            title: {text: '<?php echo $this->translate("Campaign Stats"); ?>'},
    		tooltip: {
    			shared: !0,
    			crosshairs: !0
    		},
    		plotOptions: {
    			line: {
    				dataLabels: {
    					enabled: !1
    				},
    				enableMouseTracking: !0
    			},
    			credits: {
    				enabled: !1
    			},
    			 
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
    			series: {
    				allowPointSelect: true,
    				marker: {
    					enabled: true
    				}
    			}
    		},
    		series: [
           <?php if($this->ad->type != "boost_post_cnt"){ ?>
           {
    			name: ctlBaseTitle,
    			data: ctlCount,
            color:"<?php echo $this->ctlinecolor;?>"      
          },
                {
    			name: clickBaseTitle,
    			data: clickCount,
                color:"<?php echo $this->clicklinecolor;?>"      
          },
             <?php } ?>
                {
    			name: viewBaseTitle,
    			data: viewCount,
                color:"<?php echo $this->viewlinecolor;?>"      
          }]
			 
		
    });
	
}

scriptJquery(document).ready(function () {
    sesBasicAutoScroll('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        weekStart: 1,
        autoclose: true,
    })
    Highcharts.setOptions({
        global: {
            useUTC: false
        }
    });
   setDateData('chartxcontainer');
});
//]]> 

  function showIntervalData(interval, obj,submitVal) {
    if(scriptJquery(obj).hasClass('active') && typeof submitVal == "undefined")
      return;
    scriptJquery('#chartxcontainer').hide();
    scriptJquery('#sescommunityads-graph-loading').show();
    scriptJquery('#vote-monthly').removeClass('active');
    scriptJquery('#vote-weekly').removeClass('active');
    scriptJquery('#vote-daily').removeClass('active');
    scriptJquery('#vote-hourly').removeClass('active');
    scriptJquery(obj).addClass('active');
    var url = en4.core.baseUrl + "widget/index/mod/sescommunityads/name/ads-stats/interval/"+interval;
    var request = scriptJquery.ajax({
      dataType: 'html',
      'url' : url,
      'method' : 'POST',
      'data':{
        'is_ajax':1,
        'startdate' : scriptJquery('#startdate').val(),
        'enddate' : scriptJquery('#enddate').val(),
        'sescommunityad_id': '<?php echo $this->subject->sescommunityad_id;?>',
        'type':scriptJquery(obj).attr('rel'),
      },   
      success: function(responseJSON) {
        
        setDateData('chartxcontainer', responseJSON.date, responseJSON.ctlCount,responseJSON.clickCount, responseJSON.viewCount,responseJSON.headingTitle,responseJSON.XAxisTitle, responseJSON.clickHeadingTitle, responseJSON.clickXAxisTitle, responseJSON.ctlHeadingTitle, responseJSON.ctlXAxisTitle, responseJSON.viewHeadingTitle, responseJSON.viewXAxisTitle);
        scriptJquery('#sescommunityads-graph-loading').hide();
        scriptJquery('#chartxcontainer').show();
      }
    });
    
  }
</script> 
