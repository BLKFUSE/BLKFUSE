<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl 2016-07-23 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesnews/externals/scripts/core.js'); 
?>

<section class="sesnews_footer_feature_block sesbasic_clearfix sesbasic_bxs">
	<div class="sesnews_footer_feature_block_container">
		<div class="block3_inner">
			<h2><?php echo $this->translate("Are you missing what’s important?")?></h2>
			<p><?php echo $this->translate("Yes, You are missing News as getting updated helps us to become aware about what is going on in our community. So what are you waiting for, find your interests news or create the new one.")?></p>
			<div class="block_btn_link">
				<a href="/news/create" target="_blank" class="btn btn-primary"><?php echo $this->translate("Create News")?></a>
				<span class="or_add">Or</span>
				<a href="/news/categories" target="_blank" class="btn btn-primary"><?php echo $this->translate("Explore Categories")?></a>
			</div>
		</div>
	</div>
</section>
