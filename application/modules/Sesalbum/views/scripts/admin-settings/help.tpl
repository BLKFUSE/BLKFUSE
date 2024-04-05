<?php include APPLICATION_PATH .  '/application/modules/Sesalbum/views/scripts/dismiss_message.tpl';?>

<style>

.sesalbums_nav_btns{
	float:right;
	margin-top:-62px !important;
}
.sesalbums_nav_btns a{
	background-color:#208ed3;
	border-radius:3px;
	background-position:10px center;
	background-repeat:no-repeat;
	color:#fff !important;
	float:left;
	font-weight:bold;
	padding:7px 15px 7px 30px;
	margin-left:10px;
	position:relative;
}
.sesalbums_nav_btns a:before{
	font-family:'Font Awesome 5 Free';
	left:10px;
	position:absolute;
	font-size:17px;
	font-weight:normal;
	top:5px;
}
.sesalbums_nav_btns a:hover{
	text-decoration:none;
	opacity:.8;
}
.sesalbums_nav_btns .request-btn:before{
	content:"\f059"
}
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

<h2>
    <?php echo $this->translate('Advanced Photos & Albums Plugin'); ?>
</h2>
<div class="sesalbums_nav_btns">
    <a href="<?php echo $this->url(array('module' => 'sesalbum', 'controller' => 'settings', 'action' => 'help'),'admin_default',true); ?>" class="request-btn">Help</a>
</div>
<?php include APPLICATION_PATH .  '/application/modules/Sesalbum/views/scripts/dismiss_message.tpl';?>
<?php if (engine_count($this->navigation)): ?>
<div class='tabs'>
    <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render(); ?>
</div>
<?php endif; ?>
<div class="help_support_links">
    <a href="https://help.socialenginesolutions.com" target="_blank">
       <i class="fa fa-question-circle-o"></i>
        <h4>FAQs</h4>
        <p>Get answers to all your random queries about this plugin from our Help Center. Click to read the tutorials and FAQs.</p>
    </a>
    <a href="https://socialnetworking.solutions/support/" target="_blank">
         <i class="fa fa-ticket"></i>
        <h4>Support</h4>
        <p>If you face any issues with the plugin or you do not find answer to your query in our Help Center, then file a support ticket from here.</p>
    </a>
    <a href="https://socialenginesolutions.us16.list-manage.com/subscribe?u=70b1c9baba63dcf30fec2c66d&id=3c72ee2249" target="_blank">
        <i class="fa fa-envelope-o"></i>
        <h4>Subscribe Newsletter</h4>
        <p>Do you want know more about "Questions & Answers Plugin" and it's features? If Yes, then click here to subscribe to our newsletter and stay updated.</p>
    </a>
    <a href="https://socialnetworking.solutions/contact-us/" target="_blank">
        <img src="application/modules/Sesbasic/externals/images/support/contact.png" />
        <h4>Contact Us</h4>
        <p>Do you have a feature request, feedback or anything to discuss with our expert professionals directly, just Contact us freely.</p>
    </a>
    <a href="https://socialnetworking.solutions/blog/" target="_blank">
        <img src="application/modules/Sesbasic/externals/images/support/update.png" />
        <h4>Blog Updates</h4>
        <p>We regularly post blogs about new releases & announcements. To read the new updates, check out our Blog Posts.</p>
    </a>
    <a href="https://community.socialengine.com/group/9/socialnetworking-solutions" target="_blank">
        <img src="application/modules/Sesbasic/externals/images/support/review.png" style="filter:invert(0);" />
        <h4>Write a Review</h4>
        <p>Do you like this theme and wish to leave your feedback or review? You just have to "Write a Review" for it.</p>
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
    	<a href="https://twitter.com/SocialNetSols" target="_blank">
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
        <i><img src="application/modules/Sesbasic/externals/images/support/se-icon.png" /></i>
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
      <i><img src="application/modules/Sesbasic/externals/images/support/sns-icon.png" /></i>
      <span><a href="https://socialnetworking.solutions" target="_blank">SocialNetworking.Solutions</a></span>
    </li>
  </ul>
</div>
