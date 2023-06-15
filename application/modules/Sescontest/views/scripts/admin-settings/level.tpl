<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: level.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<style>
#isPackageCont-label{display:none;}
#isPackageCont-element .description{
  font-weight: bold;
  max-width: 100%;
}
#isPackageCont{display:none;} 
</style>
<?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/dismiss_message.tpl';?>
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
    window.location.href = en4.core.baseUrl + 'admin/sescontest/settings/level/id/' + level_id;
    //alert(level_id);
  }
  scriptJquery(document).on('change','input[type=radio][name=auth_contstyle]',function(){
    if (this.value == 1) {
      scriptJquery('#chooselayout-wrapper').show();
      scriptJquery('#style-wrapper').hide();
    }else{
      scriptJquery('#style-wrapper').show();
      scriptJquery('#chooselayout-wrapper').hide();
    }
  });
  scriptJquery(document).on('change','input[type=radio][name=can_add_jury]',function(){
    if (this.value == 1) {
      scriptJquery('#juryMemberCount-wrapper').show();
    }else{
      scriptJquery('#juryMemberCount-wrapper').hide();
    }
  });
  scriptJquery(document).ready(function() {
    if(scriptJquery('#can_add_jury')) {
      var valueStyle = scriptJquery('input[name=can_add_jury]:checked').val();
      if(valueStyle == 1) {
        scriptJquery('#juryMemberCount-wrapper').show();
      }
      else {
        scriptJquery('#juryMemberCount-wrapper').hide();
      }
    }
    var valueStyle = scriptJquery('input[name=auth_contstyle]:checked').val();
    if(valueStyle == 1) {
      scriptJquery('#chooselayout-wrapper').show();
      scriptJquery('#style-wrapper').hide();
    }
    else {
      scriptJquery('#style-wrapper').show();
      scriptJquery('#chooselayout-wrapper').hide();
    }
    var x = document.getElementsByClassName("contest_package");
    var i;
    for (i = 0; i < x.length; i++) {
      var elementId = x[i].id.split("-");
      scriptJquery('#'+elementId[0]+'-wrapper').hide();
    } 
  });
</script>
