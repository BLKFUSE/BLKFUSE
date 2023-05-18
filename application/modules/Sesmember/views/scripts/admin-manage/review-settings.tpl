<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: review-settings.tpl 2016-05-25 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php include APPLICATION_PATH .  '/application/modules/Sesmember/views/scripts/dismiss_message.tpl';?>
<div class='sesbasic-form sesbasic-categories-form'>
  <div>
    <?php if(is_countable($this->subNavigation) && engine_count($this->subNavigation) ): ?>
      <div class='sesbasic-admin-sub-tabs'>
      <?php echo $this->navigation()->menu()->setContainer($this->subNavigation)->render();?>
      </div>
    <?php endif; ?>
    <div class='sesbasic-form-cont'>
      <div class='clear'>
	<div class='settings sesbasic_admin_form'>
	  <?php echo $this->form->render($this); ?>
	</div>
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
    showEditor("<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.review.summary', 1) ?>");
    allowReview("<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.allow.review', 1) ?>");
    if(document.getElementById('sesmember_review_votes'))
      showReviewVotes(document.getElementById('sesmember_review_votes').value);
  });
  function showReviewVotes(value){
    if(value == 1){
      document.getElementById('sesmember_review_first-wrapper').style.display = 'block';		
      document.getElementById('sesmember_review_second-wrapper').style.display = 'block';		
      document.getElementById('sesmember_review_third-wrapper').style.display = 'block';
    } else{
      document.getElementById('sesmember_review_first-wrapper').style.display = 'none';		
      document.getElementById('sesmember_review_second-wrapper').style.display = 'none';		
      document.getElementById('sesmember_review_third-wrapper').style.display = 'none';
    }
  } 
  function showEditor(value) {
    if(value == 1) {
      if(document.getElementById('sesmember_show_tinymce-wrapper'))
      document.getElementById('sesmember_show_tinymce-wrapper').style.display = 'block';
    } else {
      if(document.getElementById('sesmember_show_tinymce-wrapper'))
      document.getElementById('sesmember_show_tinymce-wrapper').style.display = 'none';
    }
  }
  function allowReview(value) {
    if(value == 1) {
      if(document.getElementById('sesmember_allow_owner-wrapper'))
      document.getElementById('sesmember_allow_owner-wrapper').style.display = 'block';
      if(document.getElementById('sesmember_show_pros-wrapper'))
      document.getElementById('sesmember_show_pros-wrapper').style.display = 'block';
      if(document.getElementById('sesmember_show_cons-wrapper'))
      document.getElementById('sesmember_show_cons-wrapper').style.display = 'block';
      if(document.getElementById('sesmember_review_title-wrapper'))
      document.getElementById('sesmember_review_title-wrapper').style.display = 'block';
      if(document.getElementById('sesmember_review_summary-wrapper'))
      document.getElementById('sesmember_review_summary-wrapper').style.display = 'block';
      if(document.getElementById('sesmember_show_tinymce-wrapper'))
      document.getElementById('sesmember_show_tinymce-wrapper').style.display = 'block';
      if(document.getElementById('sesmember_show_recommended-wrapper'))
      document.getElementById('sesmember_show_recommended-wrapper').style.display = 'block';
      if(document.getElementById('sesmember_allow_share-wrapper'))
      document.getElementById('sesmember_allow_share-wrapper').style.display = 'block';
      if(document.getElementById('sesmember_show_report-wrapper'))
      document.getElementById('sesmember_show_report-wrapper').style.display = 'block';
      showEditor("<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.review.summary', 1) ?>");
      if(document.getElementById('sesmember_rating_stars_one-wrapper'))
      document.getElementById('sesmember_rating_stars_one-wrapper').style.display = 'block';
      if(document.getElementById('sesmember_rating_stars_two-wrapper'))
      document.getElementById('sesmember_rating_stars_two-wrapper').style.display = 'block';
      if(document.getElementById('sesmember_rating_stars_three-wrapper'))
      document.getElementById('sesmember_rating_stars_three-wrapper').style.display = 'block';
      if(document.getElementById('sesmember_rating_stars_four-wrapper'))
      document.getElementById('sesmember_rating_stars_four-wrapper').style.display = 'block';
      if(document.getElementById('sesmember_rating_stars_five-wrapper'))
      document.getElementById('sesmember_rating_stars_five-wrapper').style.display = 'block';
      
    } else {
      if(document.getElementById('sesmember_allow_owner-wrapper'))
      document.getElementById('sesmember_allow_owner-wrapper').style.display = 'none';
      if(document.getElementById('sesmember_show_pros-wrapper'))
      document.getElementById('sesmember_show_pros-wrapper').style.display = 'none';
      if(document.getElementById('sesmember_show_cons-wrapper'))
      document.getElementById('sesmember_show_cons-wrapper').style.display = 'none';
      if(document.getElementById('sesmember_review_title-wrapper'))
      document.getElementById('sesmember_review_title-wrapper').style.display = 'none';
      if(document.getElementById('sesmember_review_summary-wrapper'))
      document.getElementById('sesmember_review_summary-wrapper').style.display = 'none';
      if(document.getElementById('sesmember_show_tinymce-wrapper'))
      document.getElementById('sesmember_show_tinymce-wrapper').style.display = 'none';
      if(document.getElementById('sesmember_show_recommended-wrapper'))
      document.getElementById('sesmember_show_recommended-wrapper').style.display = 'none';
      if(document.getElementById('sesmember_allow_share-wrapper'))
      document.getElementById('sesmember_allow_share-wrapper').style.display = 'none';
      if(document.getElementById('sesmember_show_report-wrapper'))
      document.getElementById('sesmember_show_report-wrapper').style.display = 'none';
      showEditor(0);
      if(document.getElementById('sesmember_rating_stars_one-wrapper'))
      document.getElementById('sesmember_rating_stars_one-wrapper').style.display = 'none';
      if(document.getElementById('sesmember_rating_stars_two-wrapper'))
      document.getElementById('sesmember_rating_stars_two-wrapper').style.display = 'none';
      if(document.getElementById('sesmember_rating_stars_three-wrapper'))
      document.getElementById('sesmember_rating_stars_three-wrapper').style.display = 'none';
      if(document.getElementById('sesmember_rating_stars_four-wrapper'))
      document.getElementById('sesmember_rating_stars_four-wrapper').style.display = 'none';
      if(document.getElementById('sesmember_rating_stars_five-wrapper'))
      document.getElementById('sesmember_rating_stars_five-wrapper').style.display = 'none';
    }
  }
</script>
