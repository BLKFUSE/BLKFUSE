<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescomadbanr
 * @package    Sescomadbanr
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2019-03-08 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php include APPLICATION_PATH .  '/application/modules/Sescommunityads/views/scripts/dismiss_message.tpl';?>
<div class='sesbasic-form sesbasic-categories-form'>
  <div>
    <?php if(is_countable($this->navigation) && engine_count($this->navigation) ): ?>
      <div class='sesbasic-admin-sub-tabs'>
        <?php echo $this->navigation()->menu()->setContainer($this->subNavigation)->render(); ?>
      </div>
    <?php endif; ?>
    <div class='clear sesbasic-form-cont'>
      <div class='settings sesbasic_admin_form'>
        <?php echo $this->form->render($this); ?>
      </div>
      <div class="sesbasic_waiting_msg_box" style="display:none;">
        <div class="sesbasic_waiting_msg_box_cont">
          <?php echo $this->translate("Please wait.. It might take some time to activate plugin."); ?>
          <i></i>
        </div>
        
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  <?php if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sescomadbanr.pluginactivated',0)){ ?>
    scriptJquery('.global_form').submit(function(e){
      scriptJquery('.sesbasic_waiting_msg_box').show();
    });
  <?php } ?>
</script>
