<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: edit-photo.tpl  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php if(!$this->is_ajax) {
  echo $this->partial('dashboard/left-bar.tpl', 'sesnews', array('news' => $this->news));	
?>
	<div class="sesnews_dashboard_content sesbm sesbasic_clearfix">
<?php }  ?>
	<div class="sesbasic_dashboard_form">
    <div class="sesnews_edit_photo_news">
      <?php echo $this->form->render() ?> 
    </div> 
	</div>
<?php if(!$this->is_ajax) { ?>
	</div>
</div>
<?php } ?>


<script type="application/javascript">
scriptJquery(document).on('submit','#EditPhoto',function(e){
	if(scriptJquery('#Filedata-label').find('label').hasClass('required') && scriptJquery('#Filedata').val() === ''){
		var objectError = scriptJquery('#Filedata');
		alert('<?php echo $this->string()->escapeJavascript("Please fill the red mark fields"); ?>');
		var errorFirstObject = scriptJquery(objectError).parent().parent();
		scriptJquery('html, body').animate({
		scrollTop: errorFirstObject.offset().top
		}, 2000);
		return false;
	}
});
</script>
