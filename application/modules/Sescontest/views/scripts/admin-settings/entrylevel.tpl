<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: entrylevel.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/dismiss_message.tpl';
 ?>
<div class='settings sesbasic_admin_form'>
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
    window.location.href = en4.core.baseUrl + 'admin/sescontest/settings/entrylevel/id/' + level_id;
    //alert(level_id);
  }
  scriptJquery(document).on('change','input[type=radio][name=canEntryMultvote]',function(){
    if (this.value == 1) {
      scriptJquery('#voteInterval-wrapper').show();
    }else{
      scriptJquery('#voteInterval-wrapper').hide();
    }
  });
  scriptJquery(document).ready(function() {
   var valueLocation = scriptJquery('input[name=canEntryMultvote]:checked').val();
    if(valueLocation == 1)
    scriptJquery('#voteInterval-wrapper').show();
    else
    scriptJquery('#voteInterval-wrapper').hide();
  });
</script>
