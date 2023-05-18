<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescommunityads/externals/styles/styles.css'); ?>

<div class="sescmads_help_center sesbasic_bxs sesbasic_clearfix">
	<div class="help_center_head">
		<div class="head_banner">
			<div class="header_desc">
        <div class="_container">
          <h2><?php echo $this->translate("With our community advertisements, connect with people to grow your business and increase your reach."); ?></h2>
          <div class="banner_btn">
            <a href="javascript:;" class="read_faq"><?php echo $this->translate("Read FAQs"); ?></a>
            <?php if($this->viewer() && Engine_Api::_()->authorization()->isAllowed('sescommunityads', $this->viewer(), 'create') ){ ?>
            <a href="<?php echo $this->url(array('action'=>'create'),'sescommunityads_general',true); ?>" class="create_ad"><?php echo $this->translate("Create and Ad"); ?></a>
            <?php } ?>
          </div>
				</div>
			</div>
		</div>
    <script type="application/javascript">
    scriptJquery('.read_faq').click(function(e){
      scriptJquery('html, body').animate({
            scrollTop: scriptJquery("#faqs").offset().top - 50
      }, 0);
    })
    </script>
		<div class="_head_con">
			<div class="_container">
        <h3><?php echo $this->translate("Your people are here !"); ?></h3>
        <p><?php echo $this->translate("Advertising on our community makes it easy for you to find the right people for your ads, get their attention and achieve desired results."); ?></p>
        <div class="head_item_align">
          <div class="_head_con_item">
            <div class="_cont_icon">
              <img src="./application/modules/Sescommunityads/externals/images/target.png" alt=""/>
            </div>
            <div class="_con_desc">
              <h4><?php echo $this->translate("Targeted Audience"); ?></h4>
              <p><?php echo $this->translate("You can easily choose to display your ads to your targeted audience here."); ?></p>
            </div>
          </div>
          <div class="_head_con_item">
            <div class="_cont_icon">
              <img src="./application/modules/Sescommunityads/externals/images/ads.png" alt=""/>
            </div>
            <div class="_con_desc">
              <h4><?php echo $this->translate("Attractive Ad Display"); ?></h4>
              <p><?php echo $this->translate("With 3 different Ad formats, you can control the display of your ads as your requirements."); ?></p>
            </div>
          </div>
          <div class="_head_con_item">
            <div class="_cont_icon">
              <img src="./application/modules/Sescommunityads/externals/images/product.png" alt=""/>
            </div>
            <div class="_con_desc">
              <h4><?php echo $this->translate("In Budget Ad Packages"); ?></h4>
              <p><?php echo $this->translate("You can choose a suitable and perfect package for your creating ads here."); ?></p>
            </div>
          </div>
        </div>
      </div>
		</div>
	</div>
	<div class="visibility_of_ads">
		<div class="_container">
      <div class="visibility_ads_inner">
        <h3><?php echo $this->translate("Ad Targeting and Visibility of Ads"); ?></h3>
        <p><?php echo $this->translate("Our ad system works based on the ad targeting by our advertisers. Our advertisers choose their desired audience and we then show the ads to the people who have shown interest in that ad or matches to the ad targeting. This means our audience will see relevant and useful ads without any technical details and any personal information being shared by our advertisers"); ?></p>
        <div class="ads_match">
					<div class="match_advertisement">
						<div class="match_head">
							<p><?php echo $this->translate("When an advertiser creates an ad and wants to reach the audience like"); ?> <strong><?php echo $this->translate("18-30 years"); ?></strong> <?php echo $this->translate("SESCOMMFemale"); ?>.</p>
							<img src="./application/modules/Sescommunityads/externals/images/match2.png" alt=""/>
						</div>
						<div class="match_bottom">
							<p><i class="fa fa-birthday-cake"></i><?php echo $this->translate("Between 18-30 years old"); ?></p>
							<p><i class="fa fa-user"></i><?php echo $this->translate("SESCOMMFemale"); ?></p>
						</div>
					</div>
					<div class="match_icon"><span><i class="fa fa-long-arrow-right"></i><br/><i class="fa fa-long-arrow-left"></i></span></div>
					<div class="match_advertisement">
						<div class="match_head">
							<p><?php echo $this->translate("When an advertiser creates an ad and wants to reach the audience like"); ?> <strong><?php echo $this->translate("18-30 years"); ?></strong> <?php echo $this->translate("Female"); ?>.</p>
							<img src="./application/modules/Sescommunityads/externals/images/match1.png" alt=""/>
						</div>
						<div class="match_bottom">
							<p><i class="fa fa-birthday-cake"></i><?php echo $this->translate("20 years old"); ?></p>
							<p><i class="fa fa-user"></i><?php echo $this->translate("SESCOMMFemale"); ?></p>
						</div>
					</div>
				</div>
      </div>
		</div>
	</div>
	<div class="how_it_works">
		<div class="_container">
      <h3><?php echo $this->translate("How our Ad System works?"); ?></h3>
      <div class="align_items">
        <div class="working_process_item">
          <img src="./application/modules/Sescommunityads/externals/images/1.png" alt=""/>
          <h4><?php echo $this->translate('Advertisers’ Selection'); ?></h4>
          <p><?php echo $this->translate("Advertisers select suitable package, campaign, business goal for their ads."); ?></p>
        </div>
        <div class="working_process_item">
          <img src="./application/modules/Sescommunityads/externals/images/2.png" alt=""/>
          <h4><?php echo $this->translate('Ad Format and Targeting'); ?></h4>
          <p><?php echo $this->translate('Advertisers choose design and format for their ads, configure them and choose their desired audience.'); ?></p>
        </div>
        <div class="working_process_item">
          <img src="./application/modules/Sescommunityads/externals/images/3.png" alt=""/>
          <h4><?php echo $this->translate('The ad is created'); ?></h4>
          <p><?php echo $this->translate('Advertisers create ads to show on this website at various places included feeds.'); ?></p>
        </div>
        <div class="working_process_item">
          <img src="./application/modules/Sescommunityads/externals/images/4.png" alt=""/>
          <h4><?php echo $this->translate('We Show Ads to Audience'); ?></h4>
          <p><?php echo $this->translate('Based on advertisers goals & desired audience, we show ads on our site to various audience without selling any user data to advertisers.'); ?></p>
        </div>
      </div>
		</div>
	</div>
	<div class="faq_blk" id="faqs">
		<div class="_container">
      <h3><?php echo $this->translate('SESCOMMFAQs'); ?></h3>
      <div class="align-items">
        <div class="faq_item">
          <img src="./application/modules/Sescommunityads/externals/images/5.png" alt=""/>
          <h4><?php echo $this->translate('Do you sell my data?'); ?></h4>
          <p><?php echo $this->translate('No, we do not sell our members data to advertisers. We show ads based on the targeting and desired audience chosen by advertisers without telling them any user information including name, posts or any other content posted on this site.'); ?></p>
        </div>
        <div class="faq_item">
          <img src="./application/modules/Sescommunityads/externals/images/6.png" alt=""/>
          <h4><?php echo $this->translate('How can I improve Ads display?'); ?></h4>
          <p><?php echo $this->translate('The ads will look beautifully if you upload good quality images, video and content to your ads. Choose high quality artwork, relevant and specific content which can interest your audience will enhance your ad. The more appealing images and content will be, the more audience you will get.'); ?></p>
        </div>
        <div class="faq_item">
          <img src="./application/modules/Sescommunityads/externals/images/7.png" alt=""/>
          <h4><?php echo $this->translate('Where all on this site, my ads will be displayed or I can see ads?'); ?></h4>
          <p><?php echo $this->translate('Ads on this site will be displayed at various places included pages, various sections, columns, Activity Feeds.'); ?></p>
          <p><?php echo $this->translate('Users may choose to report, hide the ads which they will not find relevant or suitable for them.'); ?></p>
        </div>
      </div>
		</div>
	</div>
	<div class="help_center_footer">
		<div class="_container">
			<div class="footer_desc">
        <p><?php echo $this->translate('Get Started with making an effective ad and grow your business !!'); ?></p>
        <div class="help_footer_btn">
        <?php if($this->viewer() && Engine_Api::_()->authorization()->isAllowed('sescommunityads', $this->viewer(), 'create') ){ ?>
          <a href="<?php echo $this->url(array('action'=>'create'),'sescommunityads_general',true); ?>" class="create_ad"><?php echo $this->translate("Create an Ad"); ?></a>
          <span>Or</span>
        <?php } ?>
          <a href="<?php echo $this->url(array('action'=>'browse'),'sescommunityads_general',true); ?>" class="explore_ad"><?php echo $this->translate("Explore Ads"); ?></a>
        </div>
			</div>
		</div>
	</div>
</div>
