<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl  2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php if (isset($this->info['module_title'])): ?>
    <h3 class="advertise_your_page_title"><?php echo $this->translate('Advertise your %s', $this->translate($this->info['module_title'])) ?></h3>
    <div class="advertise_your_page">
        <?php $translatedModuleTitle = $this->translate($this->info['module_title']); ?>
        <span class="advertise_text"><?php echo $this->translate('Get more audience to visit and like your %1$s with %2$s Ads!', $translatedModuleTitle, $site_title) ?></span>
        <div class="cmad_hr_link" style="float:left;clear:both;">
			<?php $create_ad_url = $this->url(array(), 'sitead_listpackage', true); ?>
			<a href="<?php echo $create_ad_url; ?>" style="padding:5px;"><?php echo $this->translate("Create an Ad"); ?> &raquo;</a>
		</div>
    </div>
<?php endif; ?>
<style type="text/css">
	.advertise_text
	{
		display: block;
	}
	.advertise_link
	{
		margin-top: 0
	}
</style>