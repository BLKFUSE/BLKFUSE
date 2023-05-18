<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2016-05-25 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl'). 'application/modules/Sesmember/externals/scripts/core.js'); ?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesmember/externals/styles/styles.css'); ?>

<div class="sesmember_review_view sesmember_profile_review sesbasic_bxs sesbasic_clearfix">
  <div class="sesmember_review_view_top sesbasic_clearfix">
    <?php if(is_array($this->stats) && engine_in_array('title', $this->stats)): ?>
    	<div class="sesmember_review_view_title"><?php echo $this->review->getTitle() ?></div>
    <?php endif; ?>
    <div class="sesmember_review_view_item_info sesbasic_clearfix">
      <div class="sesmember_review_view_info_img">
        <?php if(engine_in_array('postedin', $this->stats)): ?>
        <?php echo $this->htmlLink($this->item->getHref(), $this->itemPhoto($this->item, 'thumb.icon')); ?>
        <?php endif; ?>
      </div>
      <div class="sesmember_review_view_info_cont sesbasic_clearfix">
        <p class='sesmember_review_view_stats sesbasic_text_light sesbasic_clearfix'>
          <?php if(engine_in_array('postedin', $this->stats)): ?>
          	<?php echo $this->translate('For');?> <?php echo $this->htmlLink($this->item, $this->item) ?>
          <?php endif; ?>
          <?php if(engine_in_array('postedin', $this->stats) && engine_in_array('creationDate', $this->stats)) : ?> | <?php endif; ?>
          <?php if(is_array($this->stats) && engine_in_array('creationDate', $this->stats)): ?>
            <?php echo $this->translate('about').' '.$this->timestamp($this->review->creation_date) ?></p>
          <?php endif; ?>
        </p>
        <p class="sesmember_review_view_stats sesbasic_text_light">
          <?php if(is_array($this->stats) && engine_in_array('likeCount', $this->stats)): ?>
          <span><i class="far fa-thumbs-up"></i><?php echo $this->translate(array('%s like', '%s likes', $this->review->like_count), $this->locale()->toNumber($this->review->like_count)); ?></span>
          <?php endif; ?>
          <?php if(is_array($this->stats) && engine_in_array('commentCount', $this->stats)): ?>
          <span><i class="far fa-comment"></i><?php echo $this->translate(array('%s comment', '%s comments', $this->review->comment_count), $this->locale()->toNumber($this->review->comment_count))?></span>
          <?php endif; ?>
          <?php if(is_array($this->stats) && engine_in_array('viewCount', $this->stats)): ?>
          <span><i class="far fa-eye"></i><?php echo $this->translate(array('%s view', '%s views', $this->review->view_count), $this->locale()->toNumber($this->review->view_count))?></span>
          <?php endif; ?>
        </p>

      </div>
    </div>
    <div class="sesmember_review_show_rating review_ratings_listing">
      <?php if(engine_in_array('rating', $this->stats)){ ?>
        <div class="sesbasic_rating_star sesmember_review_listing_star">
          <?php $ratingCount = $this->review->rating;?>
          <?php for($i=0; $i<5; $i++){?>
          <?php if($i < $ratingCount):?>
          <span id="" class="sesmember_rating_star"></span>
          <?php else:?>
          <span id="" class="sesmember_rating_star sesmember_rating_star_disable"></span>
          <?php endif;?>
          <?php }?>
        </div>
      <?php } ?>
      <?php if(engine_in_array('parameter', $this->stats)){ ?>
      <?php $reviewParameters = Engine_Api::_()->getDbtable('parametervalues', 'sesmember')->getParameters(array('content_id'=>$this->review->getIdentity(),'user_id'=>$this->review->owner_id)); ?>
      <?php if(engine_count($reviewParameters)>0){ ?>
      <div class="sesmember_review_show_rating_box sesbasic_clearfix">
        <?php foreach($reviewParameters as $reviewP){ ?>
          <div class="sesbasic_clearfix">
            <div class="sesmember_review_show_rating_label"><?php echo $reviewP['title']; ?></div>
            <div class="sesmember_review_show_rating_parameters sesbasic_rating_parameter sesbasic_rating_parameter_small">
              <?php $ratingCount = $reviewP['rating'];?>
              <?php for($i=0; $i<5; $i++){?>
              <?php if($i < $ratingCount):?>
              <span id="" class="sesbasic-rating-parameter-unit"></span>
              <?php else:?>
              <span id="" class="sesbasic-rating-parameter-unit sesbasic-rating-parameter-unit-disable"></span>
              <?php endif;?>
              <?php }?>
            </div>
          </div>
        <?php } ?>
      </div>
      <?php } 
    }
    ?>
    </div>
  </div>
  
  <div class="sesmember_review_contant_disc">
    <?php if(engine_in_array('pros', $this->stats) && $this->review->pros): ?>
    	<div class="sesmember_review_view_cont_row"> <b class="label"><?php echo $this->translate("Pros"); ?></b> <?php echo $this->review->pros;  ?> </div>
    <?php endif; ?>
    <?php if(engine_in_array('cons', $this->stats) && $this->review->cons): ?>
    	<div class="sesmember_review_view_cont_row"> <b class="label"><?php echo $this->translate("Cons"); ?></b> <?php echo $this->review->cons;  ?> </div>
    <?php endif; ?>
    <?php if(engine_in_array('description', $this->stats)): ?>
    	<div class='sesmember_review_view_cont_row'> <b class="label"><?php echo $this->translate("Summary"); ?></b>
      	<div class="sesbasic_html_block"><?php echo $this->review->description;  ?></div>
      </div>
    <?php endif; ?>
    <?php if(engine_in_array('recommended', $this->stats)): ?>
      <div class="sesmember_review_view_cont_row sesmember_review_view_recommended"> <b><?php echo $this->translate("Recommended"); ?>
        <?php if($this->review->recommended): ?>
	  <i class="fa fa-check"></i></b>
        <?php else: ?>
	  <i class="fa fa-times"></i></b>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
  <?php  echo $this->partial('_reviewOptions.tpl','sesmember',array('subject'=>$this->review,'viewer'=> Engine_Api::_()->user()->getViewer(),'stats'=>$this->stats)); ?>  
</div>
