<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/scripts/core.js'); ?>

<?php if(!$this->is_ajax):?>
  <div class="sescontest_entry_view_graph sesbasic_bxs">
    <div class="sesbasic_filter_tabs sesbasic_clearfix">
      <ul>
        <?php if(isset($this->hourlyActive)):?>
          <li><a href="javascript:;" rel="hourly" onclick="showIntervalData('hourly', this)" id="vote-hourly" class="<?php if($this->view_type == 'hourly'):?>active<?php endif;?>"><?php echo $this->translate('Hourly');?></a></li>
        <?php endif;?>
        <?php if(isset($this->dailyActive)):?>
          <li><a href="javascript:;" rel="daily" onclick="showIntervalData('daily', this)" id="vote-daily" class="<?php if($this->view_type == 'daily'):?>active<?php endif;?>"><?php echo $this->translate('Daily');?></a></li>
        <?php endif;?>
        <?php if(isset($this->weeklyActive)):?>
          <li><a href="javascript:;" rel="weekly" onclick="showIntervalData('weekly', this)" id="vote-weekly" class="<?php if($this->view_type == 'weekly'):?>active<?php endif;?>"><?php echo $this->translate('Weekly');?></a></li>
        <?php endif;?>
        <?php if(isset($this->monthlyActive)):?>
          <li><a href="javascript:;" rel="monthly" onclick="showIntervalData('monthly', this)" id="vote-monthly" class="<?php if($this->view_type == 'monthly'):?>active<?php endif;?>"><?php echo $this->translate('Monthly');?></a></li>
        <?php endif;?>
      </ul>
    </div>
    <div id="chartxcontainer" class="clear sescontest_entry_view_graph_box"><div class="sesbasic_loading_container" style="height:300px;"></div></div>
    <div id="sescontest-graph-loading" class="sesbasic_loading_container sescontest_entry_view_graph_box clear" style="display: none;"></div>
  </div>
  <script src="https://code.highcharts.com/highcharts.js"></script>
<?php endif;?>
<script type='text/javascript'>//<![CDATA[
function setDateData(containerId,count,likeCount, commentCount, favouriteCount,viewCount, date,headingTitle,baseTitle,likeHeadingTitle,likBaseTitle,commentHeadingTitle,commentBaseTitle, favouriteHeadingTitle,favouriteBaseTitle, viewHeadingTitle,viewBaseTitle) {
  var jsarrya =date;
    if(typeof date == 'undefined')  {
      jsarrya = [<?php echo implode(',',$this->date)?>];
    }
    if(typeof count == 'undefined')  {
      count = [<?php echo implode(',',$this->voteCount)?>];
    }
    if(typeof likeCount == 'undefined')  {
      likeCount = [<?php echo implode(',',$this->like_count)?>];
    }
    if(typeof commentCount == 'undefined')  {
      commentCount = [<?php echo implode(',',$this->comment_count)?>];
    }
    if(typeof favouriteCount == 'undefined')  {
      favouriteCount = [<?php echo implode(',',$this->favourite_count)?>];
    }
    if(typeof viewCount == 'undefined')  {
      viewCount = [<?php echo implode(',',$this->view_count)?>];
    }
    if(typeof headingTitle == 'undefined')  {
      headingTitle = '<?php echo $this->headingTitle?>';
    }
    if(typeof baseTitle == 'undefined')  {
      baseTitle = '<?php echo $this->XAxisTitle?>';
    }
    if(typeof likeHeadingTitle == 'undefined')  {
      likeHeadingTitle = '<?php echo $this->likeHeadingTitle?>';
    }
    if(typeof likBaseTitle == 'undefined')  {
      likBaseTitle = '<?php echo $this->likeXAxisTitle?>';
    }
    if(typeof commentHeadingTitle == 'undefined')  {
      commentHeadingTitle = '<?php echo $this->commentHeadingTitle?>';
    }
    if(typeof commentBaseTitle == 'undefined')  {
      commentBaseTitle = '<?php echo $this->commentXAxisTitle?>';
    }
    if(typeof favouriteHeadingTitle == 'undefined')  {
      favouriteHeadingTitle = '<?php echo $this->favouriteHeadingTitle?>';
    }
    if(typeof favouriteBaseTitle == 'undefined')  {
      favouriteBaseTitle = '<?php echo $this->favouriteXAxisTitle?>';
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
     			title: {text: 'Date'},    
     		}, 
			 yAxis: {
            title: {text: 'Votes...'},
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
    		series: [{
    			name: baseTitle,
    			data: count,
                color:"<?php echo $this->params['votelinecolor'];?>"
                
          },
                {
    			name: likBaseTitle,
    			data: likeCount,
                color:"<?php echo $this->params['likelinecolor'];?>"      
          },
                {
    			name: commentBaseTitle,
    			data: commentCount,
                color:"<?php echo $this->params['commentlinecolor'];?>"      
          },
                {
    			name: favouriteBaseTitle,
    			data: favouriteCount,
                color:"<?php echo $this->params['favouritelinecolor'];?>"      
          },
                {
    			name: viewBaseTitle,
    			data: viewCount,
                color:"<?php echo $this->params['viewlinecolor'];?>"      
          }]
			 
		
    });
	
}
scriptJquery(document).ready(function () {
    Highcharts.setOptions({
        global: {
            useUTC: false
        }
    });
   setDateData('chartxcontainer');
});
//]]> 

  function showIntervalData(interval, obj) {
    if(scriptJquery(obj).hasClass('active'))
      return;
    scriptJquery('#chartxcontainer').hide();
    scriptJquery('#sescontest-graph-loading').show();
    scriptJquery('#vote-monthly').removeClass('active');
    scriptJquery('#vote-weekly').removeClass('active');
    scriptJquery('#vote-daily').removeClass('active');
    scriptJquery('#vote-hourly').removeClass('active');
    scriptJquery(obj).addClass('active');
    var url = en4.core.baseUrl + "widget/index/mod/sescontest/name/vote-graph/interval/"+interval;
    var request = scriptJquery.ajax({
      'url' : url,
      'method' : 'POST',
      'data':{
        'is_ajax':1,
        'entry_id': '<?php echo $this->subject->participant_id;?>',
        'type':scriptJquery(obj).attr('rel'),
        'widget_id':'<?php echo $this->widgetId;?>',
      },   
      success : function(responseJSON) {
        setDateData('chartxcontainer', responseJSON.voteCount, responseJSON.likeCount,responseJSON.commentCount, responseJSON.favouriteCount,responseJSON.viewCount,responseJSON.date, responseJSON.headingTitle, responseJSON.XAxisTitle, responseJSON.likeHeadingTitle, responseJSON.likeXAxisTitle, responseJSON.commentHeadingTitle, responseJSON.commentXAxisTitle,responseJSON.favouriteHeadingTitle, responseJSON.favouriteXAxisTitle,responseJSON.viewHeadingTitle, responseJSON.viewXAxisTitle);
        scriptJquery('#sescontest-graph-loading').hide();
        scriptJquery('#chartxcontainer').show();
      }
    });
    
  }
</script>
