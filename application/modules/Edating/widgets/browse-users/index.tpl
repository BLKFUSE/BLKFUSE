<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Edating
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2022-06-09 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
?>
<?php $allParams = $this->allParams; ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Edating/externals/styles/styles.css'); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/owl-carousel/jquery.js'); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/owl-carousel/owl.carousel.js'); ?>

<div class="edating_users_section">
  <div class="edating_users">
    <?php $i = 1; ?>
    <?php if($this->paginator->getTotalItemCount() > 0) { ?>
      <?php foreach($this->paginator as $key => $item): ?>
        <div id="edating_user_<?php echo $i; ?>" <?php if($i != 1) { ?> style="display:none;" <?php } ?>>
          <div class="edating_user_panel">
            <div class="edating_user_photo">
              <?php $getMainDatingPhoto = Engine_Api::_()->getDbTable('photos', 'edating')->getMainDatingPhoto($item->getIdentity()); ?>
              <div class="edating_item">
                <?php if($getMainDatingPhoto) { ?>
                  <span class="bg_item_photo bg_thumb_profile bg_item_photo_user " style="background-image:url(<?php echo $getMainDatingPhoto->getPhotoUrl("thumb.main"); ?>);"></span>
                <?php } else { ?>
                  <?php echo $this->itemBackgroundPhoto($item, 'thumb.main'); ?>
                <?php } ?>
              </div>
              <?php $getPhotos = Engine_Api::_()->getDbTable('photos', 'edating')->getPhotos($item->user_id, 5); ?>
              <?php if(is_countable($getPhotos) && engine_count($getPhotos) > 0) { ?>
                <?php foreach($getPhotos as $photo) { ?>
                  <div class="edating_item">
                    <span class="bg_item_photo bg_thumb_profile bg_item_photo_user " style="background-image:url(<?php echo $photo->getPhotoUrl("thumb.main"); ?>);"></span>
                  </div>
                <?php } ?>
              <?php } ?>
            </div>
            <div class="edating_profile_action_wrapper">
              <div class="meet_actions edating_profile_action">
                <?php if(is_array($allParams) && !empty($allParams['cancelbutton'])) { ?>
                <a href="javascript:void(0);" onclick="candISendMeetRequest('<?php echo $item->getIdentity()  ?>', 'disliked', '<?php echo $i; ?>', '<?php echo ($i + 1); ?>');" class="dislike"><i class="fas fa-times"></i></a>
                <?php } ?>
                <a href="javascript:void(0);" onclick="candISendMeetRequest('<?php echo $item->getIdentity()  ?>', 'liked', '<?php echo $i; ?>', '<?php echo ($i + 1); ?>');" class="like"><i class="fas fa-heart"></i></a>
              </div>
              <div class="edating_user_title">
                <?php echo $this->htmlLink($item->getHref(), $item->getTitle()); ?>
                <?php $getUserData = Engine_Api::_()->getDbTable('settings', 'edating')->getViewerRow($item->getIdentity()); ?>
                <?php if($getUserData) { ?>
                  <p class="user_des"><?php echo $getUserData->description; ?></p>
                <?php } ?>
              </div>
            </div>
            <?php if(is_array($allParams) && !empty($allParams['showinfo'])) { ?>
              <div class="user_info">
                <?php $fieldStructure = Engine_Api::_()->fields()->getFieldsStructurePartial($item); ?>
                <?php echo $this->fieldValueLoop($item, $fieldStructure); ?>
              </div>
            <?php } ?>
          </div>
        </div>
      <?php $i++; endforeach; ?>
      <div id="nocontent" style="display:none;">
        <div class="edating_user_panel">
          <div class="edating_user_photo">
            <div class="sesbasic_tip">
            <img src="application/modules/Edating/externals/images/online-dating.png">
              <span>
                <?php echo $this->translate("There are no member."); ?>
              </span>
            </div>
          </div>
        </div>
      </div>
    <?php } else { ?>
      <div>
        <div class="edating_user_panel">
          <div class="edating_user_photo">
            <div class="sesbasic_tip">
            <img src="application/modules/Edating/externals/images/online-dating.png">
              <span>
                <?php echo $this->translate("There are no member."); ?>
              </span>
            </div>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
</div>
<script type="text/javascript">
  var totalCount = '<?php echo $allParams["limit_data"]; ?>';
  function candISendMeetRequest(user_id, reaction, hidecount, showcount) {
    var URL = "<?php echo $this->url(array('action' => 'like'), 'edating_general', true); ?>";
    scriptJquery.ajax({
      method: 'post',
      url:  URL,
      'data': {
        format: 'json',
        user_id: user_id,
				reaction:reaction,
      },
      success: function(responseJson) {
        var response = jQuery.parseJSON(responseJson);
        if(response.status == true) {
          scriptJquery('#edating_user_'+hidecount).hide();
          scriptJquery('#edating_user_'+showcount).show();
          if(response.message == 'liked') {
          } else if(response.message == 'disliked') {
          }
        }
        if(totalCount == hidecount) {
          scriptJquery('#nocontent').show();
        }
      }
    });
  }

  sesowlJqueryObject('.edating_user_photo').owlCarousel({
    loop:false,
    margin:10,
    nav:true,
    items:1,
    <?php 
		$orientation = ($this->layout()->orientation == 'right-to-left' ? 'rtl' : 'ltr');
		if($orientation == 'rtl') { ?>
			rtl:true,
		<?php }?>
  });
  sesowlJqueryObject(".owl-prev").html('<i class="fa fa-angle-left"></i>');
  sesowlJqueryObject(".owl-next").html('<i class="fa fa-angle-right"></i>');
</script>
