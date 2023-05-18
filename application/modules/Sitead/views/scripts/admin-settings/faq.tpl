<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: faq.tpl 2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<h2><?php echo $this->translate("Advertisements, Community Ads & Marketing Campaigns Plugin") ?></h2>

<?php if( count($this->navigation) ): ?>
	<div class='sitead_admin_tabs'>
		<?php	echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
	</div>
<?php endif; ?>

<?php include_once APPLICATION_PATH . '/application/modules/Sitead/views/scripts/admin-settings/faq_help.tpl'; ?>
