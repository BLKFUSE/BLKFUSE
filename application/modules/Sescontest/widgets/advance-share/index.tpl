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

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/styles/styles.css'); ?>

<div class="sescontest_sidebar_button">
 <a href="javascript:;" class="sesbasic_link_btn sesbasic_popup_slide_open sesbasic_bxs sesbasic_share_btn">
 	<i class="fas fa-share-alt"></i>
  <span><?php echo $this->translate("Share")?></span>
 </a>
</div>
<!-- Slide in -->
<div id="sesbasic_popup_slide" class="well" style="display:none">
  <div class="sesbasic_popup sesbasic_bxs">
    <div class="sesbasic_popup_title">
       <?php echo $this->translate("Share This ".ucfirst(str_replace(array('sescontest_',''),'',$this->subject()->getType()))); ?>
      	<span class="sesbasic_popup_slide_close sesbasic_text_light">
        <i class="fa fa-times"></i>
      </span>
    </div>
    <div class="sesbasic_popup_content">
      <div class="sesbasic_share_popup_content_row clear sesbasic_clearfix">
      	<div class="sesbasic_share_popup_buttons clear">
        <?php if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0){ ?>
          <?php if(Engine_Api::_()->authorization()->getPermission(Engine_Api::_()->user()->getViewer()->level_id, 'messages', 'create') && is_array($this->allowAdvShareOptions) && engine_in_array('privateMessage',$this->allowAdvShareOptions)){ ?>
            <a href="javascript:void(0)" class="sesbasic_button" onClick="openSmoothBoxInUrl('<?php echo $this->url(array('module'=> 'sesbasic', 'controller' => 'index', 'action' => 'message','item_id' => $this->subject()->getIdentity(), 'type'=>$this->subject()->getType()),'default',true); ?>')"> <?php echo $this->translate("Private Message"); ?></a>
            <?php } ?>
             <?php if(is_array($this->allowAdvShareOptions) && engine_in_array('siteShare',$this->allowAdvShareOptions)){ ?>
            <a href="javascript:void(0)" class="sesbasic_button" onClick="openSmoothBoxInUrl('<?php echo $this->url(array('module'=> 'sesbasic', 'controller' =>'index','action' => 'share','type' => $this->subject()->getType(),'id' => $this->subject()->getIdentity(),'format' => 'smoothbox'),'default',true); ?>');return false;"> <?php echo $this->translate("Share on Site"); ?></a>
            <?php } ?>
             <?php if(is_array($this->allowAdvShareOptions) && engine_in_array('quickShare',$this->allowAdvShareOptions)){ ?>
            <a href="javascript:void(0)" class="sesbasic_button" onClick="sesAjaxQuickShare('<?php echo $this->url(array('module'=> 'sesbasic', 'controller' =>'index','action' => 'share','type' => $this->subject()->getType(),'id' => $this->subject()->getIdentity()),'default',true); ?>');return false;"> <?php echo $this->translate("Quick Share on Site"); ?></a>
          <?php } ?>
        <?php } ?>
        <?php if(engine_in_array('tellAFriend',$this->allowAdvShareOptions)){ ?>
            <a href="javascript:void(0)" class="sesbasic_button" onClick="openSmoothBoxInUrl('<?php echo $this->url(array('module'=> 'sesbasic', 'controller' =>'index','action' => 'tellafriend','type' => $this->subject()->getType(),'id' => $this->subject()->getIdentity()),'default',true); ?>');return false;"> <?php echo $this->translate("Tell a friend"); ?></a>
          <?php } ?>
        </div>
      </div>
      <div class="sesbasic_share_popup_content_row">
      	<div class="sesbasic_share_itme_preview sesbasic_clearfix">
        	<div class="sesbasic_share_itme_preview_img">
          	<img src="<?php echo $this->subject()->getPhotoUrl();?>" />
          </div>
          <div class="sesbasic_share_itme_preview_info">
          	<div class="sesbasic_share_itme_preview_title">
            	<a href="<?php echo $this->subject()->getHref();?>"><?php echo $this->subject()->title;?></a>
            </div>
            <div class="sesbasic_share_itme_preview_des">
             <?php if(strlen($this->subject()->description) > 200){ 
                  $description = mb_substr($this->subject()->description,0,200).'...';
                  echo nl2br(strip_tags($description));
                 }else{ ?>
              <?php  echo nl2br(strip_tags($this->subject()->description));?>
              <?php } ?>
            </div>	
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/jquery-1.8.2.min.js'); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/jquery.popupoverlay.js'); ?>
<script type="text/javascript">
<?php if(isset($_SESSION['newContest']) && $_SESSION['newContest']){ ?>
	var autoOpen = true;
<?php }else{ ?>
	var autoOpen = false;
<?php } ?>
jquery1_8_2SesObject(document).ready(function () {
	jquery1_8_2SesObject('#sesbasic_popup_slide').popup({
		'autoopen':autoOpen,
	});
<?php unset($_SESSION['newContest']); ?>
});
</script>
