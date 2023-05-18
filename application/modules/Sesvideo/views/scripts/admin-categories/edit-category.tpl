<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: edit-category.tpl 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php include APPLICATION_PATH .  '/application/modules/Sesvideo/views/scripts/dismiss_message.tpl';?>
<?php 

?>

<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesvideo', 'controller' => 'categories', 'action' => 'index'), $this->translate("Back to Categories"), array('class'=>'sesbasic_icon_back buttonlink')) ?>
<br /><br />
<div class='clear'>
  <div class='settings sesbasic_admin_form'>
    <?php echo $this->form->render($this); ?>
  </div>
</div>

<script type="application/javascript">
scriptJquery("#category_name").keyup(function(){
		var Text = scriptJquery(this).val();
		Text = Text.toLowerCase();
		Text = Text.replace(/[^a-zA-Z0-9]+/g,'-');
		scriptJquery("#slug").val(Text);        
});

</script>
