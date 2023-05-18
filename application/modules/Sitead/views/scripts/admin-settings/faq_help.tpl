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

<script type="text/javascript">
  function faq_show(id) {
    if($(id).style.display == 'block') {
      $(id).style.display = 'none';
    } else {
      $(id).style.display = 'block';
    }
  }
</script>

<?php if(!empty($this->faq)) : ?>
	<p><?php echo $this->translate("Browse the different FAQ sections of this plugin by clicking on the corresponding tabs below.") ?><p>
	<br />
	<?php $action = 'faq' ?>
<?php else : ?>
	<?php $action = 'readme' ?>
<?php endif; ?>
<div class='tabs seaocore_faq_tabs'>
		<ul class="navigation">
		  <li class="<?php if($this->faq_type == 'general') { echo "active"; } ?>">
		 	<?php echo $this->htmlLink(array('route'=>'admin_default','module' => 'sitead','controller' => 'settings','action' => $action, 'faq_type' => 'general'), $this->translate('General'), array())
		  ?>
			</li>
			<li class="<?php if($this->faq_type == 'package') { echo "active"; } ?>">
		   <?php
		    echo $this->htmlLink(array('route'=>'admin_default','module' => 'sitead','controller' => 'settings','action' => $action, 'faq_type' => 'package'), $this->translate('Packages'), array())
		  ?>
			</li>
			<li class="<?php if($this->faq_type == 'blocks') { echo "active"; } ?>">
		   <?php
		    echo $this->htmlLink(array('route'=>'admin_default','module' => 'sitead','controller' => 'settings','action' => $action, 'faq_type' => 'blocks'), $this->translate('Ad Blocks'), array())
		  ?>
			</li>
			<li class="<?php if($this->faq_type == 'targeting') { echo "active"; } ?>">
		   <?php
		    echo $this->htmlLink(array('route'=>'admin_default','module' => 'sitead','controller' => 'settings','action' => $action, 'faq_type' => 'targeting'), $this->translate('Targeting'), array())
		  ?>
<!--			<li class="<?php if($this->faq_type == 'ajax') { echo "active"; } ?>">
		   <?php
		    echo $this->htmlLink(array('route'=>'admin_default','module' => 'sitead','controller' => 'settings','action' => $action, 'faq_type' => 'ajax'), $this->translate('Ajax Based'), array())
		  ?>
			</li>-->
			<li class="<?php if($this->faq_type == 'stats') { echo "active"; } ?>">
		   <?php
		    echo $this->htmlLink(array('route'=>'admin_default','module' => 'sitead','controller' => 'settings','action' => $action, 'faq_type' => 'stats'), $this->translate('Reports & Statistics'), array())
		  ?>
			</li>
			<li class="<?php if($this->faq_type == 'language') { echo "active"; } ?>">
		   <?php
		    echo $this->htmlLink(array('route'=>'admin_default','module' => 'sitead','controller' => 'settings','action' => $action, 'faq_type' => 'language'), $this->translate('Language'), array())
		  ?>
			</li>
		</ul>
	</div>

<?php switch($this->faq_type) : 
	case 'general': ?>
		<div class="admin_seaocore_files_wrapper">
			<ul class="admin_seaocore_files seaocore_faq">
				<li>
					<a href="javascript:void(0);" onClick="faq_show('faq_1');"><?php echo $this->translate(" How do I go about setting up this plugin on my site ?");?></a>
					<div class='faq' style='display: none;' id='faq_1'>
						<?php echo $this->translate('Ans: Given are the steps you should follow to configure the plugin on your site according to your requirements:<br /><br />
					1) If you want paid ads on your site, then configure payment related settings on your site from the Billing > Settings and Billing > Gateways sections.<br /><br />
					2) Configure the "Global Settings" of this plugin.<br /><br />
					3)  Decide the locations/pages on your site where you want to place the Ad Blocks for Site Ads. Then, go to the Admin > Appearance > Layout Editor section and place "Display Advertisements" widget on those pages where you want to display ads and configure various available settings for that block from edit section of the widget.
For placing Ad Blocks on the non-widgetized pages of your site, please check the FAQ in "Ad Blocks" tab.<br /><br />
					4) Manage content modules on your site that can be advertised, from the "Manage Modules" section. With Advertisements, Community Ads & Marketing Campaigns Plugin, users can create different types of ads with different formats and advertise their content/external website on the site. This advertising system allows you to enable your users to advertise their content from absolutely any content module.<br /><br />
					5) Create ad packages for advertising on your site. Users will have to select an ad package while creating an ad. You can choose settings for ad packages like free/paid, ad category, content items allowed to be advertised, pricing model, etc. Different ad types have different ad packages and package properties.<br /><br />
					6)  Configure other settings for advertising on your site like: Member Level Settings, Targeting Settings, Graphs Settings, etc.<br /><br />
					7) Create and manage advertising help pages on your site from the "Manage Help & Learn More" section.');?>
						</div>
				</li>

				<li>
					<a href="javascript:void(0);" onClick="faq_show('faq_2');"><?php echo $this->translate("What are the recommended values for dimensions/ limit of Ad elements that should be entered in the Global Settings ?");?></a>
					<div class='faq' style='display: none;' id='faq_2'>
						<?php echo $this->translate("Ans: Given are the recommended values for dimensions of Ad elements. You may do minor variations in them according to your site's design/theme, but we highly recommend you not to make much difference in the dimensions, otherwise the ads may not display correctly on your site:<br />
						a) Ad Title Length: 25 characters <br/>
                        b) Ad Body Length: 135 characters");?>
						</div>
				</li>
				<li>
					<a href="javascript:void(0);" onClick="faq_show('faq_8');"><?php echo $this->translate(" I have some content modules on my website and I want to advertise those modules. Can I do that ?");?></a>
					<div class='faq' style='display: none;' id='faq_8'>
						<?php echo $this->translate('Yes. This plugin enables you to advertise any module present on your website. All you need is to include that content module in the Ad Package, which users will select to create advertisements of that content. You may create a new package or can edit the existing package as well for this.');?>
						</div>
				</li>
				<li>
					<a href="javascript:void(0);" onClick="faq_show('faq_9');"><?php echo $this->translate(" One of my advertisers is complaining that their ad is getting very fewer views/impressions. What can I do to make an ad appear more prominent / frequent ?");?></a>
					<div class='faq' style='display: none;' id='faq_9'>
						<?php echo $this->translate('Ans: Edit that ad from the "Manage Advertisements" section, and assign a large value to the "Weight" of that ad. This ad will now get higher priority for being displayed. Note: This functionality should only be used for exceptional cases, and you are suggested to change the weight of the ad back to zero after the purpose is achieved.');?>
						</div>
				</li>
					<li>
					<a href="javascript:void(0);" onClick="faq_show('faq_10');"><?php echo $this->translate(" What types of pages are there in the help and learn more section ?");?></a>
					<div class='faq' style='display: none;' id='faq_10'>
						<?php echo $this->translate("Ans: The help and learn more section contains two types of pages. One, pages built with the rich editor, and two, FAQ pages. You can create new help pages of the first type. If you do not want a page to be shown in the help page, then you can simply delete it.");?>
						</div>
				</li>
				<li>
					<a href="javascript:void(0);" onClick="faq_show('faq_11');"><?php echo $this->translate(" For the content of help and learn more pages of my site, are there any URLs I can refer to ?");?></a>
					<div class='faq' style='display: none;' id='faq_11'>
						<?php echo $this->translate("Ans: Yes, You may refer to the given URLs for the content of help and learn more pages of your site:<br /><br />

					Overview :-<br />
					http://www.facebook.com/advertising/<br /><br />
					
					Get Started :-<br />
					http://www.facebook.com/adsmarketing/index.php?sk=gettingstarted<br /><br />
					
					Improve your ads :-<br />
					http://www.facebook.com/adsmarketing/index.php?sk=adtypes<br /><br />
					
					General FAQ :<br />
					http://www.facebook.com/help/?page=409<br /><br />
					
					Design FAQ<br />
					http://www.facebook.com/help/?page=861<br /><br />
					
					Targeting FAQ<br />
					http://www.facebook.com/help/?page=863");?>
						</div>
				</li>
				<li>
					<a href="javascript:void(0);" onClick="faq_show('faq_14');"><?php echo $this->translate(" I want to showcase the advertising feature of my site. How can I do that ?");?></a>
					<div class='faq' style='display: none;' id='faq_14'>
						<?php echo $this->translate('Ans: You can do this by using a widget named  "Advertise: Create an Ad". This widget will show "Create an Ad" button at user end.');?>
						</div>
				</li>
				<li>
					<a href="javascript:void(0);" onClick="faq_show('faq_15');"><?php echo $this->translate(' Where can I place the main "Advertising" Link ?');?></a>
					<div class='faq' style='display: none;' id='faq_15'>
						<?php echo $this->translate("Ans: You can place the main Advertising link on your site at these 4 places, by choosing from the Global Settings of this plugin: Main Navigation Menu, Mini Navigation Menu, Footer Menu, Member Home Page Left side Navigation.");?>
						</div>
				</li>
				<li>
					<a href="javascript:void(0);" onClick="faq_show('faq_16');"><?php echo $this->translate("If an ad has been created for a content item and that content item, later on, gets deleted / disabled, then, will the ad still display ?");?></a>
					<div class='faq' style='display: none;' id='faq_16'>
						<?php echo $this->translate("Ans : Yes, the ad will continue to be displayed unless it gets disapproved/paused. However, clicking on the URL of the ad will not open the content item as that item has been removed.");?>
						</div>
				</li>
				<li>
					<a href="javascript:void(0);" onClick="faq_show('faq_18');"><?php echo $this->translate("The CSS of this plugin is not coming on my site. What should I do ?");?></a>
					<div class='faq' style='display: none;' id='faq_18'>
						<?php echo $this->translate("Ans: Please enable the 'Development Mode' system mode for your site from the Admin homepage and then check the page which was not coming fine. It should now seem fine. Now you can again change the system mode to 'Production Mode'.");?>
						</div>
				</li>
				<li>
					<a href="javascript:void(0);" onClick="faq_show('faq_21');"><?php echo $this->translate("Which account type should I select while creating a PayPal account on paypal.com for payment gateways ?");?></a>
					<div class='faq' style='display: none;' id='faq_21'>
						<?php echo $this->translate("Ans : When you start creating your PayPal account, some account types will be shown to you as an initial step of the sign-up process. We recommend you to select ‘Business’ type of PayPal account.");?>
						</div>
				</li>
				<li>
					<a href="javascript:void(0);" onClick="faq_show('faq_22');"><?php echo $this->translate("Can I delete an advertisement or a campaign I have created ?");?></a>
					<div class='faq' style='display: none;' id='faq_22'>
						<?php echo $this->translate("Ans : No, you can not delete an ad permanently. But you can delete an ad on a temporary basis which means that its status would appear as 'Deleted' everywhere on the site. Such an ad would just stop running and not be visible to the viewers on the site. But all the things like the details, statistics, etc of an ad would not be deleted permanently. This is because deleting an ad permanently will affect the consistency of statistics. While you can delete a campaign permanently and hence all the ads belonging to that campaign and their stats will also be deleted. ");?>
					</div>
				</li>

				<li>
					<a href="javascript:void(0);" onClick="faq_show('faq_23');"><?php echo $this->translate("How many Ad Categories/Types are there ?");?></a>
					<div class='faq' style='display: none;' id='faq_23'>
						<?php echo $this->translate("Ans : There are four ad categories/types:</br>
						 a) Boost Your Post (You need to install Advanced Activity Feeds/Wall Plugin)</br>
						 b) Promote Your Content (You have to enable content modules in package)</br>
						 c) Promote Your Page (You need to install Directory/Pages Plugin) </br>
						 d) Get More Website Visitor");?>
					</div>
				</li>

				<li>
					<a href="javascript:void(0);" onClick="faq_show('faq_49');"><?php echo $this->translate("How many Ads can I create in one campaign in this plugin ?");?></a>
					<div class='faq' style='display: none;' id='faq_49'>
						<?php echo $this->translate("Ans : You can create unlimited ads in a campaign and manage them by admin panel.");?>
					</div>
				</li>
				<li>
					<a href="javascript:void(0);" onClick="faq_show('faq_51');"><?php echo $this->translate("From where can I check details for payment transactions done on my website?");?></a>
					<div class='faq' style='display: none;' id='faq_51'>
						<?php echo $this->translate("Ans : You can check all the transaction related details under Transactions Section.");?>
					</div>
				</li>
				<li>
					<a href="javascript:void(0);" onClick="faq_show('faq_50');"><?php echo $this->translate("Are the three ad formats available for any ad type ?");?></a>
					<div class='faq' style='display: none;' id='faq_50'>
						<?php echo $this->translate("Ans : The three ad formats ie. Carousel, Image, Video are available for all ad types except Boost your post. It promotes your post as it is.");?>
					</div>
				</li>
				<li>
					<a href="javascript:void(0);" onClick="faq_show('faq_52');"><?php echo $this->translate("How to show ads in Core Activity Feed ?");?></a>
					<div class='faq' style='display: none;' id='faq_52'>
						<?php echo $this->translate("Ans : In Global Settings select Show Ads in Core Feed to Yes, then enter after how many feeds you want to show Ads.");?>
					</div>
				</li>

			</ul>
		</div>
	<?php break; ?>

	<?php case 'package': ?>
			<div class="admin_seaocore_files_wrapper">
				<ul class="admin_seaocore_files seaocore_faq">
					<li>
							<a href="javascript:void(0);" onClick="faq_show('faq_5');"><?php echo $this->translate("What are Ad packages for ?");?></a>
							<div class='faq' style='display: none;' id='faq_5'>
								<?php echo $this->translate("Ans: Before creating an ad on your site, users will have to choose a package for it. Ad packages in this advertising system are very flexible and create many settings as mentioned below, to suit your advertising needs:<br />
					- Paid / Free package<br />
					- Package cost<br />
					- Category of Ad<br />
					- Content types to be advertisable<br />
					- Ad blocks on the site where ads of this package should appear<br />
					- Pricing Model (clicks / views / days)<br />
					- Expiry limit for ads of this package<br />
					- Activate Carousel Ad format<br />
					- Activate Image Ad format<br />
					- Activate Video Ad format<br />
					- Make ads of package sponsored<br />
					- Make ads of package featured<br />
					- Allow targeting of ads of this package<br />
					- Show package's ads to non-logged-in visitors<br />
					- Require / not-require admin approval for ads of this package<br />
					- Enable advertisers to renew their ads of this package before expiry");?>
								</div>
					</li>
					<li>
						<a href="javascript:void(0);" onClick="faq_show('faq_23');"><?php echo $this->translate("Why am I not able to delete an Ad Package ?");?></a>
						<div class='faq' style='display: none;' id='faq_23'>
							<?php echo $this->translate("Ans : You can not delete an Ad Package once you have created it. This is because the consistency of the already created ads in that package would get affected in that case. But if you wish, you can disable an ad category for that package from Manage Packages section and hence it would not be displayed in the list of packages during the initial step of the ad creation in  that ad category.");?>
							</div>
					</li>
					<li>
						<a href="javascript:void(0);" onClick="faq_show('faq_26');"><?php echo $this->translate("I want to let some advertisers create Ads under the PAID package(like Pay for Clicks or Views) but I don't want them to pay for this as a trial. How can I do so ?");?></a>
						<div class='faq' style='display: none;' id='faq_26'>
							<?php echo $this->translate("Ans: In that case, you would have to first make that Ad as 'Approved' from 'Manage Advertisements' section of this plugin. Then, you would have to 'Renew' it by clicking on 'Renew' link in the rightmost 'Options' column.<br />If you would not Renew the Ad, it would get expired before the limit of clicks/views/days gets completed. In this way, you(site admin) can manually make an advertisement run for FREE under the paid package.");?>
							</div>
					</li>
					<li>
						<a href="javascript:void(0);" onClick="faq_show('faq_27');"><?php echo $this->translate('I have created an Ad in the paid package and then approved it manually from "Manage Advertisements" section without making the payment. But it is showing only 5 views in the "Remaining" column while the package this Ad belongs to says 100,000 views. What can be the reason ?');?></a>
						<div class='faq' style='display: none;' id='faq_27'>
							<?php echo $this->translate("Ans: This plugin provides '5 views' in case Ad belongs to package with pricing model 'Pay for Views' and provides '1 click' in case Ad belongs to package with pricing model 'Pay for Clicks' for a trial to the advertiser and after those 5 views or 1 click are done and payment is still not made, the ad gets expired automatically.<br />So, now if you want to continue that advertisement without payment, then after making it 'Approved' from 'Manage Advertisements' section, you will also have to renew it by clicking on 'Renew' link in the rightmost 'Options' column. It will renew the Ad and will give it 100,000 Views.");?>
							</div>
					</li>
					<li>
						<a href="javascript:void(0);" onClick="faq_show('faq_28');"><?php echo $this->translate("Is it possible to let users create free trial Ads on my site valid only for a limited period of time ?");?></a>
						<div class='faq' style='display: none;' id='faq_28'>
							<?php echo $this->translate('Ans: Yes, it is possible. You may do so by doing the following things:<br />1) You can disable the existing free package from the "Manage Ad Packages" section at the admin panel of this plugin.<br />2) Now, create a new free package from there and you can set the specifications of that package according to what features you want to give users in that and then in the field "Pricing Model", select "Pay For Days" option and do not select the checkbox for "Enable Ad Renewal => Ad creators will be able to renew their ads of this package before expiry." so that users will not be able to renew their Ad and use this package for more than the no. of days you have set there.<br />3) Also, in future when you do not want users to create free Ads anymore at your site, you can disable all the FREE packages from "Manage Ad Packages" section.');?>
							</div>
					</li>
					<li>
						<a href="javascript:void(0);" onClick="faq_show('faq_48');"><?php echo $this->translate("Can I enable Ad Package for specific ad category ?");?></a>
						<div class='faq' style='display: none;' id='faq_48'>
							<?php echo $this->translate("Ans: Yes, you can enable ad package for specific ad category. Go to Admin> Manage Ad Packages > If you are creating new then select “Create New Ad Package“ otherwise Click on Edit. Then select the Ad Category to which this Ad Package should be available.");?>
							</div>
					</li>
				</ul>
		</div>
	<?php break; ?>

	<?php case 'blocks': ?>
		<div class="admin_seaocore_files_wrapper">
			<ul class="admin_seaocore_files seaocore_faq">
				<li>
					<a href="javascript:void(0);" onClick="faq_show('faq_3');"><?php echo $this->translate("How can I place an 'Ad Block' on a Non-widgetized page ?");?></a>
					<div class='faq' style='display: block;' id='faq_3'>
                      <p>To do so, please follow the steps mentioned below:</p>
                      <p>Step 1: Open the desired file.</p>
                       <p>Step 2: Copy and paste the below code at desired position in the file:</p><br> <div class ="code">
						<?php echo '&lt;?php  $cmad_show_type="all";  &nbsp;&nbsp;<b>// Select the any one type of Ad - all, sponsored, featured</b> <br>
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;$cmad_itemCount = 10;   &nbsp;&nbsp; <b>// Enter the number of Ads</b> <br> 
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; $cmad_packageIds = array();      <b>// Packages Ids in Array Like array(id1,id2,....,idn)</b><br><br>
 echo $this->content()->renderWidget("sitead.ads", array( "show_type" => $cmad_show_type, "itemCount"=>$cmad_itemCount,"packageIds"=>$cmad_packageIds)); ?&gt;'?>
						</div></div>
				</li>

<!--				<li>
					<a href="javascript:void(0);" onClick="faq_show('faq_4');"><?php //echo $this->translate("Q How do I add an Ad Block on a page?");?></a>
					<div class='faq' style='display: none;' id='faq_4'>
						<?php //echo $this->translate('Ans: Please refer to the above question : "On which all pages of my site can I show ads?"');?>
						</div>
				</li>-->
			</ul>
		</div>
	<?php break; ?>

	<?php case 'targeting': ?>
		<div class="admin_seaocore_files_wrapper">
			<ul class="admin_seaocore_files seaocore_faq">
				<li>
					<a href="javascript:void(0);" onClick="faq_show('faq_6');"><?php echo $this->translate("What is Ad Targeting ?");?></a>
					<div class='faq' style='display: none;' id='faq_6'>
						<?php echo $this->translate("Ans: Ad targeting enables advertisers to reach their targetted audience which is more likely to be interested in their ads. If an ad package allows targeting, then advertisers using it will be able to configure targeting for their ad based on user profile fields. Only users having profiles matching with the targeting criteria will be shown the targeted ad. You can configure settings for ads targeting from the Targeting Settings section. Basic targeting occurs on specific profile fields (gender, city, country, education, interests, etc). Advanced targeting occurs such that advertiser will be able to select the Profile Type to which the ad should be targeted and the generic profile fields for that profile type on which targeting should be done.");?>
						</div>
				</li>
				<li>
					<a href="javascript:void(0);" onClick="faq_show('faq_7');"><?php echo $this->translate("What are specific profile fields and generic profile fields ?");?></a>
					<div class='faq' style='display: none;' id='faq_7'>
						<?php echo $this->translate('Ans: To better understand this, please go to the "Settings" > "Profile Questions" section of this Admin Panel. Once there, click on "edit" for a field. In the lightbox, click on the "Question Type" field. You will see the Generic and Specific fields in the dropdown. Fields of type: gender, city, country, education, interests, etc are specific profile fields and fields of type single line text input, select box, etc are generic profile fields.');?>
						</div>
				</li>
			</ul>
		</div>
	<?php break; ?>

<?php case 'ajax': ?>
		<div class="admin_seaocore_files_wrapper">
			<ul class="admin_seaocore_files seaocore_faq">
				<li>
					<a href="javascript:void(0);" onClick="faq_show('faq_17');"><?php echo $this->translate("How do I get Ajax-Based display of Ad Blocks on my site ?");?></a>
					<div class='faq' style='display: none;' id='faq_17'>
						<?php echo $this->translate('Ans: To get Ajax-based Ad Blocks on your site, please go through the following points:<br /><br />1) In "Global Settings", you can "Yes" for the field "Default Ajax Based Display of Ad Blocks" to make the display of all the ad blocks as "Ajax-based" by default.<br />2) When you create a new block from the "Manage Blocks" section at admin panel, you can choose to make that block as ajax-based by selecting "Yes" corresponding to the field "Ajax Based Display".<br />3) If you place an ad block on a page from "Layout" > "Layout Editor" section and do not add it from "Manage Blocks" section, in that case it will be ajax-based if "Default Ajax Based Display of Ad Blocks" in Global Settings has been selected as "Yes".<br /><br />In this way, you can select for every single Ad block on your site to be displayed as Ajax-based or not on the basis of the criticality of that page on which it has been rendered.');?>
					</div>
				</li>
				<li>
					<a href="javascript:void(0);" onClick="faq_show('faq_24');"><?php echo $this->translate("What are Ajax based Ads ?");?></a>
					<div class='faq' style='display: none;' id='faq_24'>
						<?php echo $this->translate("Ans: Ajax based Ads means that the Ad blocks containing Ads will be dispalyed through ajax.<br /><br />Now, the choice is yours that you want to make the ads blocks display on your site as ajax based or not.You can enable it from the 'Default Ajax Based Display of Ad Blocks' field in the Global Settings section of this plugin. And then you can customize this setting for each and every ad block displaying at your site from 'Manage Ad Blocks' section.<br />It just depend on the criticality of the page on which the ad block is being rendered. For ex-<br /><br />1) If you feel like that your page is too heavy and normal ads will make it even more heavy. In that case you can make the ad blocks for that page as 'Ajax based' from Manage Blocks section of this plugin.<br />2) If you think that a particular page has to be loaded fast and you don't want Ads to slow down its execution or display even for a little, in that case you can make the Ad blocks for that page as ajax based.<br /><br />Ajax based ad blocks are displayed on the page after it is rendered to the user. But there might be some places at your site where advertisement display is very important or the main priority. In that case you would not like it if the ad blocks are being displayed slowly after the full render of the whole page. So, in such a case you don't need to make them as ajax-based.");?>
					</div>
				</li>
			</ul>
		</div>
	<?php break; ?>

	<?php case 'stats': ?>
		<div class="admin_seaocore_files_wrapper">
			<ul class="admin_seaocore_files seaocore_faq">
				<li>
					<a href="javascript:void(0);" onClick="faq_show('faq_12');"><?php echo $this->translate("Can advertisers see the performance of their Ads ?");?></a>
					<div class='faq' style='display: none;' id='faq_12'>
						<?php echo $this->translate('Ans: Yes, advertisements can see both graphical as well as statistical reports of their ads and campaigns. Graphical statistics are shown in the "My Campaigns" section to users for all their ads and campaigns, whereas tabular statistics can be seen in the "Reports" section. Advertisers can see the clicks, views and CTR (clickthrough rate) for their ads. You can also configure settings for the graphs from the "Graphs Settings" section in the admin panel.');?>
					</div>
				</li>
				<li>
					<a href="javascript:void(0);" onClick="faq_show('faq_25');"><?php echo $this->translate("What are clicks, views and CTR ?");?></a>
					<div class='faq' style='display: none;' id='faq_25'>
						<?php echo $this->translate("Ans: In this plugin, there are 3 types of stats related to an Ad ie. Views, Clicks and CTR.<br /><br />1) Views get incremented as the page is refreshed from the browser. Yes, it means that if you refresh the browser 10 times in a row, it is counted as 10 views.<br />2) Clicks get incremented after being filtered on the basis of IP addresses. More than 1 clicks in a row from the same IP are counted as only 1 click.<br />3) CTR stands for Clicks to Views ratio.<br /><br />Please remember that statistics measured at daily intervals may be based on different time zones, resulting in different daily totals.");?>
					</div>
				</li>
			</ul>
		</div>
	<?php break; ?>

	<?php case 'language': ?>
		<div class="admin_seaocore_files_wrapper">
			<ul class="admin_seaocore_files seaocore_faq">
				<li>
					<a href="javascript:void(0);" onClick="faq_show('faq_19');"><?php echo $this->translate("There are multiple languages on my site. How should this plugin be used for non-English languages ?");?></a>
					<div class='faq' style='display: none;' id='faq_19'>
						<?php echo $this->translate("Ans : This plugin only comes with English language by default. For other languages, you need to copy the 'sitead.csv' language file from the directory: '/application/languages/en/' of your site, to the directory '/application/languages/LANGUAGE_PACK_DIRECTORY/'. Then, go to the section 'Layout' > 'Language Manager' in the Admin Panel and edit phrases for the desired language.");?>
						</div>
				</li>
				<li>
					<a href="javascript:void(0);" onClick="faq_show('faq_20');"><?php echo $this->translate("In the Ad creation step, the error: 'exception 'Engine_Exception' with message 'No subject translation available for system email 'sitead_userad_disapproved'' in /var/www/application/modules/Core/Api/Mail.php:395' is coming. Why is this coming and what should be done to resolve this?");?></a>
					<div class='faq' style='display: none;' id='faq_20'>
						<?php echo $this->translate("Ans : This error comes if you are using a language other than English on your site, and have not done the required language file settings. For resolving this, you need to copy the 'sitead.csv' language file from the directory: '/application/languages/en/' of your site, to the directory '/application/languages/LANGUAGE_PACK_DIRECTORY/'. Then, go to the section 'Layout' > 'Language Manager' in the Admin Panel and edit phrases for the desired language.");?>
					</div>
				</li>
			</ul>
		</div>
	<?php break; ?>
<?php endswitch; ?>