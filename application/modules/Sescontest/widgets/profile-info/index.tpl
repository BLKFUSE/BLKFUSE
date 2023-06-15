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

<ul class='sesbasic_clearfix sesbasic_bxs sesbasic_sidebar_block sescontest_sidebar_info_block'>
   <?php if(engine_in_array('owner',$this->criteria)){ ?>
      <li class="sesbasic_clearfix sescontest_list_stats  sescontest_list_created_by">
        <span>
        <i title='<?php echo $this->translate("Created by"); ?>' class="fa fa-user sesbasic_text_light"></i>
        <span><a href="<?php echo $this->subject->getOwner()->getHref(); ?>"><?php echo $this->translate('by ');?><?php echo $this->subject->getOwner()->getTitle(); ?></a></span>
        </span>
      </li>
   <?php } ?>
   <?php if(engine_in_array('creationDate',$this->criteria)){ ?>
    <li class="sesbasic_clearfix sescontest_list_stats sescontest_list_created_on">
      <span>
       <i title='<?php echo $this->translate("Created on"); ?>' class="fa fa-edit sesbasic_text_light"></i>
      	<span><?php echo $this->translate('%1$s', $this->timestamp($this->subject->creation_date)); ?></span>
      </span>
    </li>
  <?php } ?>
    <?php if(engine_in_array('tag',$this->criteria) && engine_count($this->contestTags)){ ?>
   <li class="sesbasic_clearfix sescontest_list_stats sescontest_list_tag">
    <span class="widthfull">
          <i title='<?php echo $this->translate("Tags"); ?>' class="fa fa-tag sesbasic_text_light"></i>
          <span>
            <?php 
            	$counter = 1;
            	 foreach($this->contestTags as $tag):
                if($tag->getTag()->text != ''){ ?>
                  <a href='javascript:void(0);' onclick='javascript:tagAAAction(<?php echo $tag->getTag()->tag_id; ?>,"<?php echo $tag->getTag()->text; ?>");'>#<?php echo $tag->getTag()->text ?></a>
                  <?php if(engine_count($this->contestTags) != $counter){ 
                  	echo ",";	
                   } ?>
          <?php	 } 
          		$counter++;
              endforeach;  ?>
          </span>
          </span>
        </li>
     <?php } ?>
  <?php if(engine_in_array('mediaType',$this->criteria)){ ?>
    <li class="sesbasic_clearfix sescontest_list_stats sescontest_list_media">
      <span class="widthfull">
        <i title="<?php echo $this->translate('Media: '); ?>" class="fa fa-film sesbasic_text_light"></i>
        <?php if($this->subject->contest_type == 3):?>
          <a href="<?php echo $this->url(array('action' => 'video'),'sescontest_media',true);?>"><?php echo $this->translate('Video Contest');?></a>
        <?php elseif($this->subject->contest_type == 4):?>
          <a href="<?php echo $this->url(array('action' => 'audio'),'sescontest_media',true);?>"><?php echo $this->translate('Audio Contest');?></a>
        <?php elseif($this->subject->contest_type == 2):?>
          <a href="<?php echo $this->url(array('action' => 'photo'),'sescontest_media',true);?>"><?php echo $this->translate('Photo Contest');?></a>
        <?php else:?>
          <a href="<?php echo $this->url(array('action' => 'text'),'sescontest_media',true);?>"><?php echo $this->translate('Writing Contest');?></a>
        <?php endif;?>
      </span>
    </li>
   <?php } ?>
  <?php if(engine_in_array('categories',$this->criteria)){ ?>
  <?php $category = Engine_Api::_()->getItem('sescontest_category',$this->subject->category_id); ?>
   <?php if($category){ ?>
    <li class="sesbasic_clearfix sescontest_list_stats sescontest_list_category">
      <span>
      <i title="<?php echo $this->translate("Category"); ?>" class="fa fa-tags sesbasic_text_light"></i>
      <span><a href="<?php echo $category->getHref(); ?>"><?php echo $category->category_name; ?></a>
        <?php $subcategory = Engine_Api::_()->getItem('sescontest_category',$this->subject->subcat_id); ?>
         <?php if($subcategory && $this->subject->subcat_id != 0){ ?>
            &nbsp;&raquo;&nbsp;<a href="<?php echo $subcategory->getHref(); ?>"><?php echo $subcategory->category_name; ?></a>
        <?php $subsubcategory = Engine_Api::_()->getItem('sescontest_category',$this->subject->subsubcat_id); ?>
         <?php if($subsubcategory && $this->subject->subsubcat_id != 0){ ?>
            &nbsp;&raquo;&nbsp;<a href="<?php echo $subsubcategory->getHref(); ?>"><?php echo $subsubcategory->category_name; ?></a>
        <?php } ?>
      <?php } ?></span></span>
    </li>
    <?php }          
  } ?>
    <?php if(engine_in_array('date',$this->criteria)){ ?>
      <li class="sesbasic_clearfix sescontest_list_stats sescontest_list_start_time">
        <span class="widthfull">
          <i title="<?php echo $this->translate('Contest Start & End Date'); ?>" class="fa fa-calendar sesbasic_text_light"></i>
          <?php $dateinfoParams['starttime'] = true; ?>
          <?php $dateinfoParams['endtime']  =  true; ?>
          <?php $dateinfoParams['timezone']  =  true; ?>
          <?php echo $this->contestStartEndDates($this->subject,$dateinfoParams); ?>
        </span>
      </li>
     <?php } ?>
   <?php if(engine_in_array('entryDate',$this->criteria)){ ?>
    <li class="sesbasic_clearfix sescontest_list_stats sescontest_list_entry_date">
      <span class="widthfull">
        <i title="<?php echo $this->translate('Entry Submission Start & End Date'); ?>" class="fa fa-calendar sesbasic_text_light"></i>
        <?php $dateinfoParams = array();?>
        <?php $dateinfoParams['joinstarttime'] = true; ?>
        <?php $dateinfoParams['joinendtime']  =  true; ?>
        <?php $dateinfoParams['timezone']  =  true; ?>
        <?php echo $this->contestStartEndDates($this->subject,$dateinfoParams); ?>
      </span>
    </li>
   <?php } ?>
   <?php if(engine_in_array('voteDate',$this->criteria)){ ?>
    <li class="sesbasic_clearfix sescontest_list_stats sescontest_voting_date">
      <span class="widthfull">
        <i title="<?php echo $this->translate('Voting Start & End Date'); ?>" class="fa fa-calendar sesbasic_text_light"></i>
        <?php $dateinfoParams = array();?>
        <?php $dateinfoParams['votingstarttime'] = true; ?>
        <?php $dateinfoParams['votingendtime']  =  true; ?>
        <?php $dateinfoParams['timezone']  =  true; ?>
        <?php echo $this->contestStartEndDates($this->subject,$dateinfoParams); ?>
      </span>
    </li>
   <?php } ?>
   <?php if(engine_count(array_intersect($this->criteria,array('like','comment','favourite','view','follow','entryCount'))) > 0):?>
      <li class="sesbasic_clearfix sescontest_list_statics">
        <span class="widthfull">
          <i title="<?php echo $this->translate('Statistics'); ?>" class="fas fa-chart-bar sesbasic_text_light"></i>
          <span>
          <?php if(engine_in_array('comment',$this->criteria)){ ?>
            <span title="<?php echo $this->translate(array('%s comment', '%s comments', $this->subject->comment_count), $this->locale()->toNumber($this->subject->comment_count)); ?>"><?php echo $this->translate(array('%s comment', '%s comments', $this->subject->comment_count), $this->locale()->toNumber($this->subject->comment_count)); ?></span>,
            <?php } ?>
           <?php if(engine_in_array('like',$this->criteria)){ ?>
            <span title="<?php echo $this->translate(array('%s like', '%s likes', $this->subject->like_count), $this->locale()->toNumber($this->subject->like_count)); ?>"><?php echo $this->translate(array('%s like', '%s likes', $this->subject->like_count), $this->locale()->toNumber($this->subject->like_count)); ?></span>, 
             <?php } ?>
           <?php if(engine_in_array('view',$this->criteria)){ ?>
            <span title="<?php echo $this->translate(array('%s view', '%s views', $this->subject->view_count), $this->locale()->toNumber($this->subject->view_count)); ?>"><?php echo $this->translate(array('%s view', '%s views', $this->subject->view_count), $this->locale()->toNumber($this->subject->view_count)); ?></span>, 
             <?php } ?>
           <?php if(engine_in_array('favourite',$this->criteria)){ ?>
            <span title="<?php echo $this->translate(array('%s favourite', '%s favourites', $this->subject->favourite_count), $this->locale()->toNumber($this->subject->favourite_count)); ?>"><?php echo $this->translate(array('%s favourite', '%s favourites', $this->subject->favourite_count), $this->locale()->toNumber($this->subject->favourite_count)); ?></span>,
           <?php } ?>   
           <?php if(engine_in_array('follow',$this->criteria)){ ?>
            <span title="<?php echo $this->translate(array('%s follower', '%s followers', $this->subject->follow_count), $this->locale()->toNumber($this->subject->follow_count)); ?>"><?php echo $this->translate(array('%s follower', '%s followers', $this->subject->follow_count), $this->locale()->toNumber($this->subject->follow_count)); ?></span>,
           <?php } ?>  
           <?php if(engine_in_array('entryCount',$this->criteria)){ ?>
            <span title="<?php echo $this->translate(array('%s entry', '%s entries', $this->subject->join_count), $this->locale()->toNumber($this->subject->join_count)); ?>"><?php echo $this->translate(array('%s entry', '%s entries', $this->subject->join_count), $this->locale()->toNumber($this->subject->join_count)); ?></span>
           <?php } ?>  
        </span>
      </span>
    </li>   
  <?php endif;?>
</ul>
<script type="application/javascript">
var tabId_pI = <?php echo $this->identity; ?>;
scriptJquery(document).ready(function() {
	tabContainerHrefSesbasic(tabId_pI);	
});
var tagAAAction = window.tagAAAction = function(tag,value){
	var url = "<?php echo $this->url(array('module' => 'sescontest','action'=>'browse'), 'sescontest_general', true) ?>?tag_id="+tag+'&tag_name='+value;
 window.location.href = url;
}
</script>
