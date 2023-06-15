<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/pinboardcomment.js');?>
<?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/dismiss_message.tpl';?>
<div class="sesbasic_search_result">
	<?php echo $this->htmlLink($this->url(array('module' => 'sescontestpackage', 'controller' => 'package','action'=>'index')), $this->translate("Back to Manage Package"), array('class' => 'buttonlink sesbasic_icon_back')); ?>
</div><br />
<div class="sesbasic_admin_form settings">
  <?php echo $this->form->render($this) ?>
</div>
<script type="application/javascript">
  function showRenewData(value){
    if(value == 1)
      document.getElementById('renew_link_days-wrapper').style.display = 'block';
    else
      document.getElementById('renew_link_days-wrapper').style.display = 'none';
  }
  function checkOneTime(value){
    if(value == 'forever'){
      document.getElementById('is_renew_link-wrapper').style.display = 'block';
      document.getElementById('renew_link_days-wrapper').style.display = 'block';
    }else{
      document.getElementById('is_renew_link-wrapper').style.display = 'none';
      document.getElementById('renew_link_days-wrapper').style.display = 'none';
    }
  }
  document.getElementById("recurrence-select").onclick = function(e){
    var value = this.value;
    checkOneTime(value);
    var value = document.getElementById('is_renew_link').value;
    showRenewData(value);
  };
  scriptJquery(document).ready(function() {
    var value = document.getElementById('recurrence-select').value;
    checkOneTime(value);
    var value = document.getElementById('is_renew_link').value;
    showRenewData(value);
  });
</script>


<script type="text/javascript">
  var fetchLevelSettings = function(level_id) {
    window.location.href = en4.core.baseUrl + 'admin/sescontest/settings/level/id/' + level_id;
    //alert(level_id);
  }
  scriptJquery(document).on('change','input[type=radio][name=can_add_jury]',function(){
    if (this.value == 1) {
      scriptJquery('#jury_member_count-wrapper').show();
    }else{
      scriptJquery('#jury_member_count-wrapper').hide();
    }
  });
  scriptJquery(document).on('change','input[type=radio][name=contest_choose_style]',function(){
    if (this.value == 1) {
      scriptJquery('#contest_chooselayout-wrapper').show();
      scriptJquery('#contest_style_type-wrapper').hide();
    }else{
      scriptJquery('#contest_style_type-wrapper').show();
      scriptJquery('#contest_chooselayout-wrapper').hide();
    }
  });
  scriptJquery(document).ready(function() {
    if(scriptJquery('#can_add_jury')) {
      var valueStyle = scriptJquery('input[name=can_add_jury]:checked').val();
      if(valueStyle == 1) {
        scriptJquery('#jury_member_count-wrapper').show();
      }
      else {
        scriptJquery('#jury_member_count-wrapper').hide();
      }
    }
   var valueStyle = scriptJquery('input[name=contest_choose_style]:checked').val();
    if(valueStyle == 1) {
      scriptJquery('#contest_chooselayout-wrapper').show();
      scriptJquery('#contest_style_type-wrapper').hide();
    }
    else {
      scriptJquery('#contest_style_type-wrapper').show();
      scriptJquery('#contest_chooselayout-wrapper').hide();
   }
  });
</script>
