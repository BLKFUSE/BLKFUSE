<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: help-and-learnmore.tpl 2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php if(!Engine_Api::_()->seaocore()->checkModuleNameAndNavigation()):?>
<div class="layout_middle">
  <div class="generic_layout_container">
	<div class="headline">
	<h2>
		<?php echo $this->translate('Advertising');  ?>
	</h2>
	<div class='tabs'>
		<?php echo $this->navigation($this->navigation)->render() ?>
	</div>
</div>
</div>
</div>
<?php endif;?>
<?php
if( !empty($this->pageObject) ) {
?>
<script type="text/javascript">
	var display_faq = '<?php echo $this->display_faq ?>';
	if(display_faq) {
	  var active_tab = '<?php echo $this->faqpage_id ;?>';
	}
	else {
	  var active_tab = '<?php echo $this->pageObject[0]['infopage_id'];?>';
	}
</script>
<?php
}
if( ($this->page_default == 1) && empty($this->display_faq) ) {
	include_once APPLICATION_PATH . '/application/modules/Sitead/views/scripts/help-pages/_sitead-help-overview.tpl';
}else if( ($this->page_default == 2) && empty($this->display_faq) ) {
	include_once APPLICATION_PATH . '/application/modules/Sitead/views/scripts/help-pages/_sitead-help-getstrarted.tpl';
}else if( $this->page_default == 3 ) {
	include_once APPLICATION_PATH . '/application/modules/Sitead/views/scripts/help-pages/_sitead-help-improve-ad.tpl';
}else if( !empty($this->viewFaq) || !empty($this->display_faq) ) {
	include_once APPLICATION_PATH . '/application/modules/Sitead/views/scripts/help-pages/_sitead-help-faq.tpl';
}else {
  include_once APPLICATION_PATH . '/application/modules/Sitead/views/scripts/help-pages/_sitead-pages.tpl';
}
?>