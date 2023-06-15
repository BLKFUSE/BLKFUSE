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

<section class="sesnews_feature_block sesbasic_clearfix sesbasic_bxs">
	<div class="sesnews_feature_block_container">
		<div class="block_left_desc">
			<h2><?php echo $this->translate("There’s nothing more valuable than knowledge. As news, Updates you with the things happening around you and gives you the latest updates.")?></h2>
			<p><?php echo $this->translate("News is a part of everyday life and keeping up with what is going on in the world can be difficult. With the help of this plugin, you can learn about what is happening and what is trending in any category you desire.")?></p>
			<p>With the help of News plugin you can:</p>	
			<ul>
				<li><?php echo $this->translate("Create the News in any category you want.")?></li>
				<li><?php echo $this->translate("Add the RSS links and get the latest updated news.")?></li>
				<li><?php echo $this->translate("You can subscribe the RSS created by the other users.")?></li>
				<li><?php echo $this->translate("And many more…")?></li>
			</ul>	
			<div class="block_btn_link">
				<a href="/news/create" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp; <?php echo $this->translate("Create News")?></a>
				<a href="/news/create" class="btn btn-primary"><i class="fa fa-rss"></i>&nbsp; <?php echo $this->translate("Add RSS")?></a>
			</div>
		</div>
		<div class="block_right_img">
			<img src="application/modules/Sesnews/externals/images/explore-news.png" alt="" />
		</div>
	</div>
</section>