<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: level-settings.tpl  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>

<?php include APPLICATION_PATH .  '/application/modules/Sesnews/views/scripts/dismiss_message.tpl';?>

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

<script type="text/javascript">
  var fetchLevelSettings = function(level_id) {
    window.location.href = en4.core.baseUrl + 'admin/sesnews/review/level-settings/id/' + level_id;
  }
</script>
