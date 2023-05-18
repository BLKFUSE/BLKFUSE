<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesbday
 * @package    Sesbday
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: todays-birthday.tpl  2018-12-20 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>

  <section class="sesbday_todaybday_section">
  	<div class="sesbday_listing_head"><?php echo $this->translate("Today's Birthdays.");?></div>
    <div class="sesbday_listing_content sesbday_listing_row">
    	<ul>
       <?php 
			$countBirthday = engine_count($this->users['data']);
			if(!$countBirthday){
			
		?>
		<div class="tip">
				<span><?php echo $this->translate("No user has birthday today.");?></span>
		</div>
		<?php 
		}else {
		
			foreach($this->users['data'] as $users){
		?>
			
		
      	<li class="sesbday_listing_item sesbasic_clearfix">
        	<div class="_thumb">
          	<a href="<?php echo $users->getHref(); ?>"><span class="bg_item_photo" style="background-image:url(<?php echo $users->getPhotoUrl(); ?>);"></span></a>
          </div>
          <div class="_cont">
          <div class="_name"><a href="<?php echo $users->getHref(); ?>"><?php echo $users->getTitle(); ?></a></div>
          <div class="_date sesbasic_text_light"><?php echo $this->translate("%s years old.",date('Y')-date('Y',strtotime($users['value'])));?></div>
			
            <div class="_textbox">
				<span id="textId<?php echo str_replace(' ', '', $users->getIdentity()); ?>" style="display:none; color:green;"><?php echo str_replace(' ', '', $users->getIdentity()); ?></span>
				<?php if($users['wish_id'])	{ ?>
					<span> <?php echo $this->translate("Birthday Wish Message has already send.");?></span>
				<?php continue; } ?>
            	<textarea placeholder="<?php echo $this->translate("Write a birthday wish on his profile");?>" id="messageId<?php echo str_replace(' ', '', $users->getIdentity()); ?>"></textarea>
              <div class="_pbtn"><button id="wishBirthdayId<?php echo str_replace(' ', '', $users->getIdentity()); ?>" onclick="wishBirthday('Id<?php echo str_replace(' ', '', $users->getIdentity()); ?>')"><?php echo $this->translate("Post");?></button></div>
            </div>
          </div>
        </li>
       <?php	} } ?>
      </ul>
    </div>
  </section>
  
 <script>
var requestTab;
	function wishBirthday(wishedUser) {
		var wishData=scriptJquery("#message"+wishedUser).val();
		var userId = scriptJquery("#text"+wishedUser).html();
		if(wishData == ""){
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
        wishingMessage: wishData,
        userIdentity:userId,
        is_ajax : 1,
		  },
		  success: function(responseHTML) {
				scriptJquery("#wishBirthday"+wishedUser).hide();
				scriptJquery("#message"+wishedUser).hide();
				scriptJquery("#text"+wishedUser).html("Your birthday wish has been posted successfully");
				scriptJquery("#text"+wishedUser).show();
		  }
		}));
  }
</script>
