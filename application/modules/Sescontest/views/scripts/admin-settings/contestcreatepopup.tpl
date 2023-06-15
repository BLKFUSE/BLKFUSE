<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: contestcreatepopup.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>

<?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/dismiss_message.tpl';?>
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
<?php $enableDescription = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.enable.description', 1);?>
<?php $enableTag = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.contesttags', 1);?>
<?php $enableViewPrivacy = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.show.viewprivacypopup', 1);?>
<?php $enableCommentPrivacy = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.show.commentprivacypopup', 1);?>
<script type="text/javascript">
  if('<?php echo $enableDescription;?>' == 1)
    scriptJquery('#sescontest_show_description_create_popup-wrapper').show();
  else
    scriptJquery('#sescontest_show_description_create_popup-wrapper').hide();
  if('<?php echo $enableTag;?>' == 1)
    scriptJquery('#sescontest_show_tag_create_popup-wrapper').show();
  else
    scriptJquery('#sescontest_show_tag_create_popup-wrapper').hide();
  showViewPrivacy('<?php echo $enableViewPrivacy;?>');
  function showViewPrivacy(value) {
    if(value == 1)
    scriptJquery('#sescontest_default_viewprivacypopup-wrapper').hide();
    else
    scriptJquery('#sescontest_default_viewprivacypopup-wrapper').show();
  }
  showCommentPrivacy('<?php echo $enableCommentPrivacy;?>');
  function showCommentPrivacy(value) {
    if(value == 1)
    scriptJquery('#sescontest_default_commentprivacypopup-wrapper').hide();
    else
    scriptJquery('#sescontest_default_commentprivacypopup-wrapper').show();
  }
</script>
