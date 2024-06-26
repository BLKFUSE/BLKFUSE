<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Chat
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: level.tpl 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */
?>
<?php include APPLICATION_PATH .  '/application/modules/Chat/views/scripts/_adminHeader.tpl';?>
<script type="text/javascript">
  var fetchLevelSettings = function(level_id) {
    window.location.href = en4.core.baseUrl + 'admin/chat/settings/level/id/' + level_id;
  }
</script>
<div class='clear'>
  <div class='settings'>
    <?php echo $this->form->render($this); ?>
  </div>
</div>
