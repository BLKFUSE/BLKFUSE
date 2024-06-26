<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesandroidapp
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2018-08-14 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php $settings = Engine_Api::_()->getApi('settings', 'core');
$this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesapi/externals/scripts/jscolor/jscolor.js');
?>

<script>
hashSign = '#';
isColorFieldRequired = false;
</script>
<?php include APPLICATION_PATH .  '/application/modules/Sesandroidapp/views/scripts/dismiss_message.tpl';?>
<h2 class="page_heading">
  <?php echo $this->translate("Native Android Mobile App") ?>
</h2>
<?php if(is_countable($this->navigation) && engine_count($this->navigation) ): ?>
  <div class='tabs'>
    <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
  </div>
<?php endif; ?>
<div class='clear'>
  <div class='settings sesandroidapp_themes_form' style="position:relative;">
    <?php echo $this->form->render($this); ?>
    <div class="sesandroidapp_loading_cont_overlay" style="display:none"></div>
  </div>
</div>
<script>
  scriptJquery(document).ready(function(){
    changeThemeColor(scriptJquery("input[name='theme_color']:checked").val());  
  })
  scriptJquery(document).on('click','.seschangeThemeName',function(e){
    e.preventDefault();
     var id = scriptJquery('#custom_theme_color').val();
     var href = scriptJquery(this).attr('href')+'/customtheme_id/'+id;
     Smoothbox.open(href);
      parent.Smoothbox.close;
      return false;
  });
  scriptJquery(document).on('click','#delete_custom_themes',function(e){
    e.preventDefault();
     var id = scriptJquery('#custom_theme_color').val();
     var href = scriptJquery(this).attr('href')+'/customtheme_id/'+id;
     Smoothbox.open(href);
      parent.Smoothbox.close;
      return false;
  })
  
  function changeCustomThemeColor(value) {
      changeThemeColor(scriptJquery("input[name='theme_color']:checked").val());
      if(scriptJquery("input[name='theme_color']:checked").val() == 5)
        scriptJquery('.sesandroidapp_loading_cont_overlay').show();
      var URL = en4.core.baseUrl+'sesandroidapp/admin-theme/getcustomthemecolors/';
      (scriptJquery.ajax({
          method: 'post',
          url: URL ,
          dataType : 'html',
          data: {
            format: 'html',
            customtheme_id: value,
          },
          success: function(responseHTML) {
          var customthevalyearray = scriptJquery.parseJSON(responseHTML);
          
          for(i=0;i<customthevalyearray.length;i++){
            var splitValue = customthevalyearray[i].split('||');
            scriptJquery('#'+splitValue[0]).val(splitValue[1]);
            if(scriptJquery('#'+splitValue[0]).hasClass('SEScolor')){
              if(splitValue[1] == ""){
                splitValue[1] = "#FFFFFF";  
              }
             try{
              document.getElementById(splitValue[0]).color.fromString('#'+splitValue[1]);
             }catch(err) {
               document.getElementById(splitValue[0]).value = "#FFFFFF";
             }
            }
          }
          //generate string
          scriptJquery('.sesandroidapp_loading_cont_overlay').hide();
          /*var index = 0;
          var string = "";
          scriptJquery('.global_form input').each(function(){
            if(index > 6)
            {
              var value = scriptJquery(this).val();
              string = string + "'"+value+"',";
              //console.log(scriptJquery(this).val(),index);
            }
            index++;
          });
          console.log(string);*/
        }
      }));
  }
	function changeThemeColor(value) {
    var customthemeValue = scriptJquery('#custom_theme_color').val();
    if(customthemeValue > 6){
      scriptJquery('#edit_custom_themes').show();
      scriptJquery('#delete_custom_themes').show();  
    }else{
      scriptJquery('#edit_custom_themes').hide();
      scriptJquery('#delete_custom_themes').hide();    
    }
     if(value != 5){
      scriptJquery('.sesandroidapp_bundle').prev().hide();
      scriptJquery('.sesandroidapp_bundle').hide();
      scriptJquery('#custom_theme_color-wrapper, .sesandroidapp_styling_buttons').hide();
      scriptJquery('#submit').css('display','none');
     }else{
      scriptJquery('.sesandroidapp_bundle').prev().show();
      scriptJquery('.sesandroidapp_bundle').show();
      scriptJquery('#custom_theme_color-wrapper, .sesandroidapp_styling_buttons').show();
      scriptJquery('#submit').css('display','inline-block');  
     }    
  }
  
</script>
