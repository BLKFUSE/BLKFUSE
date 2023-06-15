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

<?php $randonNumber = $this->widgetId; ?>
<?php $widgetName = 1;?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/styles/styles.css'); ?>
<?php if($this->params['viewType'] == 'pinboard'):?>
  <?php $this->headScript()->appendFile($this->layout()->staticBaseUrl .'application/modules/Sesbasic/externals/scripts/imagesloaded.pkgd.js');?>
  <?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/wookmark.min.js');?>
  <?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/pinboardcomment.js');?>
<?php endif;?>
<?php $height = $this->params['height'];?>
<?php $width = $this->params['width'];?>
<?php $pinbordWidth = $this->params['width_pinboard'];?>
<?php if(isset($this->listdescriptionActive)):?>
  <?php $descriptionLimit = $this->params['description_truncation'];?>
<?php else:?>
  <?php $descriptionLimit = 0;?>
<?php endif;?>
<?php $title= '';?>
  <?php if(!$this->is_ajax):?>
<div id="scrollHeightDivSes_<?php echo $randonNumber; ?>" class="sescontest_winners_listing sesbasic_clearfix sesbasic_bxs <?php if($this->params['palcement'] == 'vertical'):?>_vertical<?php endif;?>">
  	<div class="sescontest_winners_filters sesbasic_clearfix">
      <a href="javascript:;" rel="low" id="sescontest_htol_view_<?php echo $randonNumber; ?>" class="selectView_<?php echo $randonNumber; ?> <?php if($this->view_type == 'low'):?>active<?php endif;?>"><?php echo $this->translate('Rank High to Low');?></a>
      <span>&nbsp;|&nbsp;</span>
      <a href="javascript:;" rel="high" id="sescontest_ltoh_view_<?php echo $randonNumber; ?>" class="selectView_<?php echo $randonNumber; ?> <?php if($this->view_type == 'high'):?>active<?php endif;?>"><?php echo $this->translate('Rank Low to High');?></a>
    </div>
  
  
  <ul id="tabbed-widget_<?php echo $randonNumber; ?>" class="sescontest_winners_list sesbasic_clearfix">
  <?php endif;?>
    <?php foreach($this->paginator as $entry):?>
      <?php if(isset($this->titleActive)):?>
        <?php if(strlen($entry->getTitle()) > $this->params['title_truncation']):?>
          <?php $title = mb_substr($entry->getTitle(),0,$this->params['title_truncation']).'...';?>
        <?php else: ?>
          <?php $title = $entry->getTitle();?>
        <?php endif; ?>
      <?php endif;?>
      <?php $contest = Engine_Api::_()->getItem('contest', $entry->contest_id);?>
      <?php if($contest->contest_type == 1):?>
          <?php $contestType =  $this->translate('Blog Contest');?>
          <?php $action = 'text';?>
        <?php $imageClass = 'far fa-file-alt';?>
      <?php elseif($contest->contest_type == 2):?>
        <?php $contestType =  $this->translate('Photo Contest');?>
        <?php $action = 'photo';?>
        <?php $imageClass = 'far fa-image';?>
      <?php elseif($contest->contest_type == 3):?>
        <?php $contestType =  $this->translate('Video Contest');?>
        <?php $action = 'video';?>
        <?php $imageClass = 'fas fa-video';?>
      <?php else:?>
        <?php $contestType =  $this->translate('Music Contest');?>
        <?php $action = 'audio';?>
        <?php $imageClass = 'fas fa-music';?>
      <?php endif;?>
      <?php $canComment = Engine_Api::_()->authorization()->isAllowed('participant', $this->viewer(), 'comment');?>
      <?php $likeStatus = Engine_Api::_()->sescontest()->getLikeStatus($entry->participant_id,$entry->getType()); ?>
      <?php $favouriteStatus = Engine_Api::_()->getDbTable('favourites', 'sescontest')->isFavourite(array('resource_id' => $entry->participant_id,'resource_type' => $entry->getType())); ?>
      <?php $owner = $entry->getOwner();?>
      <?php if($this->params['viewType'] == 'grid'):?>
        <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/winners/_gridView.tpl';?>
      <?php elseif($this->params['viewType'] == 'list'):?>
        <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/winners/_listView.tpl';?>
      <?php elseif($this->params['viewType'] == 'pinboard'):?>
        <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/winners/_pinboardView.tpl';?>
      <?php endif;?>
    <?php endforeach;?>
    <?php if(engine_count($this->paginator) == 0):  ?>
      <div id="browse-widget_<?php echo $randonNumber;?>" style="width:100%;">
        <div id="error-message_<?php echo $randonNumber;?>">
          <div class="sesbasic_tip clearfix">
            <img src="<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('contest_no_photo', 'application/modules/Sescontest/externals/images/contest-icon.png'); ?>" alt="" />
            <span class="sesbasic_text_light">
              <?php echo $this->translate('There are no results that match your search. Please try again.') ?>
            </span>
          </div>
        </div>
      </div>
    <?php endif; ?>
      <?php if(!$this->is_ajax):?>
  </ul>
</div>
  <?php endif;?>
<?php if($this->params['viewType'] == 'pinboard'):?>
  <script type="text/javascript">
    var wookmark = undefined;
    var wookmark<?php echo $randonNumber ?>;
    function pinboardLayout_<?php echo $randonNumber ?>(force){      
      scriptJquery('.new_image_pinboard_<?php echo $randonNumber; ?>').css('display','none');
      var imgLoad = imagesLoaded('#tabbed-widget_<?php echo $randonNumber; ?>');
      imgLoad.on('progress',function(instance,image){
        scriptJquery(image.img).parent().parent().parent().parent().parent().show();
        scriptJquery(image.img).parent().parent().parent().parent().parent().removeClass('new_image_pinboard_<?php echo $randonNumber; ?>');
        imageLoadedAll<?php echo $randonNumber ?>(force);
      });
    }
    function imageLoadedAll<?php echo $randonNumber ?>(force){
      scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').addClass('sesbasic_pinboard_<?php echo $randonNumber; ?>');
      scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').addClass('sesbasic_pinboard');
      if (typeof wookmark<?php echo $randonNumber ?> == 'undefined' || typeof force != 'undefined') {
        (function() {
          function getWindowWidth() {
            return Math.max(document.documentElement.clientWidth, window.innerWidth || 0)
          }				
          wookmark<?php echo $randonNumber ?> = new Wookmark('.sesbasic_pinboard_<?php echo $randonNumber; ?>', {
            itemWidth: <?php echo isset($this->params['width']) ? str_replace(array('px','%'),array(''),$this->params['width']) : '300'; ?>, // Optional min width of a grid item
            outerOffset: 0, // Optional the distance from grid to parent
            align:'left',
            flexibleWidth: function () {
              // Return a maximum width depending on the viewport
              return getWindowWidth() < 1024 ? '100%' : '40%';
            }
          });
        })();
      } else {
        wookmark<?php echo $randonNumber ?>.initItems();
        wookmark<?php echo $randonNumber ?>.layout(true);
      }
    }
    scriptJquery(window).resize(function(e){pinboardLayout_<?php echo $randonNumber ?>('',true);});
    <?php if($this->params['viewType'] == 'pinboard'):?>
      scriptJquery(document).ready(function(){pinboardLayout_<?php echo $randonNumber ?>();});
    <?php endif;?>
  </script>
<?php endif;?>

<script type="text/javascript">
          
            var requestTab_<?php echo $randonNumber; ?>;
        scriptJquery(document).on('click','.selectView_<?php echo $randonNumber; ?>',function(){
      if(scriptJquery(this).hasClass('active'))
      return;
      document.getElementById("tabbed-widget_<?php echo $randonNumber; ?>").innerHTML = "<div class='clear sesbasic_loading_container'></div>";
      scriptJquery('#sescontest_htol_view_<?php echo $randonNumber; ?>').removeClass('active');
      scriptJquery('#sescontest_ltoh_view_<?php echo $randonNumber; ?>').removeClass('active');
      scriptJquery(this).addClass('active');
//       if (typeof(requestTab_<?php echo $randonNumber; ?>) != 'undefined') {
// 	    requestTab_<?php echo $randonNumber; ?>.cancel();
//       }
      requestTab_<?php echo $randonNumber; ?> = (scriptJquery.ajax({
				method: 'post',
				'url': en4.core.baseUrl + "widget/index/mod/sescontest/id/<?php echo $this->widgetId; ?>/name/<?php echo $this->widgetName; ?>",
				'data': {
					format: 'html',
					type:scriptJquery('.selectView_<?php echo $randonNumber; ?>.active').attr('rel'),
					is_ajax : 1,
          widget_id: '<?php echo $this->widgetId;?>',
          contest_id:'<?php echo $this->contest_id;?>',
				},
				success: function(responseHTML) {
					document.getElementById('tabbed-widget_<?php echo $randonNumber; ?>').innerHTML = responseHTML;
          if(scriptJquery('.selectView_<?php echo $randonNumber; ?>.active').attr('rel') == 'grid') {
            scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').addClass('row');
          } else {
            scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').removeClass('row');
          }
					if(document.getElementById("loading_image_<?php echo $randonNumber; ?>"))
					document.getElementById('loading_image_<?php echo $randonNumber; ?>').style.display = 'none';
					<?php if($this->params['viewType'] == 'pinboard'):?>
						pinboardLayout_<?php echo $randonNumber ?>();
					<?php endif;?>
				}
      }));
    });
   
  </script>
<?php if($this->is_ajax): die;?><?php endif;?>
