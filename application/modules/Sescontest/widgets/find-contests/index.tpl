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

<?php $this->headTranslate(array('SesSun','SesMon','SesTue','SesWed','SesThu','SesFri','SesSat',"SesJan", "SesFeb", "SesMar", "SesApr", "SesMay", "SesJun", "SesJul", "SesAug", "SesSep", "SesOct", "SesNov", "SesDec"));?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/scripts/moment.min.js'); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/scripts/daterangepicker.min.js'); ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/styles/daterangepicker.css'); ?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/styles/styles.css'); ?>
<div class="sescontest_find_contest_block sesbasic_bxs">
  <div class="_dayswrapper sesbasic_clearfix">
    <?php if(isset($this->todayActive)):?>
      <div class="_day">
        <a href="<?php echo $this->url(array('action' => 'browse'),'sescontest_general',true);?>?show=today" style="background-color:#4267b2;">
          <img src="application/modules/Sescontest/externals/images/today-icon.png">
          <span><?php echo $this->translate('Today');?></span>
        </a>
      </div>
    <?php endif;?>
    <?php if(isset($this->tomorrowActive)):?>
      <div class="_day">
          <a href="<?php echo $this->url(array('action' => 'browse'),'sescontest_general',true);?>?show=tomorrow" style="background-color:#fcd872;">
          <img src="application/modules/Sescontest/externals/images/tomorrow-icon.png">
          <span><?php echo $this->translate('Tomorrow');?></span>
        </a>
      </div>
    <?php endif;?>
    <?php if(isset($this->weekActive)):?>
      <div class="_day">
          <a href="<?php echo $this->url(array('action' => 'browse'),'sescontest_general',true);?>?show=week" style="background-color:#f35369;">
          <img src="application/modules/Sescontest/externals/images/week-icon.png">
          <span><?php echo $this->translate('This Week');?></span>
        </a>
      </div>
    <?php endif;?>
    <?php if(isset($this->nextweekActive)):?>
      <div class="_day">
        <a href="<?php echo $this->url(array('action' => 'browse'),'sescontest_general',true);?>?show=nextweek" style="background-color:#53abf3;">
        <img src="application/modules/Sescontest/externals/images/week-icon.png">
        <span><?php echo $this->translate('Next Week');?></span>
        </a>
      </div>
    <?php endif;?>
    <?php if(isset($this->monthActive)):?>
    <div class="_day">
      <a href="<?php echo $this->url(array('action' => 'browse'),'sescontest_general',true);?>?show=month" style="background-color:#39c355;">
      <img src="application/modules/Sescontest/externals/images/calendar-icon.png">
      <span><?php echo $this->translate('This Month');?></span>
      </a>
    </div>
    <?php endif;?>
    <?php if(isset($this->dateCriteriaActive)):?>
      <div class="_day">
        <input class="choose_date_input" type ="text" id="ses-show-calendar">
        <a href="javascript:;" style="background-color:#bec2c9;">
          <img src="application/modules/Sescontest/externals/images/calendar-plus-icon.png">
          <span><?php echo $this->translate('Choose Date');?></span>
        </a>
      </div>
    <?php endif;?>
  </div>
  <?php if(isset($this->categoryActive)):?>
    <?php $count = 1;?>
     <?php $countMore = 1;?>
    <div class="_categories">
      <ul>
        <?php foreach($this->categories as $category):?>
          <?php if($count <= $this->params['limit_data']):?>
            <?php if($count == 1):?>
              <ul>
            <?php endif;?>
                <li class="_cat"><a href="<?php echo $this->url(array('action' => 'browse'),'sescontest_general',true);?>?category_id=<?php echo $category->category_id;?>">
                <?php if($category->colored_icon){
                      $str = Engine_Api::_()->getItem('storage_file',$category->colored_icon);
                      if($str){
                 ?>
                	<i><img src="<?php echo $str->getPhotoUrl(); ?>" /></i>
                <?php } } ?>
								<?php if(empty($str)){ ?>
                  <i><img src="application/modules/Sesbasic/externals/images/category.png" alt="" /></i>
                 <?php } ?>
                  <span><?php echo $category->category_name;?></span></a>
                </li>
          <?php else:?>
            <?php if($countMore == 1):?>
              <?php if($this->params['viewMore'] == 'yes'):?>
                <li id="show-more" class="more"><a href="javascript:;" onclick="showMoreLessCategory('more');"><span><?php echo $this->translate('More');?></span><i class="fa fa-caret-down"></i></a></li>
              <?php endif;?>
               </ul>
                <ul id="show_all_cat" style="display:none;">
              <?php endif;?>
              <li class="_cat">
              	<a href="<?php echo $this->url(array('action' => 'browse'),'sescontest_general',true);?>?category_id=<?php echo $category->category_id;?>">
               		<?php if($category->colored_icon){
                      $str = Engine_Api::_()->getItem('storage_file',$category->colored_icon);
                      if($str){
                 ?>
                	<i><img src="<?php echo $str->getPhotoUrl(); ?>" /></i>
                <?php } } ?>
								<?php if(empty($str)){ ?>
                  <i><img src="application/modules/Sesbasic/externals/images/category.png" alt="" /></i>
                <?php } ?>  
                <span><?php echo $category->category_name;?></span></a>
              </li>
              <?php if($count == engine_count($this->categories)):?>
              <?php if($this->params['viewMore'] == 'yes'):?>
                <li id="show-less"  class="more"><a href="javascript:;" onclick="showMoreLessCategory('less');"><span><?php echo $this->translate('Less');?></span><i class="fa fa-caret-up"></i></a></li>
              <?php endif;?>
            </ul>
            <?php endif;?>
            <?php $countMore++;?>
          <?php endif;?>
          <?php $count++;?>
        <?php endforeach;?>
      </ul>
    </div>
  <?php endif;?>
</div>
<script>
  
  scriptJquery(document).ready(function() {
    scriptJquery(function() {
      scriptJquery('#ses-show-calendar').daterangepicker({
        opens: 'left'
      }, function(start, end, label) {
        console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
      });
    });
  });
  
  function selectedDateRedirect(){
    var dateRange = scriptJquery('#ses-show-calendar').val();
    var explodedData = dateRange.split('-');
    var starttime = explodedData[0];
    var endtime = explodedData[1];
    var url = "<?php echo $this->url(array('action' => 'browse'),'sescontest_general',true);?>"+'?starttime='+starttime+'&endtime='+endtime;
    window.location.href =url;
  }
  function showMoreLessCategory(type) {
    if(type == 'less'){
      scriptJquery('#show_all_cat').hide();
      scriptJquery('#show-more').show();
    }
    else {
      scriptJquery('#show_all_cat').show();
      scriptJquery('#show-more').hide();
    }
  }
</script>
<style>
  .datepicker .footer button.apply:before{content:"<?php echo $this->translate('Search')?>";}
  .datepicker .footer button.cancel:before{content:"<?php echo $this->translate('Cancel')?>";}
</style>
