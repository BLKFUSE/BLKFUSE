<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesbday
 * @package    Sesbday
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: popup.tpl  2018-12-20 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>

<!--Popup Code Start Here: Copy Code From Here-->
<div class="sesbday_wish_popup sesbasic_bxs">
	<section>
  	<div class="_head">
    	<div class="_head_inner">
    		<span class="_heading"><?php echo $this->translate("Friends' Birthdays.");?></span>
			<span class="_caption sesbasic_text_light"><?php echo $this->translate("Wish Your friends a happy birthday!.");?></span>
		</div>
    </div>
    <div class="sesbday_wish_popup_content">
    	<ul>
			 <?php 
				$todaysDate = date('Y-m-d');
				$this->users = Engine_Api::_()->sesbday()->getFriendBirthday($todaysDate,1);
				$countBirthday = engine_count($this->users['data']);
				foreach($this->users['data'] as $users){
				
			 ?>
			<li class="sesbday_wish_popup_item sesbasic_clearfix" id="container<?php echo str_replace(' ', '', $users->getIdentity()); ?>">
				<div class="_itemthumb">
					<a href="<?php echo $users->getHref(); ?>"><span class="bg_item_photo" style="background-image:url(<?php echo $users->getPhotoUrl(); ?>);"></span></a>
				</div>
				<div class="_itemcont">
					<div class="_name"><a href="<?php echo $users->getHref(); ?>"><?php echo $users->getTitle(); ?></a></div>
					<div class="_postbox _isopen">
					<div class="_inputtext sesbasic_text_light"></div>
						<div class="_postfields">
							<span id="userIdentity<?php echo str_replace(' ', '', $users->getIdentity()); ?>" class="bday_wish_msg sesbasic_text_light"><?php echo str_replace(' ', '', $users->getIdentity()); ?></span>
							<?php if($users['wish_id'])	{ ?>
								<span class="sesbasic_text_light"><?php echo $this->translate("Birthday Wish Message has already send.");?></span>
							<?php continue; } ?>	
							<textarea placeholder="<?php echo $this->translate("Write a birthday wish on his profile");?>" id="wishingMessage<?php echo str_replace(' ', '', $users->getIdentity()); ?>"></textarea>
							<div class="_btn"><button id="wishBirthday<?php echo str_replace(' ', '', $users->getIdentity()); ?>" onclick="birthdayWish('<?php echo str_replace(' ', '', $users->getIdentity()); ?>',this)"><?php echo $this->translate("Post");?></button></div>
						</div>
			
					</div>
				</div>
			</li>
		 <?php }?>
		</ul>
    </div>
    <div class="sesbday_wish_popup_footer"><a href="<?php echo $this->url(array('module'=>'sesbday','controller'=>'index','action'=>'browses'),'sesbday_general'); ?>"><?php echo $this->translate("See Upcoming Birthdays.");?></a></div>
  </section>
</div>
<!--Popup Code End Here-->
<script>
var requestTab;
	function birthdayWish(wishedUser,obj){
		scriptJquery(obj).html("<img src='application/modules/Core/externals/images/loading.gif'>");
		var wishingMessage = scriptJquery("#wishingMessage"+wishedUser).val();
		var userIdentity = scriptJquery("#userIdentity"+wishedUser).html();
		if(wishingMessage == ""){
			alert("Please write something");
			return false;
		}	
// 		if(typeof requestTab != 'undefined'){
// 			requestTab.cancel();
// 		}
    requestTab = (scriptJquery.ajax({
		  method: 'post',
		  dataType: 'html',
		  url: en4.core.baseUrl + 'sesbday/index/index/',
		  data: {
        wishingMessage: wishingMessage,
        userIdentity: userIdentity,
        is_ajax : 1,
		  },
		  success: function(responseHTML) {
        scriptJquery("#wishingMessage"+wishedUser).remove();
        scriptJquery("#wishBirthday"+wishedUser).remove();
        scriptJquery(obj).remove();
        scriptJquery("#userIdentity"+wishedUser).html("Birthday wish has been Posted Successfully");
        scriptJquery("#userIdentity"+wishedUser).show();
		  }
		}));
    }
</script>
