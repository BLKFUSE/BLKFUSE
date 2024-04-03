<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sestutorial
 * @package    Sestutorial
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2017-10-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>

<?php $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl'). 'application/modules/Sestutorial/externals/scripts/core.js'); ?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sestutorial/externals/styles/styles.css'); ?>

<script type="text/javascript">

  en4.core.runonce.add(function() {
    var pre_rate = <?php echo $this->tutorial->rating;?>;
    var rated = '<?php echo $this->rated;?>';
    var tutorial_id = <?php echo $this->tutorial->tutorial_id;?>;
    var total_votes = <?php echo $this->rating_count;?>;
    var viewer = <?php echo $this->viewer_id;?>;
    new_text = '';

    var rating_over = window.rating_over = function(rating) {
      if( rated == 1 ) {
        document.getElementById('rating_text').innerHTML = "<?php echo $this->translate('you already rated');?>";
        //set_rating();
      } else if( viewer == 0 ) {
        document.getElementById('rating_text').innerHTML = "<?php echo $this->translate('please login to rate');?>";
      } else {
        document.getElementById('rating_text').innerHTML = "<?php echo $this->translate('click to rate');?>";
        for(var x=1; x<=5; x++) {
          if(x <= rating) {
            scriptJquery('#rate_'+x).addClass('rating_star_big_generic rating_star_big fas fa-star');
          } else {
            scriptJquery('#rate_'+x).addClass('rating_star_big_generic rating_star_big_disabled fas fa-star');
          }
        }
      }
    }
    
    var rating_out = window.rating_out = function() {
      if (new_text != ''){
        document.getElementById('rating_text').innerHTML = new_text;
      }
      else{
        document.getElementById('rating_text').innerHTML = " <?php echo $this->translate(array('%s rating', '%s ratings', $this->rating_count),$this->locale()->toNumber($this->rating_count)) ?>";        
      }
      if (pre_rate != 0){
        set_rating();
      }
      else {
        for(var x=1; x<=5; x++) {
          document.getElementById('rate_'+x).attr('class', 'rating_star_big_generic rating_star_big_disabled fas fa-star');
        }
      }
    }

    var set_rating = window.set_rating = function() {
      var rating = pre_rate;
      if (new_text != ''){
        document.getElementById('rating_text').innerHTML = new_text;
      }
      else{
        document.getElementById('rating_text').innerHTML = "<?php echo $this->translate(array('%s rating', '%s ratings', $this->rating_count),$this->locale()->toNumber($this->rating_count)) ?>";
      }
      for(var x=1; x<=parseInt(rating); x++) {
        scriptJquery('#rate_'+x).addClass('rating_star_big_generic rating_star_big fas fa-star');
      }

      for(var x=parseInt(rating)+1; x<=5; x++) {
        scriptJquery('#rate_'+x).addClass('rating_star_big_generic rating_star_big_disabled fas fa-star');
      }

      var remainder = Math.round(rating)-rating;
      if (remainder <= 0.5 && remainder !=0){
        var last = parseInt(rating)+1;
        scriptJquery('#rate_'+last).attr('class', 'rating_star_big_generic rating_star_big_half fas fa-star');
      }
    }

    var rate = window.rate = function(rating) {
      document.getElementById('rating_text').innerHTML = "<?php echo $this->translate('Thanks for rating!');?>";
      for(var x=1; x<=5; x++) {
        scriptJquery('#rate_'+x).attr('onclick', '');
      }
      (scriptJquery.ajax({
        dataType: 'json',
        'format': 'json',
        'url' : '<?php echo $this->url(array('module' => 'sestutorial', 'controller' => 'index', 'action' => 'rate'), 'default', true) ?>',
        'data' : {
          'format' : 'json',
          'rating' : rating,
          'tutorial_id': tutorial_id
        },
        'onRequest' : function(){
          rated = 1;
          total_votes = total_votes+1;
          pre_rate = (pre_rate+rating)/total_votes;
          set_rating();
        },
        success : function(responseJSON, responseText)
        {
          if(responseJSON[0].total == 1) {
            document.getElementById('rating_text').innerHTML = responseJSON[0].total+" rating";
          } else { 
            document.getElementById('rating_text').innerHTML = responseJSON[0].total+" ratings";
          }
          new_text = responseJSON[0].total+" ratings";
        }
      }));

    }
    set_rating();
  });
</script>

<div class="sestutorial_tutorial_view sestutorial_bxs sestutorial_clearfix">
	<div class="sestutorial_tutorial_view_top">
    <div class="sestutorial_tutorial_view_top_icon">
    	<i class="far fa-file-alt"></i>
    </div>
    <div class="sestutorial_tutorial_view_top_cont sestutorial_clearfix">
      <div class="sestutorial_tutorial_view_title">
        <h2><?php echo $this->translate($this->tutorial->title); ?></h2>
      </div>
      <?php if($this->showinformation): ?>
        <div class="sestutorial_tutorial_view_stats sestutorial_text_light">
          <?php if(is_array($this->showinformation) && engine_in_array('viewcount', $this->showinformation)): ?>
            <p><i class="fa fa-eye"></i><span><?php echo $this->translate(array('%s view', '%s views', $this->tutorial->view_count), $this->locale()->toNumber($this->tutorial->view_count)); ?></span></p>
          <?php endif; ?>
          <?php if(is_array($this->showinformation) && engine_in_array('commentcount', $this->showinformation)): ?>
            <p><i class="far fa-comment"></i><span><?php echo $this->translate(array('%s comment', '%s comments', $this->tutorial->comment_count), $this->locale()->toNumber($this->tutorial->comment_count)); ?></span></p>
          <?php endif; ?>
          <?php if(engine_in_array('likecount', $this->showinformation)): ?>
            <p><i class="far fa-thumbs-up"></i><span><?php echo $this->translate(array('%s like', '%s likes', $this->tutorial->like_count), $this->locale()->toNumber($this->tutorial->like_count)); ?></span></p>
          <?php endif; ?>
          <?php if(is_array($this->showinformation) && engine_in_array('ratingcount', $this->showinformation)): ?>
            <p><i class="far fa-star"></i><span><?php echo $this->translate(array('%s rating', '%s ratings', $this->tutorial->rating), $this->locale()->toNumber($this->tutorial->rating)); ?></span></p>
          <?php endif; ?>
          
          <?php if(engine_in_array('category', $this->showinformation) && $this->tutorial->category_id) :?>
            <p><i class="far fa-folder"></i>
            	<span><?php $catName = $this->categoriesTable->getColumnName(array('column_name' => 'category_name', 'category_id' => $this->tutorial->category_id)); ?>
                <a href="<?php echo $this->url(array('action' => 'browse'), 'sestutorial_general', true).'?category_id='.urlencode($this->tutorial->category_id) ; ?>"><?php echo $catName; ?></a>
                <?php if($this->tutorial->subcat_id): ?>
                <?php $subcatName = $this->categoriesTable->getColumnName(array('column_name' => 'category_name', 'category_id' => $this->tutorial->subcat_id)); ?>
                &nbsp;&raquo;
                <a href="<?php echo $this->url(array('action' => 'browse'), 'sestutorial_general', true).'?category_id='.urlencode($this->tutorial->category_id) . '&subcat_id='.urlencode($this->tutorial->subcat_id) ?>"><?php echo $subcatName; ?></a>
                <?php endif; ?>
                <?php if($this->tutorial->subsubcat_id): ?>
                <?php $subsubcatName = $this->categoriesTable->getColumnName(array('column_name' => 'category_name', 'category_id' => $this->tutorial->subsubcat_id)); ?>
                &nbsp;&raquo;
                <a href="<?php echo $this->url(array('action' => 'browse'), 'sestutorial_general', true).'?category_id='.urlencode($this->tutorial->category_id) . '&subcat_id='.urlencode($this->tutorial->subcat_id) .'&subsubcat_id='.urlencode($this->tutorial->subsubcat_id) ?>"><?php echo $subsubcatName; ?></a>
                <?php endif; ?>
              </span>  
            </p>
          <?php endif; ?>
          <?php if (engine_in_array('tags', $this->showinformation) && engine_count($this->tutorialTags )):?>
          	<p><i class="fa fa-tag"></i>
              <span>
                <?php foreach ($this->tutorialTags as $tag): ?>
                  <a href='<?php echo $this->url(array('module' =>'sestutorial','controller' => 'index', 'action' => 'browse'),'sestutorial_general',true).'?tag_id='.$tag->tag_id.'&tag_name='.$tag->getTag()->text ;?>'>#<?php echo $tag->getTag()->text?></a>&nbsp;
                <?php endforeach; ?>
              </span>
            </p>    
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <div class="sestutorial_tutorial_view_des sestutorial_clearfix">
    <?php echo $this->tutorial->description; ?>
  </div>
  <div class="sestutorial_tutorial_view_bottom">
    <?php if($this->canRate): ?>
      <div class="sestutorial_tutorial_view_rating" onMouseOut="rating_out();">
        <span id="rate_1" class="rating_star_big_generic fas fa-star" <?php if (!$this->rated && $this->viewer_id):?> onclick="rate(1);"<?php  endif; ?> onMouseOver="rating_over(1);"></span>
        <span id="rate_2" class="rating_star_big_generic fas fa-star" <?php if (!$this->rated && $this->viewer_id):?> onclick="rate(2);"<?php endif; ?> onMouseOver="rating_over(2);"></span>
        <span id="rate_3" class="rating_star_big_generic fas fa-star" <?php if (!$this->rated && $this->viewer_id):?> onclick="rate(3);"<?php endif; ?> onMouseOver="rating_over(3);"></span>
        <span id="rate_4" class="rating_star_big_generic fas fa-star" <?php if (!$this->rated && $this->viewer_id):?> onclick="rate(4);"<?php endif; ?> onMouseOver="rating_over(4);"></span>
        <span id="rate_5" class="rating_star_big_generic fas fa-star" <?php if (!$this->rated && $this->viewer_id):?> onclick="rate(5);"<?php endif; ?> onMouseOver="rating_over(5);"></span>
        <span id="rating_text" class="rating_text"><?php echo $this->translate('click to rate');?></span>
      </div>
    <?php endif; ?>
    <?php if(engine_in_array('socialshare', $this->showinformation) || engine_in_array('siteshare', $this->showinformation)): ?>
      <?php
        $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $this->tutorial->getHref());
        $facebookUrl = 'http://www.facebook.com/sharer/sharer.php?u=' . $urlencode . '&t=' . $this->tutorial->getTitle();
        $twitterUrl = 'https://twitter.com/intent/tweet?url=' . $urlencode . '&text=' . $this->tutorial->getTitle().'%0a';
        $pinterestUrl = 'http://pinterest.com/pin/create/button/?url='.$urlencode.'&media='.urlencode((strpos($this->tutorial->getPhotoUrl('thumb.main'),'http') === FALSE ? (((!empty($_SERVER["HTTPS"]) && strtolower($_SERVER["HTTPS"] == 'on')) ? "https://" : "http://") . $_SERVER['HTTP_HOST'] ) : $this->tutorial->getPhotoUrl('thumb.main'))).'&description='.$this->tutorial->getTitle();
      ?>
      <div class="sestutorial_tutorial_view_social">
        <?php if(engine_in_array('socialshare', $this->showinformation)): ?>
          <?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesbasic') && Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sessocialshare')) { ?>
            <?php echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $this->tutorial, 'socialshare_enable_plusicon' => $this->socialshare_enable_plusicon, 'socialshare_icon_limit' => $this->socialshare_icon_limit)); ?>
          <?php } else { ?>
            <a href="<?php echo $facebookUrl; ?>" class="sestutorial_animation" onclick="return socialSharingPopUp(this.href,'Facebook');" title="<?php echo $this->translate('Facebook'); ?>"><i class='fab fa-facebook-f sestutorial_text_light'></i></a>
            <a href="<?php echo $twitterUrl; ?>" class="sestutorial_animation" onclick="return socialSharingPopUp(this.href,'Twitter');" title="<?php echo $this->translate('Twitter'); ?>"><i class='fab fa-twitter sestutorial_text_light'></i></a>
            <a href="<?php echo $pinterestUrl; ?>" class="sestutorial_animation" onclick="return socialSharingPopUp(this.href,'Pinterest');" title="<?php echo $this->translate('Pinterest'); ?>"><i class='fab fa-pinterest-p sestutorial_text_light'></i></a>
          <?php } ?>
      <?php endif; ?>
      <?php if($this->viewer()->getIdentity()) { ?>
        <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sestutorial.allowshare', 1) && engine_in_array('siteshare', $this->showinformation)): ?>
          <?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedactivity')) { ?>
            <?php $module = 'sesadvancedactivity'; ?>
          <?php } else { ?>
            <?php $module = 'activity'; ?>
          <?php } ?>
          <a href="<?php echo $this->url(array('module' => $module, 'controller'=>'index', 'action'=>'share', 'route'=>'default', 'type'=>'sestutorial_tutorial', 'id' => $this->tutorial->getIdentity(), 'format' => 'smoothbox'), 'default', true); ?>" class="smoothbox sestutorial_animation" title="<?php echo $this->translate('Share'); ?>"><i class="fas fa-share-alt sestutorial_text_light"></i></a>
        <?php endif; ?>
        <?php $LikeStatus = Engine_Api::_()->sestutorial()->getLikeStatus($this->tutorial->tutorial_id, $this->tutorial->getType()); ?>
        <a href="javascript:;" data-url="<?php echo $this->tutorial->tutorial_id ; ?>" class="btn_count sestutorial_like_sestutorial_tutorial_<?php echo $this->tutorial->tutorial_id ?> sestutorial_like_sestutorial_tutorial <?php echo ($LikeStatus) ? 'button_active' : '' ; ?>"> <i class="fa fa-thumbs-up sestutorial_text_light"></i><span><?php echo $this->tutorial->like_count; ?></span></a>
        <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sestutorial.allowreport', 1) && $this->viewer()->getIdentity() != $this->tutorial->user_id && engine_in_array('report', $this->showinformation)): ?>
          <a href="<?php echo $this->url(array("module"=> "core", "controller" => "report", "action" => "create", "route" => "default", "subject" => $this->tutorial->getGuid()), 'default', true); ?>" class="smoothbox sestutorial_animation" title="<?php echo $this->translate('Report'); ?>"><i class="fa fa-flag sestutorial_text_light"></i></a>
        <?php endif; ?>
      <?php } ?>
      </div>
      <?php endif; ?>
      
  </div>
  <?php if($this->canhelpful): ?>
    <?php if(engine_in_array('showhelpful', $this->showinformation)): ?>
      <?php $checkHelpful = Engine_Api::_()->getDbTable('helptutorials', 'sestutorial')->checkHelpful($this->tutorial->tutorial_id, $this->viewer_id);
      
      $getHelpfulvalue = Engine_Api::_()->getDbTable('helptutorials', 'sestutorial')->getHelpfulvalue($this->tutorial->tutorial_id, $this->viewer_id);
      $helpfulCountforYes = Engine_Api::_()->getDbTable('helptutorials', 'sestutorial')->helpfulCount($this->tutorial->tutorial_id, 1);
      $helpfulCountforNo = Engine_Api::_()->getDbTable('helptutorials', 'sestutorial')->helpfulCount($this->tutorial->tutorial_id, 2);
      
      $totalHelpful = $helpfulCountforYes + $helpfulCountforNo;
      $final_value = 0;
      if($helpfulCountforYes && $totalHelpful)
        $percentageHelpful = ($helpfulCountforYes / ($totalHelpful))*100;
      $final_value = round($percentageHelpful);
      ?>
      <div class="sestutorial_view_helpful_section" id="helpful_content">
        <div id="helpful_tutorial">
          <p class="sestutorial_view_helpful_section_des"><?php echo $this->translate("Was this helpful?"); ?></p>
          <div class="sestutorial_view_helpful_section_btns">
            <?php if(empty($checkHelpful)) { ?>
              <p class="sestutorial_helpfull_yes"><a href="javascript:void(0);" onclick="markasHelpful('1', '<?php echo $this->tutorial->tutorial_id; ?>')" class="sestutorial_animation"><i class="far fa-thumbs-up"></i> <?php echo $this->translate("%s Yes", $helpfulCountforYes); ?></a></p>
              <p class="sestutorial_helpfull_no"><a href="javascript:void(0);" onclick="markasHelpful('2', '<?php echo $this->tutorial->tutorial_id; ?>')" class="sestutorial_animation"><i class="far fa-thumbs-down"></i> <?php echo $this->translate("%s No", $helpfulCountforNo); ?></a></p>
            <?php } elseif($checkHelpful && $getHelpfulvalue == 1) { ?>
              <p class="disabled sestutorial_helpfull_yes"><a href="javascript:void(0);" class="sestutorial_animation"><i class="far fa-thumbs-up"></i> <?php echo $this->translate("%s Yes", $helpfulCountforYes); ?></a></p>
              <p class="sestutorial_helpfull_no"><a href="javascript:void(0);" onclick="markasHelpful('2', '<?php echo $this->tutorial->tutorial_id; ?>')" class="sestutorial_animation"><i class="far fa-thumbs-down"></i> <?php echo $this->translate("%s No", $helpfulCountforNo); ?></a></p>
            <?php } elseif($checkHelpful && $getHelpfulvalue == 2) { ?>
              <p class="sestutorial_helpfull_yes"><a href="javascript:void(0);" onclick="markasHelpful('1', '<?php echo $this->tutorial->tutorial_id; ?>')" class="sestutorial_animation"><i class="far fa-thumbs-up"></i> <?php echo $this->translate("%s Yes", $helpfulCountforYes); ?></a></p>
              <p class="disabled sestutorial_helpfull_no"><a href="javascript:void(0);" class="sestutorial_animation"><i class="far fa-thumbs-down"></i> <?php echo $this->translate("%s No", $helpfulCountforNo); ?></a></p>
            <?php } ?>
          </div>
          <?php if($final_value > 0): ?>
            <p class="sestutorial_view_helpful_section_total"><?php echo '<b>'.$final_value.'%</b>'.$this->translate(' users marked this Tutorial as helpful.');?></p>
          <?php else: ?>
            <p class="sestutorial_view_helpful_section_total"><?php echo '<b>0%</b>'.$this->translate(' users marked this Tutorial as helpful.');?></p>
          <?php endif; ?>
        </div>
      </div>
    <?php endif; ?>
  <?php endif; ?>
</div>
<script>

function markasHelpful(helpfultutorial, tutorial_id) {
  
  if(document.getElementById('helpful_tutorial'))
    document.getElementById('helpful_tutorial').style.disply = 'none';
  (scriptJquery.ajax({
    dataType: 'html',
    method: 'post',              
    'url': en4.core.baseUrl + 'sestutorial/index/helpful/',
    'data': {
      format: 'html',
      tutorial_id: tutorial_id,
      helpfultutorial: helpfultutorial,
    },
    success: function(responseHTML) {
      document.getElementById('helpful_content').innerHTML = "<div class='sestutorial_success_msg'><i class='fa fa-check'></i><span><?php echo $this->translate('Thank you for your feedback!.');?> </span></div>";
    }
  }));
  return false;
}
</script>
