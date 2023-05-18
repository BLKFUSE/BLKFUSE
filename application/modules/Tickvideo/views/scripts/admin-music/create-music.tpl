<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Tickvideo
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: create-music.tpl 2020-11-03 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>

<?php include APPLICATION_PATH .  '/application/modules/Tickvideo/views/scripts/dismiss_message.tpl';?>


<div class="sesbasic_search_reasult">
    <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'tickvideo', 'controller' => 'music', 'action' => 'manage','id'=>$this->category_id), $this->translate("Back to Manage Musics") , array('class'=>'sesbasic_icon_back buttonlink')); ?>
</div>
<div class='clear'>
    <div class='settings sesbasic_admin_form'>
        <?php echo $this->form->render($this); ?>
    </div>
</div>

<script type="application/javascript">
  scriptJquery(document).ready(function() {
    gradient(scriptJquery('#enable_gradient').val());
      video_buton(scriptJquery ("input[name='video_video_url']:checked").val());
    video_buton_check(scriptJquery('#enable_watch_video_button').val());
    double_slide(scriptJquery('#enable_double_slide').val());
    cta_button_one(scriptJquery('#enable_cta_Button_1').val());
    cta_button_two(scriptJquery('#enable_cta_button_2').val());
      overlay(scriptJquery ('#enable_overlay').val());
  });
    
function double_slide(value){
        if(value == 1){
			scriptJquery('div[id^="dbslide_"]').show();
		}
        else{
			scriptJquery('div[id^="dbslide_"]').hide();
			scriptJquery('div[id^="dummy_6"]').hide();
			scriptJquery('div[id^="remove_dbslide_image"]').hide();
		}	
        scriptJquery('#double_slide-wrapper').show();
}
function gradient(value){
    if(value == 1)
      scriptJquery('div[id^="gradient_background_color"]').show();
    else
      scriptJquery('div[id^="gradient_background_color"]').hide();
}
    function cta_button_one(value){
      if(value == 1)
        scriptJquery('div[id^="cta1_"]').show();
      else
        scriptJquery('div[id^="cta1_"]').hide();
      scriptJquery('#cta_button_one-wrapper').show();
    }

function cta_button_two(value){
        if(value == 1)
                scriptJquery('div[id^="cta2_"]').show();
        else
                scriptJquery('div[id^="cta2_"]').hide();
        scriptJquery('#cta_button_two-wrapper').show();
}

function video_buton(value){
        if(scriptJquery ("input[name='video_video_url']:checked").val()  == 4){
            scriptJquery('div[id^="video_upload"]').show();
            scriptJquery('div[id^="video_video_file_url"]').hide();
        }
        else{
             scriptJquery('div[id^="video_upload"]').hide();
            scriptJquery('div[id^="video_video_file_url"]').show();
        }
}
function video_buton_check(value){
    if(value == 1){
        scriptJquery('div[id^="video_"]').show();
        video_buton(scriptJquery ("input[name='video_video_url']:checked").val()  == 4);
    }
    else{
        scriptJquery('div[id^="video_"]').hide();
        scriptJquery('#dummy_7-wrapper').hide();
        scriptJquery('#remove_video-element').hide();


    }
        scriptJquery('#video_buton_check-wrapper').show();
}
  function overlay(value){
      if(value == 1)
          scriptJquery('div[id^="slide_"]').show();
      else
          scriptJquery('div[id^="slide_"]').hide();
      scriptJquery('#overlay-wrapper').show();
  }


</script>
<style type="text/css">
    .settings div.form-label label.required:after{
        content:" *";
        color:#f00;
    }
</style>
