<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating	
 * @package    Sesdating
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2018-09-21 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php include APPLICATION_PATH .  '/application/modules/Sesdating/views/scripts/dismiss_message.tpl';?>

<script type="text/javascript">
    var modifications = [];
    window.onbeforeunload = function() {
        if( modifications.length > 0 ) {
            return '<?php echo $this->translate("If you leave the page now, your changes will be lost. Are you sure you want to continue?") ?>';
        }
    }
    var pushModification = function(type) {
        modifications.push(type);
    }
    var removeModification = function(type) {
        for (var i = modifications.length; i--;){
            if (modifications[i] === type) modifications.splice(i, 1);
        }
    }
    var changeThemeFile = function(file) {
        var url = '<?php echo $this->url() ?>?file=' + file;
        window.location.href = url;
    }
    var saveFileChanges = function() {
        var request = scriptJquery.ajax({
            url : '<?php echo $this->url(array('action' => 'save')) ?>',
            dataType : 'json',
            method : 'post',
            data : {
                theme_id : scriptJquery('#theme_id').val(),
                file : scriptJquery('#file').val(),
                body : scriptJquery('#body').val(),
                format : 'json'
            },
            success : function(responseJSON) {
                if( responseJSON.status ) {
                    removeModification('body');
                    scriptJquery('.admin_themes_header_revert').css('display', 'inline');
                    alert('<?php echo $this->string()->escapeJavascript($this->translate("Your changes have been saved!")) ?>');
                } else {
                    alert('<?php echo $this->string()->escapeJavascript($this->translate("An error has occurred. Changes could NOT be saved.")) ?>');
                }
            }
        });
    }
</script>
<h3><?php echo $this->translate("Add and Manage Custom CSS"); ?></h3>
<p>Below, you can add the custom CSS for this theme. We recommend you to add your CSS changes here instead of Theme.css file so that you do not lose your changes when you upgrade this theme.</p>
<br />
<div class="admin_theme_editor_wrapper">
  <form action="<?php echo $this->url(array('action' => 'save')) ?>" method="post">
    <div class="admin_theme_edit">
      <?php if( $this->writeable['sesdating'] ): ?>
        <div class="admin_theme_editor_edit_wrapper">
          <div class="admin_theme_editor">
            <?php echo $this->formTextarea('body', $this->activeFileContents, array('onkeypress' => 'pushModification("body")', 'spellcheck' => 'false')) ?>
          </div>
          <button class="activate_button" type="submit" onclick="saveFileChanges();return false;"><?php echo $this->translate("Save Changes") ?></button>
          <?php echo $this->formHidden('file', 'sesdating-custom.css', array()) ?>
          <?php echo $this->formHidden('theme_id', 'sesdating', array()) ?>
        </div>
      <?php else: ?>
        <div class="admin_theme_editor_edit_wrapper">
          <div class="tip">
            <span>
              <?php echo $this->translate('CORE_VIEWS_SCRIPTS_ADMINTHEMES_INDEX_STYLESHEETSPERMISSION', $this->activeTheme->name) ?>
            </span>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </form>
</div>
