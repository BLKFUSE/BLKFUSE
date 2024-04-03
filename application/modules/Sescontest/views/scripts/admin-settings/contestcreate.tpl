<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: contestcreate.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
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

<?php $isPopup = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.enable.addcontestshortcut', 1);?>
<?php $isCategorySelection = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.category.selection', 1);?>
<?php $enableDescription = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.enable.description', 1);?>
<?php $enableEditorChoice = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.editor.media.type', 1);?>
<?php $enableGuidelines = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.guidelines', 1);?>
<script type="text/javascript">
 showCategoryIcon('<?php echo $isCategorySelection;?>');
 function showCategoryIcon(value) {
  if(value == 1)
    scriptJquery('#sescontest_category_icon-wrapper').show();
  else
    scriptJquery('#sescontest_category_icon-wrapper').hide();
 }
 showContestDescription('<?php echo $enableDescription;?>');
 function showContestDescription(value) {
  if(value == 1)
    scriptJquery('#sescontest_description_required-wrapper').show();
  else
    scriptJquery('#sescontest_description_required-wrapper').hide();
 }
 showDefaultEditor('<?php echo $enableEditorChoice;?>');
 function showDefaultEditor(value) {
  if(value == 1)
    scriptJquery('#sescontest_default_editor-wrapper').hide();
  else
    scriptJquery('#sescontest_default_editor-wrapper').show();
 } 
 showGuideEditor('<?php echo $enableGuidelines;?>');
 function showGuideEditor(value) {
  if(value == 1)
    scriptJquery('#sescontest_message_guidelines-wrapper').show();
  else
    scriptJquery('#sescontest_message_guidelines-wrapper').hide();
 }
</script>

