<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesalbum
 * @package    Sesalbum
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: ses-imageviewer-advance.tpl 2015-06-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
<?php $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl')
                 .'application/modules/Sesalbum/externals/scripts/core.js'); ?> 
<?php $baseUrl = $this->layout()->staticBaseUrl; ?>
<?php 
				$previousURL = $this->previousPhoto;
        if($previousURL != ''){
        		$previousURL =$previousURL->getHref();
        	 if(isset($this->imagePrivateURL))
        		$previousImageURL = $this->imagePrivateURL;
           else
           	$previousImageURL = $this->previousPhoto->getPhotoUrl();
      ?>
<a class="pswp__button pswp__button--arrow--left" style="display:block" href="<?php echo $this->previousPhoto->getHref(); ?>" title="<?php echo $this->translate('Previous'); ?>" onclick="openLightBoxForSesPlugins('<?php echo $previousURL; ?>','<?php echo $previousImageURL ?>');return false;" id="nav-btn-prev"></a>
<?php }
		 $nextURL = $this->nextPhoto;
        if($nextURL != ''){
        		$nextURL = $nextURL->getHref();
        	 if(isset($this->imagePrivateURL))
        			$nextImageURL = $this->imagePrivateURL;
           else
           		$nextImageURL = $this->nextPhoto->getPhotoUrl();
       ?>
<a class="pswp__button pswp__button--arrow--right" style="display:block" href="<?php echo $this->nextPhoto->getHref(); ?>" title="<?php echo $this->translate('Next'); ?>" onclick="openLightBoxForSesPlugins('<?php echo $nextURL; ?>','<?php echo $nextImageURL; ?>');return false;" id="nav-btn-next"></a>
<?php } ?>
<div class="ses_pswp_information" id="ses_pswp_information">
  <div id="heightOfImageViewerContent">
    <div id="flexcroll" >
      <div class="ses_pswp_info" id="ses_pswp_info">
        <div class="ses_pswp_information_top sesbasic_clearfix">
       	 <?php $owner_id = isset($this->child_item->owner_id) ? $this->child_item->owner_id : $this->child_item->user_id; ?>
          <?php $albumUserDetails = Engine_Api::_()->user()->getUser($owner_id); ?>
          <div class="ses_pswp_author_photo"> <?php echo $this->htmlLink($albumUserDetails->getHref(), $this->itemPhoto($albumUserDetails, 'thumb.icon')); ?> </div>
          <div class="ses_pswp_author_info"> <span class="ses_pswp_author_name"> <?php echo $this->htmlLink($albumUserDetails->getHref(), $albumUserDetails->getTitle()); ?> </span> <span class="ses_pswp_item_posted_date sesbasic_text_light"> <?php echo date('F j',strtotime($this->child_item->creation_date)); ?> </span> </div>
        </div>
        <div class="ses_pswp_item_title" id="ses_title_get"> <?php echo $this->child_item->getTitle(); ?></div>
        <div class="ses_pswp_item_description" id="ses_title_description"><?php echo nl2br($this->child_item->getDescription()) ?></div>
        <div class="ses_media_lightbox_photo_tags sesbasic_text_light" id="media_tags_ses" style="display: none;"> <?php echo $this->translate('Tagged:') ?> </div>
        <?php if($this->canEdit){ ?>
        <div class="ses_pswp_item_edit_link"> <a id="editDetailsLink" href="javascript:void(0)" class="sesbasic_button"> <i class="fas fa-pencil-alt sesbasic_text_light"></i> <?php echo $this->translate('Edit Details') ?> </a> </div>
        <?php } ?>
      </div>
      <?php if($this->canEdit){ ?>
      <div class="ses_pswp_item_edit_form" id="editDetailsForm" style="display:none;">
      <form id="changePhotoDetails">
        <input  name="title" id="titleSes" type="text" placeholder="<?php echo $this->translate('Title'); ?>" />
        <textarea id="descriptionSes" name="description" value="" placeholder="<?php echo $this->translate('Description'); ?>"></textarea>
        <input type="hidden" id="photo_id_ses" name="item_id" value="<?php echo $this->child_item->{$this->childItemPri}; ?>" />
        <input type="hidden" id="photo_itemType_ses" name="item_type" value="<?php echo $this->child_item->getType(); ?>" />
        <button id="changeSesPhotoDetails"><?php echo $this->translate('Save Changes'); ?></button>
        <button id="cancelDetailsSes"><?php echo $this->translate('Cancel'); ?></button>
      </form>
    </div>
      <?php } ?>
      <div class="ses_pswp_comments clear"> 
      <?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedcomment')){ ?>
      <?php echo $this->action("list", "comment", "sesadvancedcomment", array("type" => $this->child_item->getType(), "id" => $this->child_item->getIdentity())); 
        }else{
          echo $this->action("list", "comment", "core", array("type" => $this->child_item->getType(), "id" => $this->child_item->getIdentity()));
        }
       ?> </div>
    </div>
  </div>
</div>
<div class="pswp__top-bar" style="display:none" id="imageViewerId"> 
	<a title="<?php echo $this->translate('Close (Esc)'); ?>" class="pswp__button pswp__button--close"></a> 
  <a title="<?php echo $this->translate('Toggle Fullscreen'); ?>" onclick="toogle()" href="javascript:;" class="pswp__button sesalbum_toogle_screen"></a>
  <a <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum.show.information', 1)) { ?> title="<?php echo $this->translate('Hide Info'); ?>" <?php } else { ?> title="<?php echo $this->translate('Show Info'); ?>" <?php } ?> id="pswp__button--info-show" class="pswp__button pswp__button--info pswp__button--info-show <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum.show.information', 1)) { ?> active <?php } ?>"></a> 
  <a title="Show All Photos" id="show-all-photo-container" class="pswp__button pswp__button--show-photos"></a>
  <a title="<?php echo $this->translate('Zoom in/out') ;?>" id="pswp__button--zoom" class="pswp__button pswp__button--zoom"></a>
  <div class="pswp__top-bar-action">
    <div class="pswp__top-bar-albumname"><?php echo $this->translate('In %1$s',$this->parent_item->__toString()); ?>
    </div>
    <div class="pswp__top-bar-tag">
      <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum.add.tags',1) == 1 && $this->canTag){ ?>
      <?php echo $this->htmlLink('javascript:void(0);', $this->translate('Add Tag'), array('onclick'=>'taggerInstanceSES.begin();'));
    } ?> </div>
    <div class="pswp__top-bar-share">
      <?php if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0 && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum.add.share',1) == 1){ ?>
      <a href="javascript:;" class='smoothbox' onclick="openURLinSmoothBox('<?php echo $this->url(array("action" => "share", "type" => $this->child_item->getType(), "photo_id" => $this->child_item->getIdentity(),"format" => "smoothbox"), 'sesalbum_general', true); ?>')"><?php echo $this->translate('Share'); ?></a>
      <?php } ?>
    </div>
    <div class="pswp__top-bar-more" id="pswp_top_bar_more"> 
    	<a href="javascript:;" class="optionOpenImageViewer" id="overlay-model-class" class=""><?php echo $this->translate("Options") ; ?> 
      	<i class="fa fa-angle-down" id="overlay-model-class-down"></i>
      </a>
      <div class="pswp__top-bar-more-tooltip" style="display:none">
        <?php if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0 && $this->canDelete){ ?>
        <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum.add.delete',1) == 1){ ?>
        	<a href="javascript:;" class='smoothbox' onclick="openURLinSmoothBox('<?php echo $this->url(array('controller' => 'photo', 'action' => 'delete-ses', 'photo_id' => $this->child_item->getIdentity(),'item_type'=>$this->child_item->getType(),'module'=>'sesalbum'),'default',true); ?>')"><?php echo $this->translate('Delete'); ?></a>
        <?php } ?>
        <?php } ?>
        <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum.add.report',1) == 1 && Engine_Api::_()->user()->getViewer()->getIdentity() != 0){ ?>
        	<a href="javascript:;" class='smoothbox' onclick="openURLinSmoothBox('<?php echo ($this->url(array('module' => 'core', 'controller' => 'report', 'action' => 'create', 'subject' => $this->child_item->getGuid(), 'format' => 'smoothbox'), 'default', true)); ?>')"><?php echo $this->translate('Report'); ?></a>
        <?php } ?>
        <?php if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0 && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum.add.profilepic',1) == 1){ ?>
        	<a href="javascript:;" class='smoothbox' onclick="openURLinSmoothBox('<?php echo $this->url(array('route' => 'user_extended', 'controller' => 'edit', 'action' => 'external-photo', 'photo' => $this->child_item->getGuid(), 'format' => 'smoothbox'),'user_extended',true); ?>')"><?php echo $this->translate('Make Profile Photo'); ?></a>
        <?php } ?>
        <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum.add.download',1) == 1 && isset($this->canDownload) && $this->canDownload == 1){ ?>
        	<a class="ses-album-photo-download" href="<?php echo $this->url(array('module' =>'sesalbum','controller' => 'photo', 'action' => 'download')).'?filePath='.urlencode($this->child_item->getPhotoUrl()) . '&file_id=' . $this->child_item->getIdentity()  ;?>"><?php echo $this->translate('Download'); ?></a>
        <?php } ?>
        	<a href="javascript:;" onclick="slideShow()"><?php echo $this->translate("Slideshow"); ?></a> </div>
    </div>
    <?php if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0){ ?>
    <div class="pswp__top-bar-msg pswp__top-bar-btns">
      <?php $LikeStatus = Engine_Api::_()->sesalbum()->getLikeStatusPhoto($this->child_item->getIdentity(),$this->child_item->getType()); ?>
      <a href="javascript:void(0);" id="sesLightboxLikeUnlikeButton" data-src="albumLike" class="sesbasic_icon_btn nocount sesbasic_icon_like_btn sesalbum_othermodule_like_button<?php echo $LikeStatus ? ' button_active' : '' ;  ?>"><i class="fa fa-thumbs-up"></i><span id="like_unlike_count"></span></a>
    </div>
    <?php } ?>
    <?php $settings = Engine_Api::_()->getApi('settings', 'core'); ?>
    <?php if($settings->getSetting('sesalbum.enablesessocialshare', 0)) { ?>
      <div class="pswp__top-bar-share-btns seslightbox_share_buttons">
        <?php echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $this->child_item, 'socialshare_enable_plusicon' => $settings->getSetting('sesalbum.enableplusicon', 1), 'socialshare_icon_limit' => $settings->getSetting('sesalbum.iconlimit', 3))); ?>
      </div>
    <?php } ?>
  </div>
  <?php if(isset($this->imagePrivateURL)){
          				$imageUrl = $this->imagePrivateURL;
                  $className = 'ses-private-image';
                 }else{
                 	$imageUrl = $this->child_item->getPhotoUrl();
                  $className = '';
                  }
          ?>
 <div id="media_photo_next_ses" style="display:none;">
        <?php echo $this->htmlImage($imageUrl, $this->child_item->getTitle(), array(
              'id' => 'gallery-img',
              'class'=>$className
            )); ?>
      </div>
 <div id="sesalbum_photo_id_data_src" data-src="<?php echo $this->child_item->photo_id; ?>" style="display:none;"></div>
 <div id="sesalbum_photo_id_data_org" data-src="<?php echo $this->child_item->getPhotoUrl('','','string'); ?>" style="display:none;"></div>
  <div class="pswp__preloader">
    <div class="pswp__preloader__icn">
      <div class="pswp__preloader__cut">
        <div class="pswp__preloader__donut"></div>
      </div>
    </div>
  </div>
</div>
<div id="content-from-element" style="display:none;">
<div class="ses_ml_overlay"></div>
<div class="ses_ml_more_popup sesbasic_bxs sesbasic_clearfix">
	<div class="ses_ml_more_popup_header">
  	<span><?php echo $this->translate("You've finished Photos") ?></span>
    <a href="javascript:;" class="morepopup_bkbtn"><i id="morepopup_bkbtn_btn" class="fa fa-repeat"></i></a>
    <a href="javascript:;" class="morepopup_closebtn" id="morepopup_closebtn"><i id="morepopup_closebtn_btn" class="fas fa-times"></i></a>
  </div>
<div id="content_last_element_lightbox"><div style="text-align:center"><?php echo $this->translate("Wait,there's more ..."); ?></div></div>
</div>
</div>
