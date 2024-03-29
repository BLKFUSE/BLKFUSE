<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/flexcroll.js'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/scripts/core.js'); ?>
<?php 
   $viewer = Engine_Api::_()->user()->getViewer();
    if ($viewer->getIdentity() == 0)
      $level = Engine_Api::_()->getDbtable('levels', 'authorization')->getPublicLevel()->level_id;
    else
      $level = $viewer;
    $type = Engine_Api::_()->authorization()->getPermission($level, 'sesbasic_video', 'videoviewer');
    if ($type == 1) {
      $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl')
                      . 'application/modules/Sesbasic/externals/scripts/SesLightbox/photoswipe.min.js')
              ->appendFile(Zend_Registry::get('StaticBaseUrl')
                      . 'application/modules/Sesbasic/externals/scripts/SesLightbox/photoswipe-ui-default.min.js')
              ->appendFile(Zend_Registry::get('StaticBaseUrl')
                      . 'application/modules/Sesbasic/externals/scripts/videolightbox/sesvideoimagevieweradvance.js');
      $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl
              . 'application/modules/Sesbasic/externals/styles/photoswipe.css');
    } else {
      $loadImageViewerFile = Zend_Registry::get('StaticBaseUrl') . 'application/modules/Sesbasic/externals/scripts/videolightbox/sesvideoimageviewerbasic.js';
      $this->headScript()->appendFile($loadImageViewerFile);
      $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl
              . 'application/modules/Sesbasic/externals/styles/medialightbox.css');
    }
?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/styles/styles.css'); ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/customscrollbar.css'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/customscrollbar.concat.min.js'); ?>
<?php if(isset($this->identityForWidget) && !empty($this->identityForWidget)){
				$randonNumber = $this->identityForWidget;
      }else{
      	$randonNumber = $this->identity; 
      }
?>
<?php if(!$this->is_ajax){ ?>
<?php $baseUrl = $this->layout()->staticBaseUrl; ?>
<h3>
	<?php echo $this->translate($this->textVideo);?>
</h3>
<div id="scrollHeightDivSes_<?php echo $randonNumber; ?>" class="sesbasic_clearfix sesbasic_bxs clear">    
   <ul class="sesvideo_cat_video_listing sesbasic_clearfix clear" id="tabbed-widget_<?php echo $randonNumber; ?>">
<?php } ?>
    <?php //rating show code
      $allowRating = Engine_Api::_()->getApi('settings', 'core')->getSetting('video.video.rating',1);
      $allowShowPreviousRating = Engine_Api::_()->getApi('settings', 'core')->getSetting('video.ratevideo.show',1);
      if($allowRating == 0){
        if($allowShowPreviousRating == 0)
          $ratingShow = false;
         else
          $ratingShow = true;
      }else
        $ratingShow = true;
     ?>
    <?php $totalCount = $this->paginator->getCurrentItemCount(); 
    			$allowedLimit = 5;
          $counter =1;
          $break = false;
          $type = 1;
          $close = false;
    ?>
    <?php foreach($this->paginator as $key=>$video){  ?>
    	<?php
        $href = $video->getHref();
        $imageURL = $video->getPhotoUrl();
      ?>
      <?php if(($this->paginator->getCurrentPageNumber() == 1 || $this->loadOptionData == 'pagging') && !$break && $totalCount >= $allowedLimit ){ ?>
			<?php if(($counter-1)%5 == 0 ){ ?>
        <div class="sesbasic_clearfix sesbasic_bxs clear">
      		<div class="sesvideo_videolist_row clear sesbasic_clearfix">
      <?php } ?>
						<?php if($type == 1){ ?>
               <?php if(!$close){  ?><div class="sesvideo_videolist_column_small floatL"> <?php } ?>
                    <div class="sesvideo_cat_video_list">
                  <div class="sesvideo_thumb">
                    <a href="<?php echo $href; ?>" data-url = "<?php echo $video->getType() ?>" class="sesvideo_thumb_img sesvideo_lightbox_open">
                      <span class="sesvideo_animation" style="background-image:url(<?php echo $imageURL; ?>);"></span>
                     <?php if(isset($this->featuredLabelActive) || isset($this->sponsoredLabelActive) || isset($this->hotLabelActive)){ ?>
                      <p class="sesvideo_labels">
                      <?php if(isset($this->featuredLabelActive) && $video->is_featured == 1){ ?>
                        <span class="sesvideo_label_featured"><?php echo $this->translate('FEATURED'); ?></span>
                      <?php } ?>
                      <?php if(isset($this->sponsoredLabelActive) && $video->is_sponsored == 1){ ?>
                        <span class="sesvideo_label_sponsored"><?php echo $this->translate("SPONSORED"); ?></span>
                      <?php } ?>
                       <?php if(isset($this->hotLabelActive) && $video->is_hot == 1){ ?>
                        <span class="sesvideo_label_hot"><?php echo $this->translate("HOT"); ?></span>
                      <?php } ?>
                      </p>
                      <?php } ?>
                      <div class="sesvideo_cat_video_list_info sesvideo_animation">
                        <div>
                          <div class="sesvideo_cat_video_list_content">
                          <?php if(isset($this->titleActive)){ ?>
                            <div class="sesvideo_cat_video_list_title">
                              <?php echo $video->getTitle(); ?>
                            </div>
                            <?php } ?>
                            <?php if(isset($this->byActive)){ ?>
                            <div class="sesvideo_cat_video_list_stats">
                              <?php
                                $owner = $video->getOwner();
                                echo $this->translate('Posted by %1$s', $owner->getTitle());
                              ?>
                            </div>
                            <?php } ?>
                            <div class="sesvideo_cat_video_list_stats sesvideo_list_stats sesbasic_text_light">
                              <?php if(isset($this->likeActive) && isset($video->like_count)) { ?>
                                <span title="<?php echo $this->translate(array('%s like', '%s likes', $video->like_count), $this->locale()->toNumber($video->like_count)); ?>"><i class="sesbasic_icon_like_o"></i><?php echo $video->like_count; ?></span>
                              <?php } ?>
                              <?php if(isset($this->commentActive) && isset($video->comment_count)) { ?>
                                <span title="<?php echo $this->translate(array('%s comment', '%s comments', $video->comment_count), $this->locale()->toNumber($video->comment_count))?>"><i class="sesbasic_icon_comment_o"></i><?php echo $video->comment_count;?></span>
                              <?php } ?>
                              <?php if(isset($this->viewActive) && isset($video->view_count)) { ?>
                                <span title="<?php echo $this->translate(array('%s view', '%s views', $video->view_count), $this->locale()->toNumber($video->view_count))?>"><i class="sesbasic_icon_view"></i><?php echo $video->view_count; ?></span>
                              <?php } ?>
                              <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo.allowfavv', 1) && isset($this->favouriteActive) && isset($video->favourite_count)) { ?>
                                <span title="<?php echo $this->translate(array('%s favourite', '%s favourites', $video->favourite_count), $this->locale()->toNumber($video->favourite_count))?>"><i class="sesbasic_icon_favourite_o"></i><?php echo $video->favourite_count; ?></span>
                              <?php } ?>
                              <?php if(isset($this->ratingActive) && $ratingShow && isset($video->rating) && $video->rating > 0 ): ?>
                              <span  title="<?php echo $this->translate(array('%s rating', '%s ratings', round($video->rating,1)), $this->locale()->toNumber(round($video->rating,1)))?>">
                               <i class="far fa-star"></i><?php echo round($video->rating,1).'/5';?>
                              </span>
                            <?php endif; ?>
                            </div>
                            <?php if(isset($this->watchnowActive)){ ?>
                             <div class="sesvideo_cat_video_list_button"><?php echo $this->translate('Watch now'); ?></div>
                            <?php } ?>
                          </div>
                        </div>
                      </div>
                    </a>
                </div>
                </div>
               <?php if($close){ $close = false;  ?></div> <?php }else{ $close = true;  }   ?>
            <?php } ?>
						<?php if($type == 2){ ?>
             <div class="sesvideo_videolist_column_big floatL">
             		<div class="sesvideo_cat_video_list">
                   <div class="sesvideo_thumb">
                    <a href="<?php echo $href; ?>" data-url = "<?php echo $video->getType() ?>" class="sesvideo_thumb_img sesvideo_lightbox_open">
                      <span class="sesvideo_animation" style="background-image:url(<?php echo $imageURL; ?>);"></span>
                     <?php if(isset($this->featuredLabelActive) || isset($this->sponsoredLabelActive) || isset($this->hotLabelActive)){ ?>
                      <p class="sesvideo_labels">
                      <?php if(isset($this->featuredLabelActive) && $video->is_featured == 1){ ?>
                        <span class="sesvideo_label_featured"><?php echo $this->translate('FEATURED'); ?></span>
                      <?php } ?>
                      <?php if(isset($this->sponsoredLabelActive) && $video->is_sponsored == 1){ ?>
                        <span class="sesvideo_label_sponsored"><?php echo $this->translate("SPONSORED"); ?></span>
                      <?php } ?>
                      <?php if(isset($this->hotLabelActive) && $video->is_hot == 1){ ?>
                        <span class="sesvideo_label_hot"><?php echo $this->translate("HOT"); ?></span>
                      <?php } ?>
                      </p>
                      <?php } ?>
                      <div class="sesvideo_cat_video_list_info sesvideo_animation">
                        <div>
                          <div class="sesvideo_cat_video_list_content">
                          <?php if(isset($this->titleActive)){ ?>
                            <div class="sesvideo_cat_video_list_title">
                              <?php echo $video->getTitle(); ?>
                            </div>
                            <?php } ?>
                            <?php if(isset($this->byActive)){ ?>
                            <div class="sesvideo_cat_video_list_stats">
                              <?php
                                $owner = $video->getOwner();
                                echo $this->translate('Posted by %1$s', $owner->getTitle());
                              ?>
                            </div>
                            <?php } ?>
                            <div class="sesvideo_cat_video_list_stats sesvideo_list_stats sesbasic_text_light">
                              <?php if(isset($this->likeActive) && isset($video->like_count)) { ?>
                                <span title="<?php echo $this->translate(array('%s like', '%s likes', $video->like_count), $this->locale()->toNumber($video->like_count)); ?>"><i class="sesbasic_icon_like_o"></i><?php echo $video->like_count; ?></span>
                              <?php } ?>
                              <?php if(isset($this->commentActive) && isset($video->comment_count)) { ?>
                                <span title="<?php echo $this->translate(array('%s comment', '%s comments', $video->comment_count), $this->locale()->toNumber($video->comment_count))?>"><i class="sesbasic_icon_comment_o"></i><?php echo $video->comment_count;?></span>
                              <?php } ?>
                              <?php if(isset($this->viewActive) && isset($video->view_count)) { ?>
                                <span title="<?php echo $this->translate(array('%s view', '%s views', $video->view_count), $this->locale()->toNumber($video->view_count))?>"><i class="sesbasic_icon_view"></i><?php echo $video->view_count; ?></span>
                              <?php } ?>
                              <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo.allowfavv', 1) && isset($this->favouriteActive) && isset($video->favourite_count)) { ?>
                                <span title="<?php echo $this->translate(array('%s favourite', '%s favourites', $video->favourite_count), $this->locale()->toNumber($video->favourite_count))?>"><i class="sesbasic_icon_favourite_o"></i><?php echo $video->favourite_count; ?></span>
                              <?php } ?>
                              <?php if(isset($this->ratingActive) && $ratingShow && isset($video->rating) && $video->rating > 0 ): ?>
                              <span  title="<?php echo $this->translate(array('%s rating', '%s ratings', round($video->rating,1)), $this->locale()->toNumber(round($video->rating,1)))?>">
                               <i class="far fa-star"></i><?php echo round($video->rating,1).'/5';?>
                              </span>
                            <?php endif; ?>
                            </div>
                            <?php if(isset($this->watchnowActive)){ ?>
                             <div class="sesvideo_cat_video_list_button"><?php echo $this->translate('Watch now'); ?></div>
                            <?php } ?>
                          </div>
                        </div>
                      </div>
                    </a>
                </div>
                </div>
            </div>
            <?php } ?>
     <?php if(($counter)%5 == 0){ ?>
           </div>
         </div>
		<?php } ?>
      <?php
      	if($counter == 2 || $counter == 9 || $counter == 10) $type = 2;
       	else $type = 1;
        ?>
      <?php if($counter%5 == 0){
              $allowedLimit = $allowedLimit + 5;
            }
             if($counter%15 == 0){
              $break = true;
              }
      ?>
      <?php }else{ ?>
   					   <li class="sesvideo_cat_video_list" style="height:<?php echo is_numeric($this->height) ? $this->height.'px' : $this->height ?>;width:<?php echo is_numeric($this->width) ? $this->width.'px' : $this->width ?>;">
                <div class="sesvideo_thumb">
                    <a href="<?php echo $href; ?>" data-url = "<?php echo $video->getType() ?>" class="sesvideo_thumb_img sesvideo_lightbox_open">
                      <span class="sesvideo_animation" style="background-image:url(<?php echo $imageURL; ?>);"></span>
                     <?php if(isset($this->featuredLabelActive) || isset($this->sponsoredLabelActive) || isset($this->hotLabelActive)){ ?>
                      <p class="sesvideo_labels">
                      <?php if(isset($this->featuredLabelActive) && $video->is_featured == 1){ ?>
                        <span class="sesvideo_label_featured"><?php echo $this->translate('FEATURED'); ?></span>
                      <?php } ?>
                      <?php if(isset($this->sponsoredLabelActive) && $video->is_sponsored == 1){ ?>
                        <span class="sesvideo_label_sponsored"><?php echo $this->translate("SPONSORED"); ?></span>
                      <?php } ?>
                      <?php if(isset($this->hotLabelActive) && $video->is_hot == 1){ ?>
                        <span class="sesvideo_label_hot"><?php echo $this->translate("HOT"); ?></span>
                      <?php } ?>
                      </p>
                      <?php } ?>
                      <div class="sesvideo_cat_video_list_info sesvideo_animation">
                        <div>
                          <div class="sesvideo_cat_video_list_content">
                          <?php if(isset($this->titleActive)){ ?>
                            <div class="sesvideo_cat_video_list_title">
                              <?php echo $video->getTitle(); ?>
                            </div>
                            <?php } ?>
                            <?php if(isset($this->byActive)){ ?>
                            <div class="sesvideo_cat_video_list_stats">
                              <?php
                                $owner = $video->getOwner();
                                echo $this->translate('Posted by %1$s', $owner->getTitle());
                              ?>
                            </div>
                            <?php } ?>
                            <div class="sesvideo_cat_video_list_stats sesvideo_list_stats sesbasic_text_light">
                              <?php if(isset($this->likeActive) && isset($video->like_count)) { ?>
                                <span title="<?php echo $this->translate(array('%s like', '%s likes', $video->like_count), $this->locale()->toNumber($video->like_count)); ?>"><i class="sesbasic_icon_like_o"></i><?php echo $video->like_count; ?></span>
                              <?php } ?>
                              <?php if(isset($this->commentActive) && isset($video->comment_count)) { ?>
                                <span title="<?php echo $this->translate(array('%s comment', '%s comments', $video->comment_count), $this->locale()->toNumber($video->comment_count))?>"><i class="sesbasic_icon_comment_o"></i><?php echo $video->comment_count;?></span>
                              <?php } ?>
                              <?php if(isset($this->viewActive) && isset($video->view_count)) { ?>
                                <span title="<?php echo $this->translate(array('%s view', '%s views', $video->view_count), $this->locale()->toNumber($video->view_count))?>"><i class="sesbasic_icon_view"></i><?php echo $video->view_count; ?></span>
                              <?php } ?>
                              <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo.allowfavv', 1) && isset($this->favouriteActive) && isset($video->favourite_count)) { ?>
                                <span title="<?php echo $this->translate(array('%s favourite', '%s favourites', $video->favourite_count), $this->locale()->toNumber($video->favourite_count))?>"><i class="sesbasic_icon_favourite_o"></i><?php echo $video->favourite_count; ?></span>
                              <?php } ?>
                              <?php if(isset($this->ratingActive) && $ratingShow && isset($video->rating) && $video->rating > 0 ): ?>
                              <span  title="<?php echo $this->translate(array('%s rating', '%s ratings', round($video->rating,1)), $this->locale()->toNumber(round($video->rating,1)))?>">
                               <i class="far fa-star"></i><?php echo round($video->rating,1).'/5';?>
                              </span>
                            <?php endif; ?>
                            </div>
                            <?php if(isset($this->watchnowActive)){ ?>
                             <div class="sesvideo_cat_video_list_button"><?php echo $this->translate('Watch now'); ?></div>
                            <?php } ?>
                          </div>
                        </div>
                      </div>
                    </a>
                </div>
      </li>
    	  <?php }
   		 $counter ++;
    } ?>   
    <?php  if(is_countable($this->paginator) &&  engine_count($this->paginator) == 0){  ?>
      <div class="tip">
        <span>
        	<?php echo $this->translate("No video in this  category."); ?>
          <?php if (!$this->can_edit):?>
                    <?php echo $this->translate('Be the first to %1$spost%2$s one in this category!', '<a href="'.$this->url(array('action' => 'create'), "sesvideo_general").'">', '</a>'); ?>
          <?php endif; ?>
        </span>
      </div>
    <?php } ?>    
    <?php
          if($this->loadOptionData == 'pagging'){ ?>
 		 <?php echo $this->paginationControl($this->paginator, null, array("_pagging.tpl", "sesvideo"),array('identityWidget'=>$randonNumber)); ?>
 <?php } ?>
<?php if(!$this->is_ajax){ ?> 
 </ul>
 </div>
 <?php if($this->loadOptionData != 'pagging' && $this->loadOptionData != 'fixedbutton'){ ?>
  <div class="sesbasic_view_more sesbasic_load_btn" style="display:none;" id="view_more_<?php echo $randonNumber; ?>" onclick="viewMore_<?php echo $randonNumber; ?>();" > <?php echo $this->htmlLink('javascript:void(0);', $this->translate('View More'), array('id' => "feed_viewmore_link_$randonNumber", 'class' => 'sesbasic_animation sesbasic_link_btn fa fa-sync')); ?> </div>
  <div class="sesbasic_view_more_loading" id="loading_image_<?php echo $randonNumber; ?>" style="display: none;"> <span class="sesbasic_link_btn"><i class="fa fa-spinner fa-spin"></i></span> </div>
  <?php } ?>
  <script type="application/javascript">
function paggingNumber<?php echo $randonNumber; ?>(pageNum){
	 scriptJquery('.sesbasic_loading_cont_overlay').css('display','block');
	 var openTab_<?php echo $randonNumber; ?> = '<?php echo $this->defaultOpenTab; ?>';
    en4.core.request.send(scriptJquery.ajax({
      dataType: 'html',
      method: 'post',
      'url': en4.core.baseUrl + "widget/index/mod/sesvideo/name/<?php echo $this->widgetName; ?>/openTab/" + openTab_<?php echo $randonNumber; ?>,
      'data': {
        format: 'html',
        page: pageNum,    
				params :<?php echo json_encode($this->params); ?>, 
				is_ajax : 1,
				identity : '<?php echo $randonNumber; ?>',
				type:'<?php echo $this->view_type; ?>'
      },
      success: function(responseHTML) {
				scriptJquery('.sesbasic_loading_cont_overlay').css('display','none');
        document.getElementById('tabbed-widget_<?php echo $randonNumber; ?>').innerHTML =  responseHTML;
				dynamicWidth();
      }
    }));
    return false;
}
</script>
  <?php } ?>
<script type="text/javascript">
var valueTabData ;
// globally define available tab array
	var availableTabs_<?php echo $randonNumber; ?>;
	var requestTab_<?php echo $randonNumber; ?>;
  availableTabs_<?php echo $randonNumber; ?> = <?php echo json_encode($this->defaultOptions); ?>;
<?php if($this->loadOptionData == 'auto_load'){ ?>
		scriptJquery( window ).load(function() {
		 scriptJquery(window).scroll( function() {
			  var heightOfContentDiv_<?php echo $randonNumber; ?> = scriptJquery('#scrollHeightDivSes_<?php echo $randonNumber; ?>').offset().top;
        var fromtop_<?php echo $randonNumber; ?> = scriptJquery(this).scrollTop();
        if(fromtop_<?php echo $randonNumber; ?> > heightOfContentDiv_<?php echo $randonNumber; ?> - 100 && scriptJquery('#view_more_<?php echo $randonNumber; ?>').css('display') == 'block' ){
						document.getElementById('feed_viewmore_link_<?php echo $randonNumber; ?>').click();
				}
     });
	});
<?php } ?>
var defaultOpenTab ;
  viewMoreHide_<?php echo $randonNumber; ?>();
  function viewMoreHide_<?php echo $randonNumber; ?>() {
    if (document.getElementById('view_more_<?php echo $randonNumber; ?>'))
      document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = "<?php echo ($this->paginator->count() == 0 ? 'none' : ($this->paginator->count() == $this->paginator->getCurrentPageNumber() ? 'none' : '' )) ?>";
  }
  function viewMore_<?php echo $randonNumber; ?> (){
    var openTab_<?php echo $randonNumber; ?> = '<?php echo $this->defaultOpenTab; ?>';
    document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = 'none';
    document.getElementById('loading_image_<?php echo $randonNumber; ?>').style.display = '';    
    en4.core.request.send(scriptJquery.ajax({
      dataType: 'html',
      method: 'post',
      'url': en4.core.baseUrl + "widget/index/mod/sesvideo/name/<?php echo $this->widgetName; ?>/openTab/" + openTab_<?php echo $randonNumber; ?>,
      'data': {
        format: 'html',
        page: <?php echo $this->page + 1; ?>,    
				params :<?php echo json_encode($this->params); ?>, 
				is_ajax : 1,
				identity : '<?php echo $randonNumber; ?>',
      },
      success: function(responseHTML) {
        scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').append(responseHTML);
				document.getElementById('loading_image_<?php echo $randonNumber; ?>').style.display = 'none';
				dynamicWidth();
      }
    }));
    return false;
  }
<?php if(!$this->is_ajax){ ?>
function dynamicWidth(){
	var objectClass = scriptJquery('.sesvideo_cat_video_list_info');
	for(i=0;i<objectClass.length;i++){
			scriptJquery(objectClass[i]).find('div').find('.sesvideo_cat_video_list_content').find('.sesvideo_cat_video_list_title').width(scriptJquery(objectClass[i]).width());
	}
}
dynamicWidth();
<?php } ?>
</script>
