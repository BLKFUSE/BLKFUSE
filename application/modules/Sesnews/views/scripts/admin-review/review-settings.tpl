<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: review-settings.tpl  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php include APPLICATION_PATH .  '/application/modules/Sesnews/views/scripts/dismiss_message.tpl';?>
<div class='clear sesbasic-form'>
  <div>
    <?php if( engine_count($this->subnavigation) ): ?>
      <div class='sesbasic-admin-sub-tabs'>
        <?php echo $this->navigation()->menu()->setContainer($this->subnavigation)->render(); ?>
      </div>
    <?php endif; ?>
    <div class="sesbasic-form-cont">
      <div class='settings sesbasic_admin_form'>
        <?php echo $this->form->render($this); ?>
      </div>
    </div>
  </div>
</div>
<style type="text/css">
.sesbasic_back_icon{
  background-image: url(<?php echo $this->layout()->staticBaseUrl ?>application/modules/Core/externals/images/back.png);
}
</style>
<script>  
  scriptJquery(document).ready(function() {
    showEditor("<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.review.summary', 1) ?>");
	allowReview("<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.allow.review', 1) ?>");
  });
  
function showEditor(value) {
  if(value == 1) {
    if(document.getElementById('sesnews_review_show_tinymce-wrapper'))
      document.getElementById('sesnews_review_show_tinymce-wrapper').style.display = 'block';
  } else {
    if(document.getElementById('sesnews_review_show_tinymce-wrapper'))
    document.getElementById('sesnews_review_show_tinymce-wrapper').style.display = 'none';
  }
  
}
function allowReview(value) {
  if(value == 1) {
    if(document.getElementById('sesnews_review_allow_owner-wrapper'))
      document.getElementById('sesnews_review_allow_owner-wrapper').style.display = 'block';
	if(document.getElementById('sesnews_review_show_pros-wrapper'))
      document.getElementById('sesnews_review_show_pros-wrapper').style.display = 'block';
	if(document.getElementById('sesnews_review_show_cons-wrapper'))
      document.getElementById('sesnews_review_show_cons-wrapper').style.display = 'block';
	if(document.getElementById('sesnews_review_review_title-wrapper'))
      document.getElementById('sesnews_review_review_title-wrapper').style.display = 'block';
	if(document.getElementById('sesnews_review_review_summary-wrapper'))
      document.getElementById('sesnews_review_review_summary-wrapper').style.display = 'block';
	if(document.getElementById('sesnews_review_show_tinymce-wrapper'))
      document.getElementById('sesnews_review_show_tinymce-wrapper').style.display = 'block';
	if(document.getElementById('sesnews_review_show_recommended-wrapper'))
      document.getElementById('sesnews_review_show_recommended-wrapper').style.display = 'block';
	if(document.getElementById('sesnews_review_allow_share-wrapper'))
      document.getElementById('sesnews_review_allow_share-wrapper').style.display = 'block';
	if(document.getElementById('sesnews_review_show_report-wrapper'))
      document.getElementById('sesnews_review_show_report-wrapper').style.display = 'block';
	showEditor("<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.review.summary', 1) ?>");
  } else {
    if(document.getElementById('sesnews_review_allow_owner-wrapper'))
      document.getElementById('sesnews_review_allow_owner-wrapper').style.display = 'none';
	if(document.getElementById('sesnews_review_show_pros-wrapper'))
      document.getElementById('sesnews_review_show_pros-wrapper').style.display = 'none';
	if(document.getElementById('sesnews_review_show_cons-wrapper'))
      document.getElementById('sesnews_review_show_cons-wrapper').style.display = 'none';
	if(document.getElementById('sesnews_review_review_title-wrapper'))
      document.getElementById('sesnews_review_review_title-wrapper').style.display = 'none';
	if(document.getElementById('sesnews_review_review_summary-wrapper'))
      document.getElementById('sesnews_review_review_summary-wrapper').style.display = 'none';
	if(document.getElementById('sesnews_review_show_tinymce-wrapper'))
      document.getElementById('sesnews_review_show_tinymce-wrapper').style.display = 'none';
	if(document.getElementById('sesnews_review_show_recommended-wrapper'))
      document.getElementById('sesnews_review_show_recommended-wrapper').style.display = 'none';
	if(document.getElementById('sesnews_review_allow_share-wrapper'))
      document.getElementById('sesnews_review_allow_share-wrapper').style.display = 'none';
	if(document.getElementById('sesnews_review_show_report-wrapper'))
      document.getElementById('sesnews_review_show_report-wrapper').style.display = 'none';
	showEditor(0);
  }
}
</script>
