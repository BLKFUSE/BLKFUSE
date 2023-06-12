<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesbday
 * @package    Sesbday
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2018-12-20 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php $class = "sessmoothbox";?>
<?php if(!$this->is_ajax){?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbday/externals/styles/styles.css'); ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbday/externals/styles/calendar.css'); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/sessmoothbox/sessmoothbox.js'); ?>
<?php } ?>
<?php 
  $events = $this->users;
  $month = $this->month;
  $year = $this->year;
?>
<?php if(!$this->is_ajax){?>
 <?php if($this->loadData != 'nextprev'){ ?>
 <div class="sesbasic_load_btn sesbasic_view_more_loading_<?php echo $randonNumber;?>" id="loading_image_<?php echo $this->identity; ?>" style="display: none;"><span class="sesbasic_link_btn"><i class="fa fa-spinner fa-spin"></i></span> </div> 
<?php }else{ ?>
	<div class="sesbasic_loading_cont_overlay" id="sesbasic_loading_cont_overlay_<?php echo $this->identity ?>" style="display:none"></div>
<?php } ?>
<ul id="calander_data_<?php echo $this->identity ?>">
<?php } ?>
<li class="sesbday_calendar_container sesbasic_bxs sesbasic_clearfix" id="sescalander_<?php echo $month; ?>">
    <div class="sesbday_calendar_header sesbasic_clearfix">
     <?php if($this->loadData != 'nextprev'){ ?>
      <div class="sesbday_calendar_header_left_btns floatL"> 
      	<a href="javascript:;" class="previousCal" onclick="previousCal('<?php echo $this->year ?>','<?php echo $this->month ?>')"><i class="fa fa-chevron-up"></i></a> 
        <a href="javascript:;" class="nextCal" onclick="nextCal('<?php echo $this->year ?>','<?php echo $this->month ?>');"><i class="fa fa-chevron-down"></i></a> 
      </div>
 <?php } ?>
    <div class="sesbday_calendar_header_name floatL"><?php echo $this->translate(date('F',strtotime($year.'-'.$month))).' '.$year; ?></div>
    <?php if($this->loadData == 'nextprev'){ ?>
    	<div class="sesbday_calendar_header_right_btns"> 
      <a href="javascript:;" onclick="previousNextFixCal('<?php echo $this->year ?>','<?php echo $this->month ?>','prev');"><i class="fa fa-angle-left"></i></a> 
      <a href="javascript:;" onclick="previousNextFixCal('<?php echo $this->year ?>','<?php echo $this->month ?>','next');"><i class="fa fa-angle-right right"></i></a> 
     </div>
    <?php } ?>
  </div>
  <div class="sesbday_calendar_main"> 
    <?php
  /* draw table */
	
	$calendar = '<table cellpadding="0" cellspacing="0">';
	/* table headings */
	$headings = array($this->translate('Sun'),$this->translate('Mon'),$this->translate('Tue'),$this->translate('Wed'),$this->translate('Thu'),$this->translate('Fri'),$this->translate('Sat'));
	$calendar.= "<thead><tr><th><div class='day'>".implode('</th><th><div class="day">',$headings).'</th></tr><thead>';
	/* days and weeks vars now ... */
	$running_day = date('w',mktime(0,0,0,$month,1,$year));
	$days_in_month = date('t',mktime(0,0,0,$month,1,$year));
	$days_in_this_week = 1;
	$day_counter = 0;
	$dates_array = array();

	/* row for week one */
	$calendar.= '<tbody><tr class="calendar-row">';
	$lastDayOfPreviousMonth = date('Y-m-d',strtotime('last day of previous month',strtotime(date($year.'-'.$month.'-10'))));
 	$firstDayOfPreviousMonth = date('Y-m-d',strtotime('first day of next month',strtotime(date($year.'-'.$month.'-10'))));
 	$running_day_d = $running_day -1;
	/* print "blank" days until the first of the current week */
	for($x = 0; $x < $running_day; $x++):
  	$daysTxt = ($running_day_d) > 1 ? '-'.($running_day_d).' days' : '-'.($running_day_d).' day';
		$calendar.= '<td><div class="date_inactive"><span class="date">'.ltrim(date('d',strtotime($daysTxt,strtotime($lastDayOfPreviousMonth))),'0').'</span></div></td>';
		$days_in_this_week++;
    $running_day_d--;
	endfor;

	/* keep going with days.... */
	for($list_day = 1; $list_day <= $days_in_month; $list_day++):
		$event_day = $month.'-'.$list_day;
		$calendar.= "<td><div class=".(isset($events[$event_day]) ? "day_active" : "day").">";
    if(strlen($list_day) == 1)
      	$list_day_checklist_day = '0'.$list_day;
      else
      	$list_day_checklist_day = $list_day;
		$event_day = $month.'-'.$list_day_checklist_day;
    $url = $this->url(array('module' => 'sesbday', 'controller' => 'index', 'action' => 'get-users'), 'default', true);
    $calendar .='<a href="'.$url.'" rel="'.$year.'-'.$month.'-'.$list_day_checklist_day.'" class="sesbday_calendar_date_link sessmoothbox"></a>';
			/* add in the day number */
			$calendar.= '<a href="'.$url.'" rel="'.$year.'-'.$month.'-'.$list_day_checklist_day.'" class="date sessmoothbox">'.$list_day.'</a>';
      
	if(isset($events[$event_day])) {
      $calendar .='<ul class="sesbday_calendar_event_list">';
      $counter = 1;
      foreach($events[$event_day] as $key=>$event) {
        if($counter > $this->viewMoreAfter){
						$calendar .= '<li class="more"><a href="'.$url.'"rel="'.$year.'-'.$month.'-'.$list_day_checklist_day.'" class="sessmoothbox">+ '.(engine_count($events[$event_day]) - $counter + 1).'</a></li>';
            break;
         }
        $calendar.= '<li><a href="'.$event->getHref().'" class="" data-src="'.$event->getGuid().'" title="'.$event->getTitle().'"><img src="'.$event->getPhotoUrl().'" class="thumb_icon item_photo_user thumb_icon"></a></li>';
        $counter++;
        //more setting
      }      
      $calendar .= '</ul>';
    }
		$calendar.= '</div></td>';
		if($running_day == 6):
			$calendar.= '</tr>';
			if(($day_counter+1) != $days_in_month):
				$calendar.= '<tr>';
			endif;
			$running_day = -1;
			$days_in_this_week = 0;
		endif;
		$days_in_this_week++; $running_day++; $day_counter++;
	endfor;
	/* finish the rest of the days in the week */
	if($days_in_this_week < 8):
		for($x = 1; $x <= (8 - $days_in_this_week); $x++):
			$daysTxt = ($x - 1) > 1 ? '+'.($x - 1).' days' : '+'.($x - 1).' day';
			$calendar.= '<td><div class="date_inactive"><span class="date">'.ltrim(date('d',strtotime($daysTxt,strtotime($firstDayOfPreviousMonth))),'0').'</span></div></td>';
		endfor;
	endif;
	/* final row */
	$calendar.= '</tr></tbody>';
	/* end the table */
	$calendar.= '</table>';
	/** DEBUG **/
	$calendar = str_replace('</td>','</td>'."\n",$calendar);
	$calendar = str_replace('</tr>','</tr>'."\n",$calendar);
  echo $calendar;
  ?>
  </div>
</li>
<?php if($this->is_ajax){ ?>
<?php  die;
} ?>
</ul>
 <?php if($this->loadData != 'nextprev'){ ?>
  <div class="sesbasic_load_btn" style="display:none;" id="view_more_cal<?php echo $this->identity; ?>" onclick="viewMore_cal<?php echo $this->identity; ?>();" > <?php echo $this->htmlLink('javascript:void(0);', $this->translate('View More'), array('id' => "feed_viewmore_link_$this->identity", 'class' => 'sesbasic_animation sesbasic_link_btn fa fa-repeat')); ?> </div>
  <div class="sesbasic_load_btn sesbasic_view_more_loading_<?php echo $this->identity;?>" id="loading_image_next_<?php echo $this->identity; ?>" style="display: none;"><span class="sesbasic_link_btn"><i class="fa fa-spinner fa-spin"></i></span> </div> 
<?php } ?>
<script type="application/javascript">

function previousNextFixCal(year,month,type) {
  scriptJquery('#sesbasic_loading_cont_overlay_<?php echo $this->identity ?>').show();
  requestViewMoreCalNext = scriptJquery.ajax({
    method: 'post',
    dataType: 'html',
    url: en4.core.baseUrl + "widget/index/mod/sesbday/name/<?php echo $this->widgetName; ?>",
    data: {
      format: 'html',
      is_ajax : 1,
      identity : '<?php echo $this->identity; ?>',
      year:year,
      type:type,
      month:month,
      viewmore:'<?php echo $this->viewMoreAfter; ?>',
      loadData:'<?php echo $this->loadData; ?>',
    },
    success: function(responseHTML) {
      scriptJquery('#sesbasic_loading_cont_overlay_<?php echo $this->identity ?>').hide();
      scriptJquery('#calander_data_<?php echo $this->identity ?>').html(responseHTML);
      return false;
    }
  });
  return false;
}
</script>
