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

<div class='sescontest_profile_info sesbasic_clearfix sesbasic_bxs'>
  <?php if(isset($this->infoActive)):?>
    <div class="sescontest_profile_info_row">
      <div class="sescontest_profile_info_head"><?php echo $this->translate("Basic Info"); ?></div>
      <ul class="sescontest_profile_info_row_info">
        <li class="sesbasic_clearfix">
          <span class="_l"><?php echo $this->translate("Created by"); ?></span>
          <span class="_v"><a href="<?php echo $this->subject->getOwner()->getHref(); ?>"><?php echo $this->subject->getOwner()->getTitle(); ?></a></span>
        </li>
        <li class="sesbasic_clearfix">
          <span class="_l"><?php echo $this->translate("Created on"); ?></span>
          <span class="_v"><?php echo $this->translate('%1$s', $this->timestamp($this->subject->creation_date)); ?></span>
        </li>
        <li class="sesbasic_clearfix">
          <span class="_l"><?php echo $this->translate("Stats"); ?></span>
          <span class="basic_info_stats _v">
            <span><?php echo $this->translate(array('<b>%s</b> Like', '<b>%s</b> Likes', $this->subject->like_count), $this->locale()->toNumber($this->subject->like_count)) ?>, </span>
            <span><?php echo $this->translate(array('<b>%s</b> Comment', '<b>%s</b> Comments', $this->subject->comment_count), $this->locale()->toNumber($this->subject->comment_count)) ?>, </span>
            <span><?php echo $this->translate(array('<b>%s</b> View', '<b>%s</b> Views', $this->subject->view_count), $this->locale()->toNumber($this->subject->view_count)) ?>, </span>
            <span><?php echo $this->translate(array('<b>%s</b> Favourite', '<b>%s</b> Favourites', $this->subject->favourite_count), $this->locale()->toNumber($this->subject->favourite_count)) ?></span>
            <span><?php echo $this->translate(array('<b>%s</b> Follow', '<b>%s</b> Followers', $this->subject->follow_count), $this->locale()->toNumber($this->subject->follow_count)) ?></span>
            <span><?php echo $this->translate(array('<b>%s</b> Entry', '<b>%s</b> Entries', $this->subject->join_count), $this->locale()->toNumber($this->subject->join_count)) ?></span>
          </span>
        </li>
        <?php if($this->subject->category_id){ ?>
          <?php $category = Engine_Api::_()->getItem('sescontest_category',$this->subject->category_id); ?>
          <?php if($category){ ?>
            <li class="sesbasic_clearfix">
              <span class="_l"><?php echo $this->translate("Category"); ?></span>
              <span class="_v"><a href="<?php echo $category->getHref(); ?>"><?php echo $category->category_name; ?></a>
                <?php $subcategory = Engine_Api::_()->getItem('sescontest_category',$this->subject->subcat_id); ?>
                <?php if($subcategory && $this->subject->subcat_id != 0){ ?>
                   &nbsp;&raquo;&nbsp;<a href="<?php echo $subcategory->getHref(); ?>"><?php echo $subcategory->category_name; ?></a>
                  <?php $subsubcategory = Engine_Api::_()->getItem('sescontest_category',$this->subject->subsubcat_id); ?>
                  <?php if($subsubcategory && $this->subject->subsubcat_id != 0){ ?>
                   &nbsp;&raquo;&nbsp;<a href="<?php echo $subsubcategory->getHref(); ?>"><?php echo $subsubcategory->category_name; ?></a>
                  <?php } ?>
                <?php } ?>
              </span>
            </li>
          <?php } ?>
        <?php } ?>
        <?php if(engine_count($this->contestTags)){ ?>
          <li class="sesbasic_clearfix">
            <span class="_l"><?php echo $this->translate("Tags"); ?></span>
            <span class="_v">
              <?php 
                  $counter = 1;
                   foreach($this->contestTags as $tag):
                  if($tag->getTag()->text != ''){ ?>
                    <a href='javascript:void(0);' onclick='javascript:tagAction(<?php echo $tag->getTag()->tag_id; ?>,"<?php echo $tag->getTag()->text; ?>");'>#<?php echo $tag->getTag()->text ?></a>
                    <?php if(engine_count($this->contestTags) != $counter){ 
                      echo ",";	
                     } ?>
            <?php } $counter++; endforeach;  ?>
            </span>
          </li>
        <?php } ?>
      </ul>
    </div>
  <?php endif;?>
  <?php if(isset($this->dateActive)):?>
    <div class="sescontest_profile_info_row">
      <div class="sescontest_profile_info_head"><?php echo $this->translate("When"); ?></div>
      <ul class="sescontest_profile_info_row_info">
        <li class="sesbasic_clearfix">
          <span class="when_contest_dit _v"><?php echo $this->contestStartEndDates($this->subject); ?></span>
        </li>
      </ul>
    </div>
  <?php endif;?>
  <?php if( !empty($this->subject->description) && isset($this->descriptionActive)): ?>
  <div class="sescontest_profile_info_row">
  	<div class="sescontest_profile_info_head"><?php echo $this->translate("Details"); ?></div>
      <ul class="sescontest_profile_info_row_info">
        <li class="sesbasic_clearfix"><?php echo nl2br($this->subject->description) ?></li>
      </ul>
    </div>
  <?php endif ?>
  <?php if(isset($this->overviewActive)): ?>
    <?php if($this->subject->overview):?>
      <div class="sescontest_profile_info_row">
        <div class="sescontest_profile_info_head"><?php echo $this->translate("Overview"); ?></div>
          <ul class="sescontest_profile_info_row_info">
            <li class="sesbasic_clearfix">
              <div class="sesbasic_html_block">
                <?php echo $this->subject->overview;?>
              </div>
            </li>
          </ul>
        </div>
    <?php endif; ?>
  <?php endif;?>
  <?php if(!empty($this->sesbasicFieldValueLoop($this->subject)) && isset($this->profilefieldActive)):?>
    <div class="sescontest_profile_info_row" id="sescontest_custom_fields_val">
      <div class="sescontest_profile_info_head"><?php echo $this->translate("Other Info"); ?></div>
      <div class="sescontest_view_custom_fields sescontest_profile_info_row_info">
        <?php echo $this->sesbasicFieldValueLoop($this->subject);?>
      </div>
    </div>
  <?php endif;?>


</div> 
<script type="application/javascript">
scriptJquery(document).ready(function(e){
	//var lengthCustomFi	= scriptJquery('#sescontest_profile_info_row_info').children().length;
	if(!scriptJquery('.sescontest_view_custom_fields').html()){
		scriptJquery('#sescontest_custom_fields_val').hide();
	}
})
var tabId_info = <?php echo $this->identity; ?>;
scriptJquery(document).ready(function() {
	tabContainerHrefSesbasic(tabId_info);	
});
var tagAction = window.tagAction = function(tag,value){
	var url = "<?php echo $this->url(array('module' => 'sescontest','action'=>'browse'), 'sescontest_general', true) ?>?tag_id="+tag+'&tag_name='+value;
 window.location.href = url;
}
</script>
