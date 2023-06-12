<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _showVideoListGrid.tpl 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php
    $previousSubject = $this->subject();
?>
<script>var searchFlag =0;</script>
<?php if(isset($this->optionsEnable) && is_array($this->optionsEnable) && engine_in_array('pinboard',$this->optionsEnable) && !$this->is_ajax){
	 $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/imagesloaded.pkgd.js');
	 $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/pinboard.css');
   $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/wookmark.min.js');
   $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/pinboardcomment.js');
 } ?>
<?php if(isset($this->identityForWidget) && !empty($this->identityForWidget)){
				$randonNumber = $this->identityForWidget;
      }else{
      	$randonNumber = $this->identity;
      }
?>
<?php if(isset($this->tagChanel) && $this->tagChanel && !$this->is_ajax): ?>
	<h3>
  	<?php echo $this->translate('Channel using the tag') ?>
  	<?php foreach($this->tagChanel as $tag):?>
    		 <a href='javascript:void(0);' onclick='javascript:tagAction(<?php echo $tag->getTag()->tag_id; ?>);'>#<?php echo $tag->getTag()->text?></a>&nbsp;
    <?php	endforeach;  ?>
  </h3>
<?php endif; ?>
<?php if(isset($this->typeEdit) && $this->typeEdit == 'channel' && !$this->is_ajax){ ?>
		<div>
    	<a class="sesbasic_button" href="<?php echo $this->url(array('module' => 'sesvideo', 'action' => 'edit', 'chanel_id' => $this->chanel_id), 'sesvideo_chanel', true); ?>">
        <?php echo $this->translate('Edit Channel'); ?>
      </a>
    </div>
<?php } ?>
<?php if (isset($this->current_count) &&  isset($this->quota) && ($this->current_count >= $this->quota) && !empty($this->quota)):?>
  <div class="tip">
    <span>
      <?php echo $this->translate('You have already created the maximum number of videos allowed. If you would like to post a new video, please delete an old one first.');?>
    </span>
  </div>
  <br/>
<?php endif; ?>
<?php if(isset($this->optionsListGrid['tabbed']) && !$this->is_ajax){ ?>
<?php if($this->widgetName == 'tabbed-widget-videomanage'){ ?>
<div class="sesbasic_v_tabs_container sesbasic_clearfix sesbasic_bxs">
  <div class="sesbasic_v_tabs sesbasic_clearfix">
<?php }else if($this->showTabType == 0){ ?>
<div class="layout_core_container_tabs">
  <div class="tabs_alt tabs_parent">
<?php }else{ ?>
<div class="sesbasic_tabs_container sesbasic_clearfix sesvideo_container_tabbed<?php echo $randonNumber; ?>">
  <div class="sesbasic_tabs sesbasic_clearfix">
<?php } ?>
    <ul id="tab-widget-sesvideo-<?php echo $randonNumber; ?>" <?php echo isset($this->manageTabbedWidget) ? "style='margin-bottom:10px;'" : ''; ?>>
      <?php
      	    $defaultOptionArray = array();
            foreach($this->defaultOptions as $key=>$valueOptions){
            	$defaultOptionArray[] = $key;
            ?>
        <li <?php if($this->defaultOpenTab == $key){ ?> class="active sesbasic_tab_selected"<?php } ?> id="sesTabContainer_<?php echo $randonNumber; ?>_<?php echo $key; ?>">
          <a href="javascript:;" onclick="changeTabSes_<?php echo $randonNumber; ?>('<?php echo $key; ?>')"><?php echo $this->translate(($valueOptions)); ?></a>
        </li>
      <?php } ?>
    </ul>
    <?php if(isset($this->manageTabbedWidget)) { ?>
      <?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesvideosell')) {
        $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
        ?>
        <div>
          <div class="quicklinks sesbasic_create_btn">
            <ul class="navigation">
              <li>
                <a class="buttonlink sesvideo_icon_video_add menu_sesvideo_quick sesvideo_quick_create sesvideo_order_list" href="videoorders/user_id/<?php echo $viewer_id; ?>"><?php echo $this->translate("View Purchased / Sold Videos"); ?></a>
              </li>
            </ul>
          </div>
        </div>
      <?php } ?>
    <?php } ?>
</div>
<div class="sesbasic_tabs_content">
<?php }else if(isset($this->optionsListGrid['profileTabbed']) && !$this->is_ajax){ ?>
	<div class="sesbasic_profile_subtabs clear sesbasic_clearfix">
  <ul id="tab-widget-sesvideo-<?php echo $randonNumber; ?>">
  	<?php
    	$defaultOptionArray = array();
    	foreach($this->defaultOptions as $key=>$valueOptions){ ?>
    	<?php
      	$defaultOptionArray[] = $key;
        if($this->profile == 'own' ){
            if($key == 'mySPvideo')
              $value = $this->translate('Your Videos');
            else if($key == 'mySPchanel')
              $value = $this->translate('Your Chanels');
            else if($key == 'mySPplaylist')
              $value = $this->translate('Your Playlists');
            else
            	$value = $this->translate($valueOptions);
         }else{
            if($key == 'mySPvideo')
              $value = $this->translate("%s's Videos",$this->profile);
            else if($key == 'mySPchanel')
              $value = $this->translate("%s's Chanels",$this->profile);
            else if($key == 'mySPplaylist')
              $value = $this->translate("%s's Playlists",$this->profile);
            else
            	$value = $this->translate($valueOptions);
          }
       ?>
      <li <?php if($this->defaultOpenTab == $key){ ?> class="sesbasic_tab_selected" <?php } ?> id="sesTabContainer_<?php echo $randonNumber; ?>_<?php echo $key; ?>">
        <a href="javascript:;" onclick="changeTabSes_<?php echo $randonNumber; ?>('<?php echo $key; ?>')"><?php echo $this->translate($value); ?></a>
      </li>
    <?php } ?>
  </ul>
</div>
<?php } ?>
<?php  if(isset($this->defaultOptions) && engine_count($this->defaultOptions) == 1){ ?>
<script type="application/javascript">
	scriptJquery('#tab-widget-sesvideo-<?php echo $randonNumber; ?>').parent().css('display','none');
	scriptJquery('.sesvideo_container_tabbed<?php echo $randonNumber; ?>').css('border','none');
</script>
<?php } ?>
<?php if(!$this->is_ajax){ ?>
  <?php if( isset($this->tag) && $this->tag && !$this->is_ajax ): ?>
  <div class="floatL">
    <?php echo $this->translate('Videos using the tag') ?>
    #<?php echo $this->tag ?>
    <a href="<?php echo $this->url(array('controller' => 'index', 'action' => 'browse'), 'sesvideo_general', true) ?>">(x)</a>
  </div>
<?php endif; ?>
  <div class="sesbasic_view_type sesbasic_clearfix clear" style="display:<?php echo $this->bothViewEnable ? 'block' : 'none'; ?>;height:<?php echo $this->bothViewEnable ? '' : '0px'; ?>">
  	<div class="sesbasic_view_type_options sesbasic_view_type_options_<?php echo $randonNumber; ?>">
    <?php if(is_array($this->optionsEnable) && engine_in_array('list',$this->optionsEnable)){ ?>
      <a href="javascript:;" rel="list" id="sesvideo_list_view_<?php echo $randonNumber; ?>" class="listicon selectView_<?php echo $randonNumber; ?> <?php if($this->view_type == 'list') { echo 'active'; } ?>" title="<?php echo $this->translate('List View') ; ?>"></a>
    <?php } ?>
    <?php if(is_array($this->optionsEnable) && engine_in_array('grid',$this->optionsEnable)){ ?>
    	<a href="javascript:;" rel="grid" id="sesvideo_grid_view_<?php echo $randonNumber; ?>" class="gridicon selectView_<?php echo $randonNumber; ?> <?php if($this->view_type == 'grid') { echo 'active'; } ?>" title="<?php echo $this->translate('Grid View'); ?>"></a>
    <?php } ?>
    <?php if(is_array($this->optionsEnable) && engine_in_array('pinboard',$this->optionsEnable)){ ?>
     	<a href="javascript:;" rel="pinboard" id="sesvideo_pinboard_view_<?php echo $randonNumber; ?>" class="boardicon selectView_<?php echo $randonNumber; ?> <?php if($this->view_type == 'pinboard') { echo 'active'; } ?>" title="<?php echo $this->translate('Pinboard View'); ?>"></a>
    <?php } ?>
    </div>
  </div>
<?php } ?>
<?php if((isset($this->optionsListGrid['tabbed']) || $this->loadJs || isset($this->optionsListGrid['profileTabbed'])) && !$this->is_ajax){ ?>
  <div id="scrollHeightDivSes_<?php echo $randonNumber; ?>" class="sesbasic_clearfix sesbasic_bxs clear">
    <?php if(engine_count($this->paginator) > 0 && (!empty($_POST['searchFlag']) || (strpos($_SERVER['REQUEST_URI'],'?') == true))): ?>
     <div class="sesvideo_search_result"><?php echo $this->translate(array('%s video found.', '%s videos found.', $this->paginator->getTotalItemCount()), $this->locale()->toNumber($this->paginator->getTotalItemCount())); ?></div>
    <?php endif; ?>
    <ul class="sesvideo_video_listing sesbasic_clearfix clear <?php if ($this->view_type == 'grid'): ?> row <?php endif; ?>" id="tabbed-widget_<?php echo $randonNumber; ?>" style="min-height:50px;">
<?php } ?>
       <?php if(isset($this->chanelId)){
       		$chanelCustomUrl = array('type'=>'sesvideo_chanel','item_id'=>$this->chanelId);
       }else if(isset($this->playlistId)){
       		$chanelCustomUrl = array('type'=>'sesvideo_playlist','item_id'=>$this->playlistId);
       }else{
       		$chanelCustomUrl = array();
      	}
        ?>
      <?php foreach( $this->paginator as $item ):
          if(isset($item->type) && ($item->type == 4 || ( $item->type == 17 && strpos($item->code,'dailymotion')!==false))){ 
            if($item->type == 4)
         $dailymotionData = file_get_contents("https://api.dailymotion.com/video/".$item->code."?fields=thumbnail_url");
            else{
              $regex = '/(<iframe.*? src=(\"|\'))(.*?)((\"|\').*)/';
        preg_match($regex,$item->code, $matches);
        $video_id = explode("/",$matches[3]);
          $dailymotionData = file_get_contents("https://api.dailymotion.com/video/".$video_id[5]."?fields=thumbnail_url");
            }
          if ($dailymotionData != '') {
         $dailymotionData = json_decode($dailymotionData, true);
         $thumbnail_url = (isset($dailymotionData['thumbnail_url']) && $dailymotionData['thumbnail_url']) ? $dailymotionData['thumbnail_url'] : '';}}

        if(isset($this->getVideoItem) && $this->getVideoItem == 'getVideoItem'){
         $oldItem = $item;
        	if(isset($item->video_id))
           $item = Engine_Api::_()->getItem('video', $item->video_id);
          else if(isset($item->resource_id))
           $item = Engine_Api::_()->getItem('video', $item->resource_id);
          else if(isset($item->file_id))
           $item = Engine_Api::_()->getItem('video', $item->file_id);
          else
          	continue;
          if(isset($oldItem->watchlater_id)){
          	$watchlater_watch_id = $oldItem->watchlater_id;
            $watchlater_watch_id_exists = 'YES';
            }
        }else if(isset($this->getChanelItem) && $this->getChanelItem == 'getChanelItem'){
        	if(isset($item->chanel_id))
           $item = Engine_Api::_()->getItem('sesvideo_chanel', $item->chanel_id);
         else if(isset($item->resource_id))
           $item = Engine_Api::_()->getItem('sesvideo_chanel', $item->resource_id);
        }
     if(!$item)
     	continue;
        /*Rating code start*/
       if($item->getType() == 'video'){
          $allowRating = Engine_Api::_()->getApi('settings', 'core')->getSetting('video.video.rating',1);
          $allowShowPreviousRating = Engine_Api::_()->getApi('settings', 'core')->getSetting('video.ratevideo.show',1);
          if($allowRating == 0){
            if($allowShowPreviousRating == 0)
              $ratingShow = false;
             else
              $ratingShow = true;
          }else
            $ratingShow = true;
          $favAllow = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo.allowfavv', 1);
       }else if($item->getType() == 'sesvideo_chanel'){
          $allowRating = Engine_Api::_()->getApi('settings', 'core')->getSetting('video.chanel.rating',1);
          $allowShowPreviousRating = Engine_Api::_()->getApi('settings', 'core')->getSetting('video.ratechanel.show',1);
          if($allowRating == 0){
            if($allowShowPreviousRating == 0)
              $ratingShow = false;
             else
              $ratingShow = true;
          }else
            $ratingShow = true;
       }else
       	$ratingShow = true;
       	$favAllow = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo.allowfavc', 1);
       /*Rating code End*/
      ?>
			<?php if($this->view_type == 'grid' && $this->viewTypeStyle == 'mouseover'){ ?>
      <li class="col-lg-<?php echo $this->gridblock; ?> col-md-6 col-sm-6 col-12">
        <div class="sesvideo_listing_in_grid2 <?php if((isset($this->my_videos) && $this->my_videos) || (isset($this->my_channel) && $this->my_channel)){ ?>isoptions<?php } ?> <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.sesvideo',1) && Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($item)) { ?> paid_content <?php } ?> <?php if((isset($this->my_videos) && $this->my_videos) || (isset($this->my_channel) && $this->my_channel)){ ?>isoptions<?php } ?>" style="height:<?php echo is_numeric($this->height_grid) ? $this->height_grid.'px' : $this->height_grid ?>;   ">
        <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.sesvideo',1) && Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($item)) { ?>
                <?php echo $this->partial('application/modules/Epaidcontent/views/scripts/_paidContent.tpl', 'epaidcontent', array('item' => $item)); ?>
              <?php } ?>
          <div class="sesvideo_listing_in_grid2_thumb sesvideo_thumb sesvideo_play_btn_wrap">
            <?php
              $href = $item->getHref($chanelCustomUrl);
              $imageURL = $item->getPhotoUrl();
            ?>
            <a href="<?php echo $href; ?>" data-url = "<?php echo $item->getType() ?>" class="<?php echo $item->getType() != 'sesvideo_chanel' ? 'sesvideo_thumb_img sesvideo_lightbox_open' : 'sesvideo_thumb_nolightbox' ?>">
              <?php  if(isset($item->type) && ($item->type == 4 || ( $item->type == 17 && strpos($item->code,'dailymotion')!==false))){  ?>
              <span style="background-image:url(<?php echo $thumbnail_url; ?>);"></span>
              <?php }else{  ?>
               <span style="background-image:url(<?php echo $imageURL; ?>);"></span>
               <?php } ?>
            </a>
            <?php  if($item->getType() == 'video') { ?>
            <a href="<?php echo $item->getHref($chanelCustomUrl)?>" data-url = "<?php echo $item->getType() ?>" class="sesvideo_play_btn fa fa-play-circle sesvideo_thumb_img sesvideo_lightbox_open">
            	<span style="background-image:url(<?php echo $item->getPhotoUrl(); ?>);display:none;"></span>
            </a>
            <?php } ?>
            <?php if(isset($this->featuredLabelActive) || isset($this->sponsoredLabelActive) || isset($this->hotLabelActive)){ ?>
              <p class="sesvideo_labels">
              <?php if(isset($this->featuredLabelActive) && $item->is_featured == 1){ ?>
                <span class="sesvideo_label_featured"><?php echo $this->translate('FEATURED'); ?></span>
              <?php } ?>
              <?php if(isset($this->sponsoredLabelActive) && $item->is_sponsored == 1){ ?>
                <span class="sesvideo_label_sponsored"><?php echo $this->translate("SPONSORED"); ?></span>
              <?php } ?>
              <?php if(isset($this->hotLabelActive) && $item->is_hot == 1){ ?>
                <span class="sesvideo_label_hot"><?php echo $this->translate("HOT"); ?></span>
              <?php } ?>
              </p>
             <?php } ?>
						<?php
           		if(isset($this->socialSharingActive) || isset($this->likeButtonActive) || isset($this->favouriteButtonActive) || isset($this->playlistAddActive)){
          		$urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $item->getHref()); ?>
           		<div class="sesvideo_thumb_btns">
              	<?php if(isset($this->socialSharingActive)){ ?>

                  <?php if($this->socialshare_icon_limit): ?>
                    <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $item, 'socialshare_enable_plusicon' => $this->socialshare_enable_plusicon, 'socialshare_icon_limit' => $this->socialshare_icon_limit)); ?>
                  <?php else: ?>
                    <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $item, 'socialshare_enable_plusicon' => $this->socialshare_enable_gridviewplusicon, 'socialshare_icon_limit' => $this->socialshare_icon_gridviewlimit)); ?>
                  <?php endif; ?>

                <?php }
                if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0 ){
                       if(isset($item->chanel_id)){
                              $itemtype = 'sesvideo_chanel';
                              $getId = 'chanel_id';
                              $favAllow = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo.allowfavc', 1)
                              ;
                            }else{
                              $itemtype = 'sesvideo_video';
                              $getId = 'video_id';
                              $favAllow = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo.allowfavv', 1)
                              ;
                            }
                      $canComment =  $item->authorization()->isAllowed(Engine_Api::_()->user()->getViewer(), 'comment');
                      if(isset($this->likeButtonActive) && $canComment){
                    ?>
                  <!--Like Button-->
                  <?php $LikeStatus = Engine_Api::_()->sesvideo()->getLikeStatusVideo($item->$getId,$item->getType()); ?>
                    <a href="javascript:;" data-url="<?php echo $item->$getId ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sesvideo_like_<?php echo $itemtype; ?> <?php echo ($LikeStatus) ? 'button_active' : '' ; ?>"> <i class="fa fa-thumbs-up"></i><span><?php echo $item->like_count; ?></span></a>
                    <?php } ?>
                     <?php if($favAllow && isset($this->favouriteButtonActive) && isset($item->favourite_count)){ ?>
                    <?php $favStatus = Engine_Api::_()->getDbtable('favourites', 'sesvideo')->isFavourite(array('resource_type'=>$itemtype,'resource_id'=>$item->$getId)); ?>
                    <a href="javascript:;" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_fav_btn sesvideo_favourite_<?php echo $itemtype; ?> <?php echo ($favStatus)  ? 'button_active' : '' ?>"  data-url="<?php echo $item->$getId ; ?>"><i class="fa fa-heart"></i><span><?php echo $item->favourite_count; ?></span></a>
                  <?php } ?>
                     <?php if(empty($item->chanel_id) && isset($this->playlistAddActive)){ ?>
                    <?php if(Engine_Api::_()->authorization()->getPermission(Engine_Api::_()->user()->getViewer()->level_id, 'video', 'addplayl_video')) { ?>
                    <a href="javascript:;" onclick="opensmoothboxurl('<?php echo $this->url(array('action' => 'add','module'=>'sesvideo','controller'=>'playlist','video_id'=>$item->video_id),'default',true); ?>')" class="sesbasic_icon_btn sesvideo_add_playlist"  title="<?php echo  $this->translate('Add To Playlist'); ?>" data-url="<?php echo $item->video_id ; ?>"><i class="fa fa-plus"></i></a>
                    <?php } ?>
                  <?php } ?>
                <?php  } ?>
              </div>
            <?php } ?>
            <?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesvideosell')) {
              $videoItem = Engine_Api::_()->getItem('sesvideo_video', $item->video_id);
              if($videoItem->price > 0) { ?>
            <span class="sesvideo_paid_label sesbasic_animation"><?php echo $this->translate("Paid")?></span>
            <?php } } ?>
          </div>
          <div class="sesvideo_listing_in_grid2_info clear sesvideo_animation ">
          	<?php if(isset($this->titleActive) ){ ?>
            <div class="sesvideo_listing_in_grid2_title">
            	<?php if(strlen($item->getTitle()) > $this->title_truncation_grid){
              				$title = mb_substr($item->getTitle(),0,$this->title_truncation_grid).'...';
                       echo $this->htmlLink($item->getHref(),$title,array('title'=>$item->getTitle())  ) ?>
              <?php }else{ ?>
              <?php echo $this->htmlLink($item->getHref(),$item->getTitle(),array('title'=>$item->getTitle())  ) ?>
              <?php } ?>
            </div>
            <?php } ?>
            <?php if(isset($this->byActive)){ ?>
              <div class="sesvideo_listing_in_grid2_date sesvideo_list_stats sesbasic_text_light">
                <?php $owner = $item->getOwner(); ?>
                <span>
                  <i class="far fa-user"></i>
                  <?php echo $this->translate("by") ?> <?php echo $this->htmlLink($owner->getHref(),$owner->getTitle() ) ?>
                </span>
              </div>
             <?php } ?>
            <?php if(isset($this->categoryActive)){ ?>
            <?php if($item->category_id != '' && intval($item->category_id) && !is_null($item->category_id)){
               $categoryItem = Engine_Api::_()->getItem('sesvideo_category', $item->category_id);
            ?>
            <?php if($categoryItem): ?>
              <div class="sesvideo_listing_in_grid2_date sesvideo_list_stats sesbasic_text_light">
                <span>
                  <i class="far fa-folder-open" title="<?php echo $this->translate('Category'); ?>"></i>
                    <a href="<?php echo $categoryItem->getHref(); ?>">
                    <?php echo $categoryItem->category_name; ?></a>
                    <?php $subcategory = Engine_Api::_()->getItem('sesvideo_category',$item->subcat_id); ?>
                    <?php if($subcategory){ ?>
                        &nbsp;&raquo;&nbsp;<a href="<?php echo $subcategory->getHref(); ?>"><?php echo $subcategory->category_name; ?></a>
                    <?php } ?>
                    <?php $subsubcategory = Engine_Api::_()->getItem('sesvideo_category',$item->subsubcat_id); ?>
                    <?php if($subsubcategory){ ?>
                        &nbsp;&raquo;&nbsp;<a href="<?php echo $subsubcategory->getHref(); ?>"><?php echo $subsubcategory->category_name; ?></a>
                    <?php } ?>
                </span>
              </div>
            <?php endif; ?>
           <?php } ?>
             <?php } ?>
             <?php if(isset($this->locationActive) && isset($item->location) && $item->location && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo_enable_location', 1)){ ?>
            	<div class="sesvideo_listing_in_grid2_date sesvideo_list_stats sesvideo_list_location sesbasic_text_light">
                <span>
                  <i class="fas fa-map-marker-alt"></i>
                  <?php include APPLICATION_PATH .  '/application/modules/Sesvideo/views/scripts/_location_direction.tpl';?>
                </span>
              </div>
            <?php } ?>
            <div class="sesvideo_listing_in_grid2_date sesvideo_list_stats sesbasic_text_light">
              <?php if(isset($this->likeActive) && isset($item->like_count)) { ?>
                <span title="<?php echo $this->translate(array('%s like', '%s likes', $item->like_count), $this->locale()->toNumber($item->like_count)); ?>"><i class="sesbasic_icon_like_o"></i><?php echo $item->like_count; ?></span>
              <?php } ?>
              <?php if(isset($this->commentActive) && isset($item->comment_count)) { ?>
                <span title="<?php echo $this->translate(array('%s comment', '%s comments', $item->comment_count), $this->locale()->toNumber($item->comment_count))?>"><i class="sesbasic_icon_comment_o"></i><?php echo $item->comment_count;?></span>
              <?php } ?>
               <?php if($favAllow && isset($this->favouriteActive) && isset($item->favourite_count)) { ?>
                    <span title="<?php echo $this->translate(array('%s favourite', '%s favourites', $item->favourite_count), $this->locale()->toNumber($item->favourite_count))?>"><i class="sesbasic_icon_favourite_o
"></i><?php echo $item->favourite_count;?></span>
                  <?php } ?>

              <?php if(isset($this->viewActive) && isset($item->view_count)) { ?>
                <span title="<?php echo $this->translate(array('%s view', '%s views', $item->view_count), $this->locale()->toNumber($item->view_count))?>"><i class="sesbasic_icon_view"></i><?php echo $item->view_count; ?></span>
              <?php } ?>
               <?php if(isset($this->ratingActive) && $ratingShow && isset($item->rating) && $item->rating > 0 ): ?>
              <span title="<?php echo $this->translate(array('%s rating', '%s ratings', round($item->rating,1)), $this->locale()->toNumber(round($item->rating,1)))?>"><i class="far fa-star"></i><?php echo round($item->rating,1).'/5';?></span>
            <?php endif; ?>
            </div>
            <?php  if(isset($this->descriptiongridActive)){ ?>
                <div class="sesvideo_list_des">
                  <?php if(strlen($item->description) > $this->description_truncation_grid){
                      $description = mb_substr($item->description,0,$this->description_truncation_grid).'...';
                      echo $title = nl2br(strip_tags($description));
                     }else{ ?>
                  <?php  echo nl2br(strip_tags($item->description));?>
                  <?php } ?>
                </div>
                <?php } ?>
            <?php if(isset($this->my_videos) && $this->my_videos ){ ?>
              <?php if($this->can_edit || $item->status !=2 && $this->can_delete){ ?>
                <div class="sesvideo_listing_in_grid2_date sesbasic_text_light">
                  <span class="sesvideo_list_option_toggle fa fa-ellipsis-v sesbasic_text_light"></span>
                  <div class="sesvideo_listing_options_pulldown">
                    <?php if($this->can_edit){
                      echo $this->htmlLink(array('route' => 'sesvideo_general','module' => 'sesvideo','controller' => 'index','action' => 'edit','video_id' => $item->video_id), $this->translate('Edit Video')) ;
                    } ?>
                    <?php if ($item->status !=2 && $this->can_delete){
                      echo $this->htmlLink(array('route' => 'sesvideo_general', 'module' => 'sesvideo', 'controller' => 'index', 'action' => 'delete', 'video_id' => $item->video_id), $this->translate('Delete Video'), array('onclick' =>'opensmoothboxurl(this.href);return false;'));
                    } ?>
                  </div>
                </div>
              <?php } ?>
              <div class="sesvideo_manage_status_tip">
                <?php if($item->status == 0):?>
                   <div class="tip">
                     <span>
                       <?php echo $this->translate('Your video is in queue to be processed - you will be notified when it is ready to be viewed.')?>
                     </span>
                   </div>
                   <?php elseif($item->status == 2):?>
                   <div class="tip">
                     <span>
                       <?php echo $this->translate('Your video is currently being processed - you will be notified when it is ready to be viewed.')?>
                     </span>
                   </div>
                   <?php elseif($item->status == 3):?>
                   <div class="tip">
                     <span>
                      <?php echo $this->translate('Video conversion failed. Please try %1$suploading again%2$s.', '<a href="'.$this->url(array('action' => 'create','module'=>'sesvideo','controller'=>'index'),'default',true).'/type/3'.'">', '</a>'); ?>
                     </span>
                   </div>
                   <?php elseif($item->status == 4):?>
                   <div class="tip">
                     <span>
                      <?php echo $this->translate('Video conversion failed. Video format is not supported by FFMPEG. Please try %1$sagain%2$s.', '<a href="'.$this->url(array('action' => 'create','module'=>'sesvideo','controller'=>'index'),'default',true).'/type/3'.'">', '</a>'); ?>
                     </span>
                   </div>
                   <?php elseif($item->status == 5):?>
                   <div class="tip">
                     <span>
                      <?php echo $this->translate('Video conversion failed. Audio files are not supported. Please try %1$sagain%2$s.', '<a href="'.$this->url(array('action' => 'create','module'=>'sesvideo','controller'=>'index'),'default',true).'/type/3'.'">', '</a>'); ?>
                     </span>
                   </div>
                   <?php elseif($item->status == 7):?>
                   <div class="tip">
                     <span>
                      <?php echo $this->translate('Video conversion failed. You may be over the site upload limit.  Try %1$suploading%2$s a smaller file, or delete some files to free up space.', '<a href="'.$this->url(array('action' => 'create','module'=>'sesvideo','controller'=>'index'),'default',true).'/type/3'.'">', '</a>'); ?>
                     </span>
                   </div>
                <?php elseif(!$item->approve):?>
                   <div class="tip">
                     <span>
                      <?php echo $this->translate('Your video has been successfully submitted for approval to our adminitrators - you will be notified when it is ready to be viewed.'); ?>
                     </span>
                   </div>
                <?php endif;?>
              </div>
            <?php }else if(isset($this->my_channel) && $this->my_channel){ ?>
              <?php if($this->can_edit || $this->can_delete){  ?>
                <div>
                  <span class="sesvideo_list_option_toggle fa fa-ellipsis-v sesbasic_text_light"></span>
                  <div class="sesvideo_listing_options_pulldown">
                    <?php if($this->can_edit){  ?>
                      <a href="<?php echo $this->url(array('module' => 'sesvideo', 'action' => 'edit', 'chanel_id' => $item->chanel_id), 'sesvideo_chanel', true); ?>"><?php echo $this->translate('Edit Channel'); ?></a>
                    <?php } ?>
                    <?php if ($this->can_delete){
                      echo $this->htmlLink($this->url(array( 'module' => 'sesvideo', 'action' => 'delete', 'chanel_id' => $item->chanel_id),'sesvideo_chanel',true), $this->translate('Delete Channel'), array('onclick' =>'opensmoothboxurl(this.href);return false;'));
                    }?>
                  </div>
                </div>
              <?php } ?>
            <?php } ?>
            <?php if(isset($this->durationActive) && isset($item->duration) && $item->duration ): ?>
              <span class="sesvideo_length">
                <?php
                  if( $item->duration >= 3600 ) {
                    $duration = gmdate("H:i:s", $item->duration);
                  } else {
                    $duration = gmdate("i:s", $item->duration);
                  }
                  echo $duration;
           ?>
              </span>
            <?php endif ?>
             <?php if(isset($this->watchLaterActive) && (isset($item->watchlater_id) || isset($watchlater_watch_id_exists)) && Engine_Api::_()->user()->getViewer()->getIdentity() != '0'){ ?>
            <?php $watchLaterId = isset($watchlater_watch_id_exists) ? $watchlater_watch_id : $item->watchlater_id; ?>
              <a href="javascript:;" class="sesvideo_watch_later_btn sesvideo_watch_later <?php echo !is_null($watchLaterId)  ? 'selectedWatchlater' : '' ?>" title = "<?php echo !is_null($watchLaterId)  ? $this->translate('Remove from Watch Later') : $this->translate('Add to Watch Later') ?>" data-url="<?php echo $item->video_id ; ?>"></a>
            <?php } ?>
          </div>
        </div>
        </li>
        <?php }else if($this->view_type == 'grid' && $this->viewTypeStyle != 'mouseover'){ ?>
        <li class="col-lg-<?php echo $this->gridblock; ?> col-md-6 col-sm-6 col-12 ">
        <div class="sesvideo_listing_grid<?php if((isset($this->my_videos) && $this->my_videos) || (isset($this->my_channel) && $this->my_channel)){ ?>isoptions<?php } ?> <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.sesvideo',1) && Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($item)) { ?> paid_content <?php } ?>">
        <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.sesvideo',1) && Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($item)) { ?>
                <?php echo $this->partial('application/modules/Epaidcontent/views/scripts/_paidContent.tpl', 'epaidcontent', array('item' => $item)); ?>
              <?php } ?>
              
          <div class="sesvideo_grid_thumb sesvideo_thumb sesvideo_play_btn_wrap" style="height:<?php echo is_numeric($this->height_grid) ? $this->height_grid.'px' : $this->height_grid ?>;">
            <?php
              $href = $item->getHref($chanelCustomUrl);
              $imageURL = $item->getPhotoUrl();
            ?>
            <a href="<?php echo $href; ?>" data-url = "<?php echo $item->getType() ?>" class="<?php echo $item->getType() != 'sesvideo_chanel' ? 'sesvideo_thumb_img sesvideo_lightbox_open' : 'sesvideo_thumb_nolightbox' ?>">
             <?php  if(isset($item->type) && ($item->type == 4 || ( $item->type == 17 && strpos($item->code,'dailymotion')!==false))){  ?>
              <span style="background-image:url(<?php echo $thumbnail_url; ?>);"></span>
              <?php }else{  ?>
               <span style="background-image:url(<?php echo $imageURL; ?>);"></span>
               <?php } ?>
            </a>
            <?php  if($item->getType() == 'video') { ?>
            <a href="<?php echo $item->getHref($chanelCustomUrl)?>" data-url = "<?php echo $item->getType() ?>" class="sesvideo_play_btn fa fa-play-circle sesvideo_thumb_img sesvideo_lightbox_open">
            	<span style="background-image:url(<?php echo $item->getPhotoUrl(); ?>);display:none;"></span>
            </a>
            <?php } ?>
            <?php if(isset($this->featuredLabelActive) || isset($this->sponsoredLabelActive) || isset($this->hotLabelActive)){ ?>
              <p class="sesvideo_labels">
              <?php if(isset($this->featuredLabelActive) && $item->is_featured == 1){ ?>
                <span class="sesvideo_label_featured"><?php echo $this->translate('FEATURED'); ?></span>
              <?php } ?>
              <?php if(isset($this->sponsoredLabelActive) && $item->is_sponsored == 1){ ?>
                <span class="sesvideo_label_sponsored"><?php echo $this->translate("SPONSORED"); ?></span>
              <?php } ?>
              <?php if(isset($this->hotLabelActive) && $item->is_hot == 1){ ?>
                <span class="sesvideo_label_hot"><?php echo $this->translate("HOT"); ?></span>
              <?php } ?>
              </p>
             <?php } ?>
            <?php if(isset($this->durationActive) && isset($item->duration) && $item->duration ): ?>
              <span class="sesvideo_length">
                <?php
                  if( $item->duration >= 3600 ) {
                    $duration = gmdate("H:i:s", $item->duration);
                  } else {
                    $duration = gmdate("i:s", $item->duration);
                  }
                  echo $duration;
           ?>
              </span>
            <?php endif ?>
             <?php if(isset($this->watchLaterActive) && (isset($item->watchlater_id) || isset($watchlater_watch_id_exists)) && Engine_Api::_()->user()->getViewer()->getIdentity() != '0'){ ?>
            <?php $watchLaterId = isset($watchlater_watch_id_exists) ? $watchlater_watch_id : $item->watchlater_id; ?>
              <a href="javascript:;" class="sesvideo_watch_later_btn sesvideo_watch_later <?php echo !is_null($watchLaterId)  ? 'selectedWatchlater' : '' ?>" title = "<?php echo !is_null($watchLaterId)  ? $this->translate('Remove from Watch Later') : $this->translate('Add to Watch Later') ?>" data-url="<?php echo $item->video_id ; ?>"></a>
            <?php } ?>
						<?php
           		if(isset($this->socialSharingActive) || isset($this->likeButtonActive) || isset($this->favouriteButtonActive) || isset($this->playlistAddActive)){
          		$urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $item->getHref()); ?>
           		<div class="sesvideo_thumb_btns">
              	<?php if(isset($this->socialSharingActive)) { ?>

                  <?php if($this->socialshare_icon_limit): ?>
                    <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $item, 'socialshare_enable_plusicon' => $this->socialshare_enable_plusicon, 'socialshare_icon_limit' => $this->socialshare_icon_limit)); ?>
                  <?php else: ?>
                    <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $item, 'socialshare_enable_plusicon' => $this->socialshare_enable_gridviewplusicon, 'socialshare_icon_limit' => $this->socialshare_icon_gridviewlimit)); ?>
                  <?php endif; ?>

                <?php }
                if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0 ){
                       if(isset($item->chanel_id)){
                              $itemtype = 'sesvideo_chanel';
                              $getId = 'chanel_id';
                              $favAllow = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo.allowfavc', 1);
                            }else{
                              $itemtype = 'sesvideo_video';
                              $getId = 'video_id';
                              $favAllow = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo.allowfavv', 1);
                            }
                      $canComment =  $item->authorization()->isAllowed(Engine_Api::_()->user()->getViewer(), 'comment');
                      if(isset($this->likeButtonActive) && $canComment){
                    ?>
                  <!--Like Button-->
                  <?php $LikeStatus = Engine_Api::_()->sesvideo()->getLikeStatusVideo($item->$getId,$item->getType()); ?>
                    <a href="javascript:;" data-url="<?php echo $item->$getId ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sesvideo_like_<?php echo $itemtype; ?> <?php echo ($LikeStatus) ? 'button_active' : '' ; ?>"> <i class="fa fa-thumbs-up"></i><span><?php echo $item->like_count; ?></span></a>
                    <?php } ?>
                     <?php if($favAllow && isset($this->favouriteButtonActive) && isset($item->favourite_count)){ ?>
                    <?php $favStatus = Engine_Api::_()->getDbtable('favourites', 'sesvideo')->isFavourite(array('resource_type'=>$itemtype,'resource_id'=>$item->$getId)); ?>
                    <a href="javascript:;" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_fav_btn sesvideo_favourite_<?php echo $itemtype; ?> <?php echo ($favStatus)  ? 'button_active' : '' ?>"  data-url="<?php echo $item->$getId ; ?>"><i class="fa fa-heart"></i><span><?php echo $item->favourite_count; ?></span></a>
                  <?php } ?>
                     <?php if(empty($item->chanel_id) && isset($this->playlistAddActive)){ ?>
                    <?php if(Engine_Api::_()->authorization()->getPermission(Engine_Api::_()->user()->getViewer()->level_id, 'video', 'addplayl_video')) { ?>
                    <a href="javascript:;" onclick="opensmoothboxurl('<?php echo $this->url(array('action' => 'add','module'=>'sesvideo','controller'=>'playlist','video_id'=>$item->video_id),'default',true); ?>')" class="sesbasic_icon_btn sesvideo_add_playlist"  title="<?php echo  $this->translate('Add To Playlist'); ?>" data-url="<?php echo $item->video_id ; ?>"><i class="fa fa-plus"></i></a>
                    <?php } ?>
                  <?php } ?>
                <?php  } ?>
              </div>
            <?php } ?>
            <?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesvideosell')) {

              $videoItem = Engine_Api::_()->getItem('sesvideo_video', $item->video_id);

              if($videoItem->price > 0) { ?>
            <span class="sesvideo_paid_label sesbasic_animation"><?php echo $this->translate("Paid")?></span>
            <?php } } ?>
          </div>
          <div class="sesvideo_grid_info clear">
          	<?php if(isset($this->titleActive) ){ ?>
            <div class="sesvideo_grid_title">
            	<?php if(strlen($item->getTitle()) > $this->title_truncation_grid){
              				$title = mb_substr($item->getTitle(),0,$this->title_truncation_grid).'...';
                       echo $this->htmlLink($item->getHref(),$title,array('title'=>$item->getTitle()) ) ?>
              <?php }else{ ?>
              <?php echo $this->htmlLink($item->getHref(),$item->getTitle(),array('title'=>$item->getTitle())  ) ?>
              <?php } ?>
            </div>
            <?php } ?>
			<div class="sesvideo_grid_bottom">
            <?php if(isset($this->byActive)){ ?>
              <div class="sesvideo_grid_date sesvideo_list_stats sesbasic_text_light">
              	<?php $owner = $item->getOwner(); ?>
                <span>
                  <i class="far fa-user"></i>
                  <?php echo $this->translate("by") ?> <?php echo $this->htmlLink($owner->getHref(),$owner->getTitle() ) ?>
                </span>
              </div>
             <?php } ?>
            <?php if(isset($this->categoryActive)){ ?>
            <?php if($item->category_id != '' && intval($item->category_id) && !is_null($item->category_id)){
            	 $categoryItem = Engine_Api::_()->getItem('sesvideo_category', $item->category_id);
            ?>
            <?php if($categoryItem): ?>
	            <div class="sesvideo_grid_date sesvideo_list_stats sesbasic_text_light">
									<span>
										<i class="far fa-folder-open" title="<?php echo $this->translate('Category'); ?>"></i>
											<a href="<?php echo $categoryItem->getHref(); ?>">
											<?php echo $categoryItem->category_name; ?></a>
											<?php $subcategory = Engine_Api::_()->getItem('sesvideo_category',$item->subcat_id); ?>
	                     <?php if($subcategory){ ?>
	                        &nbsp;&raquo;&nbsp;<a href="<?php echo $subcategory->getHref(); ?>"><?php echo $subcategory->category_name; ?></a>
	                    <?php } ?>
	                    <?php $subsubcategory = Engine_Api::_()->getItem('sesvideo_category',$item->subsubcat_id); ?>
	                     <?php if($subsubcategory){ ?>
	                        &nbsp;&raquo;&nbsp;<a href="<?php echo $subsubcategory->getHref(); ?>"><?php echo $subsubcategory->category_name; ?></a>
	                    <?php } ?>
	                </span>
	            </div>
	          <?php endif; ?>
             <?php } ?>
             <?php } ?>
              <?php if(isset($this->locationActive) && isset($item->location) && $item->location  && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo_enable_location', 1)){ ?>
            	<div class="sesvideo_grid_date sesvideo_list_stats sesbasic_text_light sesvideo_list_location">
                <span>
                  <i class="fas fa-map-marker-alt"></i>
                  <?php include APPLICATION_PATH .  '/application/modules/Sesvideo/views/scripts/_location_direction.tpl';?>
                </span>
              </div>
            <?php } ?>
            <div class="sesvideo_grid_date sesvideo_list_stats sesbasic_text_light">
              <?php if(isset($this->likeActive) && isset($item->like_count)) { ?>
                <span title="<?php echo $this->translate(array('%s like', '%s likes', $item->like_count), $this->locale()->toNumber($item->like_count)); ?>"><i class="sesbasic_icon_like_o"></i><?php echo $item->like_count; ?></span>
              <?php } ?>
              <?php if(isset($this->commentActive) && isset($item->comment_count)) { ?>
                <span title="<?php echo $this->translate(array('%s comment', '%s comments', $item->comment_count), $this->locale()->toNumber($item->comment_count))?>"><i class="sesbasic_icon_comment_o"></i><?php echo $item->comment_count;?></span>
              <?php } ?>
               <?php if($favAllow && isset($this->favouriteActive) && isset($item->favourite_count)) { ?>
                  	<span title="<?php echo $this->translate(array('%s favourite', '%s favourites', $item->favourite_count), $this->locale()->toNumber($item->favourite_count))?>"><i class="sesbasic_icon_favourite_o"></i><?php echo $item->favourite_count;?></span>
                  <?php } ?>

              <?php if(isset($this->viewActive) && isset($item->view_count)) { ?>
                <span title="<?php echo $this->translate(array('%s view', '%s views', $item->view_count), $this->locale()->toNumber($item->view_count))?>"><i class="sesbasic_icon_view"></i><?php echo $item->view_count; ?></span>
              <?php } ?>
               <?php if(isset($this->ratingActive) && $ratingShow && isset($item->rating) && $item->rating > 0 ): ?>
              <span title="<?php echo $this->translate(array('%s rating', '%s ratings', round($item->rating,1)), $this->locale()->toNumber(round($item->rating,1)))?>"><i class="far fa-star"></i><?php echo round($item->rating,1).'/5';?></span>
            <?php endif; ?>
            </div>
           <?php  if(isset($this->descriptiongridActive)){ ?>
                <div class="sesvideo_list_des">
                  <?php if(strlen($item->description) > $this->description_truncation_grid){
              				$description = mb_substr($item->description,0,$this->description_truncation_grid).'...';
                      echo $title = nl2br(strip_tags($description));
                  	 }else{ ?>
                  <?php  echo nl2br(strip_tags($item->description));?>
                  <?php } ?>
                </div>
                <?php } ?>
            <?php if(isset($this->my_videos) && $this->my_videos ){ ?>
              <?php if($this->can_edit || $item->status !=2 && $this->can_delete){ ?>
                <div class="sesvideo_grid_date sesbasic_text_light">
                  <span class="sesvideo_list_option_toggle fa fa-ellipsis-v sesbasic_text_light"></span>
                  <div class="sesvideo_listing_options_pulldown">
                    <?php if($this->can_edit){
                      echo $this->htmlLink(array('route' => 'sesvideo_general','module' => 'sesvideo','controller' => 'index','action' => 'edit','video_id' => $item->video_id), $this->translate('Edit Video')) ;
                    } ?>
                    <?php if ($item->status !=2 && $this->can_delete){
                      echo $this->htmlLink(array('route' => 'sesvideo_general', 'module' => 'sesvideo', 'controller' => 'index', 'action' => 'delete', 'video_id' => $item->video_id), $this->translate('Delete Video'), array('onclick' =>'opensmoothboxurl(this.href);return false;'));
                    } ?>
                  </div>
                </div>
              <?php } ?>
              <div class="sesvideo_manage_status_tip">
                <?php if($item->status == 0):?>
                   <div class="tip">
                     <span>
                       <?php echo $this->translate('Your video is in queue to be processed - you will be notified when it is ready to be viewed.')?>
                     </span>
                   </div>
                   <?php elseif($item->status == 2):?>
                   <div class="tip">
                     <span>
                       <?php echo $this->translate('Your video is currently being processed - you will be notified when it is ready to be viewed.')?>
                     </span>
                   </div>
                   <?php elseif($item->status == 3):?>
                   <div class="tip">
                     <span>
                      <?php echo $this->translate('Video conversion failed. Please try %1$suploading again%2$s.', '<a href="'.$this->url(array('action' => 'create','module'=>'sesvideo','controller'=>'index'),'default',true).'/type/3'.'">', '</a>'); ?>
                     </span>
                   </div>
                   <?php elseif($item->status == 4):?>
                   <div class="tip">
                     <span>
                      <?php echo $this->translate('Video conversion failed. Video format is not supported by FFMPEG. Please try %1$sagain%2$s.', '<a href="'.$this->url(array('action' => 'create','module'=>'sesvideo','controller'=>'index'),'default',true).'/type/3'.'">', '</a>'); ?>
                     </span>
                   </div>
                   <?php elseif($item->status == 5):?>
                   <div class="tip">
                     <span>
                      <?php echo $this->translate('Video conversion failed. Audio files are not supported. Please try %1$sagain%2$s.', '<a href="'.$this->url(array('action' => 'create','module'=>'sesvideo','controller'=>'index'),'default',true).'/type/3'.'">', '</a>'); ?>
                     </span>
                   </div>
                   <?php elseif($item->status == 7):?>
                   <div class="tip">
                     <span>
                      <?php echo $this->translate('Video conversion failed. You may be over the site upload limit.  Try %1$suploading%2$s a smaller file, or delete some files to free up space.', '<a href="'.$this->url(array('action' => 'create','module'=>'sesvideo','controller'=>'index'),'default',true).'/type/3'.'">', '</a>'); ?>
                     </span>
                   </div>
                <?php elseif(!$item->approve):?>
                   <div class="tip">
                     <span>
                      <?php echo $this->translate('Your video has been successfully submitted for approval to our adminitrators - you will be notified when it is ready to be viewed.'); ?>
                     </span>
                   </div>
                <?php endif;?>
              </div>
            <?php }else if(isset($this->my_channel) && $this->my_channel){ ?>
              <?php if($this->can_edit || $this->can_delete){  ?>
                <div>
                  <span class="sesvideo_list_option_toggle fa fa-ellipsis-v sesbasic_text_light"></span>
                  <div class="sesvideo_listing_options_pulldown">
                    <?php if($this->can_edit){  ?>
                      <a href="<?php echo $this->url(array('module' => 'sesvideo', 'action' => 'edit', 'chanel_id' => $item->chanel_id), 'sesvideo_chanel', true); ?>"><?php echo $this->translate('Edit Channel'); ?></a>
                    <?php } ?>
                    <?php if ($this->can_delete){
                      echo $this->htmlLink($this->url(array( 'module' => 'sesvideo', 'action' => 'delete', 'chanel_id' => $item->chanel_id),'sesvideo_chanel',true), $this->translate('Delete Channel'), array('onclick' =>'opensmoothboxurl(this.href);return false;'));
                    }?>
                  </div>
                </div>
              <?php } ?>
            <?php } ?>
          </div>
        </div>
        </div>
    </li>
        <?php }else if($this->view_type == 'list'){ ?>
        		<li class="sesvideo_listing_list sesbasic_clearfix clear<?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.sesvideo',1) && Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($item)) { ?> paid_content <?php } ?>">
        		
              <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.sesvideo',1) && Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($item)) { ?>
                <?php echo $this->partial('application/modules/Epaidcontent/views/scripts/_paidContent.tpl', 'epaidcontent', array('item' => $item)); ?>
              <?php } ?>
              
              <div class="sesvideo_list_thumb sesvideo_thumb sesvideo_play_btn_wrap" style="height:<?php echo is_numeric($this->height_list) ? $this->height_list.'px' : $this->height_list ?>;width:<?php echo is_numeric($this->width_list) ? $this->width_list.'px' : $this->width_list ?>;">
             <?php
                $href = $item->getHref($chanelCustomUrl);
                $imageURL = $item->getPhotoUrl();
              ?>
              <a href="<?php echo $href; ?>" data-url = "<?php echo $item->getType() ?>" class="<?php echo $item->getType() != 'sesvideo_chanel' ? 'sesvideo_thumb_img sesvideo_lightbox_open' : 'sesvideo_thumb_nolightbox' ?>">
              	<?php  if(isset($item->type) && ($item->type == 4 || ( $item->type == 17 && strpos($item->code,'dailymotion')!==false))){  ?>
              <span style="background-image:url(<?php echo $thumbnail_url; ?>);"></span>
              <?php }else{  ?>
               <span style="background-image:url(<?php echo $imageURL; ?>);"></span>
               <?php } ?>
              </a>
              <?php if($item->getType() != 'sesvideo_chanel'){ ?>
              <a href="<?php echo $item->getHref($chanelCustomUrl)?>" data-url = "<?php echo $item->getType() ?>" class="sesvideo_play_btn fa fa-play-circle <?php echo $item->getType() != 'sesvideo_chanel' ? 'sesvideo_thumb_img sesvideo_lightbox_open' : 'sesvideo_thumb_nolightbox' ?>">
              	<span style="background-image:url(<?php echo $item->getPhotoUrl(); ?>);display:none;"></span>
              </a>
              <?php } ?>
              <?php if(isset($this->featuredLabelActive) || isset($this->sponsoredLabelActive) || isset($this->hotLabelActive)){ ?>
              <p class="sesvideo_labels">
              <?php if(isset($this->featuredLabelActive) && isset($item->is_featured) && $item->is_featured == 1){ ?>
                <span class="sesvideo_label_featured"><?php echo $this->translate('FEATURED'); ?></span>
              <?php } ?>
              <?php if(isset($this->sponsoredLabelActive) && isset($item->is_sponsored) && $item->is_sponsored == 1){ ?>
                <span class="sesvideo_label_sponsored"><?php echo $this->translate("SPONSORED"); ?></span>
              <?php } ?>
               <?php if(isset($this->hotLabelActive) && $item->is_hot == 1){ ?>
                <span class="sesvideo_label_hot"><?php echo $this->translate("HOT"); ?></span>
              <?php } ?>
              </p>
             <?php } ?>
              <?php if(isset($this->durationActive) && isset($item->duration) && $item->duration ): ?>
                <span class="sesvideo_length">
                  <?php
                    if( $item->duration >= 3600 ) {
                      $duration = gmdate("H:i:s", $item->duration);
                    } else {
                      $duration = gmdate("i:s", $item->duration);
                    }
                    echo $duration;
                  ?>
                </span>
              <?php endif ?>
               <?php if(isset($this->watchLaterActive) && (isset($item->watchlater_id) || isset($watchlater_watch_id_exists)) && Engine_Api::_()->user()->getViewer()->getIdentity() != '0'){ ?>
            <?php $watchLaterId = isset($watchlater_watch_id_exists) ? $watchlater_watch_id : $item->watchlater_id; ?>
              <a href="javascript:;" class="sesvideo_watch_later_btn sesvideo_watch_later <?php echo !is_null($watchLaterId)  ? 'selectedWatchlater' : '' ?>" title = "<?php echo !is_null($watchLaterId)  ? $this->translate('Remove from Watch Later') : $this->translate('Add to Watch Later') ?>" data-url="<?php echo $item->video_id ; ?>"></a>
            <?php } ?>
            						<?php
           		if(isset($this->socialSharingActive) || isset($this->likeButtonActive) || isset($this->favouriteButtonActive) || isset($this->playlistAddActive)){
          		$urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $item->getHref()); ?>
           		<div class="sesvideo_thumb_btns">
              	<?php if(isset($this->socialSharingActive)){ ?>

                  <?php if($this->socialshare_icon_limit): ?>
                    <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $item, 'socialshare_enable_plusicon' => $this->socialshare_enable_plusicon, 'socialshare_icon_limit' => $this->socialshare_icon_limit)); ?>
                  <?php else: ?>
                    <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $item, 'socialshare_enable_plusicon' => $this->socialshare_enable_listviewplusicon, 'socialshare_icon_limit' => $this->socialshare_icon_listviewlimit)); ?>
                  <?php endif; ?>
                <?php }
                if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0 ){
                       if(isset($item->chanel_id)){
                              $itemtype = 'sesvideo_chanel';
                              $getId = 'chanel_id';
                              $favAllow = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo.allowfavc', 1);
                            }else{
                              $itemtype = 'sesvideo_video';
                              $getId = 'video_id';
                              $favAllow = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo.allowfavv', 1);
                            }
                      $canComment =  $item->authorization()->isAllowed(Engine_Api::_()->user()->getViewer(), 'comment');
                      if(isset($this->likeButtonActive) && $canComment){
                    ?>
                  <!--Like Button-->
                  <?php $LikeStatus = Engine_Api::_()->sesvideo()->getLikeStatusVideo($item->$getId,$item->getType()); ?>
                    <a href="javascript:;" data-url="<?php echo $item->$getId ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sesvideo_like_<?php echo $itemtype; ?> <?php echo ($LikeStatus) ? 'button_active' : '' ; ?>"> <i class="fa fa-thumbs-up"></i><span><?php echo $item->like_count; ?></span></a>
                    <?php } ?>
                     <?php if($favAllow && isset($this->favouriteButtonActive) && isset($item->favourite_count)){ ?>
                    <?php $favStatus = Engine_Api::_()->getDbtable('favourites', 'sesvideo')->isFavourite(array('resource_type'=>$itemtype,'resource_id'=>$item->$getId)); ?>
                    <a href="javascript:;" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_fav_btn sesvideo_favourite_<?php echo $itemtype; ?> <?php echo ($favStatus)  ? 'button_active' : '' ?>"  data-url="<?php echo $item->$getId ; ?>"><i class="fa fa-heart"></i><span><?php echo $item->favourite_count; ?></span></a>
                  <?php } ?>
                     <?php if(empty($item->chanel_id) && isset($this->playlistAddActive)){ ?>
                    <?php if(Engine_Api::_()->authorization()->getPermission(Engine_Api::_()->user()->getViewer()->level_id, 'video', 'addplayl_video')) { ?>
                    <a href="javascript:;" onclick="opensmoothboxurl('<?php echo $this->url(array('action' => 'add','module'=>'sesvideo','controller'=>'playlist','video_id'=>$item->video_id),'default',true); ?>')" class="sesbasic_icon_btn sesvideo_add_playlist"  title="<?php echo  $this->translate('Add To Playlist'); ?>" data-url="<?php echo $item->video_id ; ?>"><i class="fa fa-plus"></i></a>
                    <?php } ?>
                  <?php } ?>
                <?php  } ?>
              </div>
            <?php } ?>
            <?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesvideosell')) {
              $videoItem = Engine_Api::_()->getItem('sesvideo_video', $item->video_id);
              if($videoItem->price > 0) { ?>
            <span class="sesvideo_paid_label sesbasic_animation"><?php echo $this->translate("Paid")?></span>
            <?php } } ?>
          	</div>
            <div class="sesvideo_list_info">
            	<?php  if(isset($this->titleActive)){ ?>
                <div class="sesvideo_list_title">
                 <?php if(strlen($item->getTitle()) > $this->title_truncation_list){
              				$title = mb_substr($item->getTitle(),0,$this->title_truncation_list).'...';
                       echo $this->htmlLink($item->getHref(),$title,array('title'=>$item->getTitle())  );
                  	 }else{ ?>
                  <?php echo $this->htmlLink($item->getHref(),$item->getTitle(),array('title'=>$item->getTitle())  ) ?>
                  <?php } ?>
                </div>
              <?php } ?>
              <?php if(isset($this->byActive)){ ?>
                <div class="sesvideo_grid_date sesvideo_list_stats sesbasic_text_light">
                  <?php $owner = $item->getOwner(); ?>
                  <span>
                    <i class="far fa-user"></i>
                    <?php echo $this->translate("by") ?> <?php echo $this->htmlLink($owner->getHref(),$owner->getTitle() ) ?>
                  </span>
                </div>
               <?php } ?>
               <?php if(isset($this->categoryActive)){ ?>
                <?php if($item->category_id != '' && intval($item->category_id) && !is_null($item->category_id)){
                     $categoryItem = Engine_Api::_()->getItem('sesvideo_category', $item->category_id);
                  	if($categoryItem){
                  ?>
                  <div class="sesvideo_list_date sesvideo_list_stats sesbasic_text_light">
                      <span>
                      		<i class="far fa-folder-open" title="<?php echo $this->translate('Category'); ?>"></i>
                          	<a href="<?php echo $categoryItem->getHref(); ?>"><?php echo $categoryItem->category_name; ?></a>
                      			<?php $subcategory = Engine_Api::_()->getItem('sesvideo_category',$item->subcat_id); ?>
                             <?php if($subcategory){ ?>
                                &nbsp;&raquo;&nbsp;<a href="<?php echo $subcategory->getHref(); ?>"><?php echo $subcategory->category_name; ?></a>
                            <?php } ?>
                            <?php $subsubcategory = Engine_Api::_()->getItem('sesvideo_category',$item->subsubcat_id); ?>
                             <?php if($subsubcategory){ ?>
                                &nbsp;&raquo;&nbsp;<a href="<?php echo $subsubcategory->getHref(); ?>"><?php echo $subsubcategory->category_name; ?></a>
                            <?php } ?>
                      </span>
                  </div>
                   <?php
                   	}
                   } ?>
                 <?php } ?>
                 <?php if(isset($this->locationActive) && isset($item->location) && $item->location && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo_enable_location', 1)){ ?>
            	<div class="sesvideo_list_date sesvideo_list_stats sesbasic_text_light sesvideo_list_location">
                <span>
                  <i class="fas fa-map-marker-alt"></i>
                  <?php include APPLICATION_PATH .  '/application/modules/Sesvideo/views/scripts/_location_direction.tpl';?>
                </span>
              </div>
            <?php } ?>
                <div class="sesvideo_list_date sesvideo_list_stats sesbasic_text_light">
                	<?php if(isset($this->likeActive) && isset($item->like_count)) { ?>
                  	<span title="<?php echo $this->translate(array('%s like', '%s likes', $item->like_count), $this->locale()->toNumber($item->like_count)); ?>"><i class="sesbasic_icon_like_o"></i><?php echo $item->like_count; ?></span>
                  <?php } ?>
                  <?php if(isset($this->commentActive) && isset($item->comment_count)) { ?>
                  	<span title="<?php echo $this->translate(array('%s comment', '%s comments', $item->comment_count), $this->locale()->toNumber($item->comment_count))?>"><i class="sesbasic_icon_comment_o"></i><?php echo $item->comment_count;?></span>
                  <?php } ?>

                  <?php if($favAllow && isset($this->favouriteActive) && isset($item->favourite_count)) { ?>
                  	<span title="<?php echo $this->translate(array('%s favourite', '%s favourites', $item->favourite_count), $this->locale()->toNumber($item->favourite_count))?>"><i class="sesbasic_icon_favourite_o"></i><?php echo $item->favourite_count;?></span>
                  <?php } ?>

                  <?php if(isset($this->viewActive) && isset($item->view_count)) { ?>
                  	<span title="<?php echo $this->translate(array('%s view', '%s views', $item->view_count), $this->locale()->toNumber($item->view_count))?>"><i class="sesbasic_icon_view"></i><?php echo $item->view_count; ?></span>
                  <?php } ?>
                  <?php if(isset($this->ratingActive) && $ratingShow && isset($item->rating) && $item->rating > 0 ): ?>
                  <span title="<?php echo $this->translate(array('%s rating', '%s ratings', round($item->rating,1)), $this->locale()->toNumber(round($item->rating,1)))?>"><i class="far fa-star"></i><?php echo round($item->rating,1).'/5';?></span>
                <?php endif; ?>
                </div>

                <?php if(isset($this->descriptionlistActive)){ ?>
                <div class="sesvideo_list_des">
                  <?php if(strlen($item->description) > $this->description_truncation_list){
                      $description = strip_tags($item->description);
              				$description = mb_substr($description,0,$this->description_truncation_list).'...';
                      echo $title = nl2br($description);
                  	 }else{ ?>
                  <?php  echo nl2br(strip_tags($item->description));?>
                  <?php } ?>
                </div>
                <?php } ?>
                <?php 
                $viewer = Engine_Api::_()->user()->getViewer()->getIdentity();
                if(!empty($viewer) && $playlist) { 
                if($viewer && ($viewer == $playlist->owner_id || $viewer->level_id == 1)){ ?>
                  <a href="<?php echo $this->url(array('module' => 'sesvideo', 'action' => 'remove-playlist-video','playlist_video_id' => $oldItem->playlistvideo_id), 'sesvideo_general', true); ?>" class="sesbasic_icon_btn sesvideo_remove_playlist" title="<?php echo $this->translate('Remove Playlist Video'); ?>" onclick='opensmoothboxurl(this.href);return false;'>
                        <i class="fa fa-times"></i>
                      </a>
                  <?php } } ?>
                <div class="sesvideo_options_buttons sesvideo_list_options sesbasic_clearfix">
                  <?php if(isset($this->my_videos) && $this->my_videos){ ?>
                    <?php if($this->can_edit){ ?>
                    	<a href="<?php echo $this->url(array('module' => 'sesvideo', 'action' => 'edit', 'video_id' => $item->video_id), 'sesvideo_general', true); ?>" class="sesbasic_icon_btn" title="<?php echo $this->translate('Edit Video'); ?>">
                    		<i class="fa fa-edit"></i>
                  		</a>
                    <?php } ?>
                    <?php if ($item->status !=2 && $this->can_delete){ ?>
                   		<a href="<?php echo $this->url(array('module' => 'sesvideo', 'action' => 'delete', 'video_id' => $item->video_id), 'sesvideo_general', true); ?>" class="sesbasic_icon_btn" title="<?php echo $this->translate('Delete Video'); ?>" onclick='opensmoothboxurl(this.href);return false;'>
                    		<i class="fa fa-trash"></i>
                  	</a>
                 		<?php } ?>
                    <?php if($item->status == 0):?>
                      <div class="tip">
                        <span>
                          <?php echo $this->translate('Your video is in queue to be processed - you will be notified when it is ready to be viewed.')?>
                        </span>
                      </div>
                    <?php elseif($item->status == 2):?>
                      <div class="tip">
                        <span>
                          <?php echo $this->translate('Your video is currently being processed - you will be notified when it is ready to be viewed.')?>
                        </span>
                      </div>
                    <?php elseif($item->status == 3):?>
                      <div class="tip">
                        <span>
                         <?php echo $this->translate('Video conversion failed. Please try %1$suploading again%2$s.', '<a href="'.$this->url(array('action' => 'create','module'=>'sesvideo','controller'=>'index'),'default',true).'/type/3'.'">', '</a>'); ?>
                        </span>
                      </div>
                    <?php elseif($item->status == 4):?>
                      <div class="tip">
                        <span>
                         <?php echo $this->translate('Video conversion failed. Video format is not supported by FFMPEG. Please try %1$sagain%2$s.', '<a href="'.$this->url(array('action' => 'create','module'=>'sesvideo','controller'=>'index'),'default',true).'/type/3'.'">', '</a>'); ?>
                        </span>
                      </div>
                    <?php elseif($item->status == 5):?>
                      <div class="tip">
                        <span>
                         <?php echo $this->translate('Video conversion failed. Audio files are not supported. Please try %1$sagain%2$s.', '<a href="'.$this->url(array('action' => 'create','module'=>'sesvideo','controller'=>'index'),'default',true).'/type/3'.'">', '</a>'); ?>
                        </span>
                      </div>
                    <?php elseif($item->status == 7):?>
                      <div class="tip">
                        <span>
                         <?php echo $this->translate('Video conversion failed. You may be over the site upload limit.  Try %1$suploading%2$s a smaller file, or delete some files to free up space.', '<a href="'.$this->url(array('action' => 'create','module'=>'sesvideo','controller'=>'index'),'default',true).'/type/3'.'">', '</a>'); ?>
                        </span>
                      </div>
                    <?php elseif(!$item->approve):?>
                   <div class="tip">
                     <span>
                      <?php echo $this->translate('Your video has been successfully submitted for approval to our adminitrators - you will be notified when it is ready to be viewed.'); ?>
                     </span>
                   </div>
                <?php endif;?>
                  <?php } else if(isset($this->my_channel) && $this->my_channel){ ?>
                    <?php if($this->can_edit){  ?>
                    	<a href="<?php echo $this->url(array('module' => 'sesvideo', 'action' => 'edit', 'chanel_id' => $item->chanel_id), 'sesvideo_chanel', true); ?>" class="sesbasic_icon_btn" title="<?php echo $this->translate('Edit Channel'); ?>">
                    		<i class="fa fa-edit"></i>
                  		</a>
                    <?php } ?>
                    <?php if ($this->can_delete){ ?>
                      <a href="<?php echo $this->url(array('module' => 'sesvideo', 'action' => 'delete', 'chanel_id' => $item->chanel_id), 'sesvideo_chanel', true); ?>" class="sesbasic_icon_btn" title="<?php echo $this->translate('Delete Channel'); ?>" onclick='opensmoothboxurl(this.href);return false;'>
                    		<i class="fa fa-trash"></i>
                  		</a>
                     <?php } ?>
                  <?php } ?>
                </div>
				</div>
            </li>
        <?php }else if(isset($this->view_type) && $this->view_type == 'pinboard'){ ?>
        		  <li class="sesbasic_bxs sesbasic_pinboard_list_item_wrap new_image_pinboard   <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.sesvideo',1) && Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($item)) { ?> paid_content <?php } ?>">
              <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.sesvideo',1) && Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($item)) { ?>
                <?php echo $this->partial('application/modules/Epaidcontent/views/scripts/_paidContent.tpl', 'epaidcontent', array('item' => $item)); ?>
              <?php } ?>
              	<div class="sesbasic_pinboard_list_item sesbm <?php if((isset($this->my_videos) && $this->my_videos) || (isset($this->my_channel) && $this->my_channel)){ ?>isoptions<?php } ?>">
                	<div class="sesbasic_pinboard_list_item_top sesvideo_play_btn_wrap">
                  	<div class="sesbasic_pinboard_list_item_thumb">
                  		<a href="<?php echo $item->getHref($chanelCustomUrl)?>" data-url = "<?php echo $item->getType() ?>" class="<?php echo $item->getType() != 'sesvideo_chanel' ? 'sesvideo_thumb_img sesvideo_lightbox_open' : 'sesvideo_thumb_nolightbox' ?>">
                        <?php  if(isset($item->type) && ($item->type == 4 || ( $item->type == 17 && strpos($item->code,'dailymotion')!==false))){  ?>
                          <img src="<?php echo $thumbnail_url; ?>">
                            <span style="background-image:url(<?php echo $thumbnail_url; ?>);"></span>
                            <?php }else{  ?>
                              <img src="<?php echo $item->getPhotoUrl(); ?>">
                             <span style="background-image:url(<?php echo $item->getPhotoUrl(); ?>);display:none;"></span>
                             <?php } ?>
                      </a>
                    </div>
                    <?php if ($item->getType() == 'video'){ ?>
                    <a href="<?php echo $item->getHref($chanelCustomUrl)?>" data-url = "<?php echo $item->getType() ?>" class="sesvideo_play_btn fa fa-play-circle <?php echo $item->getType() != 'sesvideo_chanel' ? 'sesvideo_thumb_img sesvideo_lightbox_open' : 'sesvideo_thumb_nolightbox' ?>">
                    	<span style="background-image:url(<?php echo $item->getPhotoUrl(); ?>);display:none;"></span>
                    </a>
                    <?php  } ?>
                    <?php if(isset($this->featuredLabelActive) || isset($this->sponsoredLabelActive) || isset($this->hotLabelActive)){ ?>
                      <div class="sesbasic_pinboard_list_label">
                      <?php if(isset($this->featuredLabelActive) && $item->is_featured == 1){ ?>
                        <span class="sesvideo_label_featured"><?php echo $this->translate('FEATURED'); ?></span>
                      <?php } ?>
                      <?php if(isset($this->sponsoredLabelActive) && $item->is_sponsored == 1){ ?>
                        <span class="sesvideo_label_sponsored"><?php echo $this->translate("SPONSORED"); ?></span>
                      <?php } ?>
                      <?php if(isset($this->hotLabelActive) && $item->is_hot == 1){ ?>
                        <span class="sesvideo_label_hot"><?php echo $this->translate("HOT"); ?></span>
                      <?php } ?>
                      </div>
                     <?php } ?>
                  <?php if(isset($this->socialSharingActive) || isset($this->likeButtonActive) || isset($this->favouriteButtonActive) || isset($this->playlistAddActive)){
                    $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $item->getHref()); ?>
                     <div class="sesbasic_pinboard_list_btns">
                   <?php if(isset($this->socialSharingActive)){ ?>

                    <?php if($this->socialshare_icon_limit): ?>
                      <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $item, 'socialshare_enable_plusicon' => $this->socialshare_enable_plusicon, 'socialshare_icon_limit' => $this->socialshare_icon_limit)); ?>
                    <?php else: ?>
                      <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $item, 'socialshare_enable_plusicon' => $this->socialshare_enable_pinviewplusicon, 'socialshare_icon_limit' => $this->socialshare_icon_pinviewlimit)); ?>
                    <?php endif; ?>

                    <?php }
                    if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0 ){
                           if(isset($item->chanel_id)){
                                  $itemtype = 'sesvideo_chanel';
                                  $getId = 'chanel_id';
                                  $favAllow = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo.allowfavc', 1);
                                }else{
                                  $itemtype = 'sesvideo_video';
                                  $getId = 'video_id';
                                  $favAllow = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo.allowfavv', 1);
                                }
                          $canComment =  $item->authorization()->isAllowed(Engine_Api::_()->user()->getViewer(), 'comment');
                          if(isset($this->likeButtonActive) && $canComment){
                        ?>
                      <!--Like Button-->
                      <?php $LikeStatus = Engine_Api::_()->sesvideo()->getLikeStatusVideo($item->$getId,$item->getType()); ?>
                        <a href="javascript:;" data-url="<?php echo $item->$getId ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sesvideo_like_<?php echo $itemtype; ?> <?php echo ($LikeStatus) ? 'button_active' : '' ; ?>"> <i class="fa fa-thumbs-up"></i><span><?php echo $item->like_count; ?></span></a>
                        <?php } ?>
                         <?php if($favAllow && isset($this->favouriteButtonActive) && isset($item->favourite_count)){ ?>

                        <?php $favStatus = Engine_Api::_()->getDbtable('favourites', 'sesvideo')->isFavourite(array('resource_type'=>$itemtype,'resource_id'=>$item->$getId)); ?>
                        <a href="javascript:;" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_fav_btn sesvideo_favourite_<?php echo $itemtype; ?> <?php echo ($favStatus)  ? 'button_active' : '' ?>"  data-url="<?php echo $item->$getId ; ?>"><i class="fa fa-heart"></i><span><?php echo $item->favourite_count; ?></span></a>
                      <?php } ?>
                         <?php if(empty($item->chanel_id) && isset($this->playlistAddActive)){ ?>
                         <?php if(Engine_Api::_()->authorization()->getPermission(Engine_Api::_()->user()->getViewer()->level_id, 'video', 'addplayl_video')) { ?>
                        <a href="javascript:;" onclick="opensmoothboxurl('<?php echo $this->url(array('action' => 'add','module'=>'sesvideo','controller'=>'playlist','video_id'=>$item->video_id),'default',true); ?>')" class="sesbasic_icon_btn sesvideo_add_playlist"  title="<?php echo  $this->translate('Add To Playlist'); ?>" data-url="<?php echo $item->video_id ; ?>"><i class="fa fa-plus"></i></a>
                         <?php } ?>
                      <?php } ?>
                    <?php  } ?>
                  </div>
                  <?php } ?>
										<?php if(isset($this->durationActive) && isset($item->duration) && $item->duration ): ?>
                      <span class="sesvideo_length">
                        <?php
                          if( $item->duration >= 3600 ) {
                            $duration = gmdate("H:i:s", $item->duration);
                          } else {
                            $duration = gmdate("i:s", $item->duration);
                          }
                          echo $duration;
                        ?>
                      </span>
                    <?php endif ?>
                     <?php if(isset($this->watchLaterActive) && (isset($item->watchlater_id) || isset($watchlater_watch_id_exists)) && Engine_Api::_()->user()->getViewer()->getIdentity() != '0'){ ?>
                    <?php $watchLaterId = isset($watchlater_watch_id_exists) ? $watchlater_watch_id : $item->watchlater_id; ?>
                      <a href="javascript:;" class="sesvideo_watch_later_btn sesvideo_watch_later <?php echo !is_null($watchLaterId)  ? 'selectedWatchlater' : '' ?>" title = "<?php echo !is_null($watchLaterId)  ? $this->translate('Remove from Watch Later') : $this->translate('Add to Watch Later') ?>" data-url="<?php echo $item->video_id ; ?>"></a>
                    <?php } ?>
                    <?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesvideosell')) {
                      $videoItem = Engine_Api::_()->getItem('sesvideo_video', $item->video_id);
                      if($videoItem->price > 0) { ?>
                    <span class="sesvideo_paid_label sesbasic_animation"><?php echo $this->translate("Paid")?></span>
                    <?php } } ?>
                  </div>
                  <div class="sesbasic_pinboard_list_item_cont sesbasic_clearfix">
              			<div class="sesbasic_pinboard_list_item_title">
                    <?php if(strlen($item->getTitle()) > $this->title_truncation_pinboard){
              				 $title = mb_substr($item->getTitle(),0,$this->title_truncation_pinboard).'...';
                       echo $this->htmlLink($item->getHref(),$title ) ?>
                    <?php }else{ ?>
                    <?php echo $this->htmlLink($item->getHref(),$item->getTitle() ) ?>
                    <?php } ?>
                    </div>
                    <?php if(isset($this->categoryActive)){ ?>
                      <?php if($item->category_id != '' && intval($item->category_id) && !is_null($item->category_id)){
                         $categoryItem = Engine_Api::_()->getItem('sesvideo_category', $item->category_id);
                      ?>
                      <?php if($categoryItem): ?>
                      <div class="sesvideo_grid_date sesvideo_list_stats sesbasic_text_light">
                        <span>
                          <i class="far fa-folder-open" title="<?php echo $this->translate('Category');?>"></i>
                          <a href="<?php echo $categoryItem->getHref(); ?>">
                          	<?php echo $categoryItem->category_name; ?>
                          </a>
                          <?php $subcategory = Engine_Api::_()->getItem('sesvideo_category',$item->subcat_id); ?>
                           <?php if($subcategory){ ?>
                              &nbsp;&raquo;&nbsp;<a href="<?php echo $subcategory->getHref(); ?>"><?php echo $subcategory->category_name; ?></a>
                          <?php } ?>
                          <?php $subsubcategory = Engine_Api::_()->getItem('sesvideo_category',$item->subsubcat_id); ?>
                           <?php if($subsubcategory){ ?>
                              &nbsp;&raquo;&nbsp;<a href="<?php echo $subsubcategory->getHref(); ?>"><?php echo $subsubcategory->category_name; ?></a>
                          <?php } ?>
                        </span>
                      </div>
                      <?php endif; ?>
                       <?php } ?>
                    <?php } ?>
										<?php if(isset($this->locationActive) && isset($item->location) && $item->location && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo_enable_location', 1)){ ?>
                      <div class="sesvideo_grid_date sesvideo_list_stats sesbasic_text_light sesvideo_list_location">
                        <span>
                          <i class="fas fa-map-marker-alt"></i>
                          <?php include APPLICATION_PATH .  '/application/modules/Sesvideo/views/scripts/_location_direction.tpl';?>
                        </span>
                      </div>
                    <?php } ?>
                    <?php if(isset($this->descriptionpinboardActive)){ ?>
                    	<div class="sesbasic_pinboard_list_item_des">
                      <?php if(strlen($item->description) > $this->description_truncation_pinboard){
                        $description = strip_tags($item->description);
                        $description = mb_substr($description,0,$this->description_truncation_pinboard).'...';
                        echo $title = nl2br($description);
                       }else{ ?>
                        <?php  echo nl2br(strip_tags($item->description));?>
                      <?php } ?>
                      </div>
                		<?php } ?>
                    <?php if(isset($this->my_videos) && $this->my_videos ){ ?>
                    	<?php if($this->can_edit || $item->status !=2 && $this->can_delete){ ?>
                        <div class="sesvideo_grid_date sesbasic_text_light">
                          <span class="sesvideo_list_option_toggle fa fa-ellipsis-v sesbasic_text_light"></span>
                          <div class="sesvideo_listing_options_pulldown">
                            <?php if($this->can_edit){
                              echo $this->htmlLink(array('route' => 'sesvideo_general','module' => 'sesvideo','controller' => 'index','action' => 'edit','video_id' => $item->video_id), $this->translate('Edit Video')) ;
                            } ?>
                            <?php if ($item->status !=2 && $this->can_delete){
                              echo $this->htmlLink(array('route' => 'sesvideo_general', 'module' => 'sesvideo', 'controller' => 'index', 'action' => 'delete', 'video_id' => $item->video_id), $this->translate('Delete Video'), array('onclick' =>'opensmoothboxurl(this.href);return false;'));
                            } ?>
                          </div>
                      	</div>
                      <?php } ?>
                      <div class="sesvideo_manage_status_tip">
                        <?php if($item->status == 0):?>
                           <div class="tip">
                             <span>
                               <?php echo $this->translate('Your video is in queue to be processed - you will be notified when it is ready to be viewed.')?>
                             </span>
                           </div>
                           <?php elseif($item->status == 2):?>
                           <div class="tip">
                             <span>
                               <?php echo $this->translate('Your video is currently being processed - you will be notified when it is ready to be viewed.')?>
                             </span>
                           </div>
                           <?php elseif($item->status == 3):?>
                           <div class="tip">
                             <span>
                              <?php echo $this->translate('Video conversion failed. Please try %1$suploading again%2$s.', '<a href="'.$this->url(array('action' => 'create','module'=>'sesvideo','controller'=>'index'),'default',true).'/type/3'.'">', '</a>'); ?>
                             </span>
                           </div>
                           <?php elseif($item->status == 4):?>
                           <div class="tip">
                             <span>
                              <?php echo $this->translate('Video conversion failed. Video format is not supported by FFMPEG. Please try %1$sagain%2$s.', '<a href="'.$this->url(array('action' => 'create','module'=>'sesvideo','controller'=>'index'),'default',true).'/type/3'.'">', '</a>'); ?>
                             </span>
                           </div>
                           <?php elseif($item->status == 5):?>
                           <div class="tip">
                             <span>
                              <?php echo $this->translate('Video conversion failed. Audio files are not supported. Please try %1$sagain%2$s.', '<a href="'.$this->url(array('action' => 'create','module'=>'sesvideo','controller'=>'index'),'default',true).'/type/3'.'">', '</a>'); ?>
                             </span>
                           </div>
                           <?php elseif($item->status == 7):?>
                           <div class="tip">
                             <span>
                              <?php echo $this->translate('Video conversion failed. You may be over the site upload limit.  Try %1$suploading%2$s a smaller file, or delete some files to free up space.', '<a href="'.$this->url(array('action' => 'create','module'=>'sesvideo','controller'=>'index'),'default',true).'/type/3'.'">', '</a>'); ?>
                             </span>
                           </div>
                        <?php endif;?>
                      </div>
                    <?php }else if(isset($this->my_channel) && $this->my_channel){ ?>
                    	<?php if($this->can_edit || $this->can_delete){  ?>
                        <div>
                          <span class="sesvideo_list_option_toggle fa fa-ellipsis-v sesbasic_text_light"></span>
                          <div class="sesvideo_listing_options_pulldown">
                            <?php if($this->can_edit){  ?>
                              <a href="<?php echo $this->url(array('module' => 'sesvideo', 'action' => 'edit', 'chanel_id' => $item->chanel_id), 'sesvideo_chanel', true); ?>"><?php echo $this->translate('Edit Channel'); ?></a>
                            <?php } ?>
                            <?php if ($this->can_delete){
                              echo $this->htmlLink($this->url(array( 'module' => 'sesvideo', 'action' => 'delete', 'chanel_id' => $item->chanel_id),'sesvideo_chanel',true), $this->translate('Delete Channel'), array('onclick' =>'opensmoothboxurl(this.href);return false;'));
                            }?>
                          </div>
                      	</div>
                      <?php } ?>
                    <?php } ?>
                  </div>
                  <div class="sesbasic_pinboard_list_item_btm sesbm sesbasic_clearfix">
                  	<?php if(isset($this->byActive)){ ?>
                      <div class="sesbasic_pinboard_list_item_poster sesbasic_text_light sesbasic_clearfix">
                        <?php $owner = $item->getOwner(); ?>
                        <div class="sesbasic_pinboard_list_item_poster_thumb">
                        	<?php echo $this->htmlLink($item->getOwner()->getParent(), $this->itemPhoto($item->getOwner()->getParent(), 'thumb.icon')); ?>
                        </div>
                        <div class="sesbasic_pinboard_list_item_poster_info">
                          <span class="sesbasic_pinboard_list_item_poster_info_title"><?php echo $this->htmlLink($owner->getHref(),$owner->getTitle() ) ?></span>
                          <span class="sesbasic_pinboard_list_item_poster_info_stats sesbasic_text_light">
                            <?php if(isset($this->likeActive) && isset($item->like_count)) { ?>
                              <span title="<?php echo $this->translate(array('%s like', '%s likes', $item->like_count), $this->locale()->toNumber($item->like_count)); ?>"><i class="sesbasic_icon_like_o"></i><?php echo $item->like_count; ?></span>
                            <?php } ?>
                            <?php if(isset($this->commentActive) && isset($item->comment_count)) { ?>
                              <span title="<?php echo $this->translate(array('%s comment', '%s comments', $item->comment_count), $this->locale()->toNumber($item->comment_count))?>"><i class="sesbasic_icon_comment_o"></i><?php echo $item->comment_count;?></span>
                            <?php } ?>
                             <?php if($favAllow && isset($this->favouriteActive) && isset($item->favourite_count)) { ?>
                                  <span title="<?php echo $this->translate(array('%s favourite', '%s favourites', $item->favourite_count), $this->locale()->toNumber($item->favourite_count))?>"><i class="sesbasic_icon_favourite_o"></i><?php echo $item->favourite_count;?></span>
                                <?php } ?>
                            <?php if(isset($this->viewActive) && isset($item->view_count)) { ?>
                              <span title="<?php echo $this->translate(array('%s view', '%s views', $item->view_count), $this->locale()->toNumber($item->view_count))?>"><i class="sesbasic_icon_view"></i><?php echo $item->view_count; ?></span>
                            <?php } ?>
                               <?php if(isset($this->ratingActive) && $ratingShow && isset($item->rating) && $item->rating > 0 ): ?>
                      <span title="<?php echo $this->translate(array('%s rating', '%s ratings', round($item->rating,1)), $this->locale()->toNumber(round($item->rating,1)))?>"><i class="far fa-star"></i><?php echo round($item->rating,1).'/5';?></span>
                    <?php endif; ?>
                          </span>
                        </div>
                      </div>
										<?php } ?>
                  <?php if(isset($this->enableCommentPinboardActive)){ ?>
                    <div class="sesbasic_pinboard_list_comments sesbasic_clearfix">
                    <?php $itemType = '';?>
                    <?php if(isset($item->video_id)){
                    	$item_id = $item->video_id;
                      $itemType = 'video';
                     }else if (isset($item->chanel_id)){
                    	$item_id = $item->chanel_id;
                      $itemType = 'sesvideo_chanel';
                     } ?>
                    <?php if(($itemType != '')){ ?>
                    	<?php echo Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedcomment') ? $this->action('list', 'comment', 'sesadvancedcomment', array('type' => $itemType, 'id' => $item_id,'page'=>'')) :  $this->action("list", "comment", "sesbasic", array("item_type" => $itemType, "item_id" => $item_id,"widget_identity"=>$randonNumber)); ?>
                      <?php } ?>
                    </div>
                  <?php } ?>
                  </div>
              	</div>
              </li>
        <?php
        }else if(isset($this->view_type) && $this->view_type == 'playlist'){ ?>
            <div class="sesvideo_listing_list sesbasic_clearfix clear">
            <div class="sesvideo_browse_playlist_thumb sesvideo_list_thumb sesvideo_thumb">
              <a href="<?php echo $item->getHref(); ?>" class="sesvideo_thumb_nolightbox">
                <span style="background-image:url(<?php echo $item->getPhotoUrl(); ?>);"></span>
              </a>
              <?php if(isset($this->featuredLabelActive) || isset($this->sponsoredLabelActive)){ ?>
                <p class="sesvideo_labels">
                <?php if(isset($this->featuredLabelActive) && $item->is_featured ){ ?>
                  <span class="sesvideo_label_featured"><?php echo $this->translate('FEATURED'); ?></span>
                <?php } ?>
                <?php if(isset($this->sponsoredLabelActive) && $item->is_sponsored ){ ?>
                  <span class="sesvideo_label_sponsored"><?php echo $this->translate("SPONSORED"); ?></span>
                <?php } ?>
                </p>
               <?php } ?>
							<div class="sesvideo_thumb_btns">
             		<?php
          			$urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $item->getHref()); ?>
              <?php if(isset($this->socialSharingActive)){ ?>

                    <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $item, 'socialshare_enable_plusicon' => $this->socialshare_enable_plusicon, 'socialshare_icon_limit' => $this->socialshare_icon_limit)); ?>


                    <?php } ?>
               <?php if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0): ?>
              	 <?php  if(isset($this->likeButtonActive)){ ?>
              <!--Like Button-->
              <?php $LikeStatus = Engine_Api::_()->sesvideo()->getLikeStatusVideo($item->playlist_id,'sesvideo_playlist'); ?>
                <a href="javascript:;" data-url="<?php echo $item->playlist_id ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sesvideo_like_sesvideo_playlist <?php echo ($LikeStatus) ? 'button_active' : '' ; ?>"> <i class="fa fa-thumbs-up"></i><span><?php echo $item->like_count; ?></span></a>
                <?php } ?>
                 <?php if($favAllow && isset($this->favouriteButtonActive) && isset($item->favourite_count)){ ?>
                <?php $favStatus = Engine_Api::_()->getDbtable('favourites', 'sesvideo')->isFavourite(array('resource_type'=>'sesvideo_playlist','resource_id'=>$item->playlist_id)); ?>
                <a href="javascript:;" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_fav_btn sesvideo_favourite_sesvideo_playlist <?php echo ($favStatus)  ? 'button_active' : '' ?>"  data-url="<?php echo $item->playlist_id ; ?>"><i class="fa fa-heart"></i><span><?php echo $item->favourite_count; ?></span></a>
              <?php } ?>
                <a  href="javascript:void(0);" class="smoothbox sesbasic_icon_btn" title="<?php echo $this->translate("Share") ?>" onclick="openURLinSmoothBox('<?php echo $this->escape($this->url(array('module'=>'activity', 'controller'=>'index', 'action'=>'share', 'route'=>'default', 'type'=>'sesvideo_playlist', 'id' => $item->playlist_id, 'format' => 'smoothbox'), 'default' , true)); ?>'); return false;">
                	<i class="fas fa-share-alt"></i>
                </a>
                 <?php if(isset($this->my_playlist)){ ?>
                  <a href="<?php echo $this->url(array('action'=>'edit', 'playlist_id'=>$item->getIdentity(),'slug'=>$item->getSlug()),'sesvideo_playlist_view',true) ?>" class="sesbasic_icon_btn" title="<?php echo $this->translate("Edit Playlist") ?>">
                    <i class="fa fa-edit"></i>
                  </a>
                  <a href="<?php echo $this->url(array('action'=>'delete', 'playlist_id'=>$item->getIdentity(),'slug'=>$item->getSlug(),  'format' => 'smoothbox'),'sesvideo_playlist_view',true) ?>" class="sesbasic_icon_btn smoothbox" title="<?php echo $this->translate("Delete Playlist") ?>">
                    <i class="fa fa-trash"></i>
                    </a>
                 <?php } ?>
                 <?php endif; ?>
              </div>
            </div>
            <div class="sesvideo_list_info">
              <?php if(!empty($this->titleActive)): ?>
              <div class="sesvideo_list_title">
                <?php echo $this->htmlLink($item->getHref(), $item->getTitle(),array('title'=>$item->getTitle()) ) ?>
              </div>
              <?php endif; ?>
              <?php if(!empty($this->byActive)): ?>
              <div class="sesvideo_list_date sesbasic_text_light">
                <?php echo $this->translate('Created By %s', $this->htmlLink($item->getOwner(), $item->getOwner()->getTitle())) ?>
              </div>
              <?php endif; ?>
              <div class="sesvideo_grid_date sesvideo_list_stats sesbasic_text_light">
              <?php if(isset($this->likeActive) && isset($item->like_count)) { ?>
                <span title="<?php echo $this->translate(array('%s like', '%s likes', $item->like_count), $this->locale()->toNumber($item->like_count)); ?>"><i class="sesbasic_icon_like_o"></i><?php echo $item->like_count; ?></span>
              <?php } ?>
               <?php if($favAllow && isset($this->favouriteActive) && isset($item->favourite_count)) { ?>
                  	<span title="<?php echo $this->translate(array('%s favourite', '%s favourites', $item->favourite_count), $this->locale()->toNumber($item->favourite_count))?>"><i class="sesbasic_icon_favourite_o"></i><?php echo $item->favourite_count;?></span>
                  <?php } ?>
              <?php if(isset($this->viewActive) && isset($item->view_count)) { ?>
                <span title="<?php echo $this->translate(array('%s view', '%s views', $item->view_count), $this->locale()->toNumber($item->view_count))?>"><i class="sesbasic_icon_view"></i><?php echo $item->view_count; ?></span>
              <?php } ?>
              </div>
              <?php if(!empty($this->descriptionActive)): ?>
                <div class="sesvideo_list_des">
                  <?php echo $this->viewMore(nl2br($item->description)); ?>
                </div>
              <?php endif; ?>

              <?php $playlist = $item;
              $videos = $item->getVideos();
                $playlistUrl = array('type'=>'sesvideo_playlist','item_id'=>$item->getIdentity());
             ?>
              <?php if(engine_count($videos) > 0): ?>
              <div class="clear sesbasic_clearfix sesvideo_videos_minilist_container sesbm sesbasic_custom_scroll">
                <ul class="clear sesvideo_videos_minilist sesbasic_bxs">
                  <?php foreach( $videos as $videoItem ): ?>
                  <?php $video = Engine_Api::_()->getItem('video', $videoItem->file_id); ?>
                  <?php if( !empty($video) ): ?>
                  <li class="sesbasic_clearfix sesbm">
                    <div class="sesvideo_videos_minilist_photo">
                      <a class="sesvideo_thumb_img sesvideo_lightbox_open" data-url = "<?php echo $item->getType() ?>" href="<?php echo $video->getHref($playlistUrl); ?>">
                        <span style="background-image:url(<?php echo $video->getPhotoUrl() ?>);"></span>
                      </a>
                    </div>

                     <?php if( isset($this->watchLaterActive) && isset($videoItem->watchlater_id)){ ?>
                      <div class="sesvideo_videos_minilist_buttons">
                        <a href="javascript:;" class="sesbasic_icon_btn sesvideo_watch_later <?php echo !is_null($videoItem->watchlater_id)  ? 'selectedWatchlater' : '' ?>" title = "<?php echo !is_null($videoItem->watchlater_id)  ? $this->translate('Remove from Watch Later') : $this->translate('Add to Watch Later') ?>" data-url="<?php echo $video->video_id ; ?>"><i class="far fa-clock"></i></a>
                      </div>
                    <?php } ?>
                    <div class="sesvideo_videos_minilist_name" title="<?php echo $video->title ?>">
                    	<?php echo $this->htmlLink($video->getPhotoUrl(), $this->htmlLink($video->getHref(), $video->title), array()); ?>
                    </div>
                  </li>
                  <?php endif; ?>
                  <?php endforeach; ?>
                </ul>
              </div>
              <?php endif; ?>
               </div>
            </div>
      <?php 	} ?>
        <?php endforeach; ?>
        <?php  if(  $this->paginator->getTotalItemCount() == 0 &&  (empty($this->widgetType))){  ?>
            <?php if( isset($this->category) || isset($this->tag) || isset($this->text) ):?>
              <div class="tip">
                <span>
                  <?php echo $this->translate('Nobody has posted a video with that criteria.');?>
                  <?php if ($this->can_create && empty($this->type)):?>
                    <?php echo $this->translate('Be the first to %1$spost%2$s one!', '<a href="'.$this->url(array('action' => 'create'), "sesvideo_general").'">', '</a>'); ?>
                  <?php endif; ?>
                </span>
              </div>
            <?php else:?>
              <div class="tip">
                <span>
                  <?php echo $this->translate('Nobody has created a video yet.');?>
                  <?php if ($this->can_create && empty($this->type)):?>
                    <?php echo $this->translate('Be the first to %1$spost%2$s one!', '<a href="'.$this->url(array('action' => 'create'), "sesvideo_general").'">', '</a>'); ?>
                  <?php endif; ?>
                </span>
              </div>
            <?php endif; ?>
    			<?php }else if( $this->paginator->getTotalItemCount() == 0 && isset($this->tabbed_widget) && $this->tabbed_widget){?>
          		<div class="tip">
                <span>
                	<?php $errorTip = ucwords(str_replace('SP',' ',$this->defaultOpenTab)); ?>
                  <?php echo $this->translate("There are currently no %s",$errorTip);?>
                  <?php if (isset($this->can_create) && $this->can_create  && empty($this->type)):?>
                    <?php echo $this->translate('%1$spost%2$s one!', '<a href="'.$this->url(array('action' => 'create'), "sesvideo_general").'">', '</a>'); ?>
                  <?php endif; ?>
                </span>
              </div>
          <?php } ?>
     <?php
   if((isset($this->optionsListGrid['paggindData']) || $this->loadJs) && $this->loadOptionData == 'pagging' && !isset($this->show_limited_data)){ ?>
 		 <?php echo $this->paginationControl($this->paginator, null, array("_pagging.tpl", "sesvideo"),array('identityWidget'=>$randonNumber)); ?>
 <?php } ?>
 <?php if(!$this->is_ajax){ ?>
 <?php if(isset($this->optionsListGrid['tabbed']) || $this->loadJs){ ?>
  </ul>
 <?php } ?>
 <?php if((isset($this->optionsListGrid['paggindData']) || $this->loadJs) && $this->loadOptionData != 'pagging' && !isset($this->show_limited_data)){ ?>
 <div class="sesbasic_load_btn" style="display: none;" id="view_more_<?php echo $randonNumber;?>" onclick="viewMore_<?php echo $randonNumber; ?>();" >
      <a href="javascript:void(0);" class="sesbasic_animation sesbasic_link_btn" id="feed_viewmore_link_<?php echo $randonNumber; ?>"><i class="fa fa-sync"></i><span><?php echo $this->translate('View More');?></span></a>
    </div>  
    <div class="sesbasic_load_btn sesbasic_view_more_loading_<?php echo $randonNumber;?>" id="loading_image_<?php echo $randonNumber; ?>" style="display: none;"><span class="sesbasic_link_btn"><i class="fa fa-spinner fa-spin"></i></span></div>
 <?php }
 	 if(isset($this->optionsListGrid['tabbed']) || $this->loadJs){ ?>
</div>
<?php } ?>
<?php if(isset($this->optionsListGrid['tabbed']) && !$this->is_ajax){ ?>
</div>
</div>
<?php } ?>

<?php if(isset($this->optionsListGrid['paggindData']) || isset($this->loadJs)){ ?>
<script type="text/javascript">
var valueTabData ;
// globally define available tab array
	var availableTabs_<?php echo $randonNumber; ?>;

<?php if(isset($defaultOptionArray)){ ?>
  availableTabs_<?php echo $randonNumber; ?> = <?php echo json_encode($defaultOptionArray); ?>;
<?php  } ?>
<?php if($this->loadOptionData == 'auto_load' && !isset($this->show_limited_data)){ ?>
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
scriptJquery(document).on('click','.selectView_<?php echo $randonNumber; ?>',function(){
		if(scriptJquery(this).hasClass('active'))
			return;
		if(document.getElementById("view_more_<?php echo $randonNumber; ?>"))
			document.getElementById("view_more_<?php echo $randonNumber; ?>").style.display = 'none';
		document.getElementById("tabbed-widget_<?php echo $randonNumber; ?>").innerHTML = "<div class='clear sesbasic_loading_container'></div>";
		scriptJquery('#sesvideo_grid_view_<?php echo $randonNumber; ?>').removeClass('active');
		scriptJquery('#sesvideo_list_view_<?php echo $randonNumber; ?>').removeClass('active');
		scriptJquery('#sesvideo_pinboard_view_<?php echo $randonNumber; ?>').removeClass('active');
		scriptJquery('#view_more_<?php echo $randonNumber; ?>').css('display','none');
		scriptJquery('#loading_image_<?php echo $randonNumber; ?>').css('display','none');
		scriptJquery(this).addClass('active');
		if(scriptJquery('#filter_form').length)
			var	searchData<?php echo $randonNumber; ?> = scriptJquery('#filter_form').serialize();

		if(scriptJquery('#tab-widget-sesvideo-<?php echo $randonNumber ?>').length){
			defaultOpenTab = scriptJquery('#tab-widget-sesvideo-<?php echo $randonNumber ?>').find('.sesbasic_tab_selected').attr('id');
			defaultOpenTab = defaultOpenTab.replace('sesTabContainer_','');
			defaultOpenTab = defaultOpenTab.replace('<?php echo $randonNumber; ?>_','');
		}else{
			defaultOpenTab = '';
		}
	  requestTab_<?php echo $randonNumber; ?> = (scriptJquery.ajax({
      dataType: 'html',
      method: 'post',
      'url': en4.core.baseUrl + "widget/index/mod/sesvideo/name/<?php echo $this->widgetName; ?>/openTab/" + defaultOpenTab,
      'data': {
        format: 'html',
        page: 1,
				type:scriptJquery('.selectView_<?php echo $randonNumber; ?>.active').attr('rel'),
				params : <?php echo json_encode($this->params); ?>,
				is_ajax : 1,
        searchFlag : searchFlag,
				searchParams:searchData<?php echo $randonNumber; ?>,
				identity : '<?php echo $randonNumber; ?>',
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
        pinboardLayout_<?php echo $randonNumber ?>();
      }
    }));
});
</script>
<?php } ?>
<?php } ?>
<?php if(!$this->is_ajax){ ?>
<script type="application/javascript">
	var wookmark = undefined;
 //Code for Pinboard View
  var wookmark<?php echo $randonNumber ?>;
  function pinboardLayout_<?php echo $randonNumber ?>(force){
		if(scriptJquery('.sesbasic_view_type_options_<?php echo $randonNumber; ?>').find('.active').attr('rel') != 'pinboard'){
		 scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').removeClass('sesbasic_pinboard_<?php echo $randonNumber; ?>');
		 scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').css('height','').css('display','');
	 		return;
	  }
		scriptJquery('.new_image_pinboard').css('display','none');
		var imgLoad = imagesLoaded('#tabbed-widget_<?php echo $randonNumber; ?>');
		imgLoad.on('progress',function(instance,image){
			scriptJquery(image.img.offsetParent).parent().parent().show();
			scriptJquery(image.img.offsetParent).parent().parent().removeClass('new_image_pinboard');
			imageLoadedAll<?php echo $randonNumber ?>();
		});
  }
  function imageLoadedAll<?php echo $randonNumber ?>(force){
	 scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').addClass('sesbasic_pinboard_<?php echo $randonNumber; ?>');
	 if (typeof wookmark<?php echo $randonNumber ?> == 'undefined') {
			(function() {
				function getWindowWidth(){
					return Math.max(document.documentElement.clientWidth, window.innerWidth || 0)
				}
				wookmark<?php echo $randonNumber ?> = new Wookmark('.sesbasic_pinboard_<?php echo $randonNumber; ?>', {
					itemWidth: <?php echo isset($this->width_pinboard) ? str_replace(array('px','%'),array(''),$this->width_pinboard) : '300'; ?>, // Optional min width of a grid item
					outerOffset: 0,
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
    if(typeof viewMoreHide_<?php echo $randonNumber; ?> != "undefined")
    viewMoreHide_<?php echo $randonNumber; ?>();
}
 scriptJquery(window).resize(function(e){
    pinboardLayout_<?php echo $randonNumber ?>('',true);
   });
    en4.core.runonce.add(function() {
	pinboardLayout_<?php echo $randonNumber ?>();
})
</script>
<?php } ?>
<?php if(isset($this->optionsListGrid['paggindData']) || isset($this->loadJs)){ ?>
<script type="text/javascript">
var defaultOpenTab ;
<?php if(isset($this->optionsListGrid['paggindData'])) {?>
	function changeTabSes_<?php echo $randonNumber; ?>(valueTab){
			if(valueTab == 'mySPaddVideo'){
        <?php // Other modules work
        if($this->parent_id && $this->parent_type) { ?>
          var videoUrl = "<?php echo $this->url(array('action' => 'create', 'parent_id' => $this->parent_id, 'parent_type' => $this->parent_type), 'sesvideo_general', true); ?>";
        <?php } else { ?>
          var videoUrl = "<?php echo $this->url(array('action' => 'create'), 'sesvideo_general', true); ?>";
				<?php } ?>
				window.location.href = videoUrl;
				return false;
			}else if(valueTab == 'mySPaddChannel'){
				var channelUrl = "<?php echo $this->url(array('action' => 'create'), 'sesvideo_chanel', true); ?>";
				window.location.href = channelUrl;
				return false;
			}

			if(scriptJquery("#sesTabContainer_<?php echo $randonNumber ?>_"+valueTab).hasClass('active'))
				return;
			var id = '_<?php echo $randonNumber; ?>';
			var length = availableTabs_<?php echo $randonNumber; ?>.length;
			for (var i = 0; i < length; i++){
				if(availableTabs_<?php echo $randonNumber; ?>[i] == valueTab){
					scriptJquery('#sesTabContainer'+id+'_'+availableTabs_<?php echo $randonNumber; ?>[i]).addClass('active');
					scriptJquery('#sesTabContainer'+id+'_'+availableTabs_<?php echo $randonNumber; ?>[i]).addClass('sesbasic_tab_selected');
				}
				else{
						scriptJquery('#sesTabContainer'+id+'_'+availableTabs_<?php echo $randonNumber; ?>[i]).removeClass('sesbasic_tab_selected');
					scriptJquery('#sesTabContainer'+id+'_'+availableTabs_<?php echo $randonNumber; ?>[i]).removeClass('active');
				}
			}
		if(valueTab){
				if(valueTab.search('playlist') != -1)
					scriptJquery('.sesbasic_view_type').hide();
				else
					scriptJquery('.sesbasic_view_type').show();
				document.getElementById("tabbed-widget_<?php echo $randonNumber; ?>").innerHTML = "<div class='clear sesbasic_loading_container'></div>";
			if(document.getElementById("view_more_<?php echo $randonNumber; ?>"))
				document.getElementById("view_more_<?php echo $randonNumber; ?>").style.display = 'none';
			if(document.getElementById('ses_pagging'))
				document.getElementById("ses_pagging").style.display = 'none';

			 defaultOpenTab = valueTab;
			 requestTab_<?php echo $randonNumber; ?> = scriptJquery.ajax({
        dataType: 'html',
				method: 'post',
				'url': en4.core.baseUrl+"widget/index/mod/sesvideo/name/<?php echo $this->widgetName; ?>/openTab/"+valueTab,
				'data': {
					format: 'html',
					page:  1,
					params :<?php echo json_encode($this->params); ?>,
					is_ajax : 1,
          searchFlag : searchFlag,
					identity : '<?php echo $randonNumber; ?>',
					type:scriptJquery('.selectView_<?php echo $randonNumber; ?>.active').attr('rel'),
				},
				success: function(responseHTML) {
					document.getElementById('tabbed-widget_<?php echo $randonNumber; ?>').innerHTML = '';
					scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').append(responseHTML);
					if(scriptJquery('.selectView_<?php echo $randonNumber; ?>.active').attr('rel') == 'grid') {
						scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').addClass('row');
					} else {
						scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').removeClass('row');
					}
					pinboardLayout_<?php echo $randonNumber ?>();
					if(document.getElementById('ses_pagging'))
						document.getElementById("ses_pagging").style.display = 'block';
					if(!scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?> li').length)
						scriptJquery('.sesbasic_view_type_options_<?php echo $randonNumber; ?>').hide();
					else
						scriptJquery('.sesbasic_view_type_options_<?php echo $randonNumber; ?>').show();
				}
    	});
		
    return false;
		}
	}
<?php } ?>
var params<?php echo $randonNumber; ?> = <?php echo json_encode($this->params); ?>;
var identity<?php echo $randonNumber; ?>  = '<?php echo $randonNumber; ?>';
 var page<?php echo $randonNumber; ?> = '<?php echo $this->page + 1; ?>';
 var searchParams<?php echo $randonNumber; ?> ;
	<?php if($this->loadOptionData != 'pagging'){ ?>
    en4.core.runonce.add(function() {
   viewMoreHide_<?php echo $randonNumber; ?>();
   });
  function viewMoreHide_<?php echo $randonNumber; ?>() {
    if (document.getElementById('view_more_<?php echo $randonNumber; ?>'))

      document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = "<?php echo ($this->paginator->count() == 0 ? 'none' : ($this->paginator->count() == $this->paginator->getCurrentPageNumber() ? 'none' : '' )) ?>";
  }
  function viewMore_<?php echo $randonNumber; ?> (){
    var openTab_<?php echo $randonNumber; ?> = '<?php echo $this->defaultOpenTab; ?>';
    
    if(document.getElementById('view_more_<?php echo $randonNumber; ?>'))
      document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = 'none';
    
    if(document.getElementById('loading_image_<?php echo $randonNumber; ?>'))
      document.getElementById('loading_image_<?php echo $randonNumber; ?>').style.display = '';

    requestViewMore_<?php echo $randonNumber; ?> = scriptJquery.ajax({
      dataType: 'html',
      method: 'post',
      'url': en4.core.baseUrl + "widget/index/mod/sesvideo/name/<?php echo $this->widgetName; ?>/openTab/" + openTab_<?php echo $randonNumber; ?>,
      'data': {
        format: 'html',
        page: page<?php echo $randonNumber; ?>,
				params :	params<?php echo $randonNumber; ?>,
				is_ajax : 1,
        searchFlag : searchFlag,
				searchParams:searchParams<?php echo $randonNumber; ?> ,
				identity : identity<?php echo $randonNumber; ?>,
				type:scriptJquery('.selectView_<?php echo $randonNumber; ?>.active').attr('rel'),
      },
      success: function(responseHTML) {
        if(document.getElementById('loading_images_browse_<?php echo $randonNumber; ?>'))
					scriptJquery('#loading_images_browse_<?php echo $randonNumber; ?>').remove();
				if(document.getElementById('loadingimgsesvideo-wrapper'))
          scriptJquery('#submit').html('Search');
        scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').append(responseHTML);
				if(scriptJquery('.selectView_<?php echo $randonNumber; ?>.active').attr('rel') == 'grid') {
					scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').addClass('row');
				} else {
					scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').removeClass('row');
				}
        if(document.getElementById('loading_image_<?php echo $randonNumber; ?>'))
        document.getElementById('loading_image_<?php echo $randonNumber; ?>').style.display = 'none';
        if(document.getElementById('view_more_<?php echo $randonNumber; ?>'))
        document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = "<?php echo ($this->paginator->count() == 0 ? 'none' : ($this->paginator->count() == $this->paginator->getCurrentPageNumber() + 1 ? 'none' : '' )) ?>";
				pinboardLayout_<?php echo $randonNumber ?>();
      }
    });
		
    return false;
  }
	<?php }else{ ?>
		function paggingNumber<?php echo $randonNumber; ?>(pageNum){
			 scriptJquery('.sesbasic_loading_cont_overlay').css('display','block');
			 var openTab_<?php echo $randonNumber; ?> = '<?php echo $this->defaultOpenTab; ?>';

				requestViewMore_<?php echo $randonNumber; ?> = (scriptJquery.ajax({
          dataType: 'html',
					method: 'post',
					'url': en4.core.baseUrl + "widget/index/mod/sesvideo/name/<?php echo $this->widgetName; ?>/openTab/" + openTab_<?php echo $randonNumber; ?>,
					'data': {
						format: 'html',
						page: pageNum,
						params :params<?php echo $randonNumber; ?> ,
						is_ajax : 1,
            searchFlag : searchFlag,
						searchParams:searchParams<?php echo $randonNumber; ?>  ,
						identity : identity<?php echo $randonNumber; ?>,
						type:scriptJquery('.selectView_<?php echo $randonNumber; ?>.active').attr('rel'),
					},
					success: function(responseHTML) {
					if(document.getElementById('loading_images_browse_<?php echo $randonNumber; ?>'))
					scriptJquery('#loading_images_browse_<?php echo $randonNumber; ?>').remove();
					if(document.getElementById('loadingimgsesvideo-wrapper'))
          scriptJquery('#submit').html('Search');
						scriptJquery('.sesbasic_loading_cont_overlay').css('display','none');
						document.getElementById('tabbed-widget_<?php echo $randonNumber; ?>').innerHTML =  responseHTML;
						if(scriptJquery('.selectView_<?php echo $randonNumber; ?>.active').attr('rel') == 'grid') {
							scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').addClass('row');
						} else {
							scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').removeClass('row');
						}
						pinboardLayout_<?php echo $randonNumber ?>();
					}
				}));
				
				return false;
		}
	<?php } ?>
</script>
<?php } ?>
<?php if(!$this->is_ajax) { ?>
<script type="application/javascript">
scriptJquery(document).on('click',function(){
	scriptJquery('.sesvideo_list_option_toggle').removeClass('open');
});
</script>
<?php } ?>
<?php if($this->is_ajax){ ?>
<script>
document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = "<?php echo ($this->paginator->count() == 0 ? 'none' : ($this->paginator->count() == $this->paginator->getCurrentPageNumber() ? 'none' : '' )) ?>";
</script>
<?php } ?>
<?php if(!empty($previousSubject)){ ?>
<?php //clear previous subject if set;
    Engine_Api::_()->core()->clearSubject();
    Engine_Api::_()->core()->setSubject($previousSubject);
}
?>
