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

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/styles/styles.css'); ?>  
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/styles/styles.css'); ?>
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

<?php if(isset($this->identityForWidget) && !empty($this->identityForWidget)){
				$randonNumber = $this->identityForWidget;
      }else{
      	$randonNumber = $this->identity; 
      }
?>

<?php if(!$this->is_ajax){ ?>
	<div id="scrollHeightDivSes_<?php echo $randonNumber;?>" class="sesbasic_bxs"> 
    <div id="tabbed-widget_<?php echo $randonNumber; ?>">
  <?php } ?>
    <?php foreach( $this->paginatorCategory as $item): ?>
  	<div class="sesvideo_categories_videos_listing clear sesbasic_clearfix">
    	<div class="sesvideo_catbase_list_head clear sesbasic_clearfix">
      	<a class="sesbasic_linkinherit" href="<?php echo $item->getBrowseVideoHref(); ?>?category_id=<?php echo $item->category_id ?>" title="<?php echo $this->translate($item->category_name); ?>"><?php echo $this->translate($item->category_name); ?><?php if(isset($this->count_video) && $this->count_video == 1){ ?><?php echo "(".$item->total_videos_categories.")"; ?><?php } ?></a>
       <?php if(isset($this->seemore_text) && $this->seemore_text != ''){ ?>
          <span <?php echo $this->allignment_seeall == 'right' ?  'class="floatR"' : ''; ?> >
          	<a href="<?php echo $item->getBrowseVideoHref(); ?>?category_id=<?php echo $item->category_id ?>" title="<?php echo $item->category_name; ?>">
            <?php $seemoreTranslate = $this->translate($this->seemore_text); ?>
            <?php echo str_replace('[category_name]',$this->translate($item->category_name),$seemoreTranslate); ?>
          </a>
         </span>
       <?php } ?>
      </div>
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
    <?php if(isset($this->resultArray['video_data'][$item->category_id])){
          $changeClass = 0;
     ?>
    <?php	foreach($this->resultArray['video_data'][$item->category_id] as $item){
          $href = $item->getHref();
       		$imageURL = $item->getPhotoUrl('thumb.normalmain');
    ?>
   <div class="sesvideo_videolist_column_<?php echo $changeClass == 0 ? 'big' : 'small'; ?> floatL  epaidcontent_category_associate_main">

    <div class="sesvideo_cat_video_list <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.sesvideo',1) && Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($item)) { ?> paid_content <?php } ?>">
		<?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.sesvideo',1) && Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($item)) { ?>
			<?php echo $this->partial('application/modules/Epaidcontent/views/scripts/_paidContent.tpl', 'epaidcontent', array('item' => $item)); ?>
		<?php } ?>    
       <div class="sesvideo_thumb">
        <a href="<?php echo $href; ?>" data-url = "sesvideo_chanel" class="sesvideo_thumb_img sesvideo_lightbox_open">
          <span class="sesvideo_animation" style="background-image:url(<?php echo $imageURL; ?>);"></span>
         <?php if(isset($this->featuredLabelActive) || isset($this->sponsoredLabelActive) || isset($this->hotLabelActive)){ ?>
          <p class="sesvideo_labels">
          <?php if(isset($this->featuredLabelActive) && $item->is_featured == 1){ ?>
            <span class="sesvideo_label_featured"><?php echo $this->translate("Featured"); ?></span>
          <?php } ?>
          <?php if(isset($this->sponsoredLabelActive) && $item->is_sponsored == 1){ ?>
            <span class="sesvideo_label_sponsored"><?php echo $this->translate("Sponsored"); ?></span>
          <?php } ?>
          <?php if(isset($this->hotLabelActive) && $item->is_hot == 1){ ?>
            <span class="sesvideo_label_hot"><?php echo $this->translate("Hot"); ?></span>
          <?php } ?>
          </p>
          <?php } ?>
          <?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesvideosell')) {
            $videoItem = Engine_Api::_()->getItem('sesvideo_video', $item->video_id);
            if($videoItem->price > 0) { ?>
            <p class="sesvideo_paid_label sesbasic_animation"><?php echo $this->translate("Paid")?></p>
          <?php } } ?>
          <div class="sesvideo_cat_video_list_info sesvideo_animation">
            <div>
              <div class="sesvideo_cat_video_list_content">
              <?php if(isset($this->titleActive)){ ?>
                <div class="sesvideo_cat_video_list_title">
                  <?php echo $item->getTitle(); ?>
                </div>
                <?php } ?>
                <?php if(isset($this->byActive)){ ?>
                <div class="sesvideo_cat_video_list_stats">
                  <?php
                    $owner = $item->getOwner();
                    echo $this->translate('Posted by %1$s', $owner->getTitle());
                  ?>
                </div>
                <?php } ?>
                <div class="sesvideo_cat_video_list_stats sesvideo_list_stats sesbasic_text_light">
                  <?php if(isset($this->likeActive) && isset($item->like_count)) { ?>
                    <span title="<?php echo $this->translate(array('%s like', '%s likes', $item->like_count), $this->locale()->toNumber($item->like_count)); ?>"><i class="sesbasic_icon_like_o"></i><?php echo $item->like_count; ?></span>
                  <?php } ?>
                  <?php if(isset($this->commentActive) && isset($item->comment_count)) { ?>
                    <span title="<?php echo $this->translate(array('%s comment', '%s comments', $item->comment_count), $this->locale()->toNumber($item->comment_count))?>"><i class="sesbasic_icon_comment_o"></i><?php echo $item->comment_count;?></span>
                  <?php } ?>
                  <?php if(isset($this->viewActive) && isset($item->view_count)) { ?>
                    <span title="<?php echo $this->translate(array('%s view', '%s views', $item->view_count), $this->locale()->toNumber($item->view_count))?>"><i class="sesbasic_icon_view"></i><?php echo $item->view_count; ?></span>
                  <?php } ?>
                   <?php  if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo.allowfavv', 1) && isset($this->favouriteActive) && isset($item->favourite_count)) { ?>
                    <span title="<?php echo $this->translate(array('%s favourite', '%s favourites', $item->favourite_count), $this->locale()->toNumber($item->favourite_count))?>"><i class="sesbasic_icon_favourite_o"></i><?php echo $item->favourite_count; ?></span>
                  <?php } ?>
                  <?php if(isset($this->ratingActive) && $ratingShow && isset($item->rating) && $item->rating > 0 ): ?>
                   <span  title="<?php echo $this->translate(array('%s rating', '%s ratings', round($item->rating,1)), $this->locale()->toNumber(round($item->rating,1)))?>">
                     <i class="far fa-star"></i><?php echo round($item->rating,1).'/5';?>
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
          <?php 
          $changeClass++;
          }
          $changeClass = 0;
           ?>
      <?php } ?>
    </div>    
 <?php  
 		endforeach;
     if($this->paginatorCategory->getTotalItemCount() == 0 && !$this->is_ajax){  ?>
     <div class="tip">
      <span>
        <?php echo $this->translate('Nobody has created an video yet.');?>
        <?php if ($this->can_create):?>
          <?php echo $this->translate('Be the first to %1$screate%2$s one!', '<a href="'.$this->url(array('action' => 'create','module'=>'sesvideo'), "sesvideo_general",true).'">', '</a>'); ?>
        <?php endif; ?>
      </span>
    </div>
		<?php } 
    if($this->loadOptionData == 'pagging'){ ?>
 		 <?php echo $this->paginationControl($this->paginatorCategory, null, array("_pagging.tpl", "sesvideo"),array('identityWidget'=>$randonNumber)); ?>
 <?php } ?>
 
<?php if(!$this->is_ajax){ ?>
  </div>
	</div>
   <?php if($this->loadOptionData != 'pagging') { ?>
  <div class="sesbasic_view_more sesbasic_load_btn" style="display:none;" id="view_more_<?php echo $randonNumber; ?>" onclick="viewMore_<?php echo $randonNumber; ?>();" > <?php echo $this->htmlLink('javascript:void(0);', $this->translate('View More'), array('id' => "feed_viewmore_link_$randonNumber", 'class' => 'sesbasic_animation sesbasic_link_btn fa fa-sync')); ?> </div>
  <div class="sesbasic_view_more_loading" id="loading_image_<?php echo $randonNumber; ?>" style="display: none;"> 
  <span class="sesbasic_link_btn"><i class="fa fa-spinner fa-spin"></i></span> </div>
   <?php  } ?>
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
      document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = "<?php echo ($this->paginatorCategory->count() == 0 ? 'none' : ($this->paginatorCategory->count() == $this->paginatorCategory->getCurrentPageNumber() ? 'none' : '' )) ?>";
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
				params :'<?php echo json_encode($this->params); ?>', 
				is_ajax : 1,
				identity : '<?php echo $randonNumber; ?>',
      },
      success: function(responseHTML) {
        scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').append(responseHTML);
				dynamicWidth();
				document.getElementById('loading_image_<?php echo $randonNumber; ?>').style.display = 'none';
				
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
<?php if(!$this->is_ajax){ ?>
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
				params :'<?php echo json_encode($this->params); ?>', 
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
