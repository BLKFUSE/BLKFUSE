<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: create.tpl 2017-01-12 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>

<?php
  
  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesadvancedactivity/externals/scripts/mo.min.js');
  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesadvancedactivity/externals/scripts/animation.js');
?>
<!--[if IE]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<div class='sesbasic_popup_form settings'>
  <?php echo $this->form->render($this); ?>
</div>
<div></div>
<style>
#stringhover1-wrapper{height:1px;padding:0px;}
.sesadvancedactivity-special-link {
	position: relative;
	-webkit-transition: color 0.2s;
	transition: color 0.2s;
}
#stringhover1-wrapper, #stringhover1-element{
  overflow:visible;
}
</style>
<script type="application/javascript">
  function showanimation(obj){
    var value = obj.value;
    if(value == "")
      return;
    scriptJquery('#stringhover').removeAttr('class');
    scriptJquery('#stringhover').addClass(value);
    initSesadvAnimation();
    scriptJquery('#stringhover1-wrapper').show();
    scriptJquery('.'+value).trigger('mouseenter');
    setTimeout(function () {
        scriptJquery("#stringhover").trigger('mouseleave');
    }, 800);
  }
  scriptJquery('#stringhover').on('mouseleave',function(){
      scriptJquery('#stringhover').html('');
      scriptJquery('#stringhover').removeAttr('class');
  })
  
</script>