<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Tickvideo
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: support.tpl 2020-11-03 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<style>
.help_support_links{
	display:flex;
	flex-wrap:wrap;
}
.help_support_links a{
	padding:20px 10px 15px;
	width:30%;
	margin:5px;
	text-decoration:none !important;
	transition:all .5s ease;
}
.help_support_links a i{
	float:left;
	margin:3px 10px 10px 0;
	width:30px;
	font-size:25px;
	color:#706e6e;
	text-align:center;
}
.help_support_links a h4{
	font-size:17px;
	font-weight:bold;
	color:#706e6e;
	margin-top:5px;
}
.help_support_links a p{
	clear:both;
	font-size:13px;
	margin-top:10px;
	color:#818181;
	text-align:justify;
}
.help_support_links a p span._btn{
	background-color:#0DC7F1;
	color:#fff;
	float:right;
	padding:3px 10px;
	margin-top:10px;
	text-transform:uppercase;
	font-size:90%;
}
.help_support_links a:nth-child(1){
	background:#e1f9ed;
}
.help_support_links a:nth-child(2){
	background:#daf5ce;
}
.help_support_links a:nth-child(3){
	background:#fbf9d6;
}
.help_support_links a:nth-child(4){
	background:#ffe4ed;
}
.help_support_links a:nth-child(5){
	background:#ebe0ff;
}
.help_support_links a:nth-child(6){
	background:#eeffd8;
}
.help_support_links a:nth-child(7){
	background:#e3fffa;
}
.help_support_links a:nth-child(8){
	background:#e3e7ff;
}
.help_support_links a:nth-child(9){
	background:#ffecee;
}

.help_support_links a img{
	float:left;
	margin:0 10px 10px 0;
	width:30px;
}
.help_support_links b{
	font-weight:bold;
}
.help_support_social_buttons{
	margin-top:30px;
}
.help_support_social_buttons ul{
	display:flex;
	justify-content:center;
}
.help_support_social_buttons ul li{
	padding:0 5px;
	width:25%;
}
.help_support_social_buttons ul li._facebook a{
	background-color:#3a589b;
}
.help_support_social_buttons ul li._twitter a{
	background-color:#1da1f2;
}
.help_support_social_buttons ul li._youtube a{
	background-color:#cf3427;
}
.help_support_social_buttons ul li._se a{
	background-color:#000000;
}
.help_support_social_buttons ul a{
	justify-content:center;
	align-items:center;
	display:flex;
	text-align:center;
	color:#fff;
	height:50px;
	box-shadow:0 5px 5px rgba(0, 0, 0, .2);
	position:relative;
	-webkit-transition:all 200ms ease 0s;
	-moz-transition:all 200ms ease 0s;
	-o-transition:all 200ms ease 0s;
	transition:all 200ms ease 0s;
	top:0;
}
.help_support_social_buttons ul a:hover{
	text-decoration:none;
	top:-5px;
}
.help_support_social_buttons ul a i{
	margin-right:8px;
	font-size:17px;
	vertical-align:middle;
}
.help_support_social_buttons ul li._se img{
	width:17px;
	vertical-align:middle;
}
.help_support_social_buttons ul a span{
	font-weight:bold;
}
.help_support_contact_links{
	margin-top:30px;
}
.help_support_contact_links ul{
	display:flex;
	justify-content:center;
	background-color:var(--theme-box-background-alt-color);
	padding:20px;
}
.help_support_contact_links ul li{
	margin:0 10px;
	font-size:15px;
	display:flex;
	align-items:center;
}
.help_support_contact_links ul li i{
	font-size:20px;
	margin-right:5px;
	vertical-align:middle;
}
.help_support_contact_links ul li span,
.help_support_contact_links ul li span a{
	font-weight:bold;
}
.help_support_contact_links ul li i.fa-skype{
	color:#00aaf2;
}
.help_support_contact_links ul li i.fa-whatsapp{
	color:#4caf50;
}
.help_support_contact_links ul li i.fa-call{
	color:#fc7a51;
}
.help_support_contact_links ul li i img{
	width:16px;
	vertical-align:top;
}
</style>

<?php include APPLICATION_PATH .  '/application/modules/Tickvideo/views/scripts/dismiss_message.tpl';?>
<div class="help_support_links">
  <a href="https://socialnetworking.solutions/social-engine/short-tiktak-video/" target="_blank">
     <i class="fa fa-file-text-o"></i>
    <h4>Detailed Description</h4>
    <p>Read all the details about this plugin on its description page.</p>
  </a>
  <a href="https://socialnetworking.solutions/plugins/shorttiktakvideo/screenshots.html" target="_blank">
     <i class="fa fa-picture-o"></i>
    <h4>Screenshots</h4>
    <p>View all the screenshots to know how this plugin look & work.</p>
  </a>
  <a href="https://help.socialnetworking.solutions/faqs/category/short-tiktak-video-plugin" target="_blank">
    <i class="fa fa-question-circle-o"></i>
    <h4>FAQs</h4>
    <p>Get answers to all your random queries about this plugin from our Help Center.</p>
  </a>
  <a href="https://socialnetworking.solutions/support/" target="_blank">
    <i class="fa fa-ticket"></i>
    <h4>Need Support - File a Ticket</h4>
    <p>If you face any issues with the plugin, then file a support ticket.</p>
  </a>
  <a href="https://socialnetworking.solutions/contact-us/" target="_blank">
     <i class="fa fa-cogs"></i>
    <h4>Feature Request</h4>
    <p>Do you need a new feature for your website, request a quote.</p>
  </a>
  <a href="https://community.socialengine.com/profile/SocialNetworkingDOTSolutions" target="_blank">
    <i class="fa fa-commenting-o"></i>
    <h4>SE Community Help</h4>
    <p>We are active on SE Community also, if you need assistance there.</p>
  </a>
  <a href="https://socialenginesolutions.us16.list-manage.com/subscribe?u=70b1c9baba63dcf30fec2c66d&id=3c72ee2249" target="_blank">
    <i class="fa fa-envelope-o"></i>
    <h4>Subscribe to Newsletter</h4>
    <p>Do you want to stay updated via newsletter? Subscribe Now.</p>
  </a>
  <a href="https://socialnetworking.solutions/tag/shorttiktakvideo/" target="_blank">
    <i class="fa fa-repeat"></i>
    <h4>Blog Updates</h4>
    <p>Checkout our blogs posts about new releases, announcements & upgrades.</p>
  </a>
  <a href="https://www.socialengine.com/marketplace/app/short-tiktak-videos-plugin" target="_blank">
    <i class="fa fa-star-o"></i>
    <h4>Write a Review</h4>
    <p>Are you liking our work?Your positive reviews matter a lot to us. <span class="_btn">Click to rate us</span></p>
  </a>
</div>
<div class="help_support_social_buttons">
    <ul>
    <li class="_facebook">
        <a href="https://www.facebook.com/SocialNetworkingDOTSolutions" target="_blank">
        <i class="fab fa-facebook"></i>
        <span>Facebook</span>
        </a>
    </li>
    <li class="_twitter">
        <a href="https://twitter.com/SocialEngineSol" target="_blank">
        <i class="fab fa-twitter"></i>
        <span>Twitter</span>
        </a>
    </li>
    <li class="_youtube">
        <a href="https://www.youtube.com/channel/UCZNb_NwWG6lURapht7AehsQ" target="_blank">
        <i class="fab fa-youtube"></i>
        <span>YouTube Tutorial</span>
        </a>
    </li>
    <li class="_se">
        <a href="https://community.socialengine.com/group/9/socialnetworking-solutions" target="_blank">
        <i><img src="application/modules/Tickvideo/externals/images/support/se-icon.png" /></i>
        <span>Expert Profile</span>
        </a>
    </li>
  </ul>
</div>
<div class="help_support_contact_links">
    <ul>
    <li>
        <i class="fab fa-skype"></i>
        <span>vaibhav.sesolutions</span>
    </li>
    <li>
        <i class="fa fa-phone"></i>
        <span>+1-213-267-7939 (USA)</span>
    </li>
    <li>
        <i class="fab fa-whatsapp"></i>
        <span>+91-9950682999</span>
    </li>
    <li>
        <i><img src="application/modules/Tickvideo/externals/images/support/sns-icon.png" /></i>
        <span><a href="https://socialnetworking.solutions/" target="_blank">SocialNetworking.Solutions</a></span>
    </li>
  </ul>
</div>